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
 *  @file OpcionCrearUsuarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuarioController
 *  @brief Controlador para crear usuario tanto juridico como natural
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
use frontend\models\usuario\CrearUsuarioNatural;
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
use frontend\models\usuario\VerificarPreguntasContribuyenteNaturalForm;
use frontend\models\usuario\VerificarPreguntasContribuyenteJuridicoForm;
use frontend\controllers\mensaje\MensajeController;
use frontend\models\usuario\ValidarCambiarPasswordNaturalForm;
use frontend\models\usuario\ReseteoPasswordNaturalForm;
use common\seguridad\Seguridad;
use common\models\utilidades\Utilidad;
use common\conexion\ConexionController;
use common\enviaremail\EnviarEmailCambioClave;

/**
 * Site controller
 */
class CambiarPasswordContribuyenteController extends Controller
{
   
    public $layout = "layout-login";

    //-- INICIO ACTIONSELECCIONARTIPOUSUARIO -->

    /**
     *
     * metodo que levanta la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de registro que desee hacer
     */
   public function actionSeleccionarTipoContribuyente(){

    return $this->render('/usuario/seleccionar-tipo-recuperar-password');
   }

    public function actionCambiarPasswordNatural()
    {

            $model = New VerificarPreguntasContribuyenteNaturalForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {


                    if ($model->validate()){

                        $buscarId = new VerificarPreguntasContribuyenteNaturalForm();

                        $p = $buscarId::buscarIdContribuyente($model);
                        if ($p == true){

                            $r = $buscarId::buscarIdAfiliaciones($p->id_contribuyente);

                            if ($r == true){
                                //die($p->id_contribuyente);
                          
                                $q =  $buscarId::buscarPreguntaSeguridad($p->id_contribuyente);
                             //die(var_dump($q));

                                if ($q == true){
                                  //die(var_dump($q));


                                    //die(var_dump($q));
                                $pregunta1 = $q[0]->pregunta;
                                $pregunta2 = $q[1]->pregunta;
                                $pregunta3 = $q[2]->pregunta;
                                $id_contribuyente = $q[0]->id_contribuyente;
                                  //die($q[0]->id_contribuyente);

                                    //die($q[2]->pregunta); 

                                    return $this->redirect(['/usuario/cambiar-password-contribuyente/mostrar-pregunta-seguridad-natural',
                                                                                                                        'pregunta1' => $pregunta1,
                                                                                                                        'pregunta2' => $pregunta2,
                                                                                                                        'pregunta3' => $pregunta3,
                                                                                                                        'id_contribuyente' => $id_contribuyente,
                                                                                                                         ]);
                                                                                                                          
                                   } else {

                                   return MensajeController::actionMensaje(Yii::t('frontend','You have not asigned security answers yet, please go to the city hall')); 
                                }
                          }else {
                                   return MensajeController::actionMensaje(Yii::t('frontend','You have not signed in afiliations, please go to create user')); 
                          }

                    }else{
                        return MensajeController::actionMensaje(Yii::t('frontend','Please Go to your city hall to reset the password'));
                    }
                }

            }



                        
                
              return $this->render('/usuario/verificar-preguntas-seguridad-natural' , ['model' => $model]);
                    

    } 

       public function actionCambiarPasswordJuridico()
    {

            $model = New VerificarPreguntasContribuyenteJuridicoForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                      //return self::actionBuscarRif($model->naturaleza, $model->cedula,$model->tipo );

                      //return $this->redirect(['juridico']);

                          //return $this->redirect(['buscar-rif']);
                    }
                        
                }
              return $this->render('/usuario/verificar-preguntas-seguridad-juridico' , ['model' => $model]);

      } 

      public function actionMostrarPreguntaSeguridadNatural($pregunta1, $pregunta2, $pregunta3, $id_contribuyente){

        $model = New ValidarCambiarPasswordNaturalForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                     return $this->redirect (['/usuario/cambiar-password-contribuyente/reseteo-password-natural',
                                                                                                          'id_contribuyente' => $id_contribuyente,

                                                                                                          ]);
                    }
                        
                }
              


        return $this->render('/usuario/mostrar-pregunta-seguridad-natural' , 
                                                        [
                                                        'model' => $model,
                                                        'pregunta1' => $pregunta1,
                                                        'pregunta2' => $pregunta2,
                                                        'pregunta3' => $pregunta3,
                                                        'id_contribuyente' => $id_contribuyente,




                                                        ]); 


      }

      public function actionReseteoPasswordNatural($id_contribuyente){




        $model = New ReseteoPasswordNaturalForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){
                     // die($model->password1);

                    $actualizarNatural =  self::actualizarPasswordNatural($id_contribuyente, $model->password1);

                    if ($actualizarNatural == true){

                      
                      

                      $consultaContribuyente = new CrearusuarioNatural();

                      $consultaContribuyente = CrearUsuarioNatural::find()
                                                                   ->where([
                                'id_contribuyente' => $id_contribuyente,
                                'tipo_naturaleza' => 0,
                                'inactivo' => 0,
                                ])
                                ->one();

                                //die($consultaContribuyente->email);

                      $enviarEmail = new EnviarEmailCambioClave();
                      $enviarEmail->EnviarEmailCambioClave($consultaContribuyente->email, $model->password1);
                      
                      return MensajeController::actionMensaje(Yii::t('frontend', 'We have sent you an email with your new password'));

                    }
                    
                    }
                        
                }
              


        return $this->render('/usuario/reseteo-password-natural' , ['model' => $model,
                                                                    'id_contribuyente' => $id_contribuyente,
                                                                    ]); 


      }

      public function actualizarPasswordNatural($id_contribuyente, $password1){

       // die(var_dump($model));

         
        $tableName = 'afiliaciones';
        $arregloCondition = ['id_contribuyente' => $id_contribuyente]; 

        //die($password1);
          // die(var_dump($arregloCampo));
         
            

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

             // $transaccion->commit();
                $conn->close();
                return true;
              
            }else{ 
         
            $transaccion->rollback();
                 $conn->close();
                 return false;
            }

            }

      
      

   
      

    //-- FIN ACTIONSELECCIONARTIPOUSUARIO -->

    

}

?>
