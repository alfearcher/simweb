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
 *  @file ModificarInactivarPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 07/10/16
 * 
 *  @class ModificarInactivarPresupuestoController
 *  @brief Controlador que renderiza contiene los metodos para la modificacion e inactivacion de los presupuestos
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

namespace backend\controllers\presupuesto\cargarpresupuesto\modificarinactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\presupuesto\cargarpresupuesto\modificarinactivar\ModificarInactivarPresupuestoForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarInactivarPresupuestoController extends Controller
{


  public $layout = 'layout-main';
   
/**
 * [actionVistaPresupuesto description] metodo que renderiza la vista con los presupuestos activos
 * @return [type] [description] retorna vista con presupuestos activos
 */
  public function actionVistaPresupuesto()
  { 

          $model = new ModificarInactivarPresupuestoForm();

          
          $dataProvider = $model->busquedaPresupuesto();
           
            
            return $this->render('/presupuesto/cargarpresupuesto/modificarinactivar/view-presupuestos-detalle', [
                                                            'dataProvider' => $dataProvider,
                                                             
                                                           
            ]);
  
  }
  /**
   * [verificarIdPresupuesto description] metodo que verifica el id del presupuesto recibido 
   * @return [type] [description] redirecciona al metodo que modifica el presupuesto
   */
  public function actionVerificarIdPresupuesto(){

    $idPresupuesto = yii::$app->request->get('value');

        $_SESSION['idPresupuesto'] = $idPresupuesto;

            return $this->redirect(['modificar-presupuesto']);
  }


  /**
   * [actionModificarPresupuesto description] metodo que renderiza la vista con el formulario para modificacion del monto del presupuesto
   * @return [type] [description] retorna la vista con el formulario
   */
  public function actionModificarPresupuesto()
  {
        
     

          $model = new ModificarInactivarPresupuestoForm();

          

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
            
            return $this->render('//presupuesto/cargarpresupuesto/modificarinactivar/view-formulario-modificar-presupuesto', [
                                                              'model' => $model,
                                                          
                                                           
            ]);
  }

  /**
   * [actionInactivarPresupuesto description] metodo que realiza la inactivacion del presupuesto
   * @return [type] [description] renderiza mensajes modales para indicar si se realizo la operacion o esta fue rechazada
   */
  public function actionInactivarPresupuesto()
  {

         $idPresupuesto = yii::$app->request->get('value');
          $_SESSION['idPresupuesto'] =  $idPresupuesto; 

              $inactivar = self::beginSave("inactivar", 0);

              if($inactivar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }  

  }


 /**
  * [modificarPresupuesto description] metodo que realiza el update en la tabla presupuestos_detalles con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function modificarPresupuesto($conn, $conexion, $model)
    {

       $idPresupuesto = $_SESSION['idPresupuesto'];
      // die($idPresupuesto);
      
        $tableName = 'presupuestos_detalle';
        
        $arregloCondition = ['id_presupuesto_detalle' => $idPresupuesto];
     
        $arregloDatos['monto'] = $model->monto;



         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

    /**
     * [inactivarCodigoContable description] metodo que realiza el update de la tabla presupuestos_detalle e inactiva el presupuesto, dejandolo en estatus 1
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @return [type]           [description] retorna true si modifica y false si no.
     */
    public function inactivarPresupuesto($conn, $conexion)
    {
      
         $idPresupuesto = $_SESSION['idPresupuesto'];
      
        $tableName = 'presupuestos_detalle';
        
        $arregloCondition = ['id_presupuesto_detalle' => $idPresupuesto];
     
     
        $arregloDatos['inactivo'] = 1;


         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

   
    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de presupuestos
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
            

              $modificar = self::modificarPresupuesto($conn, $conexion, $model);

             
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

               $inactivar = self::inactivarPresupuesto($conn, $conexion);

             
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
