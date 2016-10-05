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
 *  @file RegistrarOrdenanzaPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/09/16
 * 
 *  @class RegistrarOrdenanzaPresupuestoController
 *  @brief Controlador que renderiza la vista con el formulario de registro de ordenanzas de presupuesto
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

namespace backend\controllers\presupuesto\ordenanza\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\ordenanza\registrar\RegistrarOrdenanzaPresupuestoForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistrarOrdenanzaPresupuestoController extends Controller
{
  
  public $layout = 'layout-main';
   
  /**
   * [actionRegistroCodigoPresupuesto description] metodo que renderiza la vista con el formulario de registro de codigo presupuestario
   * @return [type] [description] retorna el formulario 
   */
  public function actionRegistrarOrdenanzaPresupuesto()
  { 

    

      $model = new RegistrarOrdenanzaPresupuestoForm();

       $dataProvider = $model->busquedaOrdenanzaPresupuesto();

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
            
            return $this->render('/presupuesto/ordenanza/registrar/formulario-registro-ordenanza-presupuesto', [
                                                              'model' => $model,
                                                             'dataProvider' => $dataProvider,
                                                            
            ]);
  
  }


   /**
    * [guardarNroPresupuesto description] metodo que realiza el guardado de los datos de ordenanzas presupuestos en la tabla ordenanzas_presupuestos
    * @param  [type] $conn     [description] parametro de conexion
    * @param  [type] $conexion [description] parametro de conexion
    * @param  [type] $model    [description] modelo que contiene la informacion
    * @return [type]           [description] retorna true si realiza el guardado , sino retorna false
    */
    public function guardarNroPresupuesto($conn, $conexion, $model)
    {

      
      $tabla = 'ordenanzas_presupuestos';
      $arregloDatos = [];
      $arregloCampo = RegistrarOrdenanzaPresupuestoForm::attributeOrdenanzasPresupuesto();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_presupuesto'] = $model->nro_presupuesto;

      $arregloDatos['ano_impositivo'] = $model->ano_impositivo;


      $arregloDatos['fecha_desde'] = date("Y-m-d" ,strtotime($model->fecha_desde));
     // die($arregloDatos['fecha_desde']);
      $arregloDatos['fecha_hasta'] = date("Y-m-d" ,strtotime($model->fecha_hasta));
       // die($arregloDatos['fecha_hasta']);
      $arregloDatos['descripcion'] = 'Ordenanza De Presupuesto'.' '.$model->ano_impositivo;
      
      $arregloDatos['observacion'] = $model->observacion;
      
      $arregloDatos['fecha_hasta'] = $model->fecha_hasta;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['fecha_modificacion'] = 0;

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }

   
    /**
     * [beginSave description] metodo padre de guardado que redirecciona hacia otros metodos encargados de finalizar el guardado
     * @param  [type] $var   [description] variable tipo string para la redireccion
     * @param  [type] $model [description] informacion enviada desde el form
     * @return [type]        [description] retorna true o false
     */
    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "guardar"){
            

              $guardar = self::guardarNroPresupuesto($conn, $conexion, $model);

             
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
