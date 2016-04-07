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
 *  @file CompradorNaturalController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 07/04/16
 * 
 *  @class CompradorNaturalController
 *  @brief Controlador que renderiza la vista para realizar la busqueda del comprador natural
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
use frontend\models\vehiculo\cambiopropietario\FormularioNaturalForm;
use frontend\models\usuario\CrearUsuarioNatural;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CompradorNaturalController extends Controller
{



    
  public $layout = 'layout-main';
   
    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionBusquedaNatural()
    {
      //die(var_dump($_SESSION['datosVehiculo']));
      //die('llegue a busqueda natural'.$_SESSION['id']); 
      
      
        if(isset(yii::$app->user->identity->id_contribuyente)){


            $model = new FormularioNaturalForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                $buscarContribuytente = self::buscarContribuyente($model);

                    if ($buscarContribuytente){
                    
                        return MensajeController::actionMensaje(991);
                     
                      
                    }else{

                      $buscarContribuyenteNatural = self::buscarContribuyenteNatural($model);

                        if ($buscarContribuyenteNatural){
                            
                            $_SESSION['datosNatural'] = $buscarContribuyenteNatural;

                            return $this->redirect(['/vehiculo/cambiopropietario/cambio-propietario-vendedor/mostrar-datos']);
                        
                        }else{
                          die('no encontro, hay que crearlo');
                        }

                      
                    }
                
               } 
            
            }
            
            return $this->render('/vehiculo/cambiopropietario/busqueda-natural', [
                                                              'model' => $model,
                                                              

                                                           
            ]);


            
        }else{
            die('no existe user');
        }

  }

  public function buscarContribuyente($model)
  {
    $idContribuyente = yii::$app->user->identity->id_contribuyente;
    //die($idContribuyente);

    $buscar = CrearUsuarioNatural::find()
                                  ->where([
                                  'naturaleza' => $model->naturaleza,
                                  //die($model->naturaleza),
                                  'cedula' => $model->cedula,
                                  'tipo' => $model->tipo,
                                  'tipo_naturaleza' => 0,
                                  'id_rif' => 0,
                                 'id_contribuyente' => $idContribuyente,
                                    ])
                                  ->all();



                  if ($buscar == true){
                    return $buscar;
                  }else{
                    return false;
                  }
  }

  public function buscarContribuyenteNatural($model)
  {
    
    //die($idContribuyente);

    $buscar = CrearUsuarioNatural::find()
                                  ->where([
                                  'naturaleza' => $model->naturaleza,
                                  //die($model->naturaleza),
                                  'cedula' => $model->cedula,
                                  'tipo' => $model->tipo,
                                  'tipo_naturaleza' => 0,
                                  'id_rif' => 0,
                                
                                    ])
                                  ->all();



                  if ($buscar == true){
                    return $buscar;
                  }else{
                    return false;
                  }
  }


   
    
    
 
              
            
}
    



    

   


    

    



?>
