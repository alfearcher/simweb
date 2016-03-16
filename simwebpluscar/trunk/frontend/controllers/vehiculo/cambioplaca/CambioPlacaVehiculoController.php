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
 *  @file CambioPlacaVehiculoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 11/03/16
 * 
 *  @class CambioPlacaVehiculoController
 *  @brief Controlador que renderiza la vista con el formulario para el cambio de placa del vehiculo
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

namespace frontend\controllers\vehiculo\cambioplaca;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\cambioplaca\CambioPlacaVehiculoForm;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CambioPlacaVehiculoController extends Controller
{



    
  public $layout = 'layout-main';
   
    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionCambioPlacaVehiculo()
    {
      
       die(var_dump(yii::$app->request->post('value')));
        if(isset(yii::$app->user->identity->id_contribuyente)){

         


            $model = new CambioPlacaVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

                if ( $model->load($postData) ) {

                    if ($model->validate()){

                 

                    }
            
                }
            
                return $this->render('/vehiculo/cambioplaca/cambio-placa-vehiculo', [
                                                              'model' => $model,
                                                            

                                                           
                ]);
            
          

        }else{

            die('no existe user');

        }
    }

    

 
              
            
}
    



    

   


    

    



?>
