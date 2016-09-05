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
 *  @file DeshabilitarLoteCalcomaniaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/04/2016
 * 
 *  @class DeshabilitarLoteCalcomaniaController
 *  @brief Controlador que renderiza la vista con los lotes de calcomania activos para deshabilitarlos
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

namespace backend\controllers\vehiculo\calcomania\deshabilitarlote;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\vehiculo\calcomania\deshabilitarlote\DeshabilitarLoteForm;
use common\models\calcomania\calcomaniamodelo\Calcomania;
/**
 * Site controller
 */

session_start();





class DeshabilitarLoteCalcomaniaController extends Controller
{



    
  public $layout = 'layout-main';
   

  public function actionBusquedaLoteCalcomania()
  {
   // die('llegue a lote calcomania');
    
      if(isset(yii::$app->user->identity->id_user)){
          
          $searchModel = new DeshabilitarLoteForm();

          $dataProvider = $searchModel->search();
         

          return $this->render('/vehiculo/calcomania/deshabilitarlote/seleccionar-lote-calcomania', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                ]); 
      }else{
          echo "No existe User";
      }
  }

  /**
   * [actionVerificarLote description] metodo que instancia el modelo que realiza la busqueda en la tabla lote_calcomania
   * @return [type] [description]
   */
  public function actionVerificarLote()
  {


  $idLote = yii::$app->request->post('id');

    $buscarLoteCalcomania = new DeshabilitarLoteForm();

      $validarLote = $buscarLoteCalcomania->buscarCalcomania($idLote);

        if($validarLote == true){
          $_SESSION['idLote'] = $validarLote;
          return $this->redirect(['motivo-deshabilitacion']);
        }
  }

  /**
   * [actionMotivoDeshabilitacion description] metodo que renderiza la vista para que el funcionario ingrese los motivos por los cuales
   * esta deshabilitando el lote de calcomanias seleccionado
   * 
   * @return [type] [description] retorna la renderizacion de la vista para ingresar los motivos de deshabilitacion
   */
  public function actionMotivoDeshabilitacion()
  {
    //die('llegue a motivos'.var_dump($_SESSION['idLote']));

     $model = new DeshabilitarLoteForm();

             $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                $verificarLoteExistente = self::verificarLoteExistente();

                  if($verificarLoteExistente == true){
                    return MensajeController::actionMensaje(996);
                  }else{
                    $deshabilitar = self::beginSave($model);

                        if($deshabilitar == true){
                            return MensajeController::actionMensaje(200);
                        }else{
                            return MensajeController::actionMensaje(920);
                        }

                  }
              }
            }
                    
                  
            return $this->render('/vehiculo/calcomania/deshabilitarlote/motivo-deshabilitacion-lote-calcomania', [
                                                              'model' => $model,

                                                              
            ]);
  }

  /**
   * [verificarLoteExistente description] metodo que realiza una busqueda en la tabla calcomanias, para verificar si el lote que se va a 
   * deshabilitar, ya fue asignado a alguien
   * @return [type] [description] retorna true si consigue un match, y retorna false si no consigue nada.
   */
  public function verificarLoteExistente()
  {
      $datos = $_SESSION['idLote'];
      $anoImpositivo = $datos[0]->ano_impositivo;
      $rangoInicial = $datos[0]->rango_inicial;
      $rangoFinal = $datos[0]->rango_final;
    
     
       $busquedaLote = Calcomania::find()
                                       

                                    ->select('*')
                                   
                                    ->where('nro_calcomania between '. $rangoInicial .' and '.$rangoFinal)
                                    ->andWhere('estatus =:estatus' , [':estatus' => 0])
                                    ->andWhere('ano_impositivo =:ano_impositivo' , [':ano_impositivo' => $anoImpositivo])
                                    ->all();


      if ($busquedaLote == true){
        return true;
      }else{
        return false;
      }
  }

 
  /**
   * [deshabilitarLote description] metodo que realiza la deshabilitacion del lote en base al id del lote de calcomanias
   * @param  [type] $conn     [description] parametro de conexion
   * @param  [type] $conexion [description] parametro de conexion
   * @param  [type] $model    [description] modelo que incluye la causa y las observaciones por las cuales se realiza la deshabilitacion
   * @return [type]           [description] retorna un true si el proceso se cumple y un false si hay algun problema.
   */
  public function deshabilitarLote($conn, $conexion,$model)
  {
      $idLote = $_SESSION['idLote'][0]->id_lote_calcomania; 

      $tableName = 'lote_calcomania';
      $arregloCondition = ['id_lote_calcomania' => $idLote]; //id del lote de las calcomanias
      
     
      $arregloDatos['inactivo'] = 1;

      $arregloDatos['causa'] = $model->causa;

      $arregloDatos['observacion'] = $model->observacion;


      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

            if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

            return true;
              
          }
         

  }
  


  /**
   * [actionBeginSave description] metodo que realiza los procesos del conexion controller y deshabilita el lote de calcomanias
   * @return [type] [description] si retorna true, actualiza el deshabilita el lote de calcomanias pero si retorna false envia un mensaje de error
   * 
   */
  public function beginSave($model)
  {
  
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

        $transaccion = $conn->beginTransaction();

                $deshabilitarLote = self::deshabilitarLote($conn, $conexion, $model);


                    if ($deshabilitarLote == true ){
                       //die('deshabilito');
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
