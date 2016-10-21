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
 *  @file RegistrarRubroController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 15/10/16
 * 
 *  @class RegistrarRubroController
 *  @brief Controlador que contiene los metodos para realizar el registro de los codigos de subnivel
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

namespace backend\controllers\rubros\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\tasas\codigosubnivel\registrar\RegistrarCodigoSubnivelForm;

/**
 * Site controller
 */

session_start();





class RegistrarRubroController extends Controller
{
  
  public $layout = 'layout-main';
   
 /**
  * [actionRegistroRubro description] metodo que renderiza el formulario para el registro de rubro
  * @return [type] [description] retorna formulario de registro
  */
  public function actionRegistroRubro()
  { 
    

      $model = new RrgistrarRubroForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){
              //  die('valiudo');

                  $guardar = self::beginSave("guardar", $model);
                  
                       if($guardar == true){
                          return MensajeController::actionMensaje(100);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                     
                
              }
            }
            
            return $this->render('/rubros/registrar/formulario-registro-rubro', [
                                                          'model' => $model,
                                                             
                                                           
            ]);
  
  }




/**
 * [guardarGrupoSubnivel description] metodo que realiza el guardado de los grupos subniveles
 * @param  [type] $conn     [description] parametro de conexion
 * @param  [type] $conexion [description] parametro de conexion
 * @param  [type] $model    [description] modelo con la informacion del formulario
 * @return [type]           [description] retorna true o false
 */
    public function guardarGrupoSubnivel($conn, $conexion, $model)
    {

      
      $tabla = 'grupos_subniveles';
      $arregloDatos = [];
      $arregloCampo = RegistrarCodigoSubnivelForm::attributeGrupoSubnivel();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['grupo_subnivel'] = $model->grupo_subnivel;

      $arregloDatos['descripcion'] = strtoupper($model->descripcion);

      $arregloDatos['inactivo'] = 0;    

          
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
     die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "guardar"){
            

              $guardar = self::guardarGrupoSubnivel($conn, $conexion, $model);

             
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
