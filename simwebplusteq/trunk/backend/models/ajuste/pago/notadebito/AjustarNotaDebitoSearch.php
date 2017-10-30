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
 *  @file AjustarNotaDebitoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-10-2017
 *
 *  @class AjustarNotaDebitoSearch
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

	namespace backend\models\ajuste\pago\notadebito;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositodetalle\DepositoDetalle;
	use yii\helpers\ArrayHelper;
	use common\models\planilla\PagoDetalle;
	use common\conexion\ConexionController;


	/**
	 * Clase que permite localizar los registros que poseen inconsistencias
	 * en la contabilizacion de sus columnas:
	 * si monto - ( descuento + monto-reconocimiento ) < 0, el registro posee
	 * una inconsistencia ya que esta operacion debe ser igual p mayor a creo (0).
	 * Se reaizara el ajuste sobre los registros que posean esta inconsistencia
	 * realizando nuevamente la distribucion del monto total por descuento y por
	 * monto-reconocimiento. Teniendo como entrada el numero de planilla.
	 * El ajuste se realiza sobre los datos actuales de la planilla sin necesidad
	 * de buscar valores adicionales en otra entidad.
	 */
	class AjustarNotaDebitoSearch
	{
		/**
		 * Identificador de la planilla.
		 * @var integer
		 */
		private $_planilla;

		/**
		 * Detalles de la planilla consultada.
		 * @var array
		 */
		private $_detalle_planilla;

		/**
		 * Detalles de la planilla que se ajustaron. Se tomaran los registros
		 * del año impositivo especificado.
		 * @var array.
		 */
		private $_detalle_planilla_ajustado;

		/**
		 * ConexionController.
		 * @var ConexionController
		 */
		private $_conexion;

		/**
		 * Conection
		 * @var Conection
		 */
		private $_conn;

		/**
		 * Año impositivo de la planilla donde presenta la inconsistencia
		 * y se tomara como parametro para realizar el ajuste solo en ese año.
		 * @var integer
		 */
		private $_ano_impositivo;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla identificador (numero) de la planilla.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param Connection $conn instancia de la clase.
		 */
		public function __construct($planilla, $conexion = null, $conn = null)
		{
			$this->_planilla = $planilla;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_detalle_planilla = self::findPlanilla();
			$this->_detalle_planilla_ajustado = [];

		}



		/***/
		public function iniciarAjuste()
		{
			if ( count($this->_detalle_planilla) > 0 ) {

			}
		}



		/***/
		private function distribuirDescuento()
		{
			if ( count($this->_detalle_planilla_ajustado) > 0 ) {
				// Quiere decir que existen registro para ajustar.

				$sumaDescuento = self::getTotalPorColumna('descuento', $this->_detalle_planilla_ajustado);
				$sumaReconocimiento = self::getTotalPorColumna('monto_reconociento', $this->_detalle_planilla_ajustado);
				foreach ( $this->_detalle_planilla_ajustado as $key => $detalle ) {

				}
			}
		}


		/**
		 * Metodo que determina si existe una inconsistencia en el registro de la planilla.
		 * El metodo retorna un true si existe la inconsistencia, false en caso contrario.
		 * @param  array $itemDetallePlanilla registro de la planilla
		 * @return boolean
		 */
		public function inconsistente($itemDetallePlanilla)
		{
			$sumaDeduccion = $itemDetallePlanilla['descuento'] + $itemDetallePlanilla['monto_reconociento'];
			$sumaAdicion = $itemDetallePlanilla['monto'];
			if ( $sumaAdicion < $sumaDeduccion ) {
				return true;
			}
			return false;
		}



		/**
		 * Metodo que determina la inconsistecia y envia a crear un arreglo con los registros
		 * de ese año que posee la inconsistencia.
		 * @return none
		 */
		private function determnarInconsitencia()
		{
			foreach ( $this->_detalle_planilla as $detalle ) {
				if ( self::inconsistencia($detalle) ) {
					self::separarItemDetallePlanilla($detalle['ano_impositivo']);
					break;
				}
			}
		}


		/**
		 * Metodo que envia el detalle de la planilla para armar un arreglo de los registros
		 * que seran ajustados.
		 * @param integer $añoImpositivo año impositivo de la planilla donde se encuentra la
		 * inconsistencia.
		 * @return none
		 */
		private function separarItemDetallePlanilla($añoImpositivo)
		{
			foreach ( $this->_detalle_planilla as $detalle ) {
				if ( (int)$detalle['ano_impositivo'] == $añoImpositivo ) {
					self::addItemParaAjustar($detalle);
				}
			}
		}


		/**
		 * Metodo que agrega un registro al arreglo de os detalles que seran ajustados.
		 * @param array $itemDetallePlanilla arreglo que representa un registro de la
		 * planilla.
		 */
		private function addItemParaAjustar($itemDetallePlanilla)
		{
			$this->_detalle_planilla_ajustado[] = $itemDetallePlanilla;
		}



		/**
		 * Metodo que configurationa las variables que permitiran la interaccion
		 * con la base de datos.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}


		/**
		 * Metodo que retorna el modelo basico de consulta de la planilla.
		 * @return PagoDetalle
		 */
		private function findPagoDetalleModel()
		{
			return PagoDetalle::find()->alias('D');
		}



		/**
		 * Metodo que realiza la consulta general sobre los registros de la
		 * planilla. Retornará los registros de la planilla.
		 * @return array
		 */
		public function findPlanilla()
		{
			$findModel = self::findPagoDetalleModel();
			return $findModel->joinWith('pagos P', true, 'INNER JOIN')
							 ->where(['planilla' => $this->_planilla])
							 ->orderBy([
									'ano_impositivo' => SORT_ASC,
									'trimestre' => SORT_ASC,
								])
							 ->asArray()
							 ->all();
		}


		/**
		 * Metodo getter de los detalles de la planilla.
		 * @return array
		 */
		public function getDetallePlanilla()
		{
			return $this->_detalle_planilla;
		}



		/**
		 * Metodo getter de los detalles de la planilla ajustada
		 * @return array
		 */
		public function getDetallePlanillaAjustada()
		{
			return $this->_detalle_planilla_ajustado;
		}


		/**
		 * Metodo que realiza la totalizacion por columna de los detalles de la planilla.
		 * Retorna un monto de la totalizaxion segun la columna especificada.
		 * @param string $columna descripcion de la columna que se desea totalizar.
		 * @param array $detalle arreglo de atributos de los detalles de la planilla.
		 * @return double
		 */
		public function getTotalPorColumna($columna, $detalle)
		{
			return $total = array_sum(array_column($detalle, $columna));
		}
	}
?>