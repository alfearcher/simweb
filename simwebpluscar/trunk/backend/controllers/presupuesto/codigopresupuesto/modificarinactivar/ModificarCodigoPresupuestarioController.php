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
 *  @file ModificarCodigoPresupuestarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 27/09/16
 * 
 *  @class ModificarCodigosPresupuestarioController
 *  @brief Controlador que renderiza la vista con el formulario de modificacion del codigo presupuestario
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

namespace backend\controllers\presupuesto\codigopresupuesto\modificarinactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarCodigoPresupuestarioController extends Controller
{

    const SCENARIO_SEARCH_NIVEL_CONTABLE = 'search_nivel';
    const SCENARIO_SEARCH_CODIGO_CONTABLE = 'search_codigo';
  
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaCodigoMultiple description] metodo que renderiza la vista con la busqueda multiple del codigo presupuestario, tanto por dicho codigo
   * como por nivel contable
   * @return [type] [description] retorna la vista
   */
  public function actionBusquedaCodigoMultiple()
  { 

          $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new BusquedaCodigoMultipleForm();
          
          if(isset($post['btn-busqueda-nivel'])){ 
              $model->scenario = self::SCENARIO_SEARCH_NIVEL_CONTABLE;
          }elseif(isset($post['btn-busqueda-codigo'])){
              $model->scenario = self::SCENARIO_SEARCH_CODIGO_CONTABLE;
         
          } 

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {


          if(isset($post['btn-busqueda-nivel'])){ 
              if ($model->validate()){
               //die(var_dump($model->nivel_contable));
                  $_SESSION['datos'] = $model;
                  return self::buscarNivelPresupuestario($model->nivel_contable);
 
                  
              }

          }elseif(isset($post['btn-busqueda-codigo'])){
              if ($model->validate()){
                 $_SESSION['datos'] = $model;
                 return self::actionBuscarCodigoPresupuestario($model->codigo);
              }
        
             

               
          }

         }
            
            return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/busqueda-codigo-multiple', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  
  }

  /**
   * [buscarNivelPresupuestario description] metodo que realiza la busqueda de la informacion del codigo presupuestario en la tabla codigos_contables
   * @param  [type] $nivel [description] nivel presupuesario
   * @return [type]        [description] retorna la informacion por niveles presupuestarios 
   */
  public function  buscarNivelPresupuestario($nivel)
  {
   // die($model)
    
      $model = new BusquedaCodigoMultipleForm();

          $dataProvider = $model->busquedaNivelPresupuesto($nivel);

          return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/mostrar-informacion-nivel-presupuestario',[ 
                                  'dataProvider' => $dataProvider,

            ]);
  }

  /**
   * [actionBuscarCodigoPresupuestario description]  metodo que realiza la busqueda por codigo del nivel contable
   * @param  [type] $codigo [description] codigo contable
   * @return [type]        [description] retorna el gridview con el dataprovider si consigue y false si no consigue
   */
  public function actionBuscarCodigoPresupuestario($codigo){

      $model = new BusquedaCodigoMultipleForm();

          $dataProvider = $model->busquedaNivelPresupuestoCodigo($codigo);

             return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/mostrar-informacion-nivel-presupuestario-codigo',[ 
                                  'dataProvider' => $dataProvider,

            ]);
  }

  /**
   * [actionModificarCodigoPresupuestario description] metodo que renderiza el formulario para la modificacion de codigo presupuestario
   * @return [type] [description] retorna la vista con el formulario
   */
  public function actionModificarCodigoPresupuestario()
  {
        
           $idCodigo = yii::$app->request->get('value');
           $_SESSION['idCodigo'] =  $idCodigo; 

          $model = new ModificarCodigoPresupuestarioForm();

          $datos = $model->busquedaDatosCodigoPresupuestario($idCodigo);

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){


               
                 $modificar = self::beginSave("modificar", $model);

                    if($modificar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                      
                     
                
              }
            }
            
            return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/view-modificar-codigo-presupuestario', [
                                                              'model' => $model,
                                                              'datos' => $datos,
                                                           
            ]);
  }

  /**
   * [actionInactivarCodigoPresupuestario description] metodo que realiza la inactivacion del codigo presupuestario
   * @return [type] [description] renderiza mensajes modales para indicar si se realizo la operacion o esta fue rechazada
   */
  public function actionInactivarCodigoPresupuestario()
  {

         $idCodigo = yii::$app->request->get('value');
          $_SESSION['idCodigo'] =  $idCodigo; 

              $inactivar = self::beginSave("inactivar", 0);

              if($inactivar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }  

  }


 /**
  * [modificarCodigosContables description] metodo que realiza el update en la tabla codigos_contables con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function modificarCodigosContables($conn, $conexion, $model)
    {
       $idCodigo = $_SESSION['idCodigo'];

      
        $tableName = 'codigos_contables';
        
        $arregloCondition = ['id_codigo' => $idCodigo];
     
        $arregloDatos['nivel_contable'] = $model->nivel_contable;

         $arregloDatos['codigo'] = $model->codigo;

        $arregloDatos['descripcion'] = $model->descripcion;

         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

    /**
     * [inactivarCodigoContable description] metodo que realiza el update de la tabla codigos_contables e inactiva el codigo, dejandolo en estatus 1
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @return [type]           [description] retorna true si modifica y false si no.
     */
    public function inactivarCodigoContable($conn, $conexion)
    {
       $idCodigo = $_SESSION['idCodigo'];

      
        $tableName = 'codigos_contables';
        
        $arregloCondition = ['id_codigo' => $idCodigo];
     
        $arregloDatos['inactivo'] = 1;


         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

   
    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de codigo contable
     * @param  [type] $var   [description] variable tipo string que sirve para el redireccionamiento de metodos de inactivacion o modificacion
     * @param  [type] $model [description] datos enviados desde los formularios
     * @return [type]        [description] retorna true si el proceso se cumple y false si no se cumple
     */
    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "modificar"){
            

              $modificar = self::modificarCodigosContables($conn, $conexion, $model);

             
              if ($modificar == true){

                

                    $transaccion->commit();
                    $conn->close();

                     
                    return true;


              }else{

                    $transaccion->rollback();
                    $conn->close();
                    return false;
              }
                  
                 
          }else if($var == "inactivar"){

               $inactivar = self::inactivarCodigoContable($conn, $conexion);

             
              if ($inactivar == true){

                

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
