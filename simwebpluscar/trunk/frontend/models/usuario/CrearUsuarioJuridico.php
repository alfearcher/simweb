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
 *  @file CrearUsuarioJuridico.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 04/01/2016
 * 
 *  @class CrearUsuarioJuridico
 *  @brief Modelo que instancia la conexion a base de datos para buscar datos en contribuyentes. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 * 
 *  getDb
 *  tableName
 *  
 *
 *  @inherits
 *  
 */ 

namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use common\models\Users;
use yii\db\ActiveRecord;


class CrearUsuarioJuridico extends ActiveRecord{
  
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
      return 'contribuyentes';
    }
    

   
     
  
}

 ?>