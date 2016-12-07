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
 *  @file ModificarPropagandaController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 18/07/16
 *
 *  @class ModificarPropagandaController
 *  @brief Controlador que renderiza la vista con el formulario para la modificacion de la propaganda
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

namespace frontend\controllers\propaganda\modificarpropaganda;

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
use frontend\models\propaganda\modificarpropaganda\ModificarPropagandaForm;
use backend\models\propaganda\Propaganda;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use frontend\models\propaganda\crearpropaganda\CrearPropagandaForm;


/**
 * Site controller
 */

session_start();




class ModificarPropagandaController extends Controller
{




  public $layout = 'layout-main';

  /**
   * [actionVistaSeleccion description] metodo que muestra lista de propagandas pertenecientes al contribuyente para seleccionar una
   * y modificarla
   * @return [type] [description] devuelve la lista con las propagandas asignadas al contribuyente
   */
  public function actionVistaSeleccion()
  {

       $idConfig = yii::$app->request->get('id');

      $_SESSION['id'] = $idConfig;

      //die($_SESSION['id']);
      if(isset(yii::$app->user->identity->id_contribuyente)){

          $searchModel = new ModificarPropagandaForm();

          $dataProvider = $searchModel->search();


          return $this->render('/propaganda/modificarpropaganda/seleccionar-propaganda', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,

                                                ]);
      }else{
          echo "No existe User";
      }


  }






  public function actionBuscarPropaganda()
  {

      $idContribuyente = yii::$app->user->identity->id_contribuyente;

      $_SESSION['idContribuyente'] = $idContribuyente;

      $idPropaganda = yii::$app->request->post('id');

      $_SESSION['idPropaganda'] = $idPropaganda;


      $modelSearch = new ModificarPropagandaForm();
      $model = $modelSearch->busquedaPropaganda($_SESSION['idPropaganda'], $_SESSION['idContribuyente']);
    //  die(var_dump($model));
          if ($model == true ){

             $_SESSION['datosPropaganda'] = $model;
            $modelSearch->attributes = $model->attributes;
              return $this->render('/propaganda/modificarpropaganda/formulario-modificar-propaganda', [
                                                              'model' => $modelSearch,

              ]);





          return $this->redirect(['modificar-propaganda']);

          }else{

              die('no existe propaganda asociado a ese ID');
          }
  }



  /**
   * [actionCrearPropaganda description] metodo que renderiza la vista del formulario para la inscripcion de la propaganda
   * @return [type] [description] retorna la vista del formulario
   */
  public function actionModificarPropaganda()
  {
           //die(var_dump($_SESSION['datosPropaganda']));
            $datosPropaganda = $_SESSION['datosPropaganda'];
            $postData = yii::$app->request->post();
            die(var_dump($postData));
            $model = New ModificarPropagandaForm();


            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die(var_dump($postData));


               if ($model->validate()){
               //die('valido');

                 $verificarSolicitud = self::verificarSolicitud($datosPropaganda->id_impuesto , $_SESSION['id']);

                     if($verificarSolicitud == true){
                       return MensajeController::actionMensaje(403);
                    }else{

                 $buscarGuardar = self::beginSave("buscarGuardar", $model);

                    if($buscarGuardar == true){
                     return MensajeController::actionMensaje(100);
                    }else{

                    return MensajeController::actionMensaje(920);
           }


             }
              }
          }
          // die(var_dump($datosPropaganda));
          return $this->render('/propaganda/modificarpropaganda/formulario-modificar-propaganda', [
                                                              'model' => $model,
                                                              //'datos' => $datosPropaganda,

            ]);



  }


  public function verificarSolicitud($idPropaganda,$idConfig)
  {
      $clase = new ModificarPropagandaForm();

      $buscar = $clase->verificarSolicitud($idConfig, $idPropaganda);

          if($buscar == true){
            return true;
          }else{
            return false;
          }

  }

  public function buscarNumeroSolicitud($conn, $conexion)
  {
      //die('hola');
      $buscar = new ParametroSolicitud($_SESSION['id']);



        $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

        $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);



      $datosPropaganda = $_SESSION['datosPropaganda'];
      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $resultado['impuesto'];

      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['id_impuesto'] = $datosPropaganda->id_impuesto;

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



      $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
      //die(var_dump($nivelAprobacion));

      $datosPropaganda = $_SESSION['datosPropaganda'];
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

      $arregloDatos['id_impuesto'] = $datosPropaganda->id_impuesto;

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

      $arregloDatos['medio_difusion'] = $model->medio_difusion;

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

      $arregloDatos['unidad'] = $model->unidad;


        if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }

    public function modificarPropagandaMaestro($conn, $conexion, $model)
    {
      $datos = yii::$app->user->identity;

       $buscar = new ParametroSolicitud($_SESSION['id']);



      $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
      //die(var_dump($nivelAprobacion));

      $tableName = 'propagandas';

      $arregloCondition = ['id_impuesto' => $_SESSION['idPropaganda']];

      $arregloDatos['nombre_propaganda'] = $model->nombre_propaganda;

     // $arregloDatos['id_impuesto'] = 0;



      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['ano_impositivo'] = date('Y');

      $arregloDatos['direccion'] = $model->direccion;
      //die(var_dump($arregloDatos['direccion']));

      $arregloDatos['id_cp'] = 0;

      $arregloDatos['clase_propaganda'] = $model->clase_propaganda;

      $arregloDatos['tipo_propaganda'] = $model->tipo_propaganda;
      //die($arregloDatos['tipo_propaganda']);

      $arregloDatos['uso_propaganda'] = $model->uso_propaganda;

      $arregloDatos['medio_difusion'] = $model->medio_difusion;

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










      if ($nivelAprobacion == 1){

      $arregloDatos['inactivo'] = 1;

      }else{

      $arregloDatos['inactivo'] = 0;
      }

      $arregloDatos['alto'] = $model->alto;

      $arregloDatos['ancho'] = $model->ancho;

      $arregloDatos['profundidad'] = $model->profundidad;

      $arregloDatos['unidad'] = $model->unidad;


      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;

          }



    }

    public function beginSave($var, $model)
    {
    //die('llegue a begin'.var_dump($model));

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

                //die('consiguio  id'.$buscar);

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

                      $solicitud = 'Modificacion de Datos de Propaganda';

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

                      $actualizarPropaganda = self::modificarPropagandaMaestro($conn,$conexion, $model);

                          if ($buscar and $guardar and $actualizarPropaganda == true ){
                           // die('los tres son verdad');

                          $transaccion->commit();
                          $conn->close();

                         $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Modificacion de Datos de Propaganda';

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
