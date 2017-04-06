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
	 * PreReferenciaPlanilla.
	 */
	class GererarReferenciaBancaria
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


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 * @param SerialReferenciaUsuario $modelSerial modelo de la clase con los seriales
		 * que se utilizaran en la referencia contra las planillas. Si este modelo es null
		 * se asumira como seriles el mismo numero de la planilla que esta contenida en el
		 * recibo.
		 */
		public function __construct($recibo, $modelSerial = null)
		{
			$this->_recibo = $recibo;
			$this->_modelSerial = $modelSerial;
		}



		/**
		 * Metodo que inicia el proceso de las referencias bancarias
		 * @return array
		 */
		public function iniciarReferencia()
		{
			self::getDatoRecibo();
			self::getPlanillaRecibo();




			return self::getReferencia();
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
		 * Metodo que realiza la consulta para obtener el registro del recibo.
		 * @return
		 */
		private function getDatoRecibo()
		{
			$this->_deposito = Deposito::find()->where('recibo =:recibo',
															['recibo' => $this->_recibo])
											   ->asArray()
											   ->all();
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




		/***/
		private function armarReferencias()
		{

		}



	}

?>