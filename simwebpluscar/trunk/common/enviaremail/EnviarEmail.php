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
 *  @file EnviarEmail.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 04/01/2016
 * 
 *  @class EnviarEmail
 *  @brief Modelo que contiene el metodo que se encarga de enviar el email al usuario automaticamente con su usuario y contraseña. 
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
 *  enviarEmail
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


class EnviarEmail{
  
    public function enviarEmail($email,$nuevaClave)
    {

       return Yii::$app->mailer->compose()
        ->setFrom('manuelz0510@gmail.com')
        ->setTo($email)
        ->setSubject('Bienvenido al Servicio Online')
        ->setTextBody('Bienvenido al Servicio Online')
        ->setHtmlBody('Estimado Contribuyente: <br><br>
                       Usted ha realizado con exito su registro<br><br>
                       Usuario: ' .$email.'<br>'.'Contraseña: '.$nuevaClave)
        ->send();

    }

    }

 ?>