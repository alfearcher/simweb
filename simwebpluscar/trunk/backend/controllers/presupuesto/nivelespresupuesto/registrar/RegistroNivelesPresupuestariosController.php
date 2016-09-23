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
 *  @file RegistroNivelesPresupuestariosController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 22/09/16
 * 
 *  @class RegistroNivelesPresupuestariosController
 *  @brief Controlador que renderiza la vista con el formulario de registro de niveles presupuestarios
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

namespace backend\controllers\presupuesto\nivelespresupuesto\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use frontend\models\vehiculo\calcomania\CalcomaniaSearch;
use frontend\models\vehiculo\calcomania\SolicitudExtravioForm;
use backend\models\presupuesto\nivelespresupuesto\registrar\RegistrarNivelesPresupuestariosForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistroNivelesPresupuestariosController extends Controller
{
  
  public $layout = 'layout-main';
   
  
  public function actionRegistroNivelesPresupuestarios()
  {
   // die('llegue a registro');

      $model = new RegistrarNivelesPresupuestariosForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){


               
                   $guardar = self::beginSave("guardar", $model);

                      if($guardar == true){
                          return MensajeController::actionMensaje(100);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                      
                     
                
              }
            }
            
            return $this->render('/presupuesto/nivelespresupuesto/registrar/formulario-niveles-presupuestos', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  }


    /**
     * [guardarNivelesContables description] metodo que realiza el guardado de la informacion ingresada por el funcionario en la tabla niveles contables
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @param  [type] $model    [description] informacion enviada por el funcionario desde el formulario
     * @return [type]           [description] retorna true si el proceso guarda y false si el proceso da error
     */
    public function guardarNivelesContables($conn, $conexion, $model)
    {

      
      $tabla = 'niveles_contables';
      $arregloDatos = [];
      $arregloCampo = RegistrarNivelesPresupuestariosForm::attributeNivelesContables();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nivel_contable'] = $model->nivel_contable;

      $arregloDatos['descripcion'] = strtoupper($model->descripcion);

      $arregloDatos['ingreso_propio'] = $model->ingreso_propio;

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }

   

    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "guardar"){
            

              $guardar = self::guardarNivelesContables($conn, $conexion, $model);

             
              if ($guardar == true){

                

                    $transaccion->commit();
                    $conn->close();

                     
                    return true;


              }else{

                    $transaccion->rollback();
                    $conn->close();
                    return false;
              }
                  
                 
          }

  }
 


}



    



    

   


    

    



?>
