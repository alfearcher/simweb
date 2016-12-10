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
 *  @file GenerarLoteCalcomaniaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/04/2016
 * 
 *  @class GenerarLoteCalcomaniaController
 *  @brief Controlador que renderiza la vista con el el formulario para la generacion de los lotes de calcomania.
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

namespace backend\controllers\vehiculo\calcomania\generarlote;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\generarlote\GenerarLoteForm;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;
/**
 * Site controller
 */

session_start();





class GenerarLoteCalcomaniaController extends Controller
{



    
  public $layout = 'layout-main';
   
    /**
     * [actionGenerarLoteCalcomania description] Funcion que renderiza el formulario para generar el lote de calcomanias
     * @return [type] [description] retorna la renderizacion del formulario para la generacion del lote de calcomanias
     */
    public function actionGenerarLoteCalcomania()
    {
            
            

            
            
            $model = new GenerarLoteForm();

            $dataProvider = $model->search();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                  $verificarRango = self::verificarRango($model);

                      if ($verificarRango == true){
                        return MensajeController::actionMensaje(995);
                      }else{
                        $guardarLote = self::beginSave("guardarLote", $model);

                            if($guardarLote == true){

                                return MensajeController::actionMensaje(100);
                            
                            }else{

                                return MensajeController::actionMensaje(920);
                            }
                      }
              }
            }
            
            return $this->render('/vehiculo/calcomania/generarlote/formulario-generar-lote', [
                                                              'model' => $model,
                                                              'dataProvider' => $dataProvider,
                                                           
            ]);
            
             
    }
   
    /**
     * [verificarRango description] Metodo que realiza una busqueda en la tabla lote_calcomania para verificar que los rangos de fechas
     * ingresados no existan.
     * @return [type] [description] devuelve true si consigue la informacion y false si no la consigue.
     */
    public function verificarRango($model)
    { 
   //die(var_dump($model->ano_impositivo));
       
      $busquedaLote = LoteSearch::find()
                                       

                                    ->select('*')
                                   
                                    ->where(['BETWEEN', 'rango_inicial', $model->rango_inicial,$model->rango_final])
                                    ->orWhere(['BETWEEN', 'rango_final', $model->rango_inicial,$model->rango_final])
                                    ->andWhere('inactivo =:inactivo' , [':inactivo' => 0])
                                    ->andWhere('ano_impositivo =:ano_impositivo' , [':ano_impositivo' => $model->ano_impositivo])
                                    ->all();


      if ($busquedaLote == true){
        return true;
      }else{
        return false;
      }
    }







    /**
     * [guardarLote description] metodo que realiza el guardado del lote de calcomanias en la tabla lote_calcomania
     * @param  [type] $conn     [description] parametros de conexion
     * @param  [type] $conexion [description] parametros de conexion
     * @param  [type] $model    [description] modelo que trae la nformacion del lote de calcomanias que se guardara
     * @return [type]           [description] retorna true si lo guarda y false si no lo guarda
     */
    public function guardarLote($conn, $conexion,$model)
    {
      $datos = yii::$app->user->identity;
      $resultado = false;
      $tabla = 'lote_calcomania';
      $arregloDatos = [];
      $arregloCampo = GenerarLoteForm::attributeLoteCalcomania();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['ano_impositivo'] = $model->ano_impositivo;
     
      
      $arregloDatos['rango_inicial'] = $model->rango_inicial;
      
      $arregloDatos['rango_final'] = $model->rango_final;

      $arregloDatos['rango_final'] = $model->rango_final;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['usuario'] = $datos->email;
      //die($arregloDatos['usuario']);
      
      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

          return true;
          }

    }

    /**
     * [beginSave description] metodo que realiza el guardado del lote de calcomanias
     * @param  [type] $var [description] variable que recibe en forma de string para comenzar el guardado
     * @return [type]      [description] retorna true si el commit se realiza y false si hay un roll back
     */
    public function beginSave($var, $model)
    {
      //die('llegue a beginsave');
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if($var == "guardarLote"){
            //die('llegue a var');

              $buscar = self::guardarLote($conn, $conexion, $model);

                  if ($buscar == true){

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
