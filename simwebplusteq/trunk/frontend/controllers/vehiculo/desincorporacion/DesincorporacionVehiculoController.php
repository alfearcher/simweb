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
 *  @file DesincorporacionVehiculoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/03/16
 * 
 *  @class DesincorporacionVehiculoController
 *  @brief Controlador que renderiza la vista con el formulario para la desincorporacion del vehiculo
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

namespace frontend\controllers\vehiculo\desincorporacion;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use frontend\models\vehiculo\cambioplaca\CambioPlacaVehiculoForm;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\desincorporacion\DesincorporacionVehiculoForm;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use common\models\configuracion\solicitud\SolicitudProcesoEvento;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);

 

class DesincorporacionVehiculoController extends Controller
{

 
 
    
  public $layout = 'layout-main';
   



    public function actionVistaSeleccion($errorCheck = "")
  {

    //die('llegue a action vista');

      //die($_SESSION['id']);
      if(isset(yii::$app->user->identity->id_contribuyente)){


        $idConfig = yii::$app->request->get('id');
        
        $_SESSION['id'] = $idConfig;

       // die($idConfig);

          $searchModel = new VehiculoSearch();

          $dataProvider = $searchModel->search();
       

          return $this->render('/vehiculo/desincorporacion/seleccionar-vehiculo-desincorporacion', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                'errorCheck' => $errorCheck,
                                                ]); 
      }else{
          echo "No existe User";
      }
    

  }


  public function actionMotivosDesincorporacion()
  {
      $errorCheck = ""; 
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idVehiculo = yii::$app->request->post('chk-desincorporar-vehiculo');
      //die(var_dump($idVehiculo));
      $_SESSION['idVehiculo'] = $idVehiculo;
//die(var_dump($_SESSION['idVehiculo']));
  
      $validacion = new DesincorporacionVehiculoForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-desincorporar-vehiculo')) == true){
           $modelsearch = new VehiculoSearch();
           $busqueda = $modelsearch->busquedaVehiculo($idVehiculo, $idContribuyente);
      //die(var_dump($busqueda));
          if ($busqueda == true){ 
           
        
              $_SESSION['datosVehiculo'] = $busqueda;
        
          return $this->redirect(['desincorporar-vehiculo']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
       }else{
          $errorCheck = "Please select a car";
          return $this->redirect(['vista-seleccion' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
     
  }

    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionDesincorporarVehiculo()
    {


        $todoBien = true;
      
        if(isset(yii::$app->user->identity->id_contribuyente)){

        

          $datosVehiculo = $_SESSION['datosVehiculo']; 
         // die(var_dump($datosVehiculo));
           if (isset($_SESSION['datosVehiculo'])){ 


            $model = new DesincorporacionVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                  //die(var_dump($datosVehiculo));
                  
                  foreach($datosVehiculo as $key => $value) {
                     
                     $value['id_vehiculo'];
                    //die($value['id_vehiculo']);
                     $verificarSolicitud = self::verificarSolicitud($value['id_vehiculo'] , $_SESSION['id']);
                      if($verificarSolicitud == true){
                        //die(var_dump($value['id_vehiculo']));
                        $todoBien = false;
                      
                       }
                  }


                    if($todoBien){

                      $buscarActualizar = self::beginSave("buscarActualizar", $model, $datosVehiculo);

                    if($buscarActualizar == true){
                     
                        return MensajeController::actionMensaje(100);

                    }else{

                        return MensajeController::actionMensaje(920);
                    
                    }
                    
                    }else{
                       return MensajeController::actionMensaje(403);

                    }
                }
                
            
            }
            
            return $this->render('/vehiculo/desincorporacion/motivos-desincorporacion', [
                                                              'model' => $model,
                                                              

                                                           
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

    








    public function buscarNumeroSolicitud($conn, $conexion, $model, $idVehiculo)
    {
      

      $buscar = new ParametroSolicitud($_SESSION['id']);
      //die(var_dump($buscar));
      

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

      //die($arregloDatos['impuesto']);
     
      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['id_impuesto'] = $idVehiculo;

      

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

    public function guardarRegistroDesincorporacion($conn, $conexion, $model , $idSolicitud, $idVehiculo)
    {
        $buscar = new ParametroSolicitud($_SESSION['id']);
        //die(var_dump($buscar));


        $buscar->getParametroSolicitud(["impuesto"]);

        $resultado = $buscar->getParametroSolicitud(["impuesto"]);

        $impuesto = $resultado['impuesto']; 

        ///die($impuesto);

      

      $numeroSolicitud = $idSolicitud;
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_desincorporaciones';
      $arregloDatos = [];
      $arregloCampo = DesincorporacionVehiculoForm::attributeSldesincorporaciones();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

      $arregloDatos['id_contribuyente'] = $idContribuyente;

      $arregloDatos['id_impuesto'] = $idVehiculo;

      $arregloDatos['impuesto'] = $impuesto;

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['causa_desincorporacion'] = $model->motivos;

      $arregloDatos['observacion'] = $model->otrosMotivos;

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['inactivo'] = 0;


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }


    public function actualizarDesincorporacion($conn, $conexion, $model)
    {
     
    //die(var_dump($model));
     

      $tableName = 'vehiculos';
      $arregloCondition = ['id_vehiculo' => $_SESSION['idVehiculo']];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['status_vehiculo'] = 1;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      //$transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;
              
          }
         

  }


    
     public function beginSave($var, $model, $datosVehiculo)
    {
      
      $todoBien = true;

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        //die(var_dump($buscar->getParametroSolicitud(["nivel_aprobacion"])));

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

              foreach($datosVehiculo as $key => $value){

                $idSolicitud = 0;
                $idSolicitud = self::buscarNumeroSolicitud($conn, $conexion, $model, $value['id_vehiculo']);


                 if ($idSolicitud > 0){
                 // die($idSolicitud);

                    $guardar = self::guardarRegistroDesincorporacion($conn,$conexion, $model, $idSolicitud , $value['id_vehiculo']);

                    $model->nro_solicitud = $idSolicitud;
                    $resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $nivelAprobacion);

                      if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                          if ($idSolicitud and $guardar == true ){
                            //die('guardo en los dos');
                            $todoBien == true;
                          }
                        
                      
                      }else{


                            $actualizarDesincorporacion = self::actualizarDesincorporacion($conn,$conexion, $model);

                            if ($idSolicitud and $guardar and $actualizarDesincorporacion == true ){
                              $todoBien == true;

                            }
                        }

              }else{
                $todoBien == false;
                break;
              }

             }
              

             
                    if ($todoBien == true){

                    $transaccion->commit();
                    $conn->close();
                    
                      $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Desincorporacion de Vehiculo';

                      $DocumentosRequisito = new DocumentoSolicitud();

                      $documentos = $DocumentosRequisito->Documentos();
                        $enviarNumeroSolicitud->plantillaEmailSolicitud($login,$solicitud, $idSolicitud, $documentos);


                        if($enviarNumeroSolicitud == true){

                            return true;


                        }else{
                          return false;
                        }

                    }else{

                       $transaccion->rollback();
                      $conn->close();
                      return false;
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
