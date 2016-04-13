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
 *  @file EnviarEmailSolicitud.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 01/03/2016
 * 
 *  @class EnviarEmailSolicitud
 *  @brief Modelo que contiene el metodo que se encarga de enviar el email al usuario automaticamente con informacion
 *  relacionada a la realizacion de la solicitud. 
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


class EnviarEmailSolicitud{

    /**
     * [enviarEmail description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email string [description] variable que recibe el email del contribuyente al que se le enviara el correo 
     * con la informacion de la solicitud
     * @param  [type] $solicitud string [description] variable que recibe el tipo de solicitud que realiza el contribuyente
     * @return [type]            [description] retorna la funcion que hace que envie el correo
     */
    public function enviarEmail($email, $solicitud, $nro_solicitud, $documento)
    { 
   $docu = '';
    foreach ($documento as $key => $doc){
      $d = $documento[$key];
      settype($d, 'string');
      if ($docu == ''){

       $docu = $d.'<br>'; 
      }else{
        $docu = $docu .$d.'<br>';
      }

      
                       

                       }    
      
        
        
        return Yii::$app->mailer->compose()
        ->setFrom('manuelz0510@gmail.com')
        ->setTo($email)
        ->setSubject('Solicitudes Online')
        ->setTextBody('Solicitudes Online')
        ->setHtmlBody('Estimado Contribuyente: <br><br>
                       Usted ha realizado con exito su Solicitud '.$solicitud.' de numero: '.$nro_solicitud.'<br><br>'.
                       'Por favor dirijase a la alcaldia para completar la solicitud competente. '.
                       'Los documentos a consignar son los siguientes: <br>'.$docu.'<br>'.
                       'Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                       a terceros estos datos.<br><br>
                       Esta es una cuenta no monitoreada, por favor no responder este correo.')


        ->send();
 
    }

} 

 ?>