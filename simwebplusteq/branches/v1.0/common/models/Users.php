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
 *  @file Users.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class Users
 *  @brief Clase que permite conectarse a la tabla users de la base de datos.
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
 *  @inherits
 *  
 */
namespace common\models;
use Yii;
use yii\db\ActiveRecord;

//conexion a la base de datos y retornando Yii::$app->deb y la tabla users...
class Users extends ActiveRecord{
     
    public static function getDb()
    {
        return Yii::$app->db;
    }
    
    public static function tableName()
    {
        return 'users';
    }
     
}

