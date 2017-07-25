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

namespace backend\controllers\usuario;

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
use backend\models\usuario\RecuperarPasswordNaturalForm;
use backend\models\usuario\RecuperarPasswordJuridicoForm;


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
use backend\models\usuario\MensajeRecuperarForm;

use common\models\contribuyente\ContribuyenteBase;
use frontend\models\usuario\Afiliacion;
use common\enviaremail\PlantillaEmail;

session_start();
/**
 * Site controller
 */
class RecuperarAccesoContribuyenteController extends Controller
{   
    // usuario/recuperar-acceso-contribuyente/seleccionar-tipo-contribuyente
   
    public $layout = "layout-login";

    //-- INICIO ACTIONSELECCIONARTIPOUSUARIO -->

    /**
     *
     * metodo que renderiza la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de cambio de password que desee realizar
     */
   public function actionSeleccionarTipoContribuyente(){
   
    return $this->render('/usuario/seleccionar-tipo-contribuyentes-recuperar-password');
   }

    
    /**
     * [actionCambiarPasswordNatural] metodo que busca los id del usuario natural en la tabla contribuyente, afiliacion y 
     * luego busca las preguntas de seguridad en la tabla para cambiarlas
     * @return [type] [description] vista que te pide ingresar tu cedula y tu email para buscar las preguntas de seguridad
     * @return [description] render de la vista que te muestra las preguntas de seguridad seteadas 
     * para autenticar el usuario ($pregunta1, $pregunta2,$pregunta3)
     */
    public function actionRecuperarPasswordNatural(){

            $model = New RecuperarPasswordNaturalForm();

              //$postData = Yii::$app->request->post();

              if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

               
                
                if ( $model->load(Yii::$app->request->post()) ) {


                    if ($model->validate()){

                        $buscarId = new RecuperarPasswordNaturalForm();

                        $buscarContribuyente = $buscarId::buscarIdContribuyente($model); 
                        if ($buscarContribuyente == true){

                            $buscarAfiliaciones = $buscarId::buscarIdAfiliaciones($buscarContribuyente->id_contribuyente);
                            $_SESSION['Afiliaciones']=$buscarAfiliaciones;
                            $_SESSION['Contribuyente'] = $buscarContribuyente;
                            if ($buscarAfiliaciones == true) {
                                
                            
                                $this->redirect(['mensaje-recuperar']);
                            
                            } else {

                            return MensajeController::actionMensaje(976);//El contribuyente no posee afiliacion en el sitema
                            }
                        }else{
                            return MensajeController::actionMensaje(932);//Taxpayer not defined ----- contribuyente no definido
                        }
                    
                    }

                }

                    return $this->render('/usuario/recuperar-password-natural' , ['model' => $model]);
                    

    }

