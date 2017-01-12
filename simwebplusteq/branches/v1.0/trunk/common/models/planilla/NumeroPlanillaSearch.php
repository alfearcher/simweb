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
 *  @file NumeroPlanilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-04-2016
 *
 *  @class NumeroPlanilla
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

	namespace common\models\planilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\planilla\NumeroPlanilla;
	use common\conexion\ConexionController;



	/**
	* Clase que se encarga de generar un numero de planilla para la liquidacion.
	* Se genera el nuemro de planilla y se envia por metodo. La generacion del
	* numero es independiente del proceso que lo solicite, es decir, si el proceso
	* que solicita el numero de planilla no termina satisfactoriamente, el numero
	* de planilla igualmente se generara y guardara el tabla.
	*/
	class NumeroPlanillaSearch extends NumeroPlanilla
	{

		protected $planilla;
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
			$this->planilla = 0;

			$this->usuario = Yii::$app->identidad->getUsuario();
			if ( trim($db) == '' ) { $db = 'db';}
			self::init($db);

			// $this->conexion = $conexionLocal;
			// $this->conn = $connLocal;
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
		 * El formato de lo retornado es ['numero'] => numero de planilla.
		 * @return Array Retorna un arreglo con el indice [numero] y el valor del
		 * elemento es el utlimo numero generado de la entidad.
		 */
		protected function getUltimoNumeroPlanilla()
		{
			$model = NumeroPlanilla::find()->select('MAX(planilla) as numero')->asArray()->one();
			return isset($model) ? $model : null;
		}



		/**
		 * Metodo que inicializa el modelo con los datos para que este sea insertado en la
		 * entidad.
		 * @return Long Retorna el numero (planilla) generado por la operacion de insercion.
		 * si no logra insertar retorna cero (0).
		 */
		public function getGenerarNumeroPlanilla()
		{
			$model = New NumeroPlanilla();

			$model->ente = Yii::$app->ente->getEnte();
			// $usuario = Yii::$app->identidad->getUsuario();
			//$model->usuario = Yii::$app->user->identity->email;
			$model->usuario = $this->usuario;
			$model->fecha_hora = date('Y-m-d H:i:s');

			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			self::guardarNumeroPlanilla($model);

			// Se pregunta si se guardo el numero de planilla.
			if ( self::getPlanilla() > 0 ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return self::getPlanilla();
		}



		/**
		 * Metodo para obtener el numero de planilla generado en la insercion.
		 * @return Long Retorna numero de la entidad.
		 */
		public function getPlanilla()
		{
			return $this->planilla;
		}



		/**
		 * Metodo que inserta un registro en la entidad para obtener el nunmero de planilla generado.
		 * @param  ActiveRecord $model, Modelo de la entidad.
		 * @return Long, Retorna el ultimo autoincrementar generado por la instancia de conexion.
		 */
		private function guardarNumeroPlanilla($model)
		{
			$this->planilla = 0;
			if ( isset($model) ) {
				$tableName = $model->tableName();		// Nombre de la tabla.
				$arregloDatos = $model->attributes;
				$arregloDatos = $model->toArray();

				if ( $this->_conexion->guardarRegistro($this->_conn, $tableName, $arregloDatos) ) {
					$this->planilla = $this->_conn->getLastInsertID();
				}
			}
		}

	}

?>