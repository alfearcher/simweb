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

namespace frontend\controllers\usuario;

use Yii;
use common\models\LoginForm;
use frontend\models\usuario\CrearUsuarioNaturalForm;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
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


     public function actionSeleccionarTipoUsuario()
    {
        return $this->render('/usuario/seleccionar-tipo');
    }   

      


      public function actionCrearUsuarioNatural()
    {

      //die('llegue');
      $model = New CrearUsuarioNaturalForm();

            $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model);
                }

//die('llegue2');
                if ( $model->load($postData) ) {

                 //die('llegue2');
                        
   }
   return $this->render('/usuario/crear-usuario-natural' , ['model' => $model]);

  } 


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

                 //die('llegue2');
                        
   }
   return $this->render('/usuario/crear-usuario-juridico' , ['model' => $model]);

  }
 }
  

 
 
     




?>
