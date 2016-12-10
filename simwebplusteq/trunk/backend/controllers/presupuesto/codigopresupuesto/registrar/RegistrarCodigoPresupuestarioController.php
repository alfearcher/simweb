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
 *  @file RegistrarCodigoPresupuestariosController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/09/16
 * 
 *  @class RegistrarCodigoPresupuestariosController
 *  @brief Controlador que renderiza la vista con el formulario de registro de codigo presupuestario
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

namespace backend\controllers\presupuesto\codigopresupuesto\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\registrar\RegistrarCodigoPresupuestarioForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistrarCodigoPresupuestarioController extends Controller
{
  
  public $layout = 'layout-main';
   
  /**
   * [actionRegistroCodigoPresupuesto description] metodo que renderiza la vista con el formulario de registro de codigo presupuestario
   * @return [type] [description] retorna el formulario 
   */
  public function actionRegistroCodigoPresupuesto()
  { 
    

      $model = new RegistrarCodigoPresupuestarioForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){


                  $guardar = self::beginSave("guardar", $model);
                  
                       if($guardar == true){
                          return MensajeController::actionMensaje(100);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                     
                
              }
            }
            
            return $this->render('/presupuesto/codigopresupuesto/registrar/formulario-codigo-presupuesto', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  
  }


    /**
     * [guardarCodigosContables description] metodo que realiza el guardado de la informacion ingresada por el funcionario en la tabla codigos contables
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

      $arregloDatos['nivel_contable'] = $model->nivel_contable;

      $arregloDatos['codigo'] = $model->codigo;

      $arregloDatos['descripcion'] = $model->descripcion;

     

      $arregloDatos['monto'] = 0;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['codigo_contable'] = $model->codigo;

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
