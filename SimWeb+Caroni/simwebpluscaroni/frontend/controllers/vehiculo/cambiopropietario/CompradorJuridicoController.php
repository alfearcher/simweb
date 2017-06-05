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
 *  @file CompradorJuridicoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 08/04/16
 * 
 *  @class CompradorNaturalController
 *  @brief Controlador que renderiza la vista para realizar la busqueda del comprador juridico
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
use frontend\models\vehiculo\cambiopropietario\FormularioJuridicoForm;
use frontend\models\vehiculo\cambiopropietario\CrearContribuyenteJuridicoForm;
use frontend\models\usuario\CrearUsuarioNaturalForm;
use frontend\models\usuario\CrearUsuarioNatural;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CompradorJuridicoController extends Controller
{


  
    
  public $layout = 'layout-main';
   
    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionBusquedaJuridico()
    {
     // die('llegue a juridico');
      //die(var_dump($_SESSION['datosVehiculo']));
      //die('llegue a busqueda natural'.$_SESSION['id']); 
      
      
        if(isset(yii::$app->user->identity->id_contribuyente)){


            $model = new FormularioJuridicoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                //die('valido');

                $buscarContribuytente = self::buscarContribuyente($model);

                    if ($buscarContribuytente){
                    
                        return MensajeController::actionMensaje(991);
                     
                      
                    }else{

                      $buscarContribuyenteJuridico = self::buscarContribuyenteJuridico($model);

                        if ($buscarContribuyenteJuridico){
                            
                            
                            return $this->redirect(['/vehiculo/cambiopropietario/cambio-propietario-vendedor/mostrar-datos-juridico']);
                        
                        }else{

                         
                         // die(var_dump($_SESSION['datosNuevos']));

                          return MensajeController::actionMensaje(993);
                        }

                      
                    }
                
               } 
            
            }
            
            return $this->render('/vehiculo/cambiopropietario/busqueda-juridico', [
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
                                  'tipo_naturaleza' => 1,
                                  'id_rif' => 0,
                                 'id_contribuyente' => $idContribuyente,
                                    ])
                                  ->all();



                  if ($buscar == true){
                   // die(var_dump($buscar));
                    return $buscar;
                  }else{
                    return false;
                  }
  }

  public function buscarContribuyenteJuridico($model)
  {

    //die('llegue a buscar contribuyente');

    $buscar = CrearUsuarioNatural::find()
                                  ->where([
                                  'naturaleza' => $model->naturaleza,
                                  
                                  'cedula' => $model->cedula,

                                  'tipo' => $model->tipo,
                                    //die($model->tipo),
                                  'tipo_naturaleza' => 1,
                                  'id_rif' => 0,
                                  
                                    ])
                                  ->all();



                  if ($buscar == true){
                 // die('encontro'); 
                    $_SESSION['datosNuevos'] = $model;
                    return $buscar;

                  }else{
                    $_SESSION['datosNuevos'] = $model;
                    return false;
                  }
  }

  

  

   
    
    
 
              
            
}
    



    

   


    

    



?>
