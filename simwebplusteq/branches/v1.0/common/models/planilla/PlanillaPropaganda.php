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
 *  @file PlanillaPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-01-2017
 *
 *  @class PlanillaPropaganda
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

	namespace common\models\planilla;

 	use Yii;
	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;
 	use common\models\planilla\Planilla;
 	use common\models\calculo\liquidacion\propaganda\LiquidacionPropaganda;



	/**
	* 	Clase
	*/
	class PlanillaPropaganda extends Planilla
	{
		public $planilla = [];
		private $_id_contribuyente;
		private $_id_impuesto;
		private $_ano_impositivo;
		private $_montoCalculado;

		private $_conexion;			// Instancia de tipo ConexionController,
		 							// permite ejecutar los metodo para guardar.
		private $_conn;				// Instancia de conexion de la db.

		const IMPUESTO = 4;




		/**
		 * Constructor de la clase.
		 * @param integer $id identificador del contribuyente.
		 * @param integer $idImpuesto  identificador de la propaganda.
		 * @param ConexionController $conexionLocal [description]
		 * @param [type] $connLocal     [description]
		 * @param integer $añoImpositivo año impositivo que se desea liquidar.
		 */
		public function __construct($id, $idImpuesto, $conexionLocal,
								    $connLocal, $añoImpositivo)
		{
			$this->_id_contribuyente = $id;
			$this->_id_impuesto = $idImpuesto;
			$this->_ano_impositivo = $añoImpositivo;
			$this->_montoCalculado = 0;
			$this->_conexion = $conexionLocal;
			$this->_conn = $connLocal;
			$this->planilla = [];
			parent::__construct();
		}






		/**
		 * Metodo que permite armar un arreglo con los parametros principales de lqiuidacion
		 * - id-impuesto
		 * - impuesto
		 * - año impositivo.
		 * - descripcion.
		 * - monto.
		 * Luego si el valor de parametros viene con datos se procede a generar y guardar la planilla
		 * son los detalles. Los detalle estaran formado por un arreglo.
		 * @param  string $observacion [description]
		 * @return [type]              [description]
		 */
		public function liquidarPropaganda($observacion = '')
		{
			$resultado = false;
			if ( isset($this->_conexion) && isset($this->_conn) ) {
				$parametros = self::iniciarLiquidarPropaganda();

				if ( count($parametros) > 0 ) {
					if ( $parametros['monto'] > 0 ) {
						$this->setMontoCalculado($parametros['monto']);
						if ( trim($observacion) !== '' ) {
							$parametros['descripcion'] = strtoupper($observacion) . ' / '. $parametros['descripcion'];
						}
						$result[$parametros['ano_impositivo']] = self::generarPeriodoLiquidado($parametros);


						if ( $result !== null && isset($this->_id_contribuyente ) ) {

							// Metodo de la clase Planilla().
							$resultado = $this->iniciarGuadarPlanilla($this->_conexion, $this->_conn, $this->_id_contribuyente, $result);
							$this->planilla['planilla'] = $this->getPlanilla();
							$this->planilla['resultado'] = $resultado;

						}
					}
				}
			}
		}



		/***/
		public function getResultado()
		{
			return $this->planilla;
		}


		/***/
		public function getMontoCalculado()
		{
			return $this->_montoCalculado;
		}


		/***/
		private function setMontoCalculado($montoCalculado)
		{
			$this->_montoCalculado = $montoCalculado;
		}


		/***/
		public function getMontoTotal()
		{
			return $this->_montoTotal;
		}


		/***/
		private function setMontoTotal($montoTotal)
		{
			$this->_montoTotal = $montoTotal;
		}








		/**
		 * Metodo para calcular el impuesto
		 * @return [type] [description]
		 */
		private function iniciarLiquidarPropaganda()
		{
			$parametro = [];

			$liquidar = New LiquidacionPropaganda($this->_id_impuesto);
			$liquidar->setAnoImpositivo($this->_ano_impositivo);
			$monto = $liquidar->iniciarCalcularLiquidacionPropaganda();

			if ( $monto > 0 ) {
				$parametro['id_impuesto'] = $this->_id_impuesto;
				$parametro['impuesto'] = self::IMPUESTO;
				$parametro['ano_impositivo'] = $this->_ano_impositivo;
				$parametro['descripcion'] = '';
				$parametro['monto'] = $monto;
			}

			return $parametro;
		}








		/***/
		private function generarPeriodoLiquidado($arregloParametro)
		{
			if ( count($arregloParametro) > 0 ) {
				if ( $arregloParametro['monto'] > 0 ) {
					$fechaActual = date('Y-m-d');
					$fechaVcto = $this->getUltimoDiaMes($fechaActual);

					$modelDetalle = New PagoDetalle();

					$modelDetalle->id_impuesto = $arregloParametro['id_impuesto'];
					$modelDetalle->impuesto = $arregloParametro['impuesto'];
					$modelDetalle->ano_impositivo = $arregloParametro['ano_impositivo'];
					$modelDetalle->trimestre = 0;
					$modelDetalle->monto = $arregloParametro['monto'];
					$modelDetalle->recargo = 0;
					$modelDetalle->interes = 0;
					$modelDetalle->descuento = 0;
					$modelDetalle->pago = 0;
					$modelDetalle->referencia = 0;
					$modelDetalle->descripcion = $arregloParametro['descripcion'];
					$modelDetalle->monto_reconocimiento = 0;
					$modelDetalle->fecha_emision = $fechaActual;
					$modelDetalle->fecha_vcto = $fechaVcto;
					$modelDetalle->exigibilidad_pago = 99;
					$modelDetalle->fecha_desde = '0000-00-00';
					$modelDetalle->fecha_hasta = '0000-00-00';

					$arregloDatos[] = $modelDetalle->attributes;

					return $arregloDatos;
				}
			}
			return null;
		}


	}

?>