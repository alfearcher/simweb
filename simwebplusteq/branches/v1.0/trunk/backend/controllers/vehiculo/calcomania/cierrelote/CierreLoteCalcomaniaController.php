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
 *  @file CierreLoteCalcomaniaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 20/05/2016
 * 
 *  @class CierreLoteCalcomaniaController
 *  @brief Controlador que renderiza la vista para realizar el cierre de lotes de calcomanias vencidas
 *  
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

namespace backend\controllers\vehiculo\calcomania\cierrelote;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\deshabilitarfuncionario\FuncionarioSearch;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\vehiculo\calcomania\deshabilitarfuncionario\DeshabilitarForm;
use backend\models\vehiculo\calcomania\cierrelote\CierreLoteCalcomaniaForm;
/**
 * Site controller
 */

session_start();





class CierreLoteCalcomaniaController extends Controller
{



    
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaFuncionario description] metodo que realiza la busqueda del funcionario para deshabilitarlo
   * @return [type] [description] retorna un dataprovider con todos los funcionarios activos
   */
  public function actionBusquedaLote()
  {
    //die('llegue a busqueda lote');
    
        $model = new CierreLoteCalcomaniaForm();

             $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                 if($model->ano_impositivo == date('Y')){

                 return MensajeController::actionMensaje(998);
                }else{
                  $_SESSION['datos'] = $model;
                  return $this->redirect(['buscar-calcomania' , 'model' => $model]);
                }
              }
            }
                    
                  
                
            return $this->render('/vehiculo/calcomania/cierrelote/busqueda-lote', [
                                                              'model' => $model,

                                                              
            ]);
  }

  public function actionBuscarCalcomania()
  {
    
       $model = $_SESSION['datos'];
       $searchModel = new CierreLoteCalcomaniaForm();

          $dataProvider = $searchModel->buscarCalcomania($model);
         

          return $this->render('/vehiculo/calcomania/cierrelote/seleccionar-calcomania', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                               
                                                ]); 
  }


 

  /**
   * [AceptarDesincorporacion description] metodo que verifica si desea deshabilitar los funcionarios seleccionados
   */
  public function actionAceptarDesincorporacion()
  {
  
      $model = new DeshabilitarForm();

             $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                 $deshabilitarCalcomanias = self::beginSave();

                    if ($deshabilitarCalcomanias == true){
                        return MensajeController::actionMensaje(200); 
                    }else{
                        return MensajeController::actionMensaje(920);
                    }
                  
              }
            }
                    
                  
                
            return $this->render('/vehiculo/calcomania/cierrelote/aceptar-desincorporacion', [
                                                              'model' => $model,

                                                              
            ]);
            
  }
  
  public function deshabilitarCalcomania($conn, $conexion)
  {
    // die('llego a deshabilitar');
      $tableName = 'calcomanias';
      $arregloCondition = ['entregado' => 0 , 'estatus' => 0, 'ano_impositivo' => $_SESSION['datos']->ano_impositivo ]; //id de la calcomania
      
      
      $arregloDatos['estatus'] = 9;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

            if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

            return true;
              
          }
         

  }
  


  /**
   * [actionBeginSave description] metodo que realiza los procesos del conexion controller y actualiza el estatus del funcionario
   * @return [type] [description] si retorna true, actualiza el estatus del funcionario pero si retorna false envia un mensaje de error
   * 
   */
  public function beginSave()
  {
   // die('llego a begin');
    
     $todoBien = true;

      $conexion = new ConexionController();

      

        $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

       

                
                $deshabilitarCalcomania = self::deshabilitarCalcomania($conn, $conexion);


                    if ($deshabilitarCalcomania == true ){
                           // die('deshabilito');
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
    



    

   


    

    



?>
