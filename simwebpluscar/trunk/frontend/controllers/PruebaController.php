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
 *  @file CrearUsuarioNaturalController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/01/2016
 * 
 *  @class CrearUsuarioNaturalController
 *  @brief Controlador para crear usuario natural
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

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;


use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;




use common\models\utilidades\Utilidad;
use frontend\models\PruebaForm;



class PruebaController extends Controller
{
   
    public $layout = "layout-login";

 
    public function actionPrueba(){
       // die('llegue');

        $model = New PruebaForm();

        $postData = Yii::$app->request->post();

        if ( $model->load($postData) && Yii::$app->request->isAjax ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            
        }

        if ( $model->load($postData) ){

             if ($model->validate()){

             }else{
                //die('llegue a no valido');
             }
            

           
                        
        }
        
     return $this->render('/prueba/prueba-calendario' , ['model' => $model]);
    }


}

?> 


