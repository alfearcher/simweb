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
 *  @file NumeroLicenciaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-01-2017
 *
 *  @class NumeroLicenciaSearch
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

	namespace backend\models\aaee\licencia\numerolicencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\licencia\numerolicencia\NumeroLicencia;
	use common\conexion\ConexionController;



	/**
	* Clase que se encarga de generar un numero correlativo para la licencia.
	* Se genera el nuemro de planilla y se envia por metodo. La generacion del
	* numero es independiente del proceso que lo solicite, es decir, si el proceso
	* que solicita el numero de planilla no termina satisfactoriamente, el numero
	* de planilla igualmente se generara y guardara el tabla.
	*/
	class NumeroLicenciaSearch extends NumeroLicencia
	{

		protected $licencia;
		protected $ente;
		protected $usuario;
		protected $fecha_hora;

		private $_conexion;
		private $_conn;
		private $_transaccion;



		/**
		 * Metodo constructor de la clase
		 * @param string $db nombre de la configuracion de conexion a base de datos.
		 */
		public function __construct($db = 'db')
		{
			$this->licencia = 0;

			$this->usuario = Yii::$app->identidad->getUsuario();
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
		public function init($db)
		{
			if ( trim($this->usuario) !== '' && trim($db) !== '' ) {
				$this->_conexion = New ConexionController();
				$this->_conn = $this->_conexion->initConectar($db);
			}
		}




		/**
		 * Metodo que retorna un array con el ultimo numero generado por la entidad.
		 * El formato de lo retornado es ['numero'] => numero de licencia.
		 * @return Array Retorna un arreglo con el indice [numero] y el valor del
		 * elemento es el utlimo numero generado de la entidad.
		 */
		protected function getUltimoNumeroLicencia()
		{
			$model = NumeroLicencia::find()->select('MAX(licencia) as numero')->asArray()->one();
			return isset($model) ? $model : null;
		}



		/**
		 * Metodo que retorna el ultimo numero del autoincremental generado.
		 * Retorna un arreglo como el siguiente:
		 * ['campo'] => valores.
		 * @return array.
		 */
		public function getUltimoNumeroGenerado()
		{
			return self::getUltimoNumeroLicencia();
		}



		/**
		 * Metodo que inicializa el modelo con los datos para que este sea insertado en la
		 * entidad.
		 * @return Long Retorna el numero (licencia) generado por la operacion de insercion.
		 * si no logra insertar retorna cero (0).
		 */
		public function getGenerarNumeroLicencia()
		{
			$model = New NumeroLicencia();

			$model->ente = Yii::$app->ente->getEnte();
			$model->usuario = $this->usuario;
			$model->fecha_hora = date('Y-m-d H:i:s');
			$model->token = md5(uniqid());
			$model->observacion = 'SIMWebPLUS';

			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			self::guardarNumeroLicencia($model);

			// Se pregunta si se guardo el numero de licencia.
			if ( self::getLicencia() > 0 ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return self::getLicencia();
		}



		/**
		 * Metodo para obtener el numero de licencia generado en la insercion.
		 * @return Long Retorna numero de la entidad.
		 */
		public function getLicencia()
		{
			return $this->licencia;
		}



		/**
		 * Metodo que inserta un registro en la entidad para obtener el numero de licencia generado.
		 * @param  NumeroLicencia $model, Modelo de la entidad.
		 * @return integer retorna el ultimo autoincrementar generado por la instancia de conexion.
		 */
		private function guardarNumeroLicencia($model)
		{
			$this->licencia = 0;
			if ( isset($model) ) {
				$tableName = $model->tableName();		// Nombre de la tabla.
				$arregloDatos = $model->attributes;
				$arregloDatos = $model->toArray();

				if ( $this->_conexion->guardarRegistro($this->_conn, $tableName, $arregloDatos) ) {
					$this->licencia = $this->_conn->getLastInsertID();
				}
			}
		}

	}

?>