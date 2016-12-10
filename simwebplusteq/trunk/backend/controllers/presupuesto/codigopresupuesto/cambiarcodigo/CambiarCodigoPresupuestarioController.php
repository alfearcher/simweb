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
 *  @file CambiarCodigoPresupuestarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29/09/16
 * 
 *  @class CambiarCodigoPresupuestarioController
 *  @brief Controlador que renderiza la vista con el formulario de asignacion de nuevo nivel presupuestario a un codigo presupuestario
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

namespace backend\controllers\presupuesto\codigopresupuesto\cambiarcodigo;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\presupuesto\codigopresupuesto\cambiarcodigo\CambiarCodigoPresupuestarioForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CambiarCodigoPresupuestarioController extends Controller
{

  
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaCodigo description] Metodo que renderiza la vista para la busqueda del codigo presupuestario y a que nivel contable pertenece
   * @return [type] [description]
   */
  public function actionBusquedaCodigoPresupuestario()
  { 

               
      $model = new CambiarCodigoPresupuestarioForm();

          $dataProvider = $model->busquedaCodigoPresupuestario();

             return $this->render('/presupuesto/codigopresupuesto/cambiarcodigo/mostrar-informacion-cambiar-codigo',[ 
                                  'dataProvider' => $dataProvider,

            ]);
  
  }

  public function actionVerificarIdCodigo(){

   // die('llegue a verificar');

           $idCodigo = yii::$app->request->post('id');
          $_SESSION['idCodigo'] = $idCodigo;

            return $this->redirect(['modificar-nivel-presupuestario-entre-codigo-presupuestario']);
  }



  /**
   * [actionModificarNivelPresupuestarioCodigoPresupuestario description] metodo que renderiza el formulario para la modificacion de codigo presupuestario
   * @return [type] [description] retorna la vista con el formulario
   */
  public function actionModificarNivelPresupuestarioEntreCodigoPresupuestario()
  {
      
            $idCodigo = $_SESSION['idCodigo']; 

         

          $model = new CambiarCodigoPresupuestarioForm();

          $datos = $model->busquedaDatosCodigoPresupuestario($idCodigo);
          //$_SESSION['idCodigo'] = $datos[0]['id_codigo'];
         
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
            
            return $this->render('/presupuesto/codigopresupuesto/cambiarcodigo/view-modificar-nivel-presupuestario-codigo', [
                                                              'model' => $model,
                                                              'datos' => $datos,
                                                           
            ]);
  }




 /**
  * [modificarCodigosContables description] metodo que realiza el update en la tabla codigos_contables con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function modificarNivelesContablesEntreCodigosContables($conn, $conexion, $model)
    {
     
        $idCodigo = $_SESSION['idCodigo'];
      
        $tableName = 'codigos_contables';
        
        $arregloCondition = ['id_codigo' => $idCodigo];
       
        $arregloDatos['nivel_contable'] = $model->nuevo_nivel_contable;

        

         

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
   // die('llegue a begin'.var_dump($idCodigo));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "modificar"){
            

              $modificar = self::modificarNivelesContablesEntreCodigosContables($conn, $conexion, $model);

             
              if ($modificar == true){

                

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
