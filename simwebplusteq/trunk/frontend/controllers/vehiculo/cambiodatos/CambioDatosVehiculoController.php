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
 *  @file CambioDatosVehiculoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 09/03/16
 * 
 *  @class CambioDatosVehiculoController
 *  @brief Controlador que renderiza la vista con el formulario de cambio de datos de vehiculo
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

namespace frontend\controllers\vehiculo\cambiodatos;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\cambiodatos\CambioDatosVehiculoForm;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use common\models\configuracion\solicitud\SolicitudProcesoEvento;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CambioDatosVehiculoController extends Controller
{


 
    
  public $layout = 'layout-main';
   
   




  public function actionVistaSeleccion()
  {

       $idConfig = yii::$app->request->get('id');

      $_SESSION['id'] = $idConfig;

      //die($_SESSION['id']);
      if(isset(yii::$app->user->identity->id_contribuyente)){

          $searchModel = new VehiculoSearch();

          $dataProvider = $searchModel->search();
       

          return $this->render('/vehiculo/cambiodatos/seleccionar-vehiculo', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
            
                                                ]); 
      }else{
          echo "No existe User";
      }
    

  }

  public function actionBuscarVehiculo()
  {

      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idVehiculo = yii::$app->request->post('id');
      $_SESSION['idVehiculo'] = $idVehiculo;
  

      $modelsearch = new VehiculoSearch();
      $busqueda = $modelsearch->busquedaVehiculo($idVehiculo, $idContribuyente);

          if ($busqueda == true){ 
        
              $_SESSION['datosVehiculo'] = $busqueda;
        
          return $this->redirect(['cambio-datos-vehiculo']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
  }


    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionCambioDatosVehiculo()
    {
   
     //die($_SESSION['idVehiculo']);
      
        
         if(isset(yii::$app->user->identity->id_contribuyente)){

        

          $datosVehiculo = $_SESSION['datosVehiculo']; 

           if (isset($_SESSION['datosVehiculo'])){ 


            $model = new CambioDatosVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                  $verificarSolicitud = self::verificarSolicitud($datosVehiculo[0]->id_vehiculo , $_SESSION['id']);

                  if($verificarSolicitud == true){

                    return MensajeController::actionMensaje(403);
                  }else{ 

                   $buscarActualizar = self::beginSave("buscarActualizar", $model);

                   if($buscarActualizar == true){
                     return MensajeController::actionMensaje(100);

                   }else{

                    return MensajeController::actionMensaje(920);
                   }
                 }
                
              }
            }
            
            return $this->render('/vehiculo/cambiodatos/seleccionar-vehiculo-cambio-datos', [
                                                              'model' => $model,
                                                              'datos' => $datosVehiculo,

                                                           
            ]);
            
             }else{
               die('no hay datos de vehiculo');
             } 

         }else{

             die('no existe user');

       }
    }

    public function verificarSolicitud($idVehiculo,$idConfig)
    {
      $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idVehiculo,
                                          'id_config_solicitud' => $idConfig,
                                          'estatus' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
             return true;
            }else{
             return false;
            }
    }


    public function buscarNumeroSolicitud($conn, $conexion, $model)
    {
      //die('hola');  
      $buscar = new ParametroSolicitud($_SESSION['id']);

      

        $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

        $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

     
     $datosVehiculo = $_SESSION['datosVehiculo'];


      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $resultado['impuesto'];
     
      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['id_impuesto'] = $datosVehiculo[0]->id_vehiculo;

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


    public function guardarRegistroVehiculo($conn, $conexion, $model , $idSolicitud)
    {
     $datosVehiculo = $_SESSION['datosVehiculo'];

      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_vehiculos';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSlVehiculo();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }
      $arregloDatos['placa'] = $datosVehiculo[0]->placa;

      $arregloDatos['id_vehiculo'] = $datosVehiculo[0]->id_vehiculo;

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['marca'] = $model->marca;

      $arregloDatos['modelo'] = $model->modelo;

      $arregloDatos['color'] = $model->color;

      $arregloDatos['precio_inicial'] = $model->precio_inicial;

      $arregloDatos['no_ejes'] = $model->no_ejes;

      $arregloDatos['liquidado'] = 0;

      $arregloDatos['estatus'] = 0;

      $arregloDatos['medida_cap'] = $model->medida_cap;

      $arregloDatos['capacidad'] = $model->capacidad;

      $arregloDatos['nro_puestos'] = $model->nro_puestos;

      $arregloDatos['peso'] = $model->peso;

      //$arregloDatos['estatus_funcionario'] = 0;

      $arregloDatos['user_funcionario'] = 0;

      $arregloDatos['fecha_funcionario'] = 0;

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['nro_cilindros'] = $model->nro_cilindros;


    if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }


     public function actualizarVehiculoMaestro($conn, $conexion, $model)
    {
     
   
     

      $tableName = 'vehiculos';
      $arregloCondition = ['id_vehiculo' => $_SESSION['idVehiculo']];
     

      $arregloDatos['placa'] = strtoupper($model->placa);

  

      $arregloDatos['marca'] = $model->marca;

      $arregloDatos['modelo'] = $model->modelo;

      $arregloDatos['color'] = $model->color;

      $arregloDatos['precio_inicial'] = $model->precio_inicial;

      $arregloDatos['no_ejes'] = $model->no_ejes;

      $arregloDatos['liquidado'] = 0;

      $arregloDatos['status_vehiculo'] = 0;

      $arregloDatos['medida_cap'] = $model->medida_cap;

      $arregloDatos['capacidad'] = $model->capacidad;

      $arregloDatos['nro_puestos'] = $model->nro_puestos;

      $arregloDatos['peso'] = $model->peso;

      $arregloDatos['nro_cilindros'] = $model->nro_cilindros;

      
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
    
      

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        $nivelAprobacion = $buscar->getParametroSolicitud(['id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion']);

       // die(var_dump($nivelAprobacion));

        $conexion = new ConexionController();

      $idSolicitud = 0;

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "buscarActualizar"){

              $buscar = self::buscarNumeroSolicitud($conn, $conexion, $model);

              if ($buscar > 0){

               // die('consiguio  id');

                  $idSolicitud = $buscar;


              }

              if ($buscar == true){

                  $guardar = self::guardarRegistroVehiculo($conn,$conexion, $model, $idSolicitud);

                  $model->nro_solicitud = $idSolicitud;
                  $resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $nivelAprobacion);
                  
                  if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                    //die('no es de aprobacion directa');

                  if ($buscar and $guardar == true ){
                    //die('actualizo y guardo');

                    $transaccion->commit();
                    $conn->close();

                      $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Cambio de datos del Vehiculo';

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

                      $actualizarVehiculo = self::actualizarVehiculoMaestro($conn,$conexion, $model);

                          if ($buscar and $guardar and $actualizarVehiculo == true ){
                            //die('los tres son verdad');

                          $transaccion->commit();
                          $conn->close();

                         $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Cambio de datos del Vehiculo';

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

    /**
     * Metodo que se encargara de gestionar la ejecucion y resultados de los procesos relacionados
     * a la solicitud. En este caso los proceso relacionados a la solicitud en el evento "CREAR".
     * Se verifica si se ejecutaron los procesos y si los mismos fueron todos positivos. Con
     * el metodo getAccion(), se determina si se ejecuto algun proceso, este metodo retorna un
     * arreglo, si el mismo es null se asume que no habia procesos configurados para que se ejecutaran
     * cuando la solicitud fuese creada. El metodo resultadoEjecutarProcesos(), permite determinar el
     * resultado de cada proceso que se ejecuto.
     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
     * @param  connection $connLocal instancia de conexion que permite ejecutar las acciones en base
     * de datos.
     * @param  model $model modelo de la instancia InscripcionSucursalForm.
     * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
     * solicitud.
     * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
     */
    private function actionEjecutaProcesoSolicitud($conexionLocal, $connLocal, $model, $conf)
    {
      $result = true;
      $resultadoProceso = [];
      $acciones = [];
      $evento = '';
      
      if ( count($conf) > 0 ) {
        if ( $conf['nivel_aprobacion'] == 1 ) {
          $evento = Yii::$app->solicitud->aprobar();
        } else {
          $evento = Yii::$app->solicitud->crear();
        }

        $procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

        // Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
        // que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
        // de resultados donde el key del arrary es el nombre del proceso ejecutado y el valor
        // del elemento corresponda a un reultado de la ejecucion. La variable $model debe contener
        // el identificador del contribuyente que realizo la solicitud y el numero de solicitud.
        $procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, $evento, $conexionLocal, $connLocal);

        // Se obtiene un array de acciones o procesos ejecutados. Sino se obtienen acciones
        // ejecutadas se asumira que no se configuraro ningun proceso para que se ejecutara
        // cuando se creara la solicitud.
        $acciones = $procesoEvento->getAccion();
        if ( count($acciones) > 0 ) {

          // Se evalua cada accion o proceso ejecutado para determinar si se realizo satisfactoriamnente.
          $resultadoProceso = $procesoEvento->resultadoEjecutarProcesos();

          if ( count($resultadoProceso) > 0 ) {
            foreach ( $resultadoProceso as $key => $value ) {
              if ( $value == false ) {
                $result = false;
                break;
              }
            }
          }
        }
      } else {
        $result = false;
      }

      return $result;

    }
    

    

 
              
            
}
    



    

   


    

    



?>
