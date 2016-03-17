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
 *  @file CambioDatosVehiculoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 09/03/16
 * 
 *  @class CambioDatosVehiculoController
 *  @brief Controlador que renderiza la vista con el formulario de cambio de datos de vehiculo
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

namespace frontend\controllers\vehiculo\cambiodatos;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\cambiodatos\CambioDatosVehiculoForm;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CambioDatosVehiculoController extends Controller
{



    
  public $layout = 'layout-main';
   
   




  public function actionVistaSeleccion()
  {


      if(isset(yii::$app->user->identity->id_contribuyente)){

          $searchModel = new VehiculoSearch();

          $dataProvider = $searchModel->search();
       

          return $this->render('/vehiculo/cambiodatos/seleccionar-vehiculo', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
            
                                                ]); 
      }else{
          echo "No existe User";
      }
    

  }

  public function actionBuscarVehiculo()
  {

      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idVehiculo = yii::$app->request->post('id');

      $modelsearch = new VehiculoSearch();
      $busqueda = $modelsearch->busquedaVehiculo($idVehiculo, $idContribuyente);

          if ($busqueda == true){ 
        
              $_SESSION['datosVehiculo'] = $busqueda;
        
          return $this->redirect(['cambio-datos-vehiculo']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
  }


    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionCambioDatosVehiculo()
    {
      
        $datosVehiculo = $_SESSION['datosVehiculo']; 
        if(isset(yii::$app->user->identity->id_contribuyente)){

          if (isset($_SESSION['datosVehiculo'])){ 


            $model = new CambioDatosVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                die('llegue de nuevo  a controlador'.var_dump($model));

                }
            
            }
            
            return $this->render('/vehiculo/cambiodatos/seleccionar-vehiculo-cambio-datos', [
                                                              'model' => $model,
                                                              'datos' => $datosVehiculo,

                                                           
            ]);
            
            }else{
              die('no hay datos de vehiculo');
            } 

        }else{

            die('no existe user');

        }
    }

    

 
              
            
}
    



    

   


    

    



?>
