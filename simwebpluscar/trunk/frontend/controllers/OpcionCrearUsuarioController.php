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
 *  @file CrearUsuarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuarioController
 *  @brief Controlador para crear usuario
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

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\CrearUsuarioForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class OpcionCrearUsuarioController extends Controller
{
   
public $layout = "layout-login";

    
      public function actionCrearUsuario()
     {
         //Creamos la instancia con el model de validación
         $model = new CrearUsuarioForm;
    
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

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
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
              return $this->render("crear-usuario", ["model" => $model]);          
 
     } // cierre del metodo registerfun

}
?>
