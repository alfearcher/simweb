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
 *  @file Tasa.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-10-2015
 *
 *  @class Tasa
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

	namespace backend\models\tasa;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\tasasolicitud\TasaMultaSolicitud;
	use common\models\presupuesto\codigopresupuesto\CodigosContables;
	use backend\models\impuesto\Impuesto;
	use common\models\tasas\GrupoSubnivel;
	use common\models\tasas\TiposRangos;
	/**
	* 	Clase
	*/
	class Tasa extends ActiveRecord
	{
		public $codigosub;

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
			return 'varios';
		}



		/**
		 * Relacion con la entidad "config-solic-tasas-multas".
		 * @return activeRecord.
		 */
		public function getTasaMultaSolicitud()
		{
			return $this->hasMany(TasaMultaSolicitud::className(), ['id_impuesto' => 'id_impuesto']);
		}

		public function getCodigoContable()
		{
			return $this->hasOne(CodigosContables::className(), ['id_codigo' => 'id_codigo']);
		}

		public function getImpuestos()
		{
			return $this->hasOne(Impuesto::className(), ['impuesto' => 'impuesto']);
		}

		public function getGrupoSubNivel()
		{
			return $this->hasOne(GrupoSubnivel::className(), ['grupo_subnivel' => 'grupo_subnivel']);
		}

			public function getTipoRango()
		{
			return $this->hasOne(TiposRangos::className(), ['tipo_rango' => 'tipo_rango']);
		}

 }