    /**
     * [actionCambiarPasswordJuridico description] metodo que busca los id del usuario juridico en la tabla contribuyente, afiliacion y 
     * luego busca las preguntas de seguridad en la tabla para cambiarlas
     * @return [type] [description] vista que te pide ingresar tu rif y tu email para buscar las preguntas de seguridad
     * @return [description]render de la vista que te pide seleccionar la empresa asociada al rif enviado ($naturaleza, $cedula, $tipo, $email)
     */
    public function actionRecuperarPasswordJuridico(){

        $model = New RecuperarPasswordJuridicoForm();

           // $postData = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model);
         }

         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){ 

                        $buscarId = new RecuperarPasswordJuridicoForm();

                            $naturaleza = $model->naturaleza;
                            $cedula = $model->cedula;
                            $tipo = $model->tipo;

                            // $buscarAfiliacionesJuridico = $buscarId->buscarIdAfiliaciones($model);
                            // //die(var_dump($buscarAfiliacionesJuridico));

                            // if ($buscarAfiliacionesJuridico == false){
                            //     return MensajeController::actionMensaje(976); //El contribuyente no posee afiliacion al sistema
                            // }else{ 
                           
                            // $idsContribuyente = [];

                            // foreach($buscarAfiliacionesJuridico as $key => $value){
                              
                            //     $idsContribuyente[] = $buscarAfiliacionesJuridico[$key]['id_contribuyente'];
                              
                            // }

                          
                           
                                   
                                $dataProvider = $buscarId::buscarIdContribuyente($model);

                                if ($dataProvider == true){
                                    
                                   
                                    return $this->render('/usuario/mostrar-contribuyente-juridico' , [
                                                                                                  'dataProvider' => $dataProvider,
                                                                                                  'naturaleza' => $naturaleza,
                                                                                                  'cedula' => $cedula,
                                                                                                  'tipo' => $tipo,
                                                                                                  ]);

                                }
                            
                                
                           
                            
                     }
                        
                 }

                    return $this->render('/usuario/recuperar-password-juridico' , ['model' => $model]);

    } 


    public function actionOcultarVariable($id)
    {
      
      $buscarId = new RecuperarPasswordNaturalForm();
      $idContribuyente = new RecuperarPasswordJuridicoForm();
        Session::actionDeleteSession(['idContribuyente']);

        $_SESSION['idContribuyente'] = $id;
        $buscarAfiliaciones = $buscarId::buscarIdAfiliaciones($_SESSION['idContribuyente']);
        $buscarContribuyente = $idContribuyente::buscarContribuyenteDatos($_SESSION['idContribuyente']);
        $_SESSION['Contribuyente'] =$buscarContribuyente;
        $_SESSION['Afiliaciones']=$buscarAfiliaciones;

        if ($buscarAfiliaciones == true) {

             
                $this->redirect(['mensaje-recuperar']);
             
        }  else {

            return MensajeController::actionMensaje(976);//El contribuyente no posee afiliacion en el sitema
        }      

    }

    /**
     * [actionMensajeRecuperar description] Metodo que se encarga de mostrar un mensaje al contribuyente 
     * paraconfirmar el proceso de recuperacion
     */
    public function actionMensajeRecuperar(){

   $model = New MensajeRecuperarForm();

   if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

            
                
                if ( $model->load(Yii::$app->request->post()) ) {


                    if ($model->validate()){ 

                        $seguridad = new Seguridad();

                        $nuevaClave = $seguridad->randKey(6);
                        $salt = Utilidad::getUtilidad();
                        $password = $nuevaClave.$salt;

                        $password_hash = md5($password);

                        if($_SESSION['Contribuyente']['email'] != null){

                             $guardo = self::GuardarAfiliacion($nuevaClave, $password_hash, $asd_SESSION['Contribuyente']);
                                            
                             if($guardo == true){

                                $envio = self::enviarRecuperacion($nuevaClave,$_SESSION['Afiliaciones']['login'],$_SESSION['Contribuyente']['email']);
                       
                                if ($envio==true){
                                    return MensajeController::actionMensaje(103);//Proceso exitoso, el usuario y clave han sido enviado a su correo electronico
                                    

                                } else {
                                    return MensajeController::actionMensaje(973);//La recuperacion de contraseña a fallado
                                }
                            } else {
                                    return MensajeController::actionMensaje(973);//La recuperacion de contraseña a fallado
                            }

                        } else {
                            return MensajeController::actionMensaje(974);//La recuperacion de contraseña a fallado por no tener correo electronico asignado como contribuyente

                        }
                        

                    }
                }
    return $this->render('/usuario/mensaje-recuperar', ['model' => $model]);
   }

   /**
      * [GuardarAfiliacion description] Metodo que se encarga de guardar los datos de la solicitud 
      * de recuperar afiliacion del contribuyente
      * @param [type] $model [description] arreglo de datos del formulario afiliacion de contribuyente
      * 
      */
     public function GuardarAfiliacion($nuevaClave,$passwordHash,$contribuyente)
     {
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            try {


                $tableName = 'afiliaciones'; 

                $arrayDatos = [  'password' => 0,
                                 'password_hash' => $passwordHash,
                                  
                              ];  
                $arrayCondition = ['id_contribuyente'=>$contribuyente['id_contribuyente']];

                if ( $conn->modificarRegistro($conexion, $tableName,  $arrayDatos, $arrayCondition) ){  
                
                    $tipoError = 0;
                    $todoBien = true;
                

                }else{ 
                    $tipoError = 0;
                    $todoBien = false;
                }
        

                if ($todoBien == true){
                    
                    $transaccion->commit();  
                    $conexion->close(); 
                    $tipoError = 0; 
                    return true;

                } else {
                
                    $transaccion->rollBack(); 
                    $conexion->close(); 
                    $tipoError = 0; 
                    return false; 

                }
                  
               
          
          } catch ( Exception $e ) {
              //echo $e->errorInfo[2];
          } 
                       
     }

   /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function enviarRecuperacion($clave,$login,$emailContribuyente)
     {
         
         $solicitud = 'Restauracion de usuario y contraseña';

         if($_SESSION['Contribuyente']['tipo_naturaleza'] == 1){

            $contribuyente = $_SESSION['Contribuyente']['razon_social'];

         } else {

            $contribuyente = $_SESSION['Contribuyente']['nombres']. ' '.$_SESSION['Contribuyente']['apellidos'];

         } 
         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaRecuperarLogin($emailContribuyente, $solicitud, $clave,$login, $contribuyente)){

             return true; 
         } else { 

             return false; 
         }


     }

   
        

}
?>
