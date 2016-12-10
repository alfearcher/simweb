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
 *  @file TarifaVehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class TarifaVehiculo
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

	namespace backend\models\utilidad\tarifa\vehiculo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculoDetalle;
	use common\models\vehiculo\clasevehiculo\ClaseVehiculo;
	use backend\models\utilidad\tiporango\TipoRango;


	/**
	* 	Clase
	*/
	class TarifaVehiculo extends ActiveRecord
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
			return 'tarifas_vehiculos';
		}



		/**
		 * Relacion con la entidad "tarifas-vehiculos-detalles".
		 * @return [type] [description]
		 */
		public function getTarifaDetalle()
		{
			return $this->hasMany(TarifaVehiculoDetalle::className(), ['id_tarifa_vehiculo' => 'id_tarifa_vehiculo']);
		}



		/**
		 * Relacion con la entidad "clases-vehiculos".
		 * @return [type] [description]
		 */
		public function getClaseVehiculo()
		{
			return $this->hasOne(ClaseVehiculo::className(), ['clase_vehiculo' => 'clase_vehiculo']);
		}




		/**
		 * Relacion con la entidad "tipos-rangos". El segundo tipo, corresponde a la entidad
		 * "tarifas-vehiculos" (tipo_monto).
		 * @return [type] [description]
		 */
		public function getTipoRango()
		{
			return $this->hasOne(TipoRango::className(), ['tipo_rango' => 'tipo_monto']);
		}

	}

?>