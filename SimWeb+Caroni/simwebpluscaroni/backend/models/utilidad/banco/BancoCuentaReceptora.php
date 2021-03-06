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
 *  @file BancoCuentaReceptora.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-03-2017
 *
 *  @class BancoCuentaReceptora
 *  @brief Clase Modelo de la entidad "bancos-cuentas-receptoras"
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

	namespace backend\models\utilidad\banco;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\banco\Banco;



	/**
	* Clase principal de la entidad "bancos-cuentas-receptoras"
	*/
	class BancoCuentaReceptora extends ActiveRecord
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
			return 'bancos_cuentas_receptoras';
		}



		/**
		 * Relacion con la entidad " bancos"
		 * @return ActiveQueryInterface.
		 */
		public function getBanco()
		{
			return $this->hasOne(Banco::className(),['id_banco' => 'id_banco']);
		}
	}

?>