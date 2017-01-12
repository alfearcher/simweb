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
 *  @file CambioPropietarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/04/16
 * 
 *  @class CambioPropietarioController
 *  @brief Controlador que renderiza los formularios necesarios para el cambio de propietario como comprador
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

namespace frontend\controllers\vehiculo\cambiopropietario;

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
use frontend\models\vehiculo\cambiopropietario\CompradorForm;
use frontend\models\vehiculo\cambioplaca\BusquedaVehiculos;
use frontend\models\vehiculo\cambiopropietario\MostrarDatosVehiculoForm;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
session_start();





class CambioPropietarioCompradorController extends Controller
{



    
  public $layout = 'layout-main';
   




  public function actionComprador()
  {

    //die($_SESSION['id']);

      $model = new CompradorForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                 
                    $buscarPlacaContribuyente = self::buscarPlacaContribuyente($model);

                    if ($buscarPlacaContribuyente == true){

                      return MensajeController::actionMensaje(991);

                    }else{

                      $buscarPlacaNormal = self::buscarPlacaNormal($model);

                        if ($buscarPlacaNormal == true){

                           
                            $_SESSION['datosVehiculo'] = $buscarPlacaNormal;



                            return $this->redirect(['mostrar-datos']);

                        }else{

                          return MensajeController::actionMensaje(992);
                        
                        }
                    }
                }
                

              
            }
            return $this->render('/vehiculo/cambiopropietario/verificar-placa-traspaso', [
                                                        'model' => $model,
                                                       
            ]);  
    }


  public function buscarPlacaContribuyente($model)
  {

    $idContribuyente = yii::$app->user->identity->id_contribuyente;
    
          $buscarPlacaContribuyente = BusquedaVehiculos::find()
                                            ->where([
                                            'placa' => $model->placa,
                                           
                                            'id_contribuyente' => $idContribuyente,
                                            'status_vehiculo' => 0,

                                                ])
                                            ->all();

                                           //die(var_dump($buscarPlacaContribuyente));

                if ($buscarPlacaContribuyente == true){

                    
                  
                    return $buscarPlacaContribuyente;
                }else{
                   
                    return false;

                }
  }

  public function buscarPlacaNormal($model)
  {
    //die($model->placa);
      $buscarPlacaNormal = BusquedaVehiculos::find()
                                            ->where([
                                            'placa' => $model->placa,
                                            'status_vehiculo' => 0,

                                              ])
                                            ->all();

                                            //die(var_dump($buscarPlacaNormal));

                if ($buscarPlacaNormal == true){

                    
                  
                    return $buscarPlacaNormal;
                }else{
                   
                    return false;

                }
    
  }

  public function actionMostrarDatos()
  {

   
  $datosVehiculo = $_SESSION['datosVehiculo'];
  // die(var_dump($datosVehiculo));
    $model = new MostrarDatosVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                  $verificarSolicitud = self::verificarSolicitud($datosVehiculo[0]->id_vehiculo , $_SESSION['id']);

                  if($verificarSolicitud == true){

                    return MensajeController::actionMensaje(403);
                  }else{ 
                 
                        $buscarGuardar = self::beginsave("buscarGuardar" , $datosVehiculo);

                          if($buscarGuardar == true){
                            return MensajeController::actionMensaje(100);
                          }else{
                            return MensajeController::actionMensaje(920);
                          }

                   }
                        
                }
            }
                
                

             
            
            return $this->render('/vehiculo/cambiopropietario/mostrar-datos-vehiculo', [
                                                        'model' => $model,
                                                        'datosVehiculo' => $datosVehiculo,

                                                       
            ]);   
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
             // die($buscar);
              return true;
            }else{

              //die('no encontro');
              return false;
            }
  }

  public function buscarNumeroSolicitud($conn, $conexion, $model)
    {

      $buscar = new ParametroSolicitud($_SESSION['id']);

      $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

      $datosVehiculo = $model[0]->id_vehiculo;
      //die($datosVehiculo);
      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $resultado['impuesto'];
     
      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['tipo_solicitud'] = 66;

      $arregloDatos['id_impuesto'] = $datosVehiculo;

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


    public function guardarCambioPropietario($conn, $conexion, $model , $idSolicitud)
    {
      //die($_SESSION['id']);

      $buscar = new ParametroSolicitud($_SESSION['id']);

      $resultado = $buscar->getParametroSolicitud(["impuesto", "nivel_aprobacion"]);
        //die(var_dump($resultado));
      $idImpuesto = $model[0]->id_vehiculo;

      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_cambios_propietarios';
      $arregloDatos = [];
      $arregloCampo = CompradorForm::attributeSlCambioPropietario();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

     

      $arregloDatos['id_impuesto'] = $idImpuesto;

      $arregloDatos['impuesto'] = $resultado;

      $arregloDatos['id_propietario'] = $model[0]->id_contribuyente;

      $arregloDatos['id_comprador'] = $datos->id_contribuyente;

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['estatus'] = 0;

      $arregloDatos['nro_solicitud'] = $idSolicitud;

      $arregloDatos['origen'] = 'LAN';

      if ($resultado['nivel_aprobacion'] == 1){

        $arregloDatos['fecha_hora_proceso'] = date('Y-m-d h:m:i');

      }else{

        $arregloDatos['fecha_hora_proceso'] = '0000-00-00 00:00:00';

      } 

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

              $resultado = true;

              return $resultado;
        }

    }



    public function guardarCambioPropietarioMaestro($conn, $conexion, $model)
    {
     
    $idContribuyente = yii::$app->user->identity->id_contribuyente;
     
    //die($idContribuyente);
      $tableName = 'vehiculos';
      $arregloCondition = ['id_vehiculo' => $model[0]->id_vehiculo];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['id_contribuyente'] = $idContribuyente;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      //$transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;
              
          }
         

  }

  public function beginSave($var, $datosVehiculo)
  {

    //die(var_dump($datosVehiculo));
        
      $buscar = new ParametroSolicitud($_SESSION['id']);

      $buscar->getParametroSolicitud(["nivel_aprobacion"]);

      $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);

      $conexion = new ConexionController();

      $idSolicitud = 0;

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if($var == "buscarGuardar"){

              $buscar = self::buscarNumeroSolicitud($conn, $conexion, $datosVehiculo);

              if ($buscar > 0){
                //die($buscar);
                  $idSolicitud = $buscar;


              }

                if ($buscar == true){

                  $guardar = self::guardarCambioPropietario($conn,$conexion, $datosVehiculo, $idSolicitud);

                  if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                    //die('no es de aprobacion directa');

                  if ($buscar and $guardar == true ){

                   // die('los dos primeros son true');

                    $transaccion->commit();
                    $conn->close();

                      $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Cambio de Propietario';

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

                      $guardarMaestro = self::guardarCambioPropietarioMaestro($conn,$conexion, $datosVehiculo);

                          if ($buscar and $guardar and $guardarMaestro == true ){

                            //die('las tres son true');

                          $transaccion->commit();
                          $conn->close();

                          $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Cambio de Propietario';

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
