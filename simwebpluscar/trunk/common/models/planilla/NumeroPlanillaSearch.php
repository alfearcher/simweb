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

	/**
	* 	Clase
	*/
	class NumeroPlanillaSearch extends NumeroPlanilla
	{

		protected $planilla;
		protected $ente;
		protected $usuario;
		protected $fecha_hora;

		public $conexion;
		public $conn;



		/***/
		public function __construct($conexionLocal, $connLocal)
		{
			$this->planilla = 0;
			$this->conexion = $conexionLocal;
			$this->conn = $connLocal;
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
			$usuario = isset(Yii::$app->user->identity->email) ? Yii::$app->user->identity->email : Yii::$app->user->identity->login;
			//$model->usuario = Yii::$app->user->identity->email;
			$model->usuario = $usuario;
			$model->fecha_hora = date('Y-m-d H:i:s');

			$this->guardarNumeroPlanilla($model);
			return $this->getPlanilla();
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

				if ( $this->conexion->guardarRegistro($this->conn, $tableName, $arregloDatos) ) {
					$this->planilla = $this->conn->getLastInsertID();
				}
			}
		}

	}

?>