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
 *  @file GererarReferenciaBancaria.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-04-2017
 *
 *  @class GererarReferenciaBancaria
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

	namespace common\models\referencia;

 	use Yii;
 	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;
 	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;
 	use backend\models\recibo\deposito\Deposito;
 	use backend\models\recibo\depositoplanilla\DepositoPlanilla;


	/**
	 * Clase que se encarga de generar la relacion entre las planillas que contiene un recibo
	 * y las referencias bancarias. Esta referencias bancarias son las planillas que vienen
	 * del banco como pagadas (txt, edo cuenta, etc). Se busca relacionar la planilla del sistema
	 * con esta planilla que viene del banco, que en muchas casos coincidiran en numero. Dichas
	 * referencias tambien se realizan de manera manual cuando el recibo se paga en caja.
	 * La regla de relacion entre las planillas y las referencias bancarias (seriales), sigue lo
	 * siguiente:
	 * n planillas -----> m seriales, donde n == m.
	 * n planillas -----> m seriales, donde n !== m; n > m.
	 * Cuando n = 1, la planilla se relacionara a tantos seriales existan.
	 * Cuando n > m y n > 1, las planillas se relaconaran por el monto, siempre que valor de la
	 * variables $model sea null.
	 * -------------------------------------------------------------------------------------------
	 * La salida de la clase debe ser un arreglo con los datos de la relacion de las planillas
	 * con los seriales (referencias bancarias). Este arreglo debe tener la estructura del modelo
	 * PreReferenciaPlanilla. Si ocurre un error en el proceso de armado de las relaciones, el arreglo
	 * retornado sera vacio.
	 */
	class GenerarReferenciaBancaria
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
		 * Variable modelo SerialReferenciaUsuario.
		 * @var SerialReferenciaUsuario.
		 */
		private $_modelSerial;

		/**
		 * Variable que contiene la relacion entre las planillas y los seriales bancarios.
		 * Esta variable es un modelo del tipo PreReferenciaPlanilla.
		 * @var array
		 */
		private $_referencia = [];

		public $errores = [];

		private $_observacion;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 * @param SerialReferenciaUsuario $modelSerial modelo de la clase con los seriales
		 * que se utilizaran en la referencia contra las planillas. Si este modelo es null
		 * se asumira como seriles el mismo numero de la planilla que esta contenida en el
		 * recibo.
		 * @param $obsevacion nota que se colocarar en la referencia, basicamenet indicando
		 * si es una referencia manual o automatica, además de indicar la cuenta recauddora.
		 */
		public function __construct($recibo, $modelSerial = null, $observacion = '')
		{
			$this->_recibo = $recibo;
			$this->_modelSerial = $modelSerial;
			$this->_observacion = $observacion;
		}



		/**
		 * Metodo que inicia el proceso de las referencias bancarias
		 * @return array
		 */
		public function iniciarReferencia()
		{
			self::getDatoRecibo();
			self::getPlanillaRecibo();

			if ( $this->_modelSerial !== null ) {
				if ( is_array($this->_modelSerial) ) {
					$model = $this->_modelSerial[0];
				} else {
					$model = $this->_modelSerial;
				}

            	if ( !is_a($model, SerialReferenciaUsuario::className()) ) {
            		self::setError(Yii::t('backend', 'La clase de modelSerial no corresponde con el esperado'));
            		return;
            	}
            }

            self::armarReferencias();
			return self::getReferencia();
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
			return $this->errores;
		}



		/**
		 * Metodo getter de las referencias.
		 * @return array
		 */
		public function getReferencia()
		{
			return $this->_referencia;
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



		/**
		 * Metodo que llama a los metos que ejecutan las relaciones entre el serial y la planilla.
		 * @return
		 */
		private function armarReferencias()
		{

			if ( count($this->_depositoPlanilla) > 0 ) {
				if ( $this->_modelSerial == null ) {

					$this->_modelSerial = [New SerialReferenciaUsuario()];

					// Significa que los seriales a tomar para las referencias son las mismas planillas.
					foreach ( $this->_depositoPlanilla as $key => $value ) {

						$this->_modelSerial[$key]['recibo'] = $value['recibo'];
						$this->_modelSerial[$key]['serial'] = $value['planilla'];
						$this->_modelSerial[$key]['fecha_edocuenta'] = $this->_deposito['fecha'];
						$this->_modelSerial[$key]['monto_edocuenta'] = $value['monto'];
						$this->_modelSerial[$key]['estatus'] = 0;
						$this->_modelSerial[$key]['observacion'] = $this->_observacion;
						$this->_modelSerial[$key]['usuario'] = Yii::$app->identidad->getUsuario();

						self::relacionar($this->_modelSerial[$key], $value);
					}


				} else {
					foreach ( $this->_depositoPlanilla as $itemPlanilla ) {

						if ( count($this->_depositoPlanilla) == 1 ) {
							self::cicloSerialPorPlanillla($itemPlanilla, false);

						} elseif ( count($this->_depositoPlanilla) > 1 ) {
							self::cicloSerialPorPlanillla($itemPlanilla, true);

						}
					}
				}
			} else {
				self::setError(Yii::t('backend', 'No estan defiidas las planillas'));
			}
		}



		/**
		 * Metodo que crea un ciclo con los seriales y lo recorre para enviar las referencia
		 * por cada planilla.
		 * @param array $datoPlanilla arreglo de DepositoPlanilla
		 * @param boolean $igualdadMonto determina que se buscaran los seriales que coincidan
		 * con el monto de la planilla.
		 * @return
		 */
		private function cicloSerialPorPlanillla($datoPlanilla, $igualdadMonto)
		{
			if ( count($this->_modelSerial) > 0 ) {
				if (count($this->_modelSerial) == 1 ) {

					$serial = $this->_modelSerial[0];
					self::relacionar($serial, $datoPlanilla);

				} else {
					foreach ( $this->_modelSerial as $serial ) {
						if ( $igualdadMonto ) {
							if ( (float)$datoPlanilla['monto'] === (float)$serial['monto_edocuenta'] ) {
								self::relacionar($serial, $datoPlanilla);
								break;
							}
						} else {
							self::relacionar($serial, $datoPlanilla);
							break;
						}
					}
				}
			}
		}







		/**
		 * Metodo que crea la relacion entre el serial y la planilla.
		 * @param  [type] $datoSerial arreglo que contiene los atributos del serial
		 * @param  [type] $datoPlanilla arreglo que contiene  los aributos de la clase
		 * DepositoPlanilla.
		 * @return
		 */
		private function relacionar($datoSerial, $datoPlanilla)
		{
			$this->_referencia[] = [
				'recibo' => $this->_deposito['recibo'],
				'fecha' => $this->_deposito['fecha'],
				'monto_recibo' => $this->_deposito['monto'],
				'planilla' => $datoPlanilla['planilla'],
				'monto_planilla' => $datoPlanilla['monto'],
				'id_contribuyente' => $this->_deposito['id_contribuyente'],
				'fecha_edocuenta' => $datoSerial['fecha_edocuenta'],
				'serial_edocuenta' => $datoSerial['serial'],
				'debito' => ( $datoSerial['monto_edocuenta'] < 0 ) ? $datoSerial['monto_edocuenta'] : 0,
				'credito' => ( $datoSerial['monto_edocuenta'] >= 0 ) ? $datoSerial['monto_edocuenta'] : 0,
				'estatus' => $datoSerial['estatus'],
				'observacion' => $datoSerial['observacion'],
				'usuario' => $datoSerial['usuario'],
				'fecha_hora' => date('Y-m-d H:i:s'),

			];
		}


	}

?>