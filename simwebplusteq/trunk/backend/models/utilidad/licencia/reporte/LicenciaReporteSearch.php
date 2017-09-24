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
 *  @file LicenciaReporteSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 23-09-2017
 *
 *  @class LicenciaReporteSearch
 *  @brief Clase que gestiona el contenido de la entidad.
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

	namespace backend\models\utilidad\licencia\reporte;

 	use Yii;
	use backend\models\utilidad\licencia\reporte\LicenciaReporte;
	use common\conexion\ConexionController;


	/**
	 * Clase que gestiona la insercion y la consultaa relacionadas con la
	 * entidad "licencias-reportes".
	 */
	class LicenciaReporteSearch extends LicenciaReporte
	{
		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_usuario;



		/**
		 * Metodo constructor de la clase.
		 */
		public function __construct()
		{
			$this->_usuario = Yii::$app->identidad->getUsuario();
		}


		/**
		 * Metodo que setea las variables para las operaciones CRUD.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}


		/**
		 * Metodo que inserta un lote especifico de registros, utilizando
		 * un arreglo. Este arreglo debe contener una estructura similar al
		 * modelo LicenciaReporte. El metodo retornara una variable boolean
		 * que indicara si se inserto o no el lote. El arreglo debe contener
		 * al menos los campos del modelo para relacionarlos a su atributo,
		 * aquellos atributos del modelo que no coincidan se guardaran en blanco.
		 * @param array $arreglo arreglo con la informacion que se guardara
		 * en el modelo. Dicho arreglo.
		 * @return boolean
		 */
		public function insertarLote($arreglo)
		{
			$result = false;
			$tabla = $this->tableName();
			$data = self::armarData($arreglo);
			if ( count($data) > 0 && self::inicializarEntidad() ) {
				self::setConexion();
				$this->_conn->open();
				$this->_transaccion = $this->_conn->beginTransaction();
				$result = $this->_conexion->guardarLoteRegistros($this->_conn, $tabla, $this->attributes(), $data);
				if ( $result ) {
					$this->_transaccion->commit();
				} else {
					$this->_transaccion->rollBack();
				}
				$this->_conn->close();
			}
			return $result;
		}



		/**
		 * Metodo que arma la data para crear los registros que seran insertados.
		 * @param array $arreglo arreglo con la informacion que se guardara
		 * en el modelo. Dicho arreglo.
		 * @return array.
		 */
		private function armarData($arreglo)
		{
			$data = [];
			//$usuario = Yii::$app->identidad->getUsuario();
			if ( count($arreglo) > 0 ) {
				foreach ( $arreglo as $key => $campos ) {
					$data[$key]['id_licencia_reporte'] = null;
					$data[$key]['id_contribuyente'] = $campos['id_contribuyente'];
					$data[$key]['usuario'] = $this->_usuario;
					$data[$key]['observacion'] = $campos['observacion'];
				}
			}
			return $data;
		}



		/**
		 * Metodo que elimina los registros de la entidad segun el usuario.
		 * @return boolean.
		 */
		private function inicializarEntidad()
		{
			$result = false;
			$arregloCondicion = [
				'usuario' => $this->_usuario,
			];
			$tabla = $this->tableName();
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();
			$result = $this->_conexion->eliminarRegistro($this->_conn, $tabla, $arregloCondicion);
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return $result;
		}


		/**
		 * Metodo que retorna el modelo basico de consulta de la entidad.
		 * Solo tomando los registros del usuario actual.
		 * @return LicenciaReporte
		 */
		public function findLicenciaReporteModel()
		{
			return LicenciaReporte::find()->alias('L');
		}



		/**
		 * Metodo que retorna los registros segun la consulta.
		 * @return array.
		 */
		public function findLicenciaReporte()
		{
			$findModel = self::findLicenciaReporteModel();
			return $result = $findModel->all();
		}
	}

?>