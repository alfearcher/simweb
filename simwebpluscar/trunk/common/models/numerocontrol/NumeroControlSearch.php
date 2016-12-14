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
 *  @file NumeroControlSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com
 *
 *  @date 26-01-2016
 *
 *  @class NumeroControlSearch
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

	namespace common\models\numerocontrol;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\numerocontrol\NumeroControl;
	use common\conexion\ConexionController;


	/**
	* Clase que gestiona la creacion de un numero para el control de los
	* procesos a posterior. Dicho numero en realidad es un autoincremental.
	* Se realiza una insercion en la entidad respectiva y si todo es satisfactorio
	* el metodo retorna un numero de proceso, en caso contrario retorna cero (0).
	*
	*/
	class NumeroControlSearch
	{
		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_usuario;
		private $_numero;


		/**
		 * Metodo constructor de la clase, el parametro del metodo es opcional
		 * en cuyo caso se toma el valor por defecto.
		 * @param string $db nombre de la conexion a base de datos.
		 */
		public function __construct($db = '')
		{
			$user = Yii::$app->identidad->getUsuario();
			$this->_usuario = $user;
			if ( trim($db) == '' ) { $db = 'db';}
			self::init($db);

		}


		/**
		 * Metodo que inicia la conexion a base de datos, asi como el seteo de
		 * los atributos de la clase para su posterior conexion e insercion a
		 * la base de datos. El metodo exige que el usuario este seteado antes
		 * de iniciar la conexion.
		 * @param  string $db nomobre de conecxion a bade de datos.
		 * @return no retorna.
		 */
		private function init($db)
		{
			if ( trim($this->_usuario) !== '' && trim($db) !== '' ) {
				$this->_conexion = New ConexionController();
				$this->_conn = $this->_conexion->initConectar($db);
			}
		}



		/**
		 * Metodo que inicia el proceso para la generacion del numerode control.
		 * Se abre la conexion y se ejecuta la peticion de insercion para luego
		 * cerrar dicha conexion.
		 * @return integer retorna un numero entero, en caso de no generar nada
		 * retornara un cero (0).
		 */
		public function generarNumeroControl()
		{
			$this->_numero = 0;
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();
			$this->_numero = self::guardar();
			if ( $this->_numero > 0 ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();
			return $this->_numero;
		}


		/**
		 * Motodo que crea un modelo de consulta.
		 * @return active record de la clase HistoricoBoletin
		 */
		private function findNumeroControlModel()
		{
			$findModel = NumeroControl::find();

			return $findModel;
		}



		/**
		 * Metodo que realiza el salvado del registro. Y retorna el identificador
		 * creado por la insercion.
		 * @return integer retorna un entero.
		 */
	    private function guardar()
	    {
	    	$result = false;
	    	$id = 0;
	    	$model = New NumeroControl();
	    	$tabla = $model->tableName();

	    	$model['nro_control'] = null;
	    	$model['usuario'] = $this->_usuario;
	    	$model['fecha_hora'] = date('Y-m-d H:i:s');

	    	$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);
			if ( $result ) {
				$id = $this->_conn->getLastInsertID();
			}

	    	return $id;
	    }








	}

?>