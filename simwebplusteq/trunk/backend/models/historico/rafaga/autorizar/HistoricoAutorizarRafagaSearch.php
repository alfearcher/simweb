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
 *  @file HistoricoAutorizarRafagaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-08-2017
 *
 *  @class HistoricoAutorizarRafagaSearch
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

	namespace backend\models\historico\rafaga\autorizar;

	use Yii;
 	use backend\models\historico\rafaga\autorizar\HistoricoAutorizarRafaga;


	/**
	* Clase que permite lo siguinete:
	* - Determinar si un recibo esta habilitado para la impresion de la refaga.
	* - Genera la cadena que se utilizara en la rafaga del recibo.
	*/
	class HistoricoAutorizarRafagaSearch
	{

		private $_recibo;
		private $_conexion;
		private $_conn;
		private $_transaccion;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo identificador del recibo de pago.
		 * @param ConexionController $conexion instancia de la clase ConexionController.
		 * @param Connection $conn onstancio de la clase Connection.
		 */
		public function __construct($recibo, $conexion = null, $conn = null)
		{
			$this->_recibo = $recibo;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
		}



		/**
		 * Metodo que genera el modelo principal de consulta sobre la entidad
		 * "historico-autorizaciones-rafagas". Historico de autorizaciones de
		 * la rafaga del recibo.
		 * @return HistoricoAutorizarRafaga
		 */
		private function findHistoricoAutorizarRafagaModel()
		{
			return HistoricoAutorizarRafaga::find()->alias('H')
												   ->where('recibo =:recibo',
																[':recibo' => $this->_recibo]);
		}




		/**
		 * Metodo que determina si un recibo esta habilitado para la impresion
		 * de la rafaga.
		 * @return boolean; true indica que se encuentra habilitado false que no.
		 */
		public function estaHabilitado()
		{
			$findModel = self::findHistoricoAutorizarRafagaModel();
			$registers = $findModel->orderBy([
										'id_rafaga' => SORT_DESC,
									])
								   ->limit(1)
								   ->asArray()->one();
			if ( count($registers) > 0 ) {
				if ( (int)$registers['autorizar'] == 1 ) {
					return true;
				}
			}
			return false;
		}



		/**
		 * Metodo que inserta un registro
		 * @param HistoricoAutorizarRafaga $model instancia de la clase.
		 * @return boolean
		 */
		public function guardar($model)
		{
			$result = false;
			if ( self::determinarClase($model) ) {
				$tabla = $model->tableName();
				$arregloDatos = $model->attributes;

				if ( $this->_conexion !== null && $this->_conn !== null ) {
					$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos);
				}
			}

			return $result;
		}



		/**
		 * Metodo que determina que el modelo pertecezca a la clase.
		 * @param HistoricoAutorizarRafaga $model instancia de la clase con los
		 * datos a guardar.
		 * @return boolean
		 */
		private function determinarClase($model)
		{
			return is_a($model, HistoricoAutorizarRafaga::className());
		}



	}

?>