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
 *  @file CambiarPasswordContribuyenteController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 27/01/2016
 * 
 *  @class CambiarPasswordContribuyenteController
 *  @brief Controlador para cambiar el password de los contribuyentes, tanto natural como juridico
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

use frontend\models\usuario\ValidarCambiarPasswordNaturalForm;
use frontend\models\usuario\ValidarCambiarPasswordJuridicoForm;
use frontend\models\usuario\ReseteoPasswordNaturalForm;
use frontend\models\usuario\ReseteoPasswordJuridicoForm;
use common\seguridad\Seguridad;
use common\models\utilidades\Utilidad;
use common\conexion\ConexionController;
use common\enviaremail\EnviarEmailCambioClave;
use common\models\session\Session;
use common\mensaje\MensajeController;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\MostrarPreguntaSeguridadForm;

use frontend\models\usuario\Afiliacion;
session_start();
/**
 * Site controller
 */
class CambiarPasswordContribuyenteController extends Controller
{   
    
   
    public $layout = "layout-login";

    //-- INICIO ACTIONSELECCIONARTIPOUSUARIO -->

    /**
     *
     * metodo que renderiza la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de cambio de password que desee realizar
     */
   public function actionSeleccionarTipoContribuyente(){
   
    return $this->render('/usuario/seleccionar-tipo-recuperar-password');
   }

    
    /**
     * [actionCambiarPasswordNatural] metodo que busca los id del usuario natural en la tabla contribuyente, afiliacion y 
     * luego busca las preguntas de seguridad en la tabla para cambiarlas
     * @return [type] [description] vista que te pide ingresar tu cedula y tu email para buscar las preguntas de seguridad
     * @return [description] render de la vista que te muestra las preguntas de seguridad seteadas 
     * para autenticar el usuario ($pregunta1, $pregunta2,$pregunta3)
     */
    public function actionCambiarPasswordNatural(){

            $model = New VerificarPreguntasContribuyenteNaturalForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

               
                
                if ( $model->load($postData) ) {


                    if ($model->validate()){

                        $buscarId = new VerificarPreguntasContribuyenteNaturalForm();

                        $buscarContribuyente = $buscarId::buscarIdContribuyente($model);
                        if ($buscarContribuyente == true){

                            $buscarAfiliaciones = $buscarId::buscarIdAfiliaciones($buscarContribuyente->id_contribuyente);

                            if ($buscarAfiliaciones == true){
                                
                                $buscarPreguntaSeguridadNatural =  $buscarId::buscarPreguntaSeguridad($buscarAfiliaciones->id_contribuyente);
                             

                                    if ($buscarPreguntaSeguridadNatural == true){
                                  
                                    $_SESSION['preguntaSeguridad'] = $buscarPreguntaSeguridadNatural;
                                  
                                    return $this->redirect(['/usuario/cambiar-password-contribuyente/mostrar-pregunta-seguridad-natural']);
                                                                                                                          
                                    } else {

                                    return MensajeController::actionMensaje(401);

                                
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

    /**
     * [actionCambiarPasswordJuridico description] metodo que busca los id del usuario juridico en la tabla contribuyente, afiliacion y 
     * luego busca las preguntas de seguridad en la tabla para cambiarlas
     * @return [type] [description] vista que te pide ingresar tu rif y tu email para buscar las preguntas de seguridad
     * @return [description]render de la vista que te pide seleccionar la empresa asociada al rif enviado ($naturaleza, $cedula, $tipo, $email)
     */
    public function actionCambiarPasswordJuridico(){

        $model = New VerificarPreguntasContribuyenteJuridicoForm();

            $postData = Yii::$app->request->post();

                if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                        $buscarId = new VerificarPreguntasContribuyenteJuridicoForm();

                            $naturaleza = $model->naturaleza;
                            $cedula = $model->cedula;
                            $tipo = $model->tipo;

                            $buscarAfiliacionesJuridico = $buscarId->buscarIdAfiliaciones($model);
                            //die(var_dump($buscarAfiliacionesJuridico));

                            if ($buscarAfiliacionesJuridico == false){
                                return MensajeController::actionMensaje(Yii::t('frontend', 'Sorry, you are not afiliated, go to create user')); 
                            }else{ 
                           
                            $idsContribuyente = [];

                            foreach($buscarAfiliacionesJuridico as $key => $value){
                              
                                $idsContribuyente[] = $buscarAfiliacionesJuridico[$key]['id_contribuyente'];
                              
                            }

                              
                           
                                   
                                $dataProvider = $buscarId::buscarIdContribuyente($idsContribuyente);

                                if ($dataProvider == true){
                                    
                                   
                                    return $this->render('/usuario/lista-contribuyente-juridico' , [
                                                                                                  'dataProvider' => $dataProvider,
                                                                                                  'naturaleza' => $naturaleza,
                                                                                                  'cedula' => $cedula,
                                                                                                  'tipo' => $tipo,
                                                                                                  ]);

                                }
                            
                                
                            }
                            
                    }
                        
                }

                    return $this->render('/usuario/verificar-preguntas-seguridad-juridico' , ['model' => $model]);

    } 

    /**
     * [actionMostrarPreguntaSeguridadNatural description] metodo que hace que se muestren las preguntas de seguridad seteadas para autenticar el usuario
     * @param  [type] $pregunta1        [description] primera pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $pregunta2        [description] segunda pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $pregunta3        [description] tercera pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $id_contribuyente [description] id del contribuyente natural que desea buscar sus preguntas de seguridad
     * @return [type]                   [description] render de la vista que muestra las preguntas de seguridad seteadas
     * para autenticar el usuario
     * @return [description] redireccionamiento al metodo que hace que puedas cambiar el password
     */
    public function actionMostrarPreguntaSeguridadNatural(){

        $preguntaSeguridad = isset($_SESSION['preguntaSeguridad']) ? $_SESSION['preguntaSeguridad'] : null;

        if ($preguntaSeguridad != null){
      

        

        $model = New ValidarCambiarPasswordNaturalForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                    if ($model->validate()){

                     return $this->redirect (['/usuario/cambiar-password-contribuyente/reseteo-password-natural',
                                                                                                          'id_contribuyente' => $preguntaSeguridad[0]['id_contribuyente'],
                                                                                                          ]);
                   }
                        
            }
              
                     return $this->render('/usuario/mostrar-pregunta-seguridad-natural' , [
                                                                          'model' => $model,
                                                                            'preguntaSeguridad' => $preguntaSeguridad,
                                                                           ]); 
                                                                           // die(var_dump($preguntaSeguridad));

      }else {
         return MensajeController::actionMensaje(Yii::t('frontend', 'No tienes creadas tus preguntas de seguridad, por favor dirijase a la Alcaldía'));
      }
    }


    public function actionOcultarVariable($id)
    {
      //  die($id);
     
        Session::actionDeleteSession(['idContribuyente']);

        $_SESSION['idContribuyente'] = $id;
      

            return $this->redirect(['buscar-pregunta-seguridad-juridico'
                                                                
                                                                ]);
    

    }

    /**
     * [actionBuscarPreguntaSeguridadJuridico description] metodo que busca las preguntas de seguridad del usuario juridico en la tabla
     * preg_seg_contribuyente enviando como parametro el id.
     * @param  [INT] $id [description] parametro enviado para realizar la busqueda en la tabla
     * @return [description] redireccionamiento al metodo que renderiza la vista con las preguntas del usuario juridico
     * seteadas para autenticarlo.
     */
    public function actionBuscarPreguntaSeguridadJuridico()
    {   
       

        if (isset($_SESSION['idContribuyente'])){
          
           

        $id = $_SESSION['idContribuyente'];  
       
        
                $buscarPreguntas = new VerificarPreguntasContribuyenteJuridicoForm();

                $buscarPreguntaSeguridad = $buscarPreguntas::BuscarPreguntaSeguridadJuridico($id);

                if ($buscarPreguntaSeguridad == true){

                   $_SESSION['preguntaSeguridadJuridico'] = $buscarPreguntaSeguridad;
                    

                        return $this->redirect(['mostrar-pregunta-seguridad-juridico',
                                               

                                                 ]);
                
                }else{

                    return MensajeController::actionMensaje(Yii::t('frontend', 'You have not created your security answers yet, in case you forgot your password, please go to your city hall'));
                }
            
            
        
        } else{

           return MensajeController::actionMensaje(Yii::t('frontend', 'Sorry, not possible')); 
        }
    }

    /**
     * [actionMostrarPreguntaSeguridadJuridico description] metodo que renderiza la vista con las preguntas del usuario juridico seteadas
     * @param  [type] $pregunta1        [description] primera pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $pregunta2        [description] segunda pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $pregunta3        [description] tercera pregunta guardada en la tabla preg_seg_contribuyentes
     * @param  [type] $id_contribuyente [description] id del contribuyente juridico que desea buscar sus preguntas de seguridad
     * @return [type]                   [description] metodo que renderiza la vista con las preguntas de seguridad del usuario ya seteadas
     * @return [description] redireccionamiento al metodo que hace que puedas cambiar el password
     */
    public function actionMostrarPreguntaSeguridadJuridico(){

        $preguntaSeguridad = isset($_SESSION['preguntaSeguridadJuridico']) ? $_SESSION['preguntaSeguridadJuridico'] : null;
       
        if ($preguntaSeguridad != null){
           
        
        $model = New ValidarCambiarPasswordJuridicoForm();
       
            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                     
                    return $this->redirect (['/usuario/cambiar-password-contribuyente/reseteo-password-juridico',
                                                                          'id_contribuyente' => $preguntaSeguridad[0]['id_contribuyente'],


                                                                                                          ]);
                    
                }
            }
              
                return $this->render('/usuario/mostrar-pregunta-seguridad-juridico' , 
                                                        [
                                                        'model' => $model,
                                                        'preguntaSeguridad' => $preguntaSeguridad,
                                                        
                                                        
                                                        
                                                        ]); 
     }else{
         return MensajeController::actionMensaje(Yii::t('frontend', 'No tienes creadas tus preguntas de seguridad, por favor dirijase a la Alcaldía'));
     }
    }

    /**
     * [actionReseteoPasswordNatural description] metodo que renderiza la vista para poder ingresar el nuevo password
     * del usuario natural y que luego, al cambiarlo, le envia un correo al usuario con su "usuario" y "contraseña".
     * @param  [type] $id_contribuyente [description] id del contribuyente al cual se le realizara el cambio de password
     * @return [type]                   [description] render de la vista para ingresar el nuevo password del usuario natural
     */
    public function actionReseteoPasswordNatural($id_contribuyente){

        $model = New ReseteoPasswordNaturalForm();

        $postData = Yii::$app->request->post();

        if ( $model->load($postData) && Yii::$app->request->isAjax ){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
                }
                if ( $model->load($postData) ) {

                    if ($model->validate()){
                 
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

                            $enviarEmail = new EnviarEmailCambioClave();
                            $enviarEmail->EnviarEmailCambioClave($consultaContribuyente->email, $model->password1);
                      
                            return MensajeController::actionMensaje(Yii::t('frontend', 'No tienes creadas tus preguntas de seguridad, por favor dirijase a la Alcaldía'));

                        }
                    
                    }
                        
                
              

        }
        return $this->render('/usuario/reseteo-password-natural'   , ['model' => $model,
                                                                    'id_contribuyente' => $id_contribuyente,
                                                                    ]); 


       
  }
  /**
  * [actionReseteoPasswordNatural description] metodo que renderiza la vista para poder ingresar el nuevo password
  * del usuario juridico y que luego, al cambiarlo, le envia un correo al usuario con su "usuario" y "contraseña".
  * @param  [type] $id_contribuyente [description] id del contribuyente al cual se le realizara el cambio de password
  * @return [type]                   [description] render de la vista para ingresar el nuevo password del usuario juridico
  */
  public function actionReseteoPasswordJuridico($id_contribuyente){

      $model = New ReseteoPasswordJuridicoForm();

      $postData = Yii::$app->request->post();

      if ( $model->load($postData) && Yii::$app->request->isAjax ) {
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
      }

      if ( $model->load($postData) ) {

          if ($model->validate()){
                    
              $actualizarJuridico =  self::actualizarPasswordJuridico($id_contribuyente, $model->password1);

              if ($actualizarJuridico == true){

                  $consultaContribuyente = new CrearusuarioNatural();

                  $consultaContribuyente = CrearUsuarioNatural::find()
                                                                ->where([
                                                               'id_contribuyente' => $id_contribuyente,
                                                               'tipo_naturaleza' => 1,
                                                               'inactivo' => 0,
                                                                ])
                                                                ->one();

                  $enviarEmail = new EnviarEmailCambioClave();
                  $enviarEmail->EnviarEmailCambioClave($consultaContribuyente->email, $model->password1);
                      
                  return MensajeController::actionMensaje(Yii::t('frontend', 'We have sent you an email with your new password'));

              }
                    
          }
                        
      }
              


      return $this->render('/usuario/reseteo-password-juridico' , ['model' => $model,
                                                                    'id_contribuyente' => $id_contribuyente,
                                                                    ]); 

  }

