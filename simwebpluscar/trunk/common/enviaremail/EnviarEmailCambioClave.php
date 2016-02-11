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
 *  @file EnviarEmailCambioClave.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 20/01/2016
 * 
 *  @class enviarEmailCambioCLave
 *  @brief Modelo que envia el correo electronico al usuario con su nueva clave luego de haberla cambiado. 
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
 *  enviarEmailCambioClave
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


class enviarEmailCambioCLave{
    
    /**
     * [enviarEmailCambioCLave description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email     [description] parametro recibido que contiene el email, que es el login del usuario
     * @param  [type] $Password1 [description] parametro recibido que contiene la nueva contraseña del usuario
     * @return [type]            [description] retorna la funcion que hace que envie el correo
    */
    public function enviarEmailCambioCLave($email, $Password1)
    {

        

       return Yii::$app->mailer->compose()
        ->setFrom('manuelz0510@gmail.com')
        ->setTo($email)
        ->setSubject('Cambio de Contraseña')
        ->setTextBody('Cambio de Contraseña')
        ->setHtmlBody('Estimado Contribuyente: <br><br>
                       Usted ha realizado con exito su cambio de datos de acceso<br><br>
                       Usuario: ' .$email.'<br>'.'Contraseña: '.$Password1.'<br><br>'.
                       'Recuerde, esta informacion es personal y de su exclusiva responsabilidad. No divulgar ni trasnferir a terceros estos datos.<br><br>
                       Esta es una cuenta no monitoreada, por favor no responder este correo.')
        ->send();

    }

    }

 ?>