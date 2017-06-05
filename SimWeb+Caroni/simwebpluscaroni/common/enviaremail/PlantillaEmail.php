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

  private $_from;



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

        if ( trim($email) == true ) {
          $docu = '';
          if ( $documento !== null ) {
              $documentoA =  implode("<br>*", $documento['a']);
              $documentoB =  implode("<br>*", $documento['b']);
          }

          $contribuyente = self::busquedaTipoContribuyente();

                $from = Yii::$app->ente->getEmail()[0];
                $to = $email;
                $subject = $solicitud.', Solicitud: '.$nro_solicitud;
                $textBody = 'Solicitudes Online';
                $body =     'Estimado Contribuyente: '.$contribuyente.'<br><br>
                             Usted ha realizado con exito su Solicitud '.$solicitud.' numero: '.$nro_solicitud.'<br><br>'.
                             'Por favor dirijase a la alcaldia para completar la solicitud correspondiente. '.
                             'Los documentos a consignar obligatoriamente en original y copia son los siguientes: <br><br>*'.$documentoA.'<br><br>'.

                             'Adicionalmente, deberá consignar original y copia de los siguientes documentos si se tratare
                             de alguno de estos casos: <br><br>*'.
                             $documentoB.'<br><br>'.
                             //'En fisico y en digital debidamente firmado por un profesional de area colegiado y con solvencia<br><br>'.

                             'La aprobación o rechazo de su solicitud, le será notificada a través de su correo electrónico, condición ésta que debe esperar para seguir procesando su requerimiento. La celeridad en el procesamiento de su solicitud dependerá del tiempo que disponga para consignar los documentos que la soportan. <br>Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                             a terceros estos datos.<br><br>
                             Esta es una cuenta no monitoreada, por favor no responder este correo.<br>***REGULACIÓN DE USO DEL CORREO ELECTRÓNICO DE  LA ALCALDIA DEL MUNICIPIO BOLIVARIANO DE GUAICAIPURO HACIA INTERNET***  La información contenida en este correo electrónico y cualquier
anexo puede ser de carácter confidencial y es propiedad de la alcaldía del Municipio Bolivariano de Guaicaipuro. Sólo está permitido su uso, copia, transmisión, recepción o distribución a personas debidamente autorizadas. Si usted recibió este correo por error por favor destrúyalo y/o elimine cualquier copia guardada en su sistema y notifique inmediatamente al remitente o a la dirección de correo electrónica: hacienda@alcaldiadeguicaipuro.gob.ve. Usted no debe utilizar la información contenida para ningún propósito ni compartirla con otras personas.';

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
        $from = Yii::$app->ente->getEmail()[0];
        $to = $email;
        $subject = 'Solicitud procesada';
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




    /***/
    public function plantillaSolicitudSustitutivaAprobada($email, $cuerpo)
    {
        $from = Yii::$app->ente->getEmail()[0];
        $to = $email;
        $subject = 'Solicitud procesada';
        $textBody = 'Solicitudes Online';

        $contribuyente = self::busquedaTipoContribuyente();

        $cuerpo2 =  'Estimado Contribuyente: '. $contribuyente .'<br><br>' .
                    'Su solicitud de Declaración Sustitutiva ha sido aceptada. Si del análisis realizado a los
                    documentos que soportan su requerimiento, resultare una diferencia a su favor, la Administración
                    Tributaria le notificará el reconocimiento del crédito fiscal y compensará, tras los trámites
                    correspondientes, a partir del trimestre inmediato posterior a su notificación.';


        $body = $cuerpo2 . '<br><br>' . 'Esta es una cuenta no monitoreada, por favor no responder este correo.';

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


        if ( trim($email) == true ) {
          $docu = '';
          if ( $documento !== null ) {
              $documentoA =  implode("<br>*", $documento['a']);
              $documentoB =  implode("<br>*", $documento['b']);
          }

          $contribuyente = self::busquedaTipoContribuyente();

                $from = Yii::$app->ente->getEmail()[0];
                $to = $email;
                $subject = $solicitud.', Solicitud: '.$nro_solicitud;
                $textBody = 'Solicitudes Online';
                $body =     'Estimado Contribuyente: '.$contribuyente.'<br><br>
                             Usted ha realizado con exito su Solicitud '.$solicitud.' numero: '.$nro_solicitud.'<br><br>'.
                             'Por favor dirijase a la alcaldia para completar la solicitud correspondiente. '.
                             'Los documentos a consignar obligatoriamente en original y copia son los siguientes: <br><br>*'.$documentoA.'<br><br>'.

                             'Adicionalmente, deberá consignar original y copia de los siguientes documentos si se tratare
                             de alguno de estos casos: <br><br>*'.
                             $documentoB.'<br><br>'.
                             //'En fisico y en digital debidamente firmado por un profesional de area colegiado y con solvencia<br><br>'.

                             'La aprobación o rechazo de su solicitud, le será notificada a través de su correo electrónico, condición ésta que debe esperar para seguir procesando su requerimiento. La celeridad en el procesamiento de su solicitud dependerá del tiempo que disponga para consignar los documentos que la soportan. <br>Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                             a terceros estos datos.<br><br>
                             Esta es una cuenta no monitoreada, por favor no responder este correo.<br>***REGULACIÓN DE USO DEL CORREO ELECTRÓNICO DE  LA ALCALDIA DEL MUNICIPIO BOLIVARIANO DE GUAICAIPURO HACIA INTERNET***  La información contenida en este correo electrónico y cualquier
anexo puede ser de carácter confidencial y es propiedad de la alcaldía del Municipio Bolivariano de Guaicaipuro. Sólo está permitido su uso, copia, transmisión, recepción o distribución a personas debidamente autorizadas. Si usted recibió este correo por error por favor destrúyalo y/o elimine cualquier copia guardada en su sistema y notifique inmediatamente al remitente o a la dirección de correo electrónica: hacienda@alcaldiadeguicaipuro.gob.ve. Usted no debe utilizar la información contenida para ningún propósito ni compartirla con otras personas.';

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



    /**
     * [plantillaRecuperarLogin description] metodo que envia email al usuario con la informacion que reciba como parametros
     * @param  [type] $email string [description] variable que recibe el email del contribuyente al que se le enviara el correo
     * con la informacion de la solicitud
     * @param  [type] $solicitud string [description] variable que recibe el tipo de solicitud que realiza el contribuyente
     * @param  [type] $clave string [description] variable que recibe el password que tiene el contribuyente
     * @param  [type] $contribuyente string [description] variable que recibe el nombre o razon social del contribuyente
     * @return [type]            [description] retorna la funcion que hace que envie el correo
     */
    public function plantillaRecuperarLogin($email, $solicitud, $clave,$login,$contribuyente)
    {

        if ( trim($email) == true ) {




                $from = Yii::$app->ente->getEmail()[0];
                $to = $email;
                $subject = $solicitud;
                $textBody = 'Solicitudes Online';
                $body =     'Estimado Contribuyente: '.$contribuyente.'<br><br>
                       Usted ha realizado con exito la recuperacion de usuario y contraseña<br><br>
                       Usuario: ' .$login.'<br>'.'Contraseña: '.$clave.'<br><br>'.
                       'A partir de este momento puede disfrutar de nuestro servicio "on-line".<br>
                       Recuerde, esta informacion es personal y de su exclusiva responsabilidad y se agradece no divulgar ni transferir
                       a terceros estos datos<br><br>
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
/*Por favor dirijase a la alcaldia para completar la solicitud correspondiente. Los documentos a consignar obligatoriamente en original y copia son los siguientes:
Documento de Propiedad del Inmueble; CI del propietario                                                                 Adicionalmente, deberá consignar original y copia de los siguientes documentos si se tratare de alguno de estos casos:                                                                                                                                      Registro Mercantil; Actas de Asamblea (Si el propietario es persona jurídica);                                                       Declaración sucesoral y RIF (En caso de ser una sucesión)                                                                     Autorización simple (En caso de ser familiar directo: padres, hijos, nietos, esposa o conyuge).                        Poder notariado (En caso de que la tramitación sea para un tercero).                                                                   En caso de inscripción de bienhechurias debe consignar carta de residencia del consejo comunal.                                                             Inmueble con superficie mayor a 1.500 m2 deberá consignar plano utm datum regven en fisico y en digital debidamente firmado por un profesional de area colegiado y con solvencia                                                                                                                                                   La aprobación o rechazo de su solicitud, le será notificada a través de su correo electrónico, condición ésta que debe esperar para seguir procesando su requerimiento. La celeridad en el procesamiento de su solicitud dependerá del tiempo que disponga para consignar los documentos que la soportan.
Recuerde, esta información es personal de su exclusiva responsabilidad se agradece no divulgar ni transferir a terceros estos datos.

Esta es una cuenta no monitoreada, por favor no responder este correo.*/


 ?>