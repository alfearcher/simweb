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
 *  @file OpcionFuncionarioController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class OpcionFuncionarioController
 *  @brief Clase que permite controlar opciones de registro de funcionarios, cambiar el password para el logueo del funcionario,
 *  registrar las preguntas secretas para la recuperacion del password y el proceso de recuperar el password mediante las preguntas
 *  de seguridad 
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  randKey
 *  registrarfuncionariousuario
 *  cambiarpasswordfuncionario
 *  iniciarrecuperacionpasswordfuncionario
 *  recuperarpasswordfuncionario
 *  changepassword
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
use backend\models\FormRegistrarFuncionarioUsuario;
use backend\models\FormCambiarPasswordFuncionario;
// pregunta seguridad
use backend\models\FormAsignarPreguntaSecreta;
use backend\models\PreguntasUsuarios;
//recuperar password funcionario
use backend\models\FormIniciarRecuperacionPasswordFuncionario;
use backend\models\FormRecuperarPasswordFuncionario;
use backend\models\FormChangePassword;
use backend\models\BuscarFuncionarioUsuarioForm;
use backend\models\funcionario\Funcionario;
use backend\models\funcionario\FuncionarioForm;
// mandar url
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;
// active record consultas..
use yii\db\ActiveRecord;
use common\conexion\ConexionController;
session_start();
class OpcionFuncionarioController extends Controller
{
    public $layout = "layout-login";
    public $conn;
    public $conexion;
    public $transaccion;
/***************************** METODO RANDKEY *****************************************************
* Asigna valores aleatorios (salt, authkey, accestoken)
*   @param $str, varchar, define que digitos usara el metodo randKey.
*   @param $long, integer corto, define la cantidad de digitos del llamado al randKey.
*   @return $key, varchar, cadena de caracteres aleatorios definidos por el randKey.
*****************************************************************************************************/
     private function randKey($str='', $long=0)
     {
         $key = null;
         $str = str_split($str);
         $start = 0;
         $limit = count($str)-1;
         for($x=0; $x<$long; $x++)
         {
             $key .= $str[rand($start, $limit)];
         }
         return $key;
     }

     public function actionBuscarFuncionarioUsuario()
     {
         //Creamos la instancia con el model de validación
         $model = new BuscarFuncionarioUsuarioForm;
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null;
         $tipoError = null;
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model);
         }

         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){


                     $_SESSION['datos']=$model;
                     $msg = Yii::t('backend', 'Searching!');//VALIDANDO PREGUNTAS DE SEGURIDAD
                     $url =  "<meta http-equiv='refresh' content='1; ".Url::toRoute(['opcion-funcionario/index-funcionario'])."'>";                    
                     return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);  
                     
               }else{

                     $model->getErrors(); 
               }
           }// cierre del  post para traer el model
         
              return $this->render("buscar-funcionario-usuario", ["model" => $model]);          
 
     } // cierre del metodo registerfun


/**
 * [actionIndexFuncionario description] Pantalla que muestra los datos del funcionario al que se le creara la cuenta de usuario
 * @return [type] [description] vista del index-funcionario
 */
    public function actionIndexFuncionario()
    {
        //if ( isset( $_SESSION['idContribuyente'] ) ) {
        $searchModel = new FuncionarioForm(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-funcionario', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
        ]);
        // }  else {
        //             echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        // }
    } 
