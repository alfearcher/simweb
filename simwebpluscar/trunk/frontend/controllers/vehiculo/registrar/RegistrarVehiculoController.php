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
 *  @date 29/02/16
 * 
 *  @class RegistrarVehiculoController
 *  @brief Controlador que renderiza vista con el formulario para el registro de vehiculo
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

namespace frontend\controllers\vehiculo\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistrarVehiculoController extends Controller
{



    
   public $layout = 'layoutbase';
   
    /**
     *
     * metodo que realiza la busqueda de las preguntas de seguridad del contribuyente una vez que este esta dentro de su cuenta,
     * para mostrarlas y asi poder cambiar su password desde adentro de su cuenta
     * 
     * @return retorna la vista con las preguntas de seguridad del contribuyente
     */
    public function actionRegistrarVehiculo()
    {

        
        if(isset(yii::$app->user->identity->id_contribuyente)){

            $model = new RegistrarVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                

                }
            
            }
            
            return $this->render('/vehiculo/registrar/registrar-vehiculo', [
                                                           'model' => $model,
            ]);
            

        }else{

            die('no existe user');

        }
    }

    

   


    

    

}

?>
