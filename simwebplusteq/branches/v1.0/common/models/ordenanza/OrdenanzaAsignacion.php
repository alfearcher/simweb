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
 *  @file OrdenanzaAsignacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-12-2015
 *
 *  @class OrdenanzaAsignacion
 *  @brief Clase Modelo principal
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

 	namespace common\models\ordenanza;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\asignacion\Asignacion;
	use backend\models\configuracion\tipoasignacion\TipoAsignacion;
	use backend\models\configuracion\aplicacionasignacion\AplicacionAsignacion;

	class OrdenanzaAsignacion extends ActiveRecord
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
			return 'ordenanzas_asignaciones';
		}


		/**
		 * Relacion con la entidad "asignaciones".
		 * @return active record
		 */
		public function getAsignacion()
		{
			return $this->hasOne(Asignacion::className(), ['id_asignacion' => 'id_asignacion']);
		}



		/**
		 * Relacion con la entidad "aplicacion-asignaciones".
		 * @return active record
		 */
		public function getAplicacion()
		{
			return $this->hasOne(AplicacionAsignacion::className(), ['id_aplicacion' => 'id_aplicacion']);
		}



		/**
		 * Relacion con la entidad "tipos-asignaciones".
		 * @return active record
		 */
		public function getTipoAsignacion()
		{
			return $this->hasOne(TipoAsignacion::className(), ['tipo_asignacion' => 'tipo_asignacion']);
		}


	}
 ?>