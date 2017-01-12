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
 *  @file HistoricoSolvencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class HistoricoSolvencia
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

	namespace backend\models\aaee\historico\solvencia;

 	use Yii;
	use yii\db\ActiveRecord;
	use backend\models\impuesto\Impuesto;
	use backend\models\inmueble\InmueblesConsulta;
	use backend\models\vehiculo\VehiculosForm;

	/**
	* Clase
	*/
	class HistoricoSolvencia extends ActiveRecord
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
			return 'historico_solvencias';
		}


		/**
		 * Relacion con la entidad impuesto
		 * @return active record
		 */
		public function getImpuestos()
		{
			return $this->hasOne(Impuesto::className(),['impuesto' => 'impuesto']);
		}


		/**
		 * Relacion con la entidad "inmuebles"
		 * @return active record
		 */
		public function getInmueble()
		{
			return $this->hasOne(InmueblesConsulta::className(),['id_impuesto' => 'id_impuesto']);
		}



		/**
		 * Relacion con la entidad "vehiculos"
		 * @return active record
		 */
		public function getVehiculo()
		{
			return $this->hasOne(VehiculosForm::className(),['id_vehiculo' => 'id_impuesto']);
		}


	}

?>