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
 *  @file UsoVehiculo.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29/02/2016
 * 
 *  @class UsoVehiculo
 *  @brief  Modelo que instancia la conexion a la base de datos para buscar datos de la tabla usos_vehiculos. 
 * 
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  scenarios
 *  
 *  
 *  @inherits
 *  
 */

namespace common\models\vehiculo\usovehiculo;

use Yii;

/**
 * This is the model class for table "clases_vehiculos".
 *
 * @property string $clase_vehiculo
 * @property string $descripcion
 */
class UsoVehiculo extends \yii\db\ActiveRecord
{
    
    /**
    * @inheritdoc
    */
    public static function getDb()
    {
      return Yii::$app->db;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usos_vehiculos';
    }


    
}
