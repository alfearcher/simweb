<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file ConexionController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-03-2015
 *
 *  @class ConexionController
 *	@brief Clase que permite crear una instancia de conexion a la db.
 *
 *	Esto es un detalle
 *
 *
 *
 *	@property
 *
 *
 *	@method
 * 	initConectar
 *  conectar
 *  determinarParametros
 *  getArrayParametros
 *
 *	@inherits
 *
 */


	namespace common\conexion;


	use yii\db\Connection;
	use yii\db\Exception;				// Exception PDO
	use Yii;

	/**
	 * 	Clase de conexion a base de datos.
	 */
	class ConexionController
	{

		public $lastInsertID;


		public function __construct()
		{
		}



		/**
		 *	@param $dbConexion Variable de tipo string indica a que entidad se necesita la conexion.
		 *  @return Retorna una instancia de conexion a la bd.
		 */
		public static function initConectar($dbConexion = '')
		{
			$arregloParametros = [];
			$conn = [];
			if($dbConexion != '') {

				// Se busca un arreglo que posee los parametros de conexion a la db.
				$conn = self::getArrayParametros();
				//$conn = require(dirname(__DIR__)) . '/config/main-local.php';

				// Se selecciona el elemento del arreglo multidimensional que tiene los parametros de conexion
				// Del arreglo se selecciona el elemento cuya indice es $dbConexion.
				$conn = $conn['components'][$dbConexion];
				//Array ( [class] => yii\db\Connection [dsn] => mysql:host=localhost;dbname=sim_teq [username] => root [password] => [charset] => utf8 )

				$arregloParametros = self::determinarParametro($dbConexion, $conn);
				//Array ( [dsn] => mysql:host=localhost;dbname=sim_teq [username] => root [password] => )

				if(is_array($arregloParametros)) {
					return self::conectar($arregloParametros);
				}
			}
			return false;
		}




		/**
		 *	@return Returna un array de parametros de conexion a base de datos. El mismo es una array multidimensional.
		 */
		private static function getArrayParametros()
		{
			return $connLocal = require(dirname(__DIR__)) . '/config/main-local.php';
		}



		/**
		 *	@param $dbConexion Variable de tipo string indica a que entidad se necesita la conexion.
		 *  @param $arregloDsn Variable de tipo array de datos con los valores de conexion a la db.
		 *  @return Returna un array de parametros(dsn,username, password) de conexion a db,
		 *	$arregloParametros.
		 */
		private static function determinarParametro($dbConexion, $arregloDsn)
		{
			$arregloParametros = [];
			if ( $dbConexion == 'dbsim' || $dbConexion == 'db' ) {
				foreach ($arregloDsn as $key => $value) {
					if($key == 'dsn' || $key == 'username' || $key == 'password' || $key == 'charset' ) {
						$arregloParametros[$key] = $arregloDsn[$key];
					}
				}
			}
			return $arregloParametros;
		}




		/**
		 *	@param $arregloParametros Variable de tipo array de parametros para la conexion a db.
		 *  @return Returna una instancia de la conexion para realizar operaciones en db.
		 */
		private static function conectar($arregloParametros)
		{
			return $cnn = new Connection($arregloParametros);
		}





		/**
		 * 	Metodo que determina si existe un usuario activo y valido.
		 * 	@return boolean, returna true si existe cuenta de usuario valida,
		 * 	de lo contario inactiva la session saliendose y mostrando un mensaje.
		 */
		private static function existeUserValido()
		{
			try {
				if ( !(Yii::$app->user->identity) )
				{
	            	die(Yii::t('backend','Sorry...User not valid'));
	            }
	            else
	            {
	            	return true;
	            }
			} catch (PDOException $e) {
					die(Yii::t('backend','Sorry...User no valid'));
			}
		}



			public function guardarRegistroAfiliacion($conection, $tableName, $arrayDatos = [])
		{
			// Se controla que existe un usuario activo valido.
			//if ( $this->existeUserValido() )
			//{
				try {
					$conection->createCommand()->insert($tableName, $arrayDatos)->execute();

					return true;
				} catch (PDOException $e) {
					return false;
				}
			//} else {
			//	return false;
			//}
		}




		/**
		 *	@param $conection instancia de tipo Connection a la base de datos
		 * 	@param $tableName nombre de la tabla.
		 * 	@param $arrayDatos arreglo de datos que seran insertados, cuya estructura es campo => valor.
		 * 	@return boolean true si guardo false si no guardo.
		 *
		 */
		public function guardarRegistro($conection, $tableName, $arrayDatos = [])
		{
			// Se controla que existe un usuario activo valido.
			if ( $this->existeUserValido() )
			{
				try {
					$conection->createCommand()->insert($tableName, $arrayDatos)->execute();

					return true;
				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}





		/**
		 *	@param $conection instancia de tipo Connection a la base de datos
		 * 	@param $tableName nombre de la tabla.
		 * 	@param $arrayColumna arreglo de columna de la tabla donde vamos a insertar.
		 * 	@param $arrayValores arreglos de los valores a insertar, los mismo corresponde con las
		 * 	columnas, y el arreglo puede tener varias filas de valores.
		 * 	@return boolean true si guardo false si no guardo.
		 *
		 */
		public function guardarLoteRegistros($conection, $tableName, $arrayColumna = [], $arrayValores = [])
		{
			// Se controla que existe un usuario activo valido.
			if ( $this->existeUserValido() )
			{
				try {
					$conection->createCommand()->batchInsert($tableName, $arrayColumna, $arrayValores)->execute();
					return true;
				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}

			public function guardarLoteRegistrosPreguntas($conection, $tableName, $arrayColumna = [], $arrayValores = [])
		{
			// Se controla que existe un usuario activo valido.
			
			
				try {
					$conection->createCommand()->batchInsert($tableName, $arrayColumna, $arrayValores)->execute();
					return true;
				} catch (PDOException $e) {
					return false;
				}
			  
				return false;
			
		  
		}





		/**
		 *	@param $conection instancia de tipo Connection a la base de datos.
		 * 	@param $tableName nombre de la tabla.
		 * 	@param $arrayDatos arreglo de datos que seran actualizados, cuya estructura es campo => valor.
	 	 * 	@param $arrayCondition arreglo de datos que especifica la condicion de los registros a actualizar,
		 * 	where condition de la sentencia sql.
		 * 	@return boolean true si actualizo false si no actualizo.
		 */
		public function modificarRegistro($conection, $tableName, $arrayDatos = [], $arrayCondition = [])
		{
			// Se controla que existe un usuario activo valido.
			if ( $this->existeUserValido() )
			{
				try {
					$conection->createCommand()->update($tableName, $arrayDatos, $arrayCondition)->execute();
					return true;
				} catch (PDOExcepcion $e) {
					return false;
				}
			} else {
				return false;
			}
		}

				public function modificarRegistroNatural($conection, $tableName, $arregloDatos = [], $arregloCondition = [])
		{
			// Se controla que existe un usuario activo valido.
			
			
				try {
					$conection->createCommand()->update($tableName, $arregloDatos, $arregloCondition)->execute();
					return true;
				} catch (PDOExcepcion $e) {
					return false;
				}
			
		}





		/**
		 *	@param $conection instancia de tipo Connection a la base de datos.
		 * 	@param $tableName nombre de la tabla.
		 * 	@param $arrayCondition arreglo de datos que especifica la condicion de los registros a eliminar,
		 * 	where condition de la sentencia sql.
		 * 	@return boolean true si elimina false si no elimina.
		 */
		public function eliminarRegistro($conection, $tableName, $arrayCondition = [])
		{
			// Se controla que existe un usuario activo valido.
			if ( $this->existeUserValido() )
			{
				try {
					$conection->createCommand()->delete($tableName, $arrayCondition)->execute();
					return true;
				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 * 	Metodo que permite realizar una consulta sql directa y returna un recorset con los campos
		 *  consultados.
		 *  @param $conection instancia de tipo Connection a la base de datos.
		 * 	@return dataReader con las columnas consultadas.
		 */
		public function buscarRegistro($conection, $sql)
		{
			// Se controla que existe un usuario activo valido.
			if ( $this->existeUserValido() )
			{
				try {
					$command = $conection->createCommand($sql);
					return $dataReader = $command->queryAll();
				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}

	}
?>