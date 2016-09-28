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
                 return self::actionBuscarCodigoPresupuestario();
              }
        
             

               
          }

         }
            
            return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/busqueda-codigo-multiple', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  
  }


  public function  buscarNivelPresupuestario($nivel)
  {
   // die($model)
    ;
      $model = new BusquedaCodigoMultipleForm();

          $dataProvider = $model->busquedaNivelPresupuesto($nivel);

          return $this->render('/presupuesto/codigopresupuesto/modificarinactivar/mostrar-informacion-nivel-presupuestario',[ 
                                  'dataProvider' => $dataProvider,

            ]);
  }

  public function actionDeshabilitarCodigoPresupuestario()
  {
      die('llegue a deshabilitar');
  }


    /**
     * [guardarNivelesContables description] metodo que realiza el guardado de la informacion ingresada por el funcionario en la tabla niveles contables
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @param  [type] $model    [description] informacion enviada por el funcionario desde el formulario
     * @return [type]           [description] retorna true si el proceso guarda y false si el proceso da error
     */
    public function guardarCodigosContables($conn, $conexion, $model)
    {

      
      $tabla = 'codigos_contables';
      $arregloDatos = [];
      $arregloCampo = RegistrarCodigoPresupuestarioForm::attributeCodigosContables();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['codigo'] = $model->codigo;

      $arregloDatos['descripcion'] = $model->descripcion;

      $arregloDatos['nivel_contable'] = $model->nivel_contable;

      $arregloDatos['monto'] = $model->monto;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['codigo_contable'] = $model->codigo_contable;

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
            

              $guardar = self::guardarCodigosContables($conn, $conexion, $model);

             
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