/***************************** REGISTRAR FUNCIONARIOS ***********************************************
* Metodo para crear las cuentas de usuarios de los funcionarios
*****************************************************************************************************/
     public function actionRegistrarfuncionariousuario()
     {
         //Creamos la instancia con el model de validación
         $model = new FormRegistrarFuncionarioUsuario;
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null;
         $tipoError = null;
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model);
         }

         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){ 

die(var_dump($_SESSION['datos'][0]->login));
                   // Preparamos la consulta para guardar el usuario 
                   $table = new Users; 
		     
		           
                   $username = $model->username;
                   $email = $model->email;		   
				           //----salt-----
				           $salt = $this->randKey("abcdef0123456789", 10);   
				           $clave = $model->password + $salt;
				   
				           $password = md5($clave);                    //crypt($model->password, Yii::$app->params["salt"]);
				           $authkey = $this->randKey("abcdef0123456789", 25); // llama al metodo randkey para asignar el authkey
                   $accesstoken = $this->randKey("abcdef0123456789", 25); // llama al metodo randkey para asignar el accestoken 
                   $activate = 1;
	                 $role = 2;
				           $fecha_creacion = date("Y-m-d");
				   
				           $table = Users::find()->where("email=:email", [":email" => $model->email]);
		          
				           if ($table->count() == 0){


                        $arrayDatos = ['salt' => $salt,
                                       'id_funcionario'=>$_SESSION['idFuncionario'],
                                       'password' => $password,
                                       'username' => $username,
                                       'email' => $email,
                                       'authkey' => $authkey,
                                       'accesstoken' => $accesstoken,
                                       'activate' => $activate,
                                       'role' => $role,
                                       'fecha_creacion' => $fecha_creacion,];

                        $tableName = 'users'; 

                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                        $this->conexion->open(); 

                        $transaccion = $this->conexion->beginTransaction();

                        if ( $conn->guardarRegistro($this->conexion, $tableName, $arrayDatos) ){
                           
                            $transaccion->commit();
                            $tipoError = 0;
                            $msg = Yii::t('backend', 'SUCCESSFUL REGISTRATION OF OFFICIAL USER ACCOUNT!');//REGISTRO ESXITOSO DE CUENTAS USUARIO FUNCIONARIO
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("opcion-funcionario/registrarfuncionariousuario")."'>";
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);   
                    
                        }else{

                            $transaccion->roolBack();
                            $tipoError = 0;
                            $msg = "AH OCURRIDO UN ERROR!....Espere";
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("opcion-funcionario/registrarfuncionariousuario")."'>";
                            return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                        }
                        $this->conexion->close();
                         //-------------FIN TRY CATCH---------------


				                 // si se guardaron los datos
			                   // limpiamos los campos
	                       $model->username = null;
                         $model->email = null;
                         $model->password = null;
                         $model->password_repeat = null;
                         
                         

                   }else{ 

                         $msg = Yii::t('backend', 'AN ERROR OCCURRED WHILE CARRYING OUT THE REGISTRATION!');//HA OCURRIDO UN ERROR AL REALIZAR EL REGISTRO
                         $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("opcion-funcionario/registrarfuncionariousuario")."'>";
                         return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]); 
                   }

              }else{

                   $model->getErrors(); 
              }
         }
              return $this->render("registrarfuncionariousuario", ["model" => $model, "datos"=>$_SESSION['datos']]);          
 
     } // cierre del metodo registerfun

	 
/*************************** CAMBIAR CONTRASENA DE FUNCIONARIOS LOGUEADOS ************************************
* Metodo para cambiar el password del funcionario ya logueado
**************************************************************************************************************/
     public function actionCambiarpasswordfuncionario()
     {
         
         //Instancia para validar el formulario
         $model = new FormCambiarPasswordFuncionario;
         
         //Mensaje que será mostrado al usuario en la vista
	       $url = null;
         $msg = null;
         $tipoError = null;
		     
		     //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

              Yii::$app->response->format = Response::FORMAT_JSON; 
              return ActiveForm::validate($model);
         }
  
         if ($model->load(Yii::$app->request->post())){

             if ($model->validate()){

                 //Buscar al usuario a través del email
                 $table = Users::find()->where("email=:email", [":email" => $model->email]);
         
		 
		             //Si el usuario existe
                 if ($table->count() == 1){
         
                     $table = Users::find()->where("email=:email", [":email" => $model->email])->one();
					           //Guardamos los cambios en la tabla users
					           $salt = $this->randKey("abcdef0123456789", 10);   
				             $clave = $model->password + $salt;
				     
				             $password = md5($clave);
					           $email = Yii::$app->user->identity->email;
					 
					           
                     //--------------TRY---------------
					           // conexion, y transaccion para modificar el password
					           // 

					          $arrayDatos = ['salt' => $salt, 'password' => $password];
                    $tableName = 'users';
                    $arrayCondition = ['email' => $email, 'activate' => 1,];

                    $conn = New ConexionController();

                    $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                    $this->conexion->open();

                    $transaccion = $this->conexion->beginTransaction();

                    if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){
                        
                        $transaccion->commit();
                        $tipoError = 0;
                        $msg = Yii::t('backend', 'PASSWORD CHANGE SUCCESSFUL!');//"CAMBIO DE PASSWORD EXITOSO!"
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/index")."'>";
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]); 
                    
                    }else{

                        $transaccion->roolBack();
                        $tipoError = 0;
                        $msg = "AH OCURRIDO UN ERROR!....Espere";
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("opcion-funcionario/cambiarpasswordfuncionario")."'>";
                        return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                    } 
                    $this->conexion->close();

                     //-------------FIN TRY CATCH---------------

                     //Vaciar el campo del formulario
                     $model->email = null;
                     $model->password = null;
                     $model->password_repeat = null;
                                        		   
		             }else{ //el usuario no existe

                      $msg = Yii::t('backend', 'PASSWORD CHANGE FAILED!');//"ERROR EN EL CAMBIO DE PASSWORD!"
                      $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("opcion-funcionario/cambiarpasswordfuncionario")."'>";
                      return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);  // mensaje de registro exitoso a la vista
                 }
             }else{

                 $model->getErrors();
             }
        
        }

        return $this->render("cambiarpasswordfuncionario", ["model" => $model, "msg" => $msg, "url" => $url]);
      
      	
	   } // cierre del metodo cambiarpassfun */ 
	 

