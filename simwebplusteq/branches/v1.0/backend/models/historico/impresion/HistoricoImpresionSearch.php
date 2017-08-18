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
 *  @file HistoricoImpresionSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-08-2017
 *
 *  @class HistoricoImpresionSearch
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

	namespace backend\models\historico\impresion;

	use Yii;
 	use backend\models\historico\impresion\HistoricoImpresion;
 	use common\conexion\ConexionController;



	/**
	 * Clase que permite insertar un registro en el historico de impresiones.
	 */
	class HistoricoImpresionSearch
	{
		private $_conexion;
		private $_conn;
		private $_transaccion;




		/**
		 * Metodo que setea las variables para las operaciones CRUD.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



		/**
		 * Metodo que genera el modelo general de consultas del historico de impresiones.
		 * @return HistoricoImpresion.
		 */
		public function findHistoricoImpresionModel()
		{
			return HistoricoImpresion::find()->alias('H');
		}



		/**
		 * Metodo que inserta un registro
		 * @param HistoricoImpresion $model instancia de la clase.
		 * @return boolean
		 */
		public function guardar($model)
		{
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$result = false;
			$result = self::crearHistoricoImpresion($model);
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return $result;
		}




		/**
		 * Metodo que inserta un registro
		 * @param HistoricoAutorizarRafaga $model instancia de la clase.
		 * @return boolean
		 */
		private function crearHistoricoImpresion($model)
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
		 * @param HistoricoImpresion $model instancia de la clase con los
		 * datos a guardar.
		 * @return boolean
		 */
		private function determinarClase($model)
		{
			return is_a($model, HistoricoImpresion::className());
		}



	}

?>