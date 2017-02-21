<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file AplicarDescuentoViviendaPrincipal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-02-2017
 *
 *  @class AplicarDescuentoViviendaPrincipal
 *  @brief Clase Modelo
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

	namespace common\models\descuento;

 	use Yii;
	use backend\models\configuracion\descuento\DescuentoSearch;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;
	use common\conexion\ConexionController;
	use common\models\planilla\PlanillaSearch;
	use backend\models\inmueble\principal\HistoricoViviendaPrincipalSearch;



	/**
	* Clase que gestiona l aplicacion del descuento por vivienda principal. Este esuqema
	* de descuento utiliza la planilla como fuente para el arranque del proceso, se verifica
	* que la planilla sea de inmuebles urbanos y que posea periodos, además de obtener el
	* identificador del inmueble en dicha planilla. La politica para aplicar el descuento
	* verifica que el inmueble este registrado como vivienda principal y los periodos de
	* la planilla que se veran afectados por el descuento seran aquellos que esten dentro
	* del lapso de tiempo donde el inmueble esta catalogado como de vivienda principal.
	*/
	class AplicarDescuentoViviendaPrincipal
	{

		private $_planilla;
		private $_id_contribuyente;
		private $_planillaSearch;
		private $_historicoSearch;

		private $_conn;
		private $_conexion;
		private $_transaccion;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de la planilla
		 */
		public function __construct($planilla)
		{
			$this->_planilla = $planilla;
			$this->_planillaSearch = New PlanillaSearch($planilla);
		}




		/**
		 * Metodo que setea las variables para las operaciones CRUD.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



		/**
		 * Metodo que inicia el proceso de aplicacion del descuento.
		 * @return boolean
		 */
		public function iniciarDescuentoViviendaPrincipal()
		{
			$procesoExitoso = false;
			// Registros de la planilla.
			$results = $this->_planillaSearch->getRegistroDetallePlanilla();

			if ( self::laPlanillaEsPeriodo($results) && self::planillaInmueble($results) ) {

				self::setConexion();
				self::historicoViviendaPrincipal($results);
				$historicoVivienda = $this->_historicoSearch->getHistoricoViviendaPrincipal();

				$this->_conn->open();
				$this->_transaccion = $this->_conn->beginTransaction();

				foreach ( $results as $result ) {

					$lapsoPlanilla = [
						'ano_impositivo' => $result['ano_impositivo'],
						'periodo' => $result['trimestre'],
						'exigibilidad' => $result['exigibilidad_pago'],
					];
					if ( self::lapsoPlanillaDentroRango($historicoVivienda[0], $lapsoPlanilla) ) {
						$procesoExitoso = self::aplicarDescuento($result);
						if ( !$procesoExitoso ) {
							break;
						}
					}
				}

				if ( $procesoExitoso ) {
					$this->_transaccion->commit();
				} else {
					$this->_transaccion->rollBack();
				}
				$this->_conn->close();

			}

			return $procesoExitoso;
		}




		/**
		 * Metodo que crear una instancia de la clase HistoricoViviendaPrincipalSearch
		 * @return [type] [description]
		 */
		public function historicoViviendaPrincipal($results)
		{
			$this->_historicoSearch = New HistoricoViviendaPrincipalSearch($results[0]['pagos']['id_contribuyente'],
																     	  $results[0]['id_impuesto']);
		}




		/**
		 * Metodo que permite determinar si un laspo determinado de la planlla esta
		 * dentro del rango historico de la vivienda principal.
		 * @param array $historicoVivienda registro del historico de vivienda principal.
		 * @param array $lapsoPlanilla arrego donde se especifica el año impositivo y el
		 * periodo.
		 * @return boolean.
		 */
		private function lapsoPlanillaDentroRango($historicoVivienda, $lapsoPlanilla)
		{
			$periodoDesde = $this->_historicoSearch->getPeriodoFechaDesde($lapsoPlanilla['exigibilidad'], 'fecha_desde');
			$periodoHasta = $this->_historicoSearch->getPeriodoFechaDesde($lapsoPlanilla['exigibilidad'], 'fecha_hasta');

			$añoHistoricoDesde = date('Y', strtotime($historicoVivienda['fecha_desde']));
			$añoHistoricoHasta = date('Y', strtotime($historicoVivienda['fecha_hasta']));

			if ( (int)$lapsoPlanilla['ano_impositivo'] == (int)$añoHistoricoDesde ) {
				if ( (int)$lapsoPlanilla['periodo'] >= (int)$periodoDesde ) {

					return true;

				}
			} elseif ( (int)$lapsoPlanilla['ano_impositivo'] > (int)$añoHistoricoDesde ) {
				if ( (int)$lapsoPlanilla['ano_impositivo'] == (int)$añoHistoricoHasta ) {

					if ( (int)$lapsoPlanilla['periodo'] <= (int)$periodoHasta ) {

						return true;

					}
				} elseif ( (int)$lapsoPlanilla['ano_impositivo'] < (int)$añoHistoricoHasta ) {

					return true;
				}
			}

			return false;

		}



		/**
		 * Metodo que aplico el descuento sobre el monto.
		 * @param array $registroPlanilla registro de la planilla del lapso.
		 * @return boolean.
		 */
		private function aplicarDescuento($registroPlanilla)
		{
			$tabla = PagoDetalle::tableName();

			$arregloDatos = [
				'descuento' => $registroPlanilla['monto'] * (0.6),
				'descripcion' => $registroPlanilla['descripcion'] . ' / Descuento por Viviendo Principal',
			];

			$arregloCondicion = [
				'id_detalle' => $registroPlanilla['id_detalle'],
			];

			return $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);

		}





		/**
		 * Metodo que permite determinar si una planilla es de periodo o no.
		 * @param  array $results arreglo de los registros de la planilla.
		 * @return boolean.
		 */
		private function laPlanillaEsPeriodo($results)
		{
			if ( (int)$results[0]['trimestre'] > 0 ) {
				return true;
			} else {
				return false;
			}
		}




		/**
		 * Metodo que permite determinar si la planilla pertenece al impuesto de
		 * inmuebles urbanos. Impuesto == 2.
		 * @param  array $results arreglo de los registros de la planilla.
		 * @return boolean.
		 */
		private function planillaInmueble($results)
		{
			if ( (int)$results[0]['impuesto'] == 2 ) {
				return true;
			} else {
				return false;
			}
		}

	}

?>