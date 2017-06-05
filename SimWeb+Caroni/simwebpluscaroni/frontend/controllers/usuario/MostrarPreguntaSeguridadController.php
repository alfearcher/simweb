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
 *  @file MostrarPreguntaSeguridadController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/02/16
 * 
 *  @class MostrarPreguntaSeguridad
 *  @brief Controlador que renderiza vista con las preguntas de seguridad al usuario desde adentro de su cuenta para poder cambiar el password
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *
 *  
 *
 *  @inherits
 *  
 */ 

namespace frontend\controllers\usuario;

use Yii;

use common\models\LoginForm;
use frontend\models\usuario\CrearUsuarioNaturalForm;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use frontend\models\usuario\CrearUsuarioJuridico;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\MostrarPreguntaSeguridadForm;
use frontend\models\usuario\ReseteoPasswordForm;
use common\models\utilidades\Utilidad;
use common\seguridad\Seguridad;
use common\conexion\ConexionController;
use frontend\models\usuario\CrearUsuarioNatural;
use common\enviaremail\EnviarEmailCambioClave;
use common\mensaje\MensajeController;

/**
 * Site controller
 */

session_start();



class MostrarPreguntaSeguridadController extends Controller
{



    
   public $layout = 'layout-main';
   
    /**
     *
     * metodo que realiza la busqueda de las preguntas de seguridad del contribuyente una vez que este esta dentro de su cuenta,
     * para mostrarlas y asi poder cambiar su password desde adentro de su cuenta
     * 
     * @return retorna la vista con las preguntas de seguridad del contribuyente
     */
    public function actionBuscarMostrarPreguntaSeguridad()
    {
     
        $datosContribuyente = yii::$app->user->identity;

        $buscarPreguntas = PreguntaSeguridadContribuyente::find()
                                                    ->where([
                                                    'id_contribuyente' => $datosContribuyente->id_contribuyente,
                                                    'inactivo' => 0,

                                                        ])
                                                    ->all();
        if($buscarPreguntas == false){

          return MensajeController::actionMensaje(405);
        }

        $model = new MostrarPreguntaSeguridadForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                //  die('llegue a mostrar');

                   return $this->redirect (['/usuario/mostrar-pregunta-seguridad/reseteo-password',
                                                                          'id_contribuyente' => $datosContribuyente->id_contribuyente,



                                                                                                          ]);

                }
                

              
            }
            return $this->render('/usuario/mostrar-pregunta-seguridad', [
                                                        'model' => $model,
                                                        'preguntaSeguridad' => $buscarPreguntas,
            ]);  
    }

    

    /**
     * [actionReseteoPassword description] metodo que resetea el password del contribuyente , cuando este lo cambia desde adentro de su cuenta
     * @return [type] [description] retorna un mensaje indicando que el usuario y la contraseña fueron enviados por correo
     */
    public function actionReseteoPassword()
    {


        $idContribuyente = yii::$app->user->identity->id_contribuyente;

       $model = New ReseteoPasswordForm();

        $postData = Yii::$app->request->post();

        if ( $model->load($postData) && Yii::$app->request->isAjax ){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
                }
                if ( $model->load($postData) ) {

                    if ($model->validate()){
                 
                        $actualizarPassword =  self::actualizarPasswordNatural($idContribuyente, $model->password1);

                        if ($actualizarPassword == true){

                            $consultaContribuyente = new CrearusuarioNatural();

                            $consultaContribuyente = CrearUsuarioNatural::find()
                                                                          ->where([
                                                                         'id_contribuyente' => $idContribuyente,
                                                                         'inactivo' => 0,
                                                                          ])
                                                                         ->one();

                            $enviarEmail = new EnviarEmailCambioClave();
                            $enviarEmail->EnviarEmailCambioClave($consultaContribuyente->email, $model->password1);
                      
                            return MensajeController::actionMensaje(402);

                        }
                    
                    }
                        
                
              

        }
        return $this->render('/usuario/reseteo-password'   , ['model' => $model,
                                                                    'id_contribuyente' => $idContribuyente,
                                                                    ]);  die('llegue a reseteo password');
    }

    /**
     * [actualizarPasswordNatural description] metodo que actualiza el password del contribuyente en la base de datos
     * @param  [type] $idContribuyente [description] id del contribuyente 
     * @param  [type] $password1       [description] password del contribuyente a cambiar
     * 
     */
    public function actualizarPasswordNatural($idContribuyente, $password1){

      $tableName = 'afiliaciones';
      $arregloCondition = ['id_contribuyente' => $idContribuyente]; 

      $seguridad = new Seguridad();

      $nuevaClave = $seguridad->randKey(6);

      $salt = Utilidad::getUtilidad();

      $password = $password1.$salt;

      $password_hash = md5($password);
         
      $arregloDatos = ['password_hash' => $password_hash];

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistroNatural($conn, $tableName, $arregloDatos, $arregloCondition)){

              $transaccion->commit();
              $conn->close();
              return true;
              
          }else{ 
         
              $transaccion->rollback();
              $conn->close();
              return false;
          }

  }



       




    

    

}

?>
