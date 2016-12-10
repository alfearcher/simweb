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
 *  @file ModificarInactivarTasasController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 13/10/16
 *
 *  @class ModificarInactivarTasasController
 *  @brief Controlador que contiene los metodos para la modificacion e inactivacion de las tasas
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

namespace backend\controllers\tasas\modificarinactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\tasas\modificarinactivar\ModificarInactivarTasasForm;
use backend\models\tasas\modificarinactivar\BusquedaTasasForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarInactivarTasasController extends Controller
{



  public $layout = 'layout-main';

  /**
   * [actionBusquedaTasa description] metodo que renderiza el formulario para la modificacion de las tasas
   * @return [type] [description]
   */
  public function actionBusquedaTasa()
  { //die('llegue');

        $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new BusquedaTasasForm();

          $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
               //die(var_dump($model).'hola');
               $_SESSION['datosTasa'] = $model;

                return $this->redirect(['mostrar-modificar-inactivar-tasa']);

                }

            }

            return $this->render('/tasas/modificarinactivar/busqueda-tasas', [
                                                              'model' => $model,


            ]);

  }




 /**
  * [actionMostrarModificarInactivarTasa description] metodo que renderiza un dataprovider con la informacion de la tasa buscada en el formulario de modificacion
  * @return [type]        [description]
  */
  public function  actionMostrarModificarInactivarTasa()
  {
      $modelo = $_SESSION['datosTasa'];

      $model = new ModificarInactivarTasasForm();

          $dataProvider = $model->busquedaTasas($modelo);

          return $this->render('/tasas/modificarinactivar/mostrar-modificar-inactivar-tasa',[
                                  'dataProvider' => $dataProvider,

            ]);
  }



  /**
   * [actionModificarTasa description] metodo que renderiza formulario para modificacion de tasa
   * @return [type] [description] renderiza el formulario para la modificacion
   */
  public function actionModificarTasa()
  {
  //die(var_dump(yii::$app->request->get()));

      $idTasa = yii::$app->request->get('value');

      $_SESSION['idTasa'] = $idTasa;


      $modelSearch = new ModificarInactivarTasasForm();
      $model = $modelSearch->busquedaTasasModificar($idTasa);

          if ($model == true){
          //  die(var_dump($model));
           $_SESSION['datosTasa'] = $model;

            $modelSearch->attributes = $model->attributes;
          // die(var_dump($model->attributes));
              return $this->render('/tasas/modificarinactivar/view-modificar-tasa', [
                                                              'model' => $modelSearch,

              ]);

          return $this->redirect(['formulario-modificar-tasa']);

          }else{

              die('no existe propaganda asociado a ese ID');
          }
  }

/**
 * [actionFormularioModificarTasa description] metodo que renderiza el formulario para la modificacion de la tasa
 * @return [type] [description] retorna el formulario con sus validaciones
 */
  public function actionFormularioModificarTasa()
  {
  //die('llegue aqui');
            $datosTasa = $_SESSION['datosTasa'];
            $postData = yii::$app->request->post();

            $model = New ModificarInactivarTasasForm();


            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die(var_dump($postData));


               if ($model->validate()){

                  $modificar = self::beginSave("modificar" , $model);

                      if($modificar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }


              }
          }

            return $this->render('/tasas/modificarinactivar/view-modificar-tasa', [
                                                              'model' => $model,


            ]);


  }
  /**
   * [actionInactivarTasa description] metodo que inactiva la tasa seleccionada
   * @return [type] [description] retorna true o false
   */
  public function actionInactivarTasa(){

      $idTasa = yii::$app->request->get('value');

          $_SESSION['idTasa'] = $idTasa;

              $inactivar = self::beginSave("inactivar" , 0);

                  if($inactivar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }
  }


 /**
  * [modificarTasas description] metodo que realiza el update en la tabla varios con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function modificarTasas($conn, $conexion, $model)
    {
       $idTasa = $_SESSION['idTasa'];


        $tableName = 'varios';

        $arregloCondition = ['id_impuesto' => $idTasa];


        $arregloDatos['id_codigo'] = $model->id_codigo;

        $arregloDatos['impuesto'] = $model->impuesto;

        $arregloDatos['ano_impositivo'] = $model->ano_impositivo;

        $arregloDatos['grupo_subnivel'] = $model->grupo_subnivel;

        $arregloDatos['codigo'] = $model->codigo;

        $arregloDatos['descripcion'] = $model->descripcion;

        $arregloDatos['monto'] = $model->monto;

        $arregloDatos['tipo_rango'] = $model->tipo_rango;

        $arregloDatos['cantidad_ut'] = $model->cantidad_ut;

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');

          $conn->open();



                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){



                    return true;

                }

    }

    /**
     * [inactivarTasa description] metodo que realiza el update de la tabla varios e inactiva la tasa, dejandola en estatus 1
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @return [type]           [description] retorna true si modifica y false si no.
     */
    public function inactivarTasa($conn, $conexion)
    {
       $idTasa = $_SESSION['idTasa'];


        $tableName = 'varios';

        $arregloCondition = ['id_impuesto' => $idTasa];

        $arregloDatos['inactivo'] = 1;




          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');

          $conn->open();



                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){



                    return true;

                }

    }


    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de tasas
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


              $modificar = self::modificarTasas($conn, $conexion, $model);


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

               $inactivar = self::inactivarTasa($conn, $conexion);


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
