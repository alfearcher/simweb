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
 *  @file CargarPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/10/16
 * 
 *  @class CargarPresupuestoController
 *  @brief Controlador que renderiza la vista con el formulario de registro de prespuesto
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

namespace backend\controllers\presupuesto\cargarpresupuesto\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\cargarpresupuesto\registrar\CargarPresupuestoForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CargarPresupuestoController extends Controller
{
  
  public $layout = 'layout-main';
   
  /**
   * [actionVistaOrdenanzaPresupuesto description] metodo que renderiza la vista con los datos de las ordenanzas de presupuesto activas
   * @return [type] [description] renderiza el gridview con la informacion buscada
   */
  public function actionVistaOrdenanzaPresupuesto()
  { 
     // die('llegue a ordenanza');
      $model = new CargarPresupuestoForm();

      $dataProvider = $model->busquedaOrdenanzaPresupuesto();

          return $this->render('/presupuesto/cargarpresupuesto/registrar/view-ordenanza-presupuesto', [
            'dataProvider' => $dataProvider,

            ]);
     
  
  }

  /**
   * [verificarOrdenanzaPresupuesto description] metodo que recibe el id ordenanza y redirecciona a otro metodo
   * @return [type] [description] redireccionamiento hacia el metodo indicado
   */
  public function actionVerificarOrdenanzaPresupuesto(){

      $idOrdenanza = yii::$app->request->post('id');

          $_SESSION['idOrdenanza'] = $idOrdenanza;

          return $this->redirect(['vista-codigo-presupuestario']);
  }

  /**
   * [actionVistaCodigoPresupuestario description] metodo que renderiza la vista con los datos de los codigos presupuestarios activos
   * @return [type] [description] renderiza el gridview con la informacion buscada
   */
  public function actionVistaCodigoPresupuestario(){

       $model = new CargarPresupuestoForm();

      $dataProvider = $model->busquedaCodigoPresupuestario();

          return $this->render('/presupuesto/cargarpresupuesto/registrar/view-codigo-presupuestario', [
            'dataProvider' => $dataProvider,

            ]);
  }

  /**
   * [verificarCodigoPresupuestario description] metodo que recibe el id del codigo presupuestario y redirecciona a otro metodo
   * @return [type] [description] redireccionamiento hacia el metodo indicado
   */
  public function actionVerificarCodigoPresupuestario(){

      $idCodigo = yii::$app->request->post('id');

      $_SESSION['idCodigo'] = $idCodigo;
     // die($_SESSION['idCodigo']);

          return $this->redirect(['cargar-monto-presupuesto']);
  }

  /**
   * [actionVistaMonto description] metodo que renderiza la vista para insertar el monto del presupuesto
   * @return [type] [description] retorna la vista del formulario para insertar el monto presupuestario
   */
  public function actionCargarMontoPresupuesto(){

            $model = new CargarPresupuestoForm();

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
            
            return $this->render('/presupuesto/cargarpresupuesto/registrar/formulario-carga-monto-presupuesto', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  }

   /**
    * [guardarDetallePresupuesto description] metodo que realiza el guardado del detalle del presupuesto en la tabla presupuestos_detalle
    * @param  [type] $conn     [description] parametro de conexion
    * @param  [type] $conexion [description] parametro de conexion
    * @param  [type] $model    [description] modelo con la informacion enviada del formulario
    * @return [type]           [description] retorna true o false
    */
    public function guardarDetallePresupuesto($conn, $conexion, $model)
    {
     // die('llegue bien');
      $idCodigo = $_SESSION['idCodigo'];
     // die($idCodigo);
      $idPresupuesto = $_SESSION['idOrdenanza'];  
      $tabla = 'presupuestos_detalle';
      $arregloDatos = [];
      $arregloCampo = CargarPresupuestoForm::attributePresupuestosDetalle();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['id_presupuesto'] = $idPresupuesto;
     //die(var_dump($arregloDatos['id_presupuesto']));

      $arregloDatos['id_codigo'] = $idCodigo;

      $arregloDatos['monto'] = $model->monto;

      $arregloDatos['inactivo'] = 0;

   

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

            //die('es true');

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
            
           // die('llegue a guardar');
              $guardar = self::guardarDetallePresupuesto($conn, $conexion, $model);

             
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
