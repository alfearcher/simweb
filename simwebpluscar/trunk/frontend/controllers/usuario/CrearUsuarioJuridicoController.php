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
 *  @file CrearUsuarioJuridicoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuarioController
 *  @brief Controlador para crear usuario Juridico
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  email_existe
 *  username_existe
 *  
 *
 *  @inherits
 *  
 */ 

namespace frontend\controllers\usuario;

use Yii;
use common\models\LoginForm;
use frontend\models\CrearUsuarioForm;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\controllers\mensaje\MensajeController;
use common\conexion\ConexionController;
use common\seguridad\Seguridad;
use common\enviaremail\EnviarEmail;

/**
 * Site controller
 */
class CrearUsuarioJuridicoController extends Controller
{
   
      public $layout = "layout-login";

      public function actionCrearUsuarioJuridico()
      {

      //die('llegue');
              $model = New CrearUsuarioJuridicoForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                      return self::actionBuscarRif($model->naturaleza, $model->cedula,$model->tipo );
                          //return $this->redirect(['buscar-rif']);
                    }
                        
                }
              return $this->render('/usuario/crear-usuario-juridico' , ['model' => $model]);

      }
    
      
      public function actionBuscarRif($naturaleza, $cedula, $tipo)
      {
      

              $dataProvider = CrearUsuarioJuridicoForm::obtenerDataProviderRif($naturaleza, $cedula, $tipo);

              die($dataProvider->getTotalCount());

              if (!isset($dataProvider)){



                  die('no existe contribuyente');
          
              } else {

                  return $this->render('/usuario/contribuyente-encontrado' , ['dataProvider' => $dataProvider, 'naturaleza'=>$naturaleza, 'cedula'=> $cedula,'tipo'=> $tipo ]);

              }
      }



       /**
  *
  *el id se refiere al id contribuyente
  *
  */
      public function actionValidarJuridico($id)
      {
                
          $model = CrearUsuarioJuridicoForm::findContribuyente($id);

          if($model == false){
              
              //se manda a formulario de carga de datos basicos
              die('validacion para formulariuo');
          } else {

            

             if ($model[0]->email == null or trim($model[0]->email) == ""){
                 
                 return MensajeController::actionMensaje('Please, go to your city hall');
              
             } else {

                 $modelAfiliacion = CrearUsuarioJuridicoForm::findAfiliacion($model[0]->id_contribuyente);

                 if ($modelAfiliacion == false){

                    self::salvarAfiliacion($model);
                

                 }  
           
             }
          
        
           }     
        }

      public function salvarAfiliacion($model)
      {
       
        $tabla = 'afiliaciones';
        $arregloDatos = [];
        $arregloCampo = CrearUsuarioJuridicoForm::attributeAfiliacion();

          // die(var_dump($arregloCampo));
         
            foreach ($arregloCampo as $key=>$value){

            $arregloDatos[$value] =0;
          }

          $seguridad = new Seguridad();

          $nuevaClave = $seguridad->randKey(6);

          $salt = $seguridad->randKey(6);

          $password = $nuevaClave.$salt;

          $password_hash = md5($password);
         
          $arregloDatos['id_contribuyente'] = $model[0]->id_contribuyente;

          $arregloDatos['login'] = $model[0] ->email;

          $arregloDatos['salt'] = $salt;

          $arregloDatos['password_hash'] = $password_hash;

          $arregloDatos['fecha_hora_afiliacion'] = date('Y-m-d h:m:i');

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
         
          $conn->open();

          $transaccion = $conn->beginTransaction();

            if ($conexion->guardarRegistroAfiliacion($conn, $tabla, $arregloDatos )){
             // $transaccion->commit();
              //die('exito');

               $enviarEmail = new EnviarEmail();
 
                $enviarEmail->enviarEmail();

              //die('envie correo');
             echo MensajeController::actionMensaje(Yii::t('frontend', 'We have sent you an email with your new user and password'));
            
            } else { 

              $transaccion->rollback();
              die('no guardo');

            }
      }

       

 
} 









      //     $model = CrearUsuarioJuridicoForm::findRif($naturaleza, $cedula, $tipo);


           
      //      //die(var_dump($model));
            
      //       if ($model == false ){

            
            
      //       } else {

      //         if (count($model)>1){
      //           die(var_dump($model));
      //         }

      //           if ($model->inactivo == 0) {

                
      //             if ($model->email == null or  trim($model->email) == ""){
      //               //die('probando');
      //               return MensajeController::actionMensaje('Please, go to ');
      //             } else { 
                    
      //               $modelAfiliacion = CrearUsuarioJuridicoForm::findAfiliacion($model->id_contribuyente);

      //                 if ($modelAfiliacion == false){

      //                 die('no estas afiliado');  
      //               } else {

      //                  return MensajeController::actionMensaje('Usted se encuentra registrado');
                      
      //               }
      //             }

      //       } else {

      //         die('el cliente se encuentra inactivo');
      //       }

            
            

      // } 


?> 


