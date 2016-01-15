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

namespace common\enviaremail;

use Yii;
use yii\base\Model;
use common\models\Users;
use yii\db\ActiveRecord;


class EnviarEmail extends Model{
  
    public function enviarEmail()
    {

       return Yii::$app->mailer->compose()
        ->setFrom('manuelz0510@gmail.com')
        ->setTo('manuel1122@hotmail.com')
        ->setSubject('Usuario y contraseña')
        ->setTextBody('Su nuevo usuario y contraseña son:')
        ->setHtmlBody($model->email
                      $model->nuevaClave)
        ->send();

    }

    }

 ?>