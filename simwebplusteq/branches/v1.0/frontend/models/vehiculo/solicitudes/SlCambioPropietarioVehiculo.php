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
 *  @file SlCambioPropietarioVehiculo.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 27/06/2016
 *
 *  @class SlCambioPropietarioVehiculo
 *  @brief Modelo que instancia la conexion a la base de datos para buscar datos de la tabla sl_cambios_propietarios
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *  getDb
 *  tableName
 *
 *
 *
 *
 *  @inherits
 *
 */

    namespace frontend\models\vehiculo\solicitudes;

    use Yii;
    use yii\base\Model;
    use common\models\Users;
    use yii\db\ActiveRecord;
    use frontend\models\vehiculo\solicitudes\SlVehiculos;
    use frontend\models\vehiculo\solicitudes\SlVehiculosForm;
   


    class SlCambioPropietarioVehiculo extends ActiveRecord
    {

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
          return 'sl_cambios_propietarios';
        }



      




    }

 ?>