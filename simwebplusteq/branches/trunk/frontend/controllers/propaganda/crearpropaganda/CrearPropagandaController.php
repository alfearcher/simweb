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
 *  @file CrearPropagandaController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 07/06/16
 *
 *  @class CrearPropagandaController
 *  @brief Controlador que renderiza la vista con el formulario para la inscripcion de la propaganda
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

namespace frontend\controllers\propaganda\crearpropaganda;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\enviaremail\EnviarEmailSolicitud;
use common\models\configuracion\solicitud\ParametroSolicitud;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use frontend\models\propaganda\crearpropaganda\CrearPropagandaForm;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;


/**
 * Site controller
 */

session_start();




class CrearPropagandaController extends Controller
{




  public $layout = 'layout-main';

  /**
   * [actionCrearPropaganda description] metodo que renderiza la vista del formulario para la inscripcion de la propaganda
   * @return [type] [description] retorna la vista del formulario
   */
  public function actionCrearPropaganda()
  {
      $idConfig = yii::$app->request->get('id');
      $_SESSION['id'] = $idConfig;

            $model = new CrearPropagandaForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
//die(var_dump($postData));

               if ($model->validate()){

                $buscarGuardar = self::beginSave("buscarGuardar", $model);

                    if($buscarGuardar == true){
                     return MensajeController::actionMensaje(100);
                    }else{

                    return MensajeController::actionMensaje(920);
            }



              }
          }
          return $this->render('/propaganda/crearpropaganda/formulario-crear-propaganda', [
                                                              'model' => $model,

            ]);



  }

  public function buscarNumeroSolicitud($conn, $conexion)
  {
      //die('hola');
      $buscar = new ParametroSolicitud($_SESSION['id']);



        $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

        $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);




      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $resultado['impuesto'];

      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['id_impuesto'] = 0;

      //die($arregloDatos['id_impuesto']);

      $arregloDatos['tipo_solicitud'] = $resultado['tipo_solicitud'];

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['fecha_hora_creacion'] = date('Y-m-d h:m:i');

      $arregloDatos['nivel_aprobacion'] = $resultado['nivel_aprobacion'];

      $arregloDatos['nro_control'] = 0;

      if ($resultado['nivel_aprobacion'] == 1){

      $arregloDatos['estatus'] = 1;

      }else{

      $arregloDatos['estatus'] = 0;
      }

      $arregloDatos['inactivo'] = 0;

      if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

              $idSolicitud = $conn->getLastInsertID();

          }


          return $idSolicitud;

    }



    public function guardarSolicitud($conn, $conexion , $idSolicitud, $model)
    {
      //die(var_dump($model));

      $buscar = new ParametroSolicitud($_SESSION['id']);

      $buscar->getParametroSolicitud(["nivel_aprobacion"]);

      $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
      //die(var_dump($nivelAprobacion));


      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_propagandas';
      $arregloDatos = [];
      $arregloCampo = CrearPropagandaForm::attributeSlPropagandas();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }
      $arregloDatos['nombre_propaganda'] = $model->nombre_propaganda;

      $arregloDatos['id_impuesto'] = 0;

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['ano_impositivo'] = date('Y');

      $arregloDatos['direccion'] = $model->direccion;
      //die(var_dump($arregloDatos['direccion']));

      $arregloDatos['id_cp'] = 0;

      $arregloDatos['clase_propaganda'] = $model->clase_propaganda;

      $arregloDatos['tipo_propaganda'] = $model->tipo_propaganda;
      //die($arregloDatos['tipo_propaganda']);

      $arregloDatos['uso_propaganda'] = $model->uso_propaganda;

      $arregloDatos['medio_difusion'] = $model->materiales;

      $arregloDatos['medio_transporte'] = $model->medio_transporte;

      $arregloDatos['fecha_desde'] = date('Y-m-d', strtotime($model->fecha_desde));
     // die($arregloDatos['fecha_desde']);

      $arregloDatos['cantidad_tiempo'] = $model->cantidad_tiempo;

      $arregloDatos['id_tiempo'] = $model->id_tiempo;

      $arregloDatos['id_sim'] = $model->id_sim;

      $arregloDatos['cantidad_base'] = $model->cantidad_base;

      $arregloDatos['base_calculo'] = $model->base_calculo;

      $arregloDatos['cigarros'] = $model->cigarros;

      $arregloDatos['bebidas_alcoholicas'] = $model->bebidas_alcoholicas;

      $arregloDatos['cantidad_propagandas'] = 0;

      $arregloDatos['planilla'] = 0;

      $arregloDatos['idioma'] = $model->idioma;

      $arregloDatos['observacion'] = $model->observacion;

      $arregloDatos['fecha_fin'] = date('Y-m-d', strtotime($model->fecha_fin));

      $arregloDatos['fecha_guardado'] = date('Y-m-d');

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['unidad'] = $model->unidad;



      if($nivelAprobacion == 1){
        //die('1');

      $arregloDatos['fecha_hora_proceso'] = date('Y-m-d h:m:i');

      }else{
      //  die('hola');

      $arregloDatos['fecha_hora_proceso'] = 0;
      }

      if ($nivelAprobacion == 1){

      $arregloDatos['estatus'] = 1;

      }else{

      $arregloDatos['estatus'] = 0;
      }

      $arregloDatos['alto'] = $model->alto;

      $arregloDatos['ancho'] = $model->ancho;

      $arregloDatos['profundidad'] = $model->profundidad;


        if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }

    public function guardarPropagandaMaestro($conn, $conexion, $model)
    {



      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'propagandas';
      $arregloDatos = [];
      $arregloCampo = CrearPropagandaForm::attributePropagandas();

         foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }
      $arregloDatos['nombre_propaganda'] = $model->nombre_propaganda;

      $arregloDatos['id_impuesto'] = 0;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['ano_impositivo'] = date('Y');

      $arregloDatos['direccion'] = $model->direccion;
      //die(var_dump($arregloDatos['direccion']));

      $arregloDatos['id_cp'] = 0;

      $arregloDatos['clase_propaganda'] = $model->clase_propaganda;

      $arregloDatos['tipo_propaganda'] = $model->tipo_propaganda;
      //die($arregloDatos['tipo_propaganda']);

      $arregloDatos['uso_propaganda'] = $model->uso_propaganda;

      $arregloDatos['medio_difusion'] = $model->materiales;

      $arregloDatos['medio_transporte'] = $model->medio_transporte;

      $arregloDatos['fecha_desde'] = date('Y-m-d', strtotime($model->fecha_desde));

      $arregloDatos['cantidad_tiempo'] = $model->cantidad_tiempo;

      $arregloDatos['id_tiempo'] = $model->id_tiempo;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['id_sim'] = $model->id_sim;

      $arregloDatos['cantidad_base'] = $model->cantidad_base;

      $arregloDatos['base_calculo'] = $model->base_calculo;

      $arregloDatos['cigarros'] = $model->cigarros;

      $arregloDatos['bebidas_alcoholicas'] = $model->bebidas_alcoholicas;

      $arregloDatos['cantidad_propagandas'] = 1;

      $arregloDatos['planilla'] = 0;

      $arregloDatos['idioma'] = $model->idioma;

      $arregloDatos['observacion'] = $model->observacion;

      $arregloDatos['fecha_fin'] = date('Y-m-d', strtotime($model->fecha_fin));

      $arregloDatos['fecha_guardado'] = date('Y-m-d');

      $arregloDatos['alto'] = $model->alto;

      $arregloDatos['ancho'] = $model->ancho;

      $arregloDatos['profundidad'] = $model->profundidad;

      $arregloDatos['unidad'] = $model->unidad;


        if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }


    }

    public function beginSave($var, $model)
    {
    // die('llegue a begin'.var_dump($model));

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);

       // die(var_dump($nivelAprobacion));

        $conexion = new ConexionController();

      $idSolicitud = 0;

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "buscarGuardar"){


              $buscar = self::buscarNumeroSolicitud($conn, $conexion);

              if ($buscar > 0){

               // die('consiguio  id');

                  $idSolicitud = $buscar;


              }

              if ($buscar == true){

                  $guardar = self::guardarSolicitud($conn,$conexion, $idSolicitud, $model);

                  if ($nivelAprobacion['nivel_aprobacion'] != 1){

                    //die('no es de aprobacion directa');

                  if ($buscar and $guardar == true ){
                    //die('actualizo y guardo');

                    $transaccion->commit();
                    $conn->close();

                      $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Inscripcion de Propaganda Comercial';

                      $DocumentosRequisito = new DocumentoSolicitud();

                      $documentos = $DocumentosRequisito->Documentos();

                        $enviarNumeroSolicitud->plantillaEmailSolicitud($login,$solicitud, $idSolicitud, $documentos);


                        if($enviarNumeroSolicitud == true){

                            return true;


                        }
                  }else{

                      $transaccion->rollback();
                      $conn->close();
                      return false;
                  }

                  }else{
                    //die('es de aprobacion directa');

                      $actualizarCalcomania = self::guardarPropagandaMaestro($conn,$conexion, $model);

                          if ($buscar and $guardar and $actualizarCalcomania == true ){
                            //die('los tres son verdad');

                          $transaccion->commit();
                          $conn->close();

                         $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Inscripcion de Propaganda Comercial';

                      $DocumentosRequisito = new DocumentoSolicitud();

                      $documentos = $DocumentosRequisito->Documentos();
                        $enviarNumeroSolicitud->plantillaEmailSolicitud($login,$solicitud, $idSolicitud, $documentos);


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







}















?>
