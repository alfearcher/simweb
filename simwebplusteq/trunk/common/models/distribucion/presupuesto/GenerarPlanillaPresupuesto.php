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
 *  @file GererarPlanillaPresupuesto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @class GererarPlanillaPresupuesto
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

	namespace common\models\distribucion\presupuesto;

 	use Yii;
 	use backend\models\recibo\deposito\Depositomo;
 	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
 	use common\models\planilla\PlanillaSearch;
 	use common\models\contribuyente\ContribuyenteBase;



	/***/
	class GenerarPlanillaPresupuesto
	{

		private $_recibo;

		/**
		 * Variable que contiene los datos del contribuyente.
		 * @var array
		 */
		private $_contribuyente = [];

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

		public $errores = [];



		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
		}


		/***/
		public function iniciarPlanillaPresupuesto()
		{
			self::getDatoRecibo();
			self::getPlanillaRecibo();

			$d = self::getRelacionImpuestoPresupuesto();

die(var_dump($d));

		}



		/**
		 * Metodo que permite obtener los datos de la planilla.
		 * @param integer $planilla numero de planilla.
		 * @return array.
		 */
		public function getDatoPlanilla($planilla)
		{
			$planillaSearch = New PlanillaSearch($planilla);
			return $planillaSearch->findPlanillaDetalle();

		}



		/**
		 * Metodo que permite obtener los datos del contribuyente.
		 * @return array
		 */
		public function getDatoContribuyente($idContribuyente)
		{
			return ContribuyenteBase::findOne($idContribuyente);
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
		 * Metodo getter de las rafaga.
		 * @return array
		 */
		public function getPlanillaPresupuesto()
		{
			return $this->_planillaPresupuesto;
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
															   ->asArray()
															   ->all();
		}



		/**
		 * Metodo que retorna los codigos presupuestarios segun el impuesto.
		 * @return array
		 */
		private static function getRelacionImpuestoPresupuesto()
		{
			return $codigos = require(dirname(__DIR__)) . '/presupuesto/relacion-impuesto-codigo-presupuesto.php';
		}




		/**
		 * Metodo que inicia el ciclo de planillas a los cuales se les hara la
		 * distribucion de codigos presupuestario.
		 * @return
		 */
		private function cicloPlanilla()
		{
			if ( count($this->_depositoPlanilla) > 0 ) {

				foreach ( $this->_depositoPlanilla as $planilla ) {
					$detallePlanilla = self::getDatoPlanilla($planilla['planilla']);
					if ( count($detallePlanilla) > 0 ) {
						foreach ( $detallePlanilla as $detalle ) {


						}

					}
				}
			}

		}



		/***/
		private static function distribuirCodigoPlanilla($detallePlanilla)
		{
			$listaIdimpuesto1 = [171, 517, 652, 654, 1120];
			if ( $detallePlanilla['impuesto'] <= 7 ) {


			} elseif ( $detallePlanilla['impuesto'] == 10 && $detallePlanilla['fecha_pago'] > '2013-01-03' && in_array($detallePlanilla['id_impuesto'], $listaIdimpuesto1) ) {
				// Activado el 08-04-2013

			} elseif ( $detallePlanilla['impuesto'] == 12 ) {

			} else {

				if ( (int)$detallePlanilla['ano_positivo'] < (int)date('Y', strtotime($detallePlanilla['impuesto'])) ) {

				} else {

				}
			}
		}




		/***/
		private static function relacionar()
		{

		}




	}

?>