  /**
   * [actualizarPasswordNatural description] metodo que actualiza el password del usuario natural en la base de datos
   * @param  [type] $id_contribuyente [description] id del contribuyente a quien se le realizara la actualizacion de password
   * @param  [type] $password1        [description] password nuevo, el cual suplantara al password olvidado
   * @return [type]                   [description] si la transaccion se realiza efectivamente, se retorna una respuesta positiva 
   * y se manda al metodo que envia el correo con el nuevo password
   * @return [description] si la transaccion no se realiza efectivamente, se retorna una respuesta negativa y retorna un mensaje de error 
   */
  public function actualizarPasswordNatural($id_contribuyente, $password1){

      $tableName = 'afiliaciones';
      $arregloCondition = ['id_contribuyente' => $id_contribuyente]; 

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
              //return true;
              return $this->render('/usuario/seleccionar-tipo-contribuyente');
          }else{ 
         
              $transaccion->rollback();
              $conn->close();
              return false;
          }

  }

  /**
  * [actualizarPasswordNatural description] metodo que actualiza el password del usuario juridico en la base de datos
  * @param  [type] $id_contribuyente [description] id del contribuyente a quien se le realizara la actualizacion de password
  * @param  [type] $password1        [description] password nuevo, el cual suplantara al password olvidado
  * @return [type]                   [description] si la transaccion se realiza efectivamente, se retorna una respuesta positiva 
  * y se manda al metodo que envia el correo con el nuevo password
  * @return [description] si la transaccion no se realiza efectivamente, se retorna una respuesta negativa y retorna un mensaje de error 
  */
  public function actualizarPasswordJuridico($id_contribuyente, $password1){

      $tableName = 'afiliaciones';
      $arregloCondition = ['id_contribuyente' => $id_contribuyente]; 

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
