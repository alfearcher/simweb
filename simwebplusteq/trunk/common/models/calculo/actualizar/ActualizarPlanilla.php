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
 *  @file ActualizarPlanilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 ActualizarPlanilla
 *
 *  @class ActualizarPLanilla
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

	namespace common\models\calculo\actualizar;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\descuento\AplicarDescuento;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;
	use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;
	use common\models\calculo\liquidacion\inmueble\LiquidacionInmueble;
	use common\models\calculo\liquidacion\propaganda\LiquidacionPropaganda;
	use common\models\calculo\liquidacion\vehiculo\LiquidacionVehiculo;
	use common\models\calculo\recargo\Recargo;
	use common\models\calculo\interes\Interes;
	use common\models\pago\PagoSearch;





	/**
	 * Clase que ejecuta el proceso de actualizacion de la planilla. Esto significa
	 * que se ejecutaran los siguienetes proceso sobre la planilla:
	 * - Recalculo del impuesto
	 * - Recalculo de Recargo.
	 * - Recalculo de Intereses Moratorios.
	 * - Recalculo y aplicacion de Descuentos.
	 * - Recalculo y aplicacion de Retenciones.
	 */
	class ActualizarPlanilla
	{

		private $_planilla;
		private $_conn;
		private $_conexion;
		private $_detallePlanilla;
		private $_impuesto;
		private $_id_impuesto;
		private $_definitiva = false;
		private $_conPeriodo = false;




		/**
		 * Metodo constrictur de la clase,
		 * @param integer $planilla numero de planila.
		 * @param ConexionController $conexion instancia de clase ConexionController.
		 * @param [type] $conn     [description]
		 */
		public function __construct($planilla, $conexion, $conn)
		{
			$this->_planilla = $planilla;
			$this->_conexion = $conexion;
			$this->_conn;

		}





		/**
		 * Metodo inicio de la clase.
		 * @return [type] [description]
		 */
		public function iniciarActualizacion()
		{
			$this->_detallePlanilla = self::getPlanillaModel()->where('planilla =:planilla',
																		[':planilla' => $this->_planilla])
															  ->asArray()
															  ->all();

			$this->_impuesto = self::getImpuestoPlanilla();
			$this->_definitiva = self::esUnaDefinitiva();
			$this->_conPeriodo = self::esUnaPlanillaConPeriodo();
			$this->_id_impuesto = self::getIdImpuesto();

			self::crearCicloActualizacion();
		}






		/**
		 * Metodo que permite determinar si una planilla es una liquidacion
		 * definitiva.
		 * @return boolean.
		 */
		public function esUnaDefinitiva()
		{
			$referencia = current($this->_detallePlanilla['referencia']);
			if ( $referencia == 1 ) {
				return true;
			} else {
				return false;
			}
		}




		/**
		 * Metodo que determina si una planilla es de periodos mayores a cero
		 * o si es de periodo igual a cero, es decir, si es de varios o no.
		 * @return boolean
		 */
		public function esUnaPlanillaConPeriodo()
		{
			$periodo = current($this->_detallePlanilla['trimestre']);
			if ( $periodo == 0 ) {
				return false;
			} else {
				return true;
			}
		}





		/**
		 * Metodo que permite determinar el identificador del objeto al cual
		 * pertenece la planilla. Este valor indica cual es el inmueble, vehiculo,
		 * propaganda, apuesta, espectaculo y en caso de actividad economica el
		 * año de declaracion.
		 * @return integer.
		 */
		public function getIdImpuesto()
		{
			return $idImpuesto = current($this->_detallePlanilla['id_impuesto']);
		}





		/**
		 * Metodo que permite determinar el impuesto de la planilla.
		 * @return integer retorna el identificador del impuesto.
		 */
		public function getImpuestoPlanilla()
		{
			return $impuesto = current($this->_detallePlanilla['impuesto']);
		}




		/**
		 * Metodo que retorna el modelo de consulta de la planilla.
		 * @return PagoDetalle
		 */
		private function getPlanillaModel()
		{
			return PagoDetalle::find()->alias('D')
									  ->joinWith('pagos P', true, 'INNER JOIN');
		}





		/**
		 * Metodo que permite determinar cual es el primer periodo del año liquidado.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @return integer.
		 */
		private function getPrimerPeriodoLiquidado($añoImpositivo)
		{
			$findModel = self::getPlanillaModel();

			if ( $this->_impuesto == 1 ) {

				if ( $this->_definitiva ) {

					return 0;

				} else {
					$result = $findModel->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->_detallePlanilla['pagos']['id_contribuyente']])
										->andWhere('impuesto =:impuesto',
													[':impuesto' => $this->_impuesto])
										->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo'=> $añoImpositivo])
										->andWhere('referencia =:referencia',
													[':referencia' => 0])
										->andWhere('pago !=:pago',
													[':pago' => 9])
										->orderBy([
											'ano_impositivo' => SORT_ASC,
											'trimestre' => SORT_ASC,
										])
										->limit(1)
										->asArray()
										->all();
				}

			} else {
				if ( $this->_conPeriodo ) {

					$result = $findModel->where('id_impuesto =:id_impuesto',
													[':id_impuesto' => $this->_id_impuesto])
										->andWhere('impuesto =:impuesto',
													[':impuesto' => $this->_impuesto])
										->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo'=> $añoImpositivo])
										->andWhere('referencia =:referencia',
													[':referencia' => 0])
										->andWhere('pago !=:pago',
													[':pago' => 9])
										->orderBy([
											'ano_impositivo' => SORT_ASC,
											'trimestre' => SORT_ASC,
										])
										->limit(1)
										->asArray()
										->all();

				} else {
					return 0;
				}
			}


			$primero = current($result['trimestre']);

			return $primero;

		}






		/**
		 * Metodo que permite determinar cual es el primer periodo del año liquidado.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @return array.
		 */
		private function getMontoPagado($añoImpositivo)
		{
			$result = [];
			$pago = New PagoSearch();
			if ( $this->_impuesto == 1 ) {

				if ( $this->_conPeriodo ) {
					if ( $yhis->_definitiva ) {


					} else {

						$pago->setIdContribuyente($this->_detallePlanilla['pagos']['id_contribuyente']);
						$result = $pago->getPagoEstimadaSegunAnoImpositivo($añoImpositivo);

					}
				}

			} elseif ( $this->_impuesto == 2 ) {

				if ( $this->_conPeriodo ) {

					$result = $pago->getPagoInmuebleEspecificoSegunAñoImpositivo($this->_id_impuesto, $añoImpositivo);

				}

			} elseif ( $this->_impuesto == 3 ) {

				if ( $this->_conPeriodo ) {

					$result = $pago->getPagoVehiculoEspecificoSegunAñoImpositivo($this->_id_impuesto, $añoImpositivo);

				}
			}

			return $result;
		}





		/**
		 * Metodo que permite determinar el factor divisor del monto del impuesto calculado.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @return integer.
		 */
		private function getFactorDivisorAño($añoImpositivo)
		{
			$factor = 0;
			$primero = 0;
			$exigibilidad = self::getExigibilidadLiquidacionLapso($this->_impuesto, $añoImpositivo);
			if ( $exigibilidad > 0 ) {
				$primero = self::getPrimerPeriodoLiquidado($añoImpositivo);
				if ( $primero == 0 ) {
					$factor = 1;
				} elseif ( $primero > 0 ) {
					$factor = ( $exigibilidad - $primero ) + 1;
				}
			}

			return $factor;
		}





		/***/
		private function crearCicloActualizacion()
		{
			$detalles = self::getDetallePlanillaPorAnoImpositivo();

			// Ciclo de la planilla por año
			foreach ( $detalles as $detalle ) {

				$montoPeriodo = 0;
				$montoAnualImpuesto = 0;
				$montoAnualImpuesto = self::liquidarImpuesto($detalle['ano_impositivo']);

				if ( $montoAnualImpuesto > 0 ) {
					$montoPeriodo = self::getMontoPeriodo($detalle['ano_impositivo'], $montoAnualImpuesto);

					foreach ( $this->_detallePlanilla as $planillaDetalle ) {

						if ( $detalle['ano_impositivo'] == $planillaDetalle['ano_impositivo'] ) {

							$montoRecargo = 0;
							$montoInteres = 0;
							$montoDescuento = 0;
							$montoReconocimiento = 0;

							$montoRecargo = self::getCalcularRecargo($planillaDetalle['ano_impositivo'],
																	 $planillaDetalle['trimestre'],
																	 $montoPeriodo);

							$montoInteres = self::getCalcularInteres($planillaDetalle['ano_impositivo'],
																	 $planillaDetalle['trimestre'],
																	 $montoPeriodo);
						}

					}
				}

			}
		}





		/**
		 * Metodo que permite realizar una consulta de la planilla agrupada por año.
		 * Esto es con la intencion de determinar cuando años posee la planilla.
		 * Se ordenan de forma ascedente.
		 * @return PagoDetalle
		 */
		public function getDetallePlanillaPorAnoImpositivo()
		{
			$findModel = self::getPlanillaModel();
			return $findModel->select(['ano_impositivo',
									   'id_pago',
									   'id_contribuyente',
									   'impuesto',
									   'id_impuesto',
									   'planilla'])
							 ->where('planilla =:planilla',
										[':planilla' => $this->_planilla])
							 ->andWhere('pago =:pago',
							 			[':pago' => 0])
							 ->groupBy([
							 	'ano_impositivo'
							 ])
							 ->orderBy([
							 	'ano_impositivo' => SORT_ASC,
							 ])
							 ->asArray()
							 -all();
		}




		/**
		 * Metodo que determina la exigibilidad del impuesto para el año impositivo.
		 * @param  integer $impuesto identificador del impuesto.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @return integer retorna un entero que indica la exigibilidad.
		 */
		private function getExigibilidadLiquidacionLapso($impuesto, $añoImpositivo)
		{
			$exigibilidad = OrdenanzaBase::getExigibilidadLiquidacion($añoImpositivo, $impuesto);
			return $exigibilidad['exigibilidad'];
		}





		/**
		 * Metodo que realiza la liquidacion del impuesto.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @return double retorna un monto anual del calculo del impuesto.
		 */
		private function liquidarImpuesto($añoImpositivo)
		{
			$montoAnual = 0;

			if ( $this->_impuesto == 1 ) {

				if ( $this->_conPeriodo ) {
					if ( $this->_definitiva ) {


					} else {

						$liquidar = New LiquidacionActividadEconomica($this->_detallePlanilla['pagos']['id_contribuyente']);
						$liquidar->iniciarCalcularLiquidacion($añoImpositivo, 1, 'estimado');
						$montoAnual = $liquidar->getCalculoAnual();

					}
				}

			} elseif ( $this->_impuesto == 2 ) {

				if ( $this->_conPeriodo ) {

					$liquidar = New LiquidacionInmueble($this->_id_impuesto);
					$liquidar->setAnoImpositivo($añoImpositivo);
					$montoAnual = $liquidar->iniciarCalcularLiquidacionInmueble();

				}

			} else if ( $this->_impuesto == 3 ) {

				if ( $this->_conPeriodo ) {

					$liquidar = New LiquidacionVehiculo($this->_id_impuesto);
					$liquidar->setAnoImpositivo($añoImpositivo);
					$montoAnual = $liquidar->iniciarCalcularLiquidacionVehiculo();

				}

			} else if ( $this->_impuesto == 4 ) {

				if ( $this->_conPeriodo ) {

					$liquidar = New LiquidacionPropaganda($this->_id_impuesto);
					$liquidar->setAnoImpositivo($añoImpositivo);
					$montoAnual = $liquidar->iniciarCalcularLiquidacionPropaganda();
				}

			} else if ( $this->_impuesto == 12 ) {

			}

			return round($montoAnual, 2);
		}




		/**
		 * Metodo para obtener recargo.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @param  integer $periodo periodo.
		 * @param  double $monto monto del impuesto del periodo.
		 * @return double
		 */
		private function getCalcularRecargo($añoImpositivo, $periodo, $monto)
		{
			$monto = 0;
			$recargo = New Recargo($this->_impuesto);
			$monto = $recargo->calcularRecargo($añoImpositivo, $periodo, $monto);
			return round($monto, 2);
		}




		/**
		 * Metodo para obtener interes.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @param  integer $periodo periodo.
		 * @param  double $monto monto del impuesto del periodo.
		 * @return double
		 */
		private function getCalcularInteres($añoImpositivo, $periodo, $monto)
		{
			$monto = 0;
			$recargo = New Interes($this->_impuesto);
			$monto = $recargo->calcularInteres($añoImpositivo, $periodo, $monto);
			return round($monto, 2);
		}





		/**
		 * Monto que determina el monto del periodo segun el monto anual del impuesto
		 * y que se determina un factor divisor para determinar el monto que le corresponde
		 * a cada peiodo.
		 * @param  integer $añoImpositivo año impositivo del calculo.
		 * @param  double $montoAnual monto anual del impuesto.
		 * @return double retorna el monto del periodo.
		 */
		private function getMontoPeriodo($añoImpositivo, $montoAnual)
		{
			$montoPeriodo = $montoAnual;
			$factorDivisor = self::getFactorDivisorAño($añoImpositivo);
			if ( $factorDivisor > 0 ) {
				$montoPeriodo = round(($montoAnual / $factorDivisor), 2);
			}

			return $montoPeriodo;
		}




		/**
		 * Metodo que determina el ajuste que se debe aplicar por dividir el
		 * monto anual del impuesto entre los periodos. Esto se hace con la
		 * intencion de que la suma de los montos de los periodos no sea diferente
		 * al monto anaul del calculo del impuesto.
		 * @param  double $montoAnual monto anual del impuesto.
		 * @param array $detallePagado clase PagoDetalle::find()->joinWith('pagos as P')
		 * @param array $detalleDeuda  clase PagoDetalle::find()->joinWith('pagos as P')
		 * @return double.
		 */
		private function ajustarMontoPeriodo($montoAnual, $detallePagado, $detalleDeuda)
		{
			$sumaPagado = 0;
			$sumaDeuda = 0;
			$montoAjuste = 0;

			// Se contabiliza lo pagado en el año.
			foreach ( $detallePagado as $pagado ) {
				$sumaPagado = $pagado['monto'] + $sumaPagado;
			}


			// Se contaviliza lo adeudado en el año.
			foreach ( $detallePagado as $pagado ) {
				$sumaDeuda = $pagado['monto'] + $sumaDeuda;
			}

			if ( $montoAnual > ($sumaPagado + $sumaDeuda) ) {
				$montoAjuste = $montoAnual - ($sumaPagado + $sumaDeuda);
			}

			return round($montoAjuste, 2);
		}


	}

?>