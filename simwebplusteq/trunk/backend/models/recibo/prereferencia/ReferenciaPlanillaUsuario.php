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
 *  @file ReferenciaPlanillaUsuario.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-03-2017
 *
 *  @class ReferenciaPlanillaUsuario
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

	namespace backend\models\recibo\prereferencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\recibo\estatus\EstatusDeposito;
	use backend\models\recibo\deposito\Deposito;
	use common\models\planilla\Pago;



	/**
	* Clase modelo principal de la entidad que permite guardar de manera temporal
	* los registros asociados a las referencias bancarias realizadas por el usuario
	* para luego guardarla de manera definitiva en la entidad "pre-referencias-planillas"
	*/
	class ReferenciaPlanillaUsuario extends ActiveRecord
	{


		/**
		 *	Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
		 * 	Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
		 * 	@return Nombre de la base de datos
		 */
		public static function getDb()
		{
			return Yii::$app->db;
		}


		/**
		 * 	Metodo que retorna el nombre de la tabla que utiliza el modelo.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'referencias_planillas_usuarios';
		}



		/**
		 * Relacion con la entidad "depositos".
		 * @return ActiveQueryInterface
		 */
		public function getRecibo()
		{
			return $this->hasOne(Deposito::className(),['recibo' => 'recibo']);
		}



		/**
		 * Relacion con la entidad "pagos".
		 * @return ActiveQueryInterface
		 */
		public function getPlanilla()
		{
			return $this->hasOne(Pago::className(),['planilla' => 'planilla']);
		}



		/**
		 * Relacion con la entidad "estatus".
		 * @return ActiveQueryInterface
		 */
		public function getCondicion()
		{
			return $this->hasOne(EstatusDeposito::className(), ['estatus' => 'estatus']);
		}


	}

?>