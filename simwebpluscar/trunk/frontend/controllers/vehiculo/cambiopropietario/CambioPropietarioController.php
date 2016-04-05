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
 *  @file CambioPropietarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/04/16
 * 
 *  @class CambioPropietarioController
 *  @brief Controlador que renderiza la vista con el formulario para el cambio de propietario del vehiculo
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

namespace frontend\controllers\vehiculo\cambiopropietario;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\enviaremail\EnviarEmailSolicitud;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\cambiopropietario\CompradorForm;
use frontend\models\vehiculo\cambioplaca\BusquedaVehiculos;
use frontend\models\vehiculo\cambiopropietario\MostrarDatosVehiculoForm;
session_start();





class CambioPropietarioController extends Controller
{



    
  public $layout = 'layout-main';
   


  /**
     *
     * metodo que renderiza la vista para la seleccion del tipo de cambio de propietario a realizarse
     * como vendedor o comprador
     * 
     * @return retorna la vista para seleccionar el tipo de cambio de propietario que desee hacer
     */
  public function actionTipoCambioPropietario()
  {

      if(isset(yii::$app->user->identity->id_contribuyente)){

          $idConfig = yii::$app->request->get('id');
          $_SESSION['id'] = $idConfig;

          //die($_SESSION['id']);
        
          return $this->render('/vehiculo/cambiopropietario/seleccionar-tipo-cambio-propietario');
    
      }
    

  }

  public function actionComprador()
  {

      $model = new CompradorForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                 
                    $buscarPlacaContribuyente = self::buscarPlacaContribuyente($model);

                    if ($buscarPlacaContribuyente == true){

                      return MensajeController::actionMensaje(991);

                    }else{

                      $buscarPlacaNormal = self::buscarPlacaNormal($model);

                        if ($buscarPlacaNormal == true){

                           
                            $_SESSION['datosVehiculo'] = $buscarPlacaNormal;



                            return $this->redirect(['mostrar-datos']);

                        }else{

                          return MensajeController::actionMensaje(992);
                        
                        }
                    }
                }
                

              
            }
            return $this->render('/vehiculo/cambiopropietario/verificar-placa-traspaso', [
                                                        'model' => $model,
                                                       
            ]);  
    }


  public function buscarPlacaContribuyente($model)
  {

    $idContribuyente = yii::$app->user->identity->id_contribuyente;
    
          $buscarPlacaContribuyente = BusquedaVehiculos::find()
                                            ->where([
                                            'placa' => $model->placa,
                                           
                                            'id_contribuyente' => $idContribuyente,
                                            'status_vehiculo' => 0,

                                                ])
                                            ->all();

                                           //die(var_dump($buscarPlacaContribuyente));

                if ($buscarPlacaContribuyente == true){

                    
                  
                    return $buscarPlacaContribuyente;
                }else{
                   
                    return false;

                }
  }

  public function buscarPlacaNormal($model)
  {
    //die($model->placa);
      $buscarPlacaNormal = BusquedaVehiculos::find()
                                            ->where([
                                            'placa' => $model->placa,
                                            'status_vehiculo' => 0,

                                              ])
                                            ->all();

                                            //die(var_dump($buscarPlacaNormal));

                if ($buscarPlacaNormal == true){

                    
                  
                    return $buscarPlacaNormal;
                }else{
                   
                    return false;

                }
    
  }

  public function actionMostrarDatos()
  {

   
  $datosVehiculo = $_SESSION['datosVehiculo'];
  // die(var_dump($datosVehiculo));
    $model = new MostrarDatosVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                 
                    
                        
                        }
                    }
                
                

             
            
            return $this->render('/vehiculo/cambiopropietario/mostrar-datos-vehiculo', [
                                                        'model' => $model,
                                                        'datosVehiculo' => $datosVehiculo,

                                                       
            ]);   
  }

    
    
 
              
            
}
    



    

   


    

    



?>
