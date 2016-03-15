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
 *  @file MostrarPreguntaSeguridadController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 29/02/16
 *
 *  @class RegistrarVehiculoController
 *  @brief Controlador que renderiza vista con el formulario para el registro de vehiculo
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

namespace frontend\controllers\vehiculo\registrar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\enviaremail\EnviarEmailSolicitud;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistrarVehiculoController extends Controller
{




   public $layout = 'layout-main';

    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de registro de vehiculo
     * @return [type] [description] render del formulario de registro de vehiculo
     */
    public function actionRegistrarVehiculo()
    {

        die(var_dump(Yii::$app->solicitud->getId()));
        if(isset(yii::$app->user->identity->id_contribuyente)){

            $model = new RegistrarVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                    $buscarGuardarVehiculo = self::beginSave("buscarGuardar" , $model);

                if($buscarGuardarVehiculo == true){


                    return MensajeController::actionMensaje(100);

                }else{
                    return MensajeController::actionMensaje(420);
                }



                }

            }

            return $this->render('/vehiculo/registrar/registrar-vehiculo', [
                                                           'model' => $model,
            ]);


        }else{

            die('no existe user');

        }
    }

    /**
     * [buscarNumeroSolicitud description] metodo que realiza la busqueda del numero de solicitud en la tabla
     * solicitudes_contribuyente
     * @param  [type] $conn     [description] conexion a la base de datos
     * @param  [type] $conexion [description] instancia de conexion controller
     * @param  [type] $model    [description] modelo del formulario de registro de vehiculo
     * @return [type]           [description] retorna el numero de solicitud de la tabla solicitudes_contribuyente
     */
    public function buscarNumeroSolicitud($conn, $conexion, $model)
    {

      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['impuesto'] = 3;

      $arregloDatos['tipo_solicitud'] = 55;

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['fecha_hora_creacion'] = date('Y-m-d h:m:i');

      $arregloDatos['nivel_aprobacion'] = 0;

      $arregloDatos['nro_control'] = 0;

      $arregloDatos['estatus'] = 1;

      $arregloDatos['inactivo'] = 0;

      if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

              $idSolicitud = $conn->getLastInsertID();

          }

          return $idSolicitud;

    }
    /**
     * [guardarRegistroVehiculo description] metodo que guarda los datos del formulario de registro de vehiculo
     * en la tabla sl_vehiculos
     * @param  [type] $conn        [description] conexion a la base de datos
     * @param  [type] $conexion    [description] instancia de conexion controller
     * @param  [type] $model       [description] modelo del formulario de registro de vehiculo
     * @param  [type] $idSolicitud [description] numero de solicitud buscando en el metodo buscarNumeroSolicitud
     * @return [type]              [description] retorna true si realizo el proceso de guardado.
     */
    public function guardarRegistroVehiculo($conn, $conexion, $model , $idSolicitud)
    {
      //die($idSolicitud);

      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_vehiculos';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeVehiculo();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

     //die($arregloDatos['nro_solicitud']);
      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['placa'] = $model->placa;

      $arregloDatos['marca'] = $model->marca;

      $arregloDatos['modelo'] = $model->modelo;

      $arregloDatos['color'] = $model->color;

      $arregloDatos['uso_vehiculo'] = $model->uso_vehiculo;

      $arregloDatos['precio_inicial'] = $model->precio_inicial;

      $arregloDatos['fecha_inicio'] = $model->fecha_inicio;

      $arregloDatos['ano_compra'] = $model->ano_compra;

      $arregloDatos['ano_vehiculo'] = $model->ano_vehiculo;

      $arregloDatos['no_ejes'] = $model->no_ejes;

      $arregloDatos['liquidado'] = 0;

      $arregloDatos['status_vehiculo'] = 0;

      $arregloDatos['exceso_cap'] = $model->exceso_cap;

      $arregloDatos['medida_cap'] = $model->medida_cap;

      $arregloDatos['capacidad'] = $model->capacidad;

      $arregloDatos['nro_puestos'] = $model->nro_puestos;

      $arregloDatos['peso'] = $model->peso;

      $arregloDatos['clase_vehiculo'] = $model->clase_vehiculo;

      $arregloDatos['tipo_vehiculo'] = $model->tipo_vehiculo;

      $arregloDatos['serial_motor'] = $model->serial_motor;

      $arregloDatos['serial_carroceria'] = $model->serial_carroceria;

      $arregloDatos['nro_calcomania'] = $model->nro_calcomania;

      $arregloDatos['estatus_funcionario'] = 0;

      $arregloDatos['user_funcionario'] = 0;

      $arregloDatos['fecha_funcionario'] = 0;

      $arregloDatos['fecha_hora'] = 0;

      $arregloDatos['nro_cilindros'] = $model->nro_cilindros;






          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }
    /**
     * [beginSave description] metodo de guardado mediante commits, recibe una variable tipo string para indicarle que
     * proceso va a ejecutar
     * @param  [type] $var   [description] variable tipo string enviada desde la funcion principal del controlador
     * @param  [type] $model [description] modelo con la informacion del formulario de registro de vehiculo
     * @return [type]        [description] retorna true si realiza el commit de los datos en las dos tablas, sl_vehiculo
     * y solicitudes_contribuyente
     */
    public function beginSave($var, $model)
    {


        $conexion = new ConexionController();

      $idSolicitud = 0;

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "buscarGuardar"){

              $buscar = self::buscarNumeroSolicitud($conn, $conexion, $model);

              if ($buscar > 0){

                  $idSolicitud = $buscar;


              }

              if ($buscar == true){

                  $guardar = self::guardarRegistroVehiculo($conn,$conexion, $model, $idSolicitud);

                  if ($buscar and $guardar == true ){

                    $transaccion->commit();
                    $conn->close();

                      $enviarNumeroSolicitud = new EnviarEmailSolicitud;

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Registro de Vehiculo';

                      $enviarNumeroSolicitud->enviarEmail($login,$solicitud, $idSolicitud);


                        if($enviarNumeroSolicitud == true){

                            return true;


                        }
                  }else{

                      $transaccion->rollback();
                      $conn->close();
                      return false;
                  }


                  }

          }

    }


}















?>
