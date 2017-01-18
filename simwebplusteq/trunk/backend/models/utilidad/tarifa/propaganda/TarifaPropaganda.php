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
 *  @file TarifaPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-04-2016
 *
 *  @class TarifaPropaganda
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

	namespace backend\models\utilidad\tarifa\propaganda;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\propaganda\tipo\TipoPropaganda;
	use backend\models\propaganda\uso\UsoPropaganda;
	use backend\models\propaganda\clase\ClasePropaganda;

	/**
	* Clase
	*/
	class TarifaPropaganda extends ActiveRecord
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
			return 'tarifas_propagandas';
		}



		/**
         * Relacion con la entidad "usos-propagandas".
         * @return [type] [description]
         */
        public function getUsoPropaganda()
        {
            return $this->hasOne(UsoPropaganda::className(), ['uso_propaganda' => 'uso_propaganda']);
        }



        /**
         * Relacion con la entidad "clases-propagandas".
         * @return [type] [description]
         */
        public function getClasePropaganda()
        {
            return $this->hasOne(ClasePropaganda::className(), ['clase_propaganda' => 'clase_propaganda']);
        }



        /**
         * Relacion con la entidad "tipos-propagandas".
         * @return [type] [description]
         */
        public function getTipoPropaganda()
        {
            return $this->hasOne(TipoPropaganda::className(), ['tipo_propaganda' => 'tipo_propaganda']);
        }


	}

?>