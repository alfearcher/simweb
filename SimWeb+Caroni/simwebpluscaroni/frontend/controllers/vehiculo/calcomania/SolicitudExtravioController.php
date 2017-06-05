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
 *  @file SolicitudExtravioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/05/16
 * 
 *  @class SolicitudExtravioController
 *  @brief Controlador que renderiza la vista con las calcomanias pertenecientes a un contribuyente para solicitar reintegro por daño o extravio
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

namespace frontend\controllers\vehiculo\calcomania;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\enviaremail\EnviarEmailSolicitud;
use frontend\models\vehiculo\cambiodatos\VehiculoSearch;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use frontend\models\vehiculo\calcomania\CalcomaniaSearch;
use frontend\models\vehiculo\calcomania\SolicitudExtravioForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class SolicitudExtravioController extends Controller
{


 
    
  public $layout = 'layout-main';
   
   




  public function actionSeleccionarCalcomania()
  {
    //die('llegue a seleccionar calcomania');

       $idConfig = yii::$app->request->get('id');
       //die($idConfig);
      $_SESSION['id'] = $idConfig;

      //die($_SESSION['id']);
      if(isset(yii::$app->user->identity->id_contribuyente)){

          $searchModel = new CalcomaniaSearch();

          $dataProvider = $searchModel->search();
       

          return $this->render('/vehiculo/calcomania/seleccionar-calcomania', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
            
                                                ]); 
      }else{
          echo "No existe User";
      }
    

  }

  public function actionVerificarCalcomania()
  {
    //die('llegue a verificar');

      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idCalcomania = yii::$app->request->post('id');
      //die($idCalcomania);
      $_SESSION['idCalcomania'] = $idCalcomania;
  
          
          $buscar = new SolicitudExtravioForm();
          $buscarVehiculo = $buscar->verificarCalcomania($idCalcomania);

            if($buscarVehiculo == true){
              $_SESSION['idVehiculo'] = $buscarVehiculo[0]->id_vehiculo;
              $_SESSION['nroCalcomania'] = $buscarVehiculo[0]->nro_calcomania;

              return $this->redirect(['reposicion-calcomania']);
            }else{
              return MensajeController::actionMensaje(920);
            }  
          
    
  }

  public function actionReposicionCalcomania()
  {

      $model = new SolicitudExtravioForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){


                
                 $verificar = new SolicitudExtravioForm();

                  $verificarSolicitud = $verificar->VerificarSolicitud($_SESSION['idVehiculo'], $_SESSION['id']);

                          if($verificarSolicitud ==  true){

                              return MensajeController::actionMensaje(945);

                          }else{
                            
                            $guardar = self::beginSave("buscarGuardar" , $model);

                                if($guardar == true){
                                    return MensajeController::actionMensaje(100);
                                }else{
                                    return MensajeController::actionMensaje(920);
                                }
                          }
                      
                     
                
              }
            }
            
            return $this->render('/vehiculo/calcomania/causa-observacion-solicitud-extravio', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  }


    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
   



    public function buscarNumeroSolicitud($conn, $conexion)
    {
     // die('hola');  
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

      $arregloDatos['id_impuesto'] = $_SESSION['idVehiculo'];

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

      $buscar = new ParametroSolicitud($_SESSION['id']);

      $buscar->getParametroSolicitud(["nivel_aprobacion"]);

      $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
      
      $nroCalcomania = $_SESSION['nroCalcomania'];
      $idImpuesto = $_SESSION['idVehiculo'];
      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_reposiciones_calcomania';
      $arregloDatos = [];
      $arregloCampo = SolicitudExtravioForm::attributeSlReposicionesCalcomania();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['id_impuesto'] = $idImpuesto;

      $arregloDatos['nro_calcomania'] = $nroCalcomania;

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['causa'] = $model->causas;

      $arregloDatos['observacion'] = $model->observacion;

      if($nivelAprobacion == 1){ 

      $arregloDatos['fecha_hora_proceso'] = date('Y-m-d h:m:i');

      }else{

      $arregloDatos['fecha_hora_proceso'] = 0;
      }

      $arregloDatos['user_funcionario'] = 0;

      $arregloDatos['estatus'] = 0;


        if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }

    public function actualizarCalcomaniaMaestro($conn, $conexion)
    {


      $tableName = 'calcomanias';
      $arregloCondition = ['id_calcomania' => $_SESSION['idCalcomania']];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['estatus'] = 9;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      //$transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;
              
          } 
    }

    public function beginSave($var, $model)
    {
    // die('llegue a begin');
      

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

                      $solicitud = 'Reposicion de Calcomania por extravio o daño';

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

                      $actualizarCalcomania = self::actualizarCalcomaniaMaestro($conn,$conexion);

                          if ($buscar and $guardar and $actualizarCalcomania == true ){
                            //die('los tres son verdad');

                          $transaccion->commit();
                          $conn->close();

                         $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Reposicion de Calcomania por extravio o daño';

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
