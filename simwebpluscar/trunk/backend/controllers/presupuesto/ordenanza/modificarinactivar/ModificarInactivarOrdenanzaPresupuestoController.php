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
 *  @file ModificarInactivarOrdenanzaPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 03/10/16
 * 
 *  @class ModificarInactivarOrdenanzaPresupuestoController
 *  @brief Controlador que renderiza la vista y contiene los metodos para modificacion e inactivacion de la ordenanza de presupuestos
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

namespace backend\controllers\presupuesto\ordenanza\modificarinactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\presupuesto\ordenanza\modificarinactivar\ModificarInactivarOrdenanzaPresupuestoForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarInactivarOrdenanzaPresupuestoController extends Controller
{

   
  
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaOrdenanzaPresupuesto description] metodo que realiza la busqueda de las ordenanzas de presupuesto para su modificacion
   * @return [type] [description] retorna un gridview con la informacion buscada
   */
  public function actionBusquedaOrdenanzaPresupuesto()
  { 

      $model = new ModificarInactivarOrdenanzaPresupuestoForm();

      $dataProvider = $model->busquedaOrdenanzaPresupuesto();

          return $this->render('/presupuesto/ordenanza/modificarinactivar/mostrar-ordenanza-presupuesto',[ 
                                  'dataProvider' => $dataProvider,
          ]);
  }

  /**
   * [actionVerificarOrdenanzaPresupuesto description] metodo que recibe los parametros por get para redireccionar hacia el metodo que realizara la modificacion
   * @return [type] [description] redirecciona al metodo que se le indique en el return redirect
   */
  public function  actionVerificarOrdenanzaPresupuesto()
  {
    
      $idOrdenanza = yii::$app->request->get('value');
      $_SESSION['idOrdenanza'] = $idOrdenanza;

          return $this->redirect(['modificar-ordenanza-presupuesto']);
  }


  public function actionModificarOrdenanzaPresupuesto()
  {

            $idOrdenanza = $_SESSION['idOrdenanza']; 

         

          $model = new ModificarInactivarOrdenanzaPresupuestoForm();

          $datos = $model->busquedaDatosOrdenanzaPresupuesto($idOrdenanza);
          $_SESSION['anoImpo'] = $datos[0]['ano_impositivo'];
         
            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                 // die('valido');
               
                 $modificar = self::beginSave("modificar", $model);

                    if($modificar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                      
                     
                
              }
            }
            
            return $this->render('/presupuesto/ordenanza/modificarinactivar/view-modificar-ordenanza-presupuesto', [
                                                              'model' => $model,
                                                              'datos' => $datos,
                                                           
            ]);
  }



  

  /**
   * [actionInactivarOrdenanzaPresupuesto description] metodo que realiza la inactivacion de la ordenanza de presupuesto
   * @return [type] [description] renderiza mensajes modales para indicar si se realizo la operacion o esta fue rechazada
   */
  public function actionInactivarOrdenanzaPresupuesto()
  {

         $idOrdenanza = yii::$app->request->get('value');
          $_SESSION['idOrdenanza'] =  $idOrdenanza; 

              $inactivar = self::beginSave("inactivar", 0);

              if($inactivar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }  

  }


 /**
  * [modificarOrdenanzaPresupuesto description] metodo que realiza la modificacion de la ordenanza de presupuestos
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos enviados desde el formulario
  * @return [type]           [description] retorna true o false
  */
    public function modificarOrdenanzaPresupuesto($conn, $conexion, $model)
    {
       $idPresupuesto = $_SESSION['idOrdenanza'];

      
        $tableName = 'ordenanzas_presupuestos';
        
        $arregloCondition = ['id_presupuesto' => $idPresupuesto];
     
        $arregloDatos['fecha_desde'] = date("Y-m-d", strtotime($model->fecha_desde));

        $arregloDatos['fecha_hasta'] = date("Y-m-d", strtotime($model->fecha_hasta));
         
        $arregloDatos['observacion'] = $model->observacion;

        $arregloDatos['fecha_modificacion'] = date('Y-m-d');

        

         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

    

    /**
     * [inactivarOrdenanzaPresupuesto description] metodo que realiza el update de la tabla ordenanzas_presupuestos e inactiva el presupuesto, dejandolo en estatus 1
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @return [type]           [description] retorna true si modifica y false si no.
     */
    public function inactivarOrdenanzaPresupuesto($conn, $conexion)
    {
       $idPresupuesto = $_SESSION['idOrdenanza'];

      
        $tableName = 'ordenanzas_presupuestos';
        
        $arregloCondition = ['id_presupuesto' => $idPresupuesto];
     
        $arregloDatos['inactivo'] = 1;


         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

   
    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de ordenanza de presupuestos
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
            

              $modificar = self::modificarOrdenanzaPresupuesto($conn, $conexion, $model);

             
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

               $inactivar = self::inactivarOrdenanzaPresupuesto($conn, $conexion);

             
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
