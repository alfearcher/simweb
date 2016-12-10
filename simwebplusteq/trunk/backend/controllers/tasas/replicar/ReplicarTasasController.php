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
 *  @file ReplicarTasasController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/16
 * 
 *  @class ReplicarTasasController
 *  @brief Controlador que contiene los metodos para la replicacion de las tasas
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

namespace backend\controllers\tasas\replicar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\tasas\modificarinactivar\ModificarInactivarTasasForm;
use backend\models\tasas\replicar\ReplicarTasasForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ReplicarTasasController extends Controller
{


  
  public $layout = 'layout-main';
   
 /**
  * [actionBusquedaLoteTasas description] metodo que renderiza el formulario para la busqueda de tasas por año impositivo
  * @return [type] [description] renderiza el formulario
  */
  public function actionBusquedaLoteTasas()
  { //die('llegue');

        $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new ReplicarTasasForm();
          
          $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
                
                if ($model->validate()){
               //die(var_dump($model).'hola');
               $_SESSION['anoImpositivo'] = $model;

                return $this->redirect(['mostrar-tasas-replicar']);
                    
                }

            }
            
            return $this->render('/tasas/replicar/busqueda-lote-tasas', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  
  }







 /**
  * [actionMostrarModificarInactivarTasa description] metodo que renderiza un dataprovider con la informacion de la tasa buscada en el formulario de modificacion
  * @return [type]        [description]
  */
  public function  actionMostrarTasasReplicar($errorCheck = "")
  {
      $modelo = $_SESSION['anoImpositivo'];
    
      $model = new ReplicarTasasForm();

          $dataProvider = $model->busquedaTasas($modelo);

          return $this->render('/tasas/replicar/mostrar-tasas-replicar',[ 
                                  'dataProvider' => $dataProvider,
                                  'errorCheck' => $errorCheck,

            ]);
  }

  /**
   * [actionVerificarTasas description] metodo que verifica los id de las tasas recibidas en la seleccion multiple
   * @return [type] [description]
   */
  public function actionVerificarTasas()
  {
      $errorCheck = ""; 
      
      $idTasas = yii::$app->request->post('chk-replicar-tasas');
      //die(var_dump($idVehiculo));
      $_SESSION['idTasas'] = $idTasas;
//die(var_dump($_SESSION['idVehiculo']));
  
      $validacion = new ReplicarTasasForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-replicar-tasas')) == true){
          
          return $this->redirect(['seleccion-ano-impositivo']);
        
       }else{
          $errorCheck = "Por favor seleccione una tasa";
          return $this->redirect(['mostrar-tasas-replicar' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
     
  }
  /**
   * [actionSeleccionAnoImpositivo description] metodo que renderiza el formulario para la seleccion del nuevo año impositivo en la replicacion de tasas
   * @return [type] [description] renderiza el formulario
   */
  public function actionSeleccionAnoImpositivo(){
      $todoBien = false;
      $datos = $_SESSION['idTasas'];
      $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new ReplicarTasasForm();
          
          $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
                
                if ($model->validate()){
                    
                    foreach ($datos as $key => $value) {
                       
                    
                    $verificarReplicacion = $model->verificarReplicacionTasas($value, $model->ano_impositivo);

                        if($verificarReplicacion ==  true){
                         // die('consiguio');
                          $todoBien = true;
                        }

                        if($todoBien == true){
                          die('año impositivo repetido');
                        }else{

                          $replicar = self::beginSave("replicar", $model);

                              if($replicar == true){
                                  return MensajeController::actionMensaje(100);
                              }else{
                                  return MensajeController::actionMensaje(900);
                              }
                        }
                   }
                    
                }

            }
            
            return $this->render('/tasas/replicar/seleccion-nuevo-ano-impositivo', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  }




 /**
  * [modificarTasas description] metodo que realiza el update en la tabla varios con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function replicarTasas($conn, $conexion, $model,$value)
    {
       

      
        $tableName = 'varios';
        
        $arregloCondition = ['id_impuesto' => $value];
     
        $arregloDatos['ano_impositivo'] = $model->ano_impositivo;

       
          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

 
   
    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de tasas
     * @param  [type] $var   [description] variable tipo string que sirve para el redireccionamiento de metodos de inactivacion o modificacion
     * @param  [type] $model [description] datos enviados desde los formularios
     * @return [type]        [description] retorna true si el proceso se cumple y false si no se cumple
     */
    public function beginSave($var, $model)
    {
      $datos = $_SESSION['idTasas'];
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "replicar"){
            
              foreach ($datos as $key => $value) {
              
             
              $replicar = self::replicarTasas($conn, $conexion, $model, $value);

                  if($replicar ==  true){

                    $todoBien = true;
                  }

               }
                if ($todoBien == true){

                

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