/************************** INICIAR RECUPERACION PASSWORD DE FUNCIONARIOS *******************************
* Metodo que evalua si el usuario existe para iniciar la recuperacion de password 
* del funcionario
*******************************************************************************************************/
    public function actionIniciarrecuperacionpasswordfuncionario()
    {    
         //Creamos la instancia con el model de validación
         $model = new FormIniciarRecuperacionPasswordFuncionario;
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null;
         $usuario = null;
         $tipoError = null;
         //Validación mediante ajax 
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

               Yii::$app->response->format = Response::FORMAT_JSON;
               return ActiveForm::validate($model);
          }
    
          if ($model->load(Yii::$app->request->post())){

               if($model->validate()){ 

                     $usuario = $model->username; 
                     $msg = Yii::t('backend', 'VALIDATING USER!');//VALIDANDO USUARIO
                     $url =  "<meta http-equiv='refresh' content='1; ".Url::toRoute(['opcion-funcionario/recuperarpasswordfuncionario', "usuario" => $usuario])."'>";                    
                     return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "usuario" => $usuario, "tipoError" => $tipoError]); 
                     
                      
                     
               }else{

                     $model->getErrors(); 
               }
           }// cierre del  post para traer el model

        
     
         return $this->render("iniciarrecuperacionpasswordfuncionario", ["model" => $model, "usuario" => $usuario]); 
    } // cierre del metodo iniciar recuperacion password del funcionario


/***************************** RECUPERAR PASSWORD DE FUNCIONARIOS ***********************************
* Metodo que evalua las preguntas de seguridad del usuario funcionario para la 
* recuperacion del password
*@param $usuario, varchar, define la identidad del usuario.
*****************************************************************************************************/
    public function actionRecuperarpasswordfuncionario($usuario)
    {
	       //Creamos la instancia con el model de validación
         $model = new FormRecuperarPasswordFuncionario;
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
	       $url = null;
         $usuario = null;
         $tipoError = null;
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

               Yii::$app->response->format = Response::FORMAT_JSON;
               return ActiveForm::validate($model);
          }
    
          if ($model->load(Yii::$app->request->post())){
              
               if($model->validate()){

                     $usuario = $model->user;
                     $msg = Yii::t('backend', 'VALIDATING THE SECURITY QUESTIONS!');//VALIDANDO PREGUNTAS DE SEGURIDAD
                     $url =  "<meta http-equiv='refresh' content='1; ".Url::toRoute(['opcion-funcionario/changepassword', "usuario" => $usuario])."'>";                    
                     return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url,"usuario" => $usuario, "tipoError" => $tipoError]);  
                     
               }else{

                     $model->getErrors(); 
               }
           }// cierre del  post para traer el model

        
	   
         return $this->render("recuperarpasswordfuncionario", ["model" => $model, "usuario" => $usuario]); 
    } // cierre del metodo recuperar password del funcionario
	

/***************************** CHANGE PASSWORD DE FUNCIONARIOS **************************************
* Metodo a ejecutar despues del reconocimiento de las preguntas de seguridad del usuario
* funcionario para cambiar el password
*   @param $usuario, varchar, define la identidad del usuario.
*****************************************************************************************************/
     public function actionChangepassword($usuario)
     {
         //Instancia para validar el formulario
         $model = new FormChangePassword;
         
         //Mensaje que será mostrado al usuario en la vista
	       $url = null;
         $msg = null;
         $tipoError = null;

		 
		     //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

               Yii::$app->response->format = Response::FORMAT_JSON;
               return ActiveForm::validate($model);
         }
  
         if ($model->load(Yii::$app->request->post())){

               if ($model->validate()){ 

                     //Guardamos los cambios en la tabla users
					           $salt = $this->randKey("abcdef0123456789", 10);   
				             $clave = $model->password + $salt;
				             $password = md5($clave);
					           $username = $usuario;

					            
					           $arrayDatos = ['salt' => $salt, 'password' => $password];
                     $tableName = 'users';
                     $arrayCondition = ['username' => $username, 'activate' => 1,];

                     $conn = New ConexionController();

                     $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                     $this->conexion->open();

                     $transaccion = $this->conexion->beginTransaction();

                     if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){

                         $transaccion->commit();
                         $tipoError = 0;
                         $msg = Yii::t('backend', 'IT HAS RESET ITS PASSWORD, REDIRECTING!');//SE HA RESETEADO SU PASSWORD, REDIRECCIONANDO
                         $url =  "<meta http-equiv='refresh' content='2; ".Url::toRoute("site/login")."'>";
                         return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                      
                     }else{

                         $transaccion->roolBack();
                         $tipoError = 0;
                         $msg = "AH OCURRIDO UN ERROR!....Espere";
                         $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";
                         return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                     } 
                     $this->conexion->close();
                }//-------------FIN TRY CATCH---------------

                     
		                 //Vaciar el campo del formulario
                     $model->username = null;
		                 $model->password = null;
		                 $model->password_repeat = null;
					           $model->verifyCode = null; 
     
                     //Mostrar el mensaje al usuario
                     
		   
		        }else{
              
                 $model->getErrors();
             }
        
        return $this->render("changepassword", ["model" => $model, "msg" => $msg, "url" => $url, "usuario" => $usuario]);
		}// cierre de metodo para cambiar clave


}