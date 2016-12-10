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
 *  @file PlanillaTasa.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-04-2016
 *
 *  @class PlanillaTasa
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
 	use common\models\calculo\liquidacion\tasa\LiquidacionTasa;



	/**
	* 	Clase
	*/
	class PlanillaTasa extends Planilla
	{
		public $planilla = [];
		private $_idContribuyente;
		private $_idImpuesto;
		private $_añoDesde;
		private $_periodoDesde;
		private $_montoCalculado;
		private $_montoTotal;
		private $_periodosLiquidados = [];
		public $conexion;			// Instancia de tipo ConexionController,
		 							// permite ejecutar los metodo para guardar.
		public $conn;				// Instancia de conexion de la db.



		/***/
		public function __construct($id, $idImpuesto, $conexionLocal, $connLocal, $monto = 0)
		{
			$this->_idContribuyente = $id;
			$this->_idImpuesto = $idImpuesto;
			$this->_periodosLiquidados = null;
			$this->conexion = $conexionLocal;
			$this->conn = $connLocal;
			$this->planilla = null;
			$this->_montoCalculado = $monto;
			parent::__construct();
		}



		/**
		 * Metodo que inicia la liquiadacion de la tasa
		 * @param  Double $factorMultiplicador, entero que indica por cuanto se debe multiplicar
		 * el resultado de la liquidacion, si este valor es cero (0), no se tomara encuenta.
		 * @param  String  $observacion, Nota que sera agregada en las observaciones de la planilla.
		 * @return Bollean Retornara true si guardo la planilla de forma exitosa, retornara false si no
		 * logra guardar la planilla.
		 */
		public function liquidarTasa($factorMultiplicador = 0, $observacion = '')
		{
			$resultado = false;
			if ( isset($this->conexion) && isset($this->conn) ) {
				$parametros = self::iniciarLiquidarTasa($factorMultiplicador);
				if ( count($parametros) > 0 ) {
					if ( $parametros['monto'] > 0 ) {
						$this->setMontoCalculado($parametros['monto']);
						if ( trim($observacion) !== '' ) {
							$parametros['descripcion'] = strtoupper($observacion) . ' / '. $parametros['descripcion'];
						}
						$result[$parametros['ano_impositivo']] = self::generarPeriodoLiquidado($parametros);
						if ( $result !== null && isset($this->_idContribuyente) ) {
							// Metodo de la clase Planilla().
							$resultado = $this->iniciarGuadarPlanilla($this->conexion, $this->conn, $this->_idContribuyente, $result);
							$this->planilla['planilla'] = $this->getPlanilla();
							$this->planilla['resultado'] = $resultado;
						}
					}
				}
			}
			//return $this->planilla;
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
		 * Metodo que setea las variables que determinan el rango de liquidacion
		 * a un valor igual a "null". Cuando esto sucede es que no existen las condiciones
		 * para continuar con la liquidacion.
		 * @return Bollean true.
		 */
		public function anulacionRangoLiquidacion()
		{
			$this->_añoDesde = null;
			$this->_periodoDesde = null;
		}



		/**
		 * Metodo que controla y valida que los rango de inicio y finalizacion de la liquidacion
		 * este correcto.
		 * @return Boolean, Retorna true si es correcto y false si el rango esta errado.
		 */
		private function validarRangoLiquidacion()
		{
			if ( $this->_añoDesde == null || $this->_periodoDesde == null ) {
				return false;
			}
			return true;
		}




		/***/
		public function iniciarLiquidarTasa($factorMultiplicador = 0)
		{
			// Array con los parametros principales y el calculo de la tasa anual.
			// + id-impuesto
			// + impuesto
			// + año impositivo
			// + descripcion
			// + monto.
			$result = null;

			$liquidacion = New LiquidacionTasa($this->_idImpuesto, $factorMultiplicador, $this->_montoCalculado);
			$result = $liquidacion->iniciarCalcularLiquidacionTasa();
			return $result;
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

					$arregloDatos[] = $modelDetalle->attributes;

					return $arregloDatos;
				}
			}
			return null;
		}


	}

?>