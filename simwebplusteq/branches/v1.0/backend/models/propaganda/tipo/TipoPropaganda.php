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
 *  @file TipoPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @class TipoPropaganda
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

    namespace backend\models\propaganda\tipo;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;


    /**
    *  Clase
    */
    class TipoPropaganda extends ActiveRecord
    {

        /**
         *  Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
         *  Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
         *  @return Nombre de la base de datos
         */
        public static function getDb()
        {
            return Yii::$app->db;
        }


        /**
         *  Metodo que retorna el nombre de la tabla que utiliza el modelo.
         *  @return Nombre de la tabla del modelo.
         */
        public static function tableName()
        {
            return 'tipos_propagandas';
        }

    }

?>