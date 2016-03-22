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

                   $buscarActualizar = self::beginSave("buscarActualizar", $model);
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


    public function buscarNumeroSolicitud($conn, $conexion, $model)
    {

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
      //die($idSolicitud);

      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_vehiculos';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSlVehiculo();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

     //die($arregloDatos['nro_solicitud']);
      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

     // $arregloDatos['placa'] = $model->placa;

      $arregloDatos['marca'] = $model->marca;

      $arregloDatos['modelo'] = $model->modelo;

      $arregloDatos['color'] = $model->color;

     // $arregloDatos['uso_vehiculo'] = $model->uso_vehiculo


      ;

      $arregloDatos['precio_inicial'] = $model->precio_inicial;

      //$arregloDatos['fecha_inicio'] = $model->fecha_inicio;

     // $arregloDatos['ano_compra'] = $model->ano_compra;

      //$arregloDatos['ano_vehiculo'] = $model->ano_vehiculo;

      $arregloDatos['no_ejes'] = $model->no_ejes;

      $arregloDatos['liquidado'] = 0;

      $arregloDatos['status_vehiculo'] = 0;

   //   $arregloDatos['exceso_cap'] = $model->exceso_cap;

      $arregloDatos['medida_cap'] = $model->medida_cap;

      $arregloDatos['capacidad'] = $model->capacidad;

      $arregloDatos['nro_puestos'] = $model->nro_puestos;

      $arregloDatos['peso'] = $model->peso;

     // $arregloDatos['clase_vehiculo'] = $model->clase_vehiculo;

      //$arregloDatos['tipo_vehiculo'] = $model->tipo_vehiculo;

     // $arregloDatos['serial_motor'] = $model->serial_motor;

      //$arregloDatos['serial_carroceria'] = $model->serial_carroceria;

      //$arregloDatos['nro_calcomania'] = $model->nro_calcomania;

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


     public function actualizarVehiculoMaestro($conn, $conexion, $model)
    {
     
     die(var_dump($model));
     

      $tableName = 'afiliaciones';
      $arregloCondition = ['id_vehiculo' => $idVehiculo]; 
      $resultado = false;

       $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['placa'] = $model->placa;

      $arregloDatos['marca'] = $model->marca;

      $arregloDatos['modelo'] = $model->modelo;

      $arregloDatos['color'] = $model->color;

      $arregloDatos['uso_vehiculo'] = $model->uso_vehiculo;

      $arregloDatos['precio_inicial'] = $model->precio_inicial;

      $arregloDatos['fecha_inicio'] =  date('Y-m-d', strtotime($model->fecha_inicio));

     // die( $arregloDatos['fecha_inicio']);


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

     // $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['nro_cilindros'] = $model->nro_cilindros;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

              $resultado = true;

              return $resultado;
              
          }
         

  }

     

     //die($arregloDatos['nro_solicitud']);
     





    public function beginSave($var, $model)
    {

      

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);

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

                  if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                    //die('no es de aprobacion directa');

                  if ($buscar and $guardar == true ){
                    //die('actualizo y guardo');

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
                  
                  }else{
                    //die('es de aprobacion directa');

                      $actualizarVehiculo = self::actualizarVehiculoMaestro($conn,$conexion, $model);

                          if ($buscar and $guardar and $actualizarVehiculo == true ){

                          $transaccion->commit();
                          $conn->close();

                          $enviarNumeroSolicitud = new EnviarEmailSolicitud;

                         $login = yii::$app->user->identity->login;

                         $solicitud = 'Actualizacion de datos de Vehiculo';

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
    

    

 
              
            
}
    



    

   


    

    



?>
