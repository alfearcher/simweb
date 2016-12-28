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
use frontend\models\usuario\CrearUsuarioNatural;
use common\models\contribuyente\ContribuyenteBase;
use common\enviaremail\EnviarEmailSolicitud;

class PlantillaEmail{

  private $_from = 'manuelz0510@gmail.com';



  /**
   * [busquedaTipoContribuyente description] Metodo que realiza la busqueda del tipo de usuario (natural o juridico), para asi poder
   * identificarlo al momento de enviar el correo por su nombre o razon social
   * @return [type] [description] retorna la razon social si es juridico o nombre y apellido si es natural.
   */
  private function busquedaTipoContribuyente(){

    $idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;
    // $idContribuyente = yii::$app->user->identity->id_contribuyente;

  //     $busqueda = CrearUsuarioNatural::find()
  //                                   ->where([
  //                                     'id_contribuyente' => $idContribuyente,

  //                                     ])
  //                                   ->all();
  //           if ($busqueda == true){
  //             if($busqueda[0]->tipo_naturaleza == 1){
  //               return $busqueda[0]->razon_social;
  //             }else{
  //               return $busqueda[0]->nombres.' '.$busqueda[0]->apellidos;
  //             }

  //           }else{
  //             return false;
  //           }
  //
  //


    $contribuyente = '';
    if ($idContribuyente > 0 ) {
      $contribuyente = ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
    }
    return $contribuyente;


  }



    /**
     * [enviarEmail description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email string [description] variable que recibe el email del contribuyente al que se le enviara el correo
     * con la informacion de la solicitud
     * @param  [type] $solicitud string [description] variable que recibe el tipo de solicitud que realiza el contribuyente
     * @return [type]            [description] retorna la funcion que hace que envie el correo
     */
    public function plantillaEmailSolicitud($email, $solicitud, $nro_solicitud, $documento)
    {

        if ( trim($email) == '' ) {
          $docu = '';
          if ( $documento !== null ) {
              $docu =  implode("<br>*", $documento);
          }

          $contribuyente = self::busquedaTipoContribuyente();

                $from = 'manuelz0510@gmail.com';
                $to = $email;
                $subject = 'Solicitudes Online';
                $textBody = 'Solicitudes Online';
                $body =     'Estimado Contribuyente: '.$contribuyente.'<br><br>
                             Usted ha realizado con exito su Solicitud '.$solicitud.' de numero: '.$nro_solicitud.'<br><br>'.
                             'Por favor dirijase a la alcaldia para completar la solicitud correspondiente. '.
                             'Los documentos a consignar son los siguientes: <br><br>*'.$docu.'<br><br>'.
                             'Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                             a terceros estos datos.<br><br>
                             Esta es una cuenta no monitoreada, por favor no responder este correo.';

              $enviarEmail = new EnviarEmailSolicitud();
              $enviar = $enviarEmail->enviarEmail($from, $to, $subject, $textBody, $body);

                if($enviar == true){
                  return true;
                }else{
                  return false;
                }
        } else {
          return false;
        }
    }



    /***/
    public function plantillaSolicitudProcesada($email, $cuerpo)
    {
        $from = 'manuelz0510@gmail.com';
        $to = $email;
        $subject = 'Solicitudes Online';
        $textBody = 'Solicitudes Online';
        $body = $cuerpo . '<br><br>' . 'Esta es una cuenta no monitoreada, por favor no responder este correo.';

        $enviarEmail = new EnviarEmailSolicitud();
        $enviar = $enviarEmail->enviarEmail($from, $to, $subject, $textBody, $body);

        if($enviar == true){
          return true;
        }else{
          return false;
        }
    }

    /**
     * [enviarEmail description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email string [description] variable que recibe el email del contribuyente al que se le enviara el correo
     * con la informacion de la solicitud
     * @param  [type] $solicitud string [description] variable que recibe el tipo de solicitud que realiza el contribuyente
     * @return [type]            [description] retorna la funcion que hace que envie el correo
     */
    public function plantillaEmailSolicitudInscripcion($email, $solicitud, $nro_solicitud, $documento)
    {

        if ( trim($email) == '' ) {
          $docu = '';
          if ( $documento !== null ) {
              $docu =  implode("<br>*", $documento);
          }
die(var_dump($email).var_dump(trim($email)));
          $contribuyente = self::busquedaTipoContribuyente();

                $from = 'manuelz0510@gmail.com';
                $to = $email;
                $subject = 'Solicitudes Online';
                $textBody = 'Solicitudes Online';
                $body =     'Estimado Contribuyente: '.$contribuyente.'<br><br>
                             Usted ha realizado con exito su Solicitud '.$solicitud.' de numero: '.$nro_solicitud.'<br><br>'.
                             'Por favor dirijase a la alcaldia para completar la solicitud correspondiente. '.
                             'Los documentos a consignar son los siguientes: <br><br>*'.$docu.'<br><br>'.
                             'Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                             a terceros estos datos.<br><br>
                             Esta es una cuenta no monitoreada, por favor no responder este correo.';

              $enviarEmail = new EnviarEmailSolicitud();
              $enviar = $enviarEmail->enviarEmail($from, $to, $subject, $textBody, $body);

                if($enviar == true){
                  return true;
                }else{
                  return false;
                }
        } else {
          return false;
        }
    }

}

 ?>