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

          if ($dataProvider == false ){

            die('no existe contribuyente');
          
          } else {

            return $this->render('/usuario/contribuyente-encontrado' , ['dataProvider' => $dataProvider]);

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
 }
}
?>
