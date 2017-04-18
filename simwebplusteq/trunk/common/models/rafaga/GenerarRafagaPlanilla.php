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
 *  @file GererarRafagaPlanilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @class GererarRafagaPlanilla
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

	namespace common\models\rafaga;

 	use Yii;
 	use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;
 	use backend\models\recibo\depositodetalle\DepositoDetalle;
 	use backend\models\recibo\deposito\Deposito;
 	use backend\models\recibo\depositoplanilla\DepositoPlanilla;



	/**
	 * Clase que gebera los registros para la creacion de las rafagas de las planillas.
	 * En proceso consiste en tomar cada planilla que esta contenida en el recibo de pago
	 * y determinar el monto conque se guardo en el recibo, dicho monto luego sers saldado
	 * segun la forma de pago del recibo. Considerando la siguiente politica:
	 * 1. Si el monto de una planilla no es saldado en su totalidad por una forma de pago,
	 * se procedera a tomar el monto restante para la planilla de otra forma de pago.
	 * 2. Si el monto de una planilla es saldado en su totalidad por una forma de pago y
	 * a esta forma de pago quedase un monto adicional, dicho monto se utilizaria para saldar
	 * el monto de otra planilla.
	 * 3. No debe quedar planillas sin saldar sus montos.
	 * 4. No debe sobrar montos sin utilizar en las formas de pago.
	 * Si el recibo no esta pagado (estatus = 0),  se debera buscar las formas de pago en la
	 * entidad temporal. (DepositoDetalleUsuario).
	 * Si el recibo esta pagado, se buscara los formas de pago en la entidad final (DepositoDetalle).
	 * La informacion generada por esta clase sera guardada en la entidad "planillas-aporte".
	 * La salida de la clase es una arreglo con la informacion respectiva:
	 * {
	 * 		[key] => valor,
	 * }
	 */
	class GenerarRafagaPlanilla
	{

		private $_recibo;

		/**
		 * Variable que contiene el registro del recibo.
		 * @var Deposito.
		 */
		private $_deposito;

		/**
		 * Variable que contiene el registro donde estan las planillas relacionadas
		 * al recibo.
		 * @var DepositoPlanilla.
		 */
		private $_depositoPlanilla;

		/**
		 * Variable que indica las formas de pago utilizada para pagar el recibo.
		 * @var DepositoDetalle
		 */
		private $_depositoDetalle;

		private $_planillaAporte;

		/**
		 * Variable donde se guarda las relacionas y montos saldados de las planillas.
		 * @var array
		 */
		private $_rafaga = [];

		public $_errores;

		/**
		 * Variable que determina el monto restante de una planilla.
		 * @var double.
		 */
		private $_monto_restante;

		/**
		 * Variable que indica cuando se debe selecciona otra planilla de las contenidas
		 * en el recibo.
		 * @var boolean.
		 */
		private $_otra_planilla;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
		}



		/**
		 * Metodo que inicia la clase.
		 * @return array
		 */
		public function iniciarRafaga()
		{
			self::getDatoRecibo();
			self::getPlanillaRecibo();

			if ( count($this->_errores) == 0 ) {
				if ( self::reciboPago() ) {

					// Se deben buscar las formas de pago en la entidad original.
					$this->_depositoDetalle = DepositoDetalle::find()->where('recibo =:recibo',
																					[':recibo' => $this->_recibo])
																	 ->orderBy([
																	 	'id_forma' => SORT_ASC,
																	 ])
																	 ->asArray()
																	 ->all();

				} else {

					// Se deben buscar las formas de pago en la entidad temporal.
					$this->_depositoDetalle = DepositoDetalleUsuario::find()->where('recibo =:recibo',
																						[':recibo' => $this->_recibo])
																			->orderBy([
																	 			'id_forma' => SORT_ASC,
																	 		])
																		    ->asArray()
																			->all();

				}
				self::armarCicloPlanilla();
			}

			return self::getRafaga();

		}



		/**
		 * Metodo que permitir determinar si ya se han asignados todos los mntos de las formas
		 * de pago. Determina si existe monto sobrante
		 * @return double.
		 */
		private function existeMontoPorAsignar()
		{
			$suma = 0;
			if ( count($this->_depositoDetalle) > 0 ) {
				foreach ( $this->_depositoDetalle as $detalle ) {
					$suma = $suma + $detalle['monto'];
				}
			}
			return $suma;
		}




		/**
		 * Metodo que setea un error ocurrido.
		 * @param srting $mensajeError descripcion del error ocurrido.
		 */
		public function setError($mensajeError)
		{
			$this->errores[] = $mensajeError;
		}



		/**
		 * Metodo getter de los errores existentes
		 * @return array
		 */
		public function getError()
		{
			return $this->_errores;
		}



		/**
		 * Metodo getter de las rafaga.
		 * @return array
		 */
		public function getRafaga()
		{
			return $this->_rafaga;
		}



		/**
		 * Metodo que determina si un recibo esta pagado a no.
		 * @return boolean.
		 */
		private function reciboPago()
		{
			if ( $this->_deposito['estatus'] == 1 ) {
				return true;
			} else {
				return false;
			}
		}



		/**
		 * Metodo que realiza la consulta para obtener el registro del recibo.
		 * @return
		 */
		private function getDatoRecibo()
		{
			$this->_deposito = Deposito::find()->where('recibo =:recibo',
															['recibo' => $this->_recibo])
											   ->asArray()
											   ->one();
		}



		/**
		 * Metodo que realiza la consulta para obetener las planillas relacionadas
		 * al recibo.
		 * @return
		 */
		private function getPlanillaRecibo()
		{
			$this->_depositoPlanilla = DepositoPlanilla::find()->where('recibo =:recibo',
																			[':recibo' => $this->_recibo])
															   ->orderBy([
															   		'planilla' => SORT_ASC,
															   	])
															   ->asArray()
															   ->all();
		}



		/***/
		private function armarCicloPlanilla()
		{
			if ( count($this->_depositoPlanilla) > 0 ) {
				foreach ( $this->_depositoPlanilla as $planilla ) {
					self::distribuirMontoFormaPagoEntrePlanilla($planilla);
				}
			}
		}




		/***/
		private function distribuirMontoFormaPagoEntrePlanilla($planilla)
		{
			$this->_monto_restante = 0;
			if ( self::existeMontoPorAsignar() ) {
				if ( count($this->_depositoDetalle) > 0 ) {
					foreach ( $this->_depositoDetalle as $i => $detalle ) {
						if ( $detalle['monto'] > 0 ) {
							if ( $detalle['monto'] >= $planilla['monto'] ) {
								$montoAporte = $planilla['monto'];
								$detalle['monto'] = $detalle['monto'] - $montoAporte;
								$planilla['monto'] = 0;
								$this->_depositoDetalle[$i]['monto'] = $detalle['monto'];

							} elseif ( $planilla['monto'] > $detalle['monto'] ) {
								$montoAporte = $detalle['monto'];
								$planilla['monto'] = $planilla['monto'] - $detalle['monto'];
								$detalle['monto'] = 0;
								$this->_depositoDetalle[$i]['monto'] = $detalle['monto'];
							}

							$this->_monto_restante = $planilla['monto'];
							self::relacionar($planilla['planilla'], $detalle, $montoAporte);
							if ( $this->_monto_restante == 0 ) {
								break;
							}
						}
					}
				}
			}
		}



		/**
		 * Metodo que realiza la relacion de la planilla con sus formas de pago.
		 * @param integer $planilla informacion de la entidad.
		 * @param DepositoDetalle $detalle informacion de la forma de pago.
		 * @param double $montoAporte monto que se le asignara a la planilla.
		 * @return
		 */
		private function relacionar($planilla, $detalle, $montoAporte)
		{
			$this->_rafaga[] = [
				'linea' => $detalle['linea'],
				'recibo' => $this->_recibo,
				'planilla' => $planilla,
				'id_forma' => $detalle['id_forma'],
				'monto_aporte' => $montoAporte,
				'estatus' => 0,
			];
		}


	}

?>