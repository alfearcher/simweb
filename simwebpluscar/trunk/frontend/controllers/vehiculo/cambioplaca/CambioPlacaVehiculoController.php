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
 *  @file CambioPlacaVehiculoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 11/03/16
 * 
 *  @class CambioPlacaVehiculoController
 *  @brief Controlador que renderiza la vista con el formulario para el cambio de placa del vehiculo
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

namespace frontend\controllers\vehiculo\cambioplaca;

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
use frontend\models\vehiculo\cambioplaca\PlacaSearch;
use common\models\configuracion\solicitud\ParametroSolicitud;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class CambioPlacaVehiculoController extends Controller
{



    
  public $layout = 'layout-main';
   



    public function actionVistaSeleccion()
  {



      //die($_SESSION['id']);
      if(isset(yii::$app->user->identity->id_contribuyente)){


        $idConfig = yii::$app->request->get('id');
        
        $_SESSION['id'] = $idConfig;

       // die($idConfig);

          $searchModel = new PlacaSearch();

          $dataProvider = $searchModel->search();
       

          return $this->render('/vehiculo/cambioplaca/seleccionar-vehiculo-cambio-placa', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
            
                                                ]); 
      }else{
          echo "No existe User";
      }
    

  }


  public function actionBuscarPlaca()
  {
     // die('llegue a buscar placa');
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idVehiculo = yii::$app->request->post('id');

      //die($idVehiculo);
      $_SESSION['idVehiculo'] = $idVehiculo;
  

      $modelsearch = new PlacaSearch();
      $busqueda = $modelsearch->busquedaPlaca($idVehiculo, $idContribuyente);

          if ($busqueda == true){ 
            //die('consiguio placa');
        
              $_SESSION['datosVehiculo'] = $busqueda;
        
          return $this->redirect(['cambio-placa-vehiculo']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
  }

    /**
     * [actionRegistrarVehiculo description] metodo que renderiza y valida el formulario de cambio de datos del vehiculo
     * @return [type] [description] render del formulario de cambio de datos de vehiculo
     */
    public function actionCambioPlacaVehiculo()
    {
      
      
        if(isset(yii::$app->user->identity->id_contribuyente)){

        

          $datosVehiculo = $_SESSION['datosVehiculo']; 

           if (isset($_SESSION['datosVehiculo'])){ 


            $model = new CambioPlacaVehiculoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

               // die('valido');

                   $buscarActualizar = self::beginSave("buscarActualizar", $model);

                   if($buscarActualizar == true){
                     return MensajeController::actionMensaje(100);

                   }else{

                    return MensajeController::actionMensaje(420);
                   }
                 }
                
            
            }
            
            return $this->render('/vehiculo/cambioplaca/cambio-placa-vehiculo', [
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

    public function guardarRegistroCambioPlaca($conn, $conexion, $model , $idSolicitud)
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

    
      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['placa'] = $model->placa;

     // die($arregloDatos['placa']);


    if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;


          }

    }


    public function actualizarPlaca($conn, $conexion, $model)
    {
     
    //die(var_dump($model));
     

      $tableName = 'vehiculos';
      $arregloCondition = ['id_vehiculo' => $_SESSION['idVehiculo']];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['placa'] = $model->placa;

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
     // die($_SESSION['idVehiculo']);
      

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        //die(var_dump($buscar->getParametroSolicitud(["nivel_aprobacion"])));

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

                  $guardar = self::guardarRegistroCambioPlaca($conn,$conexion, $model, $idSolicitud);

                  if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                    //die('no es de aprobacion directa');

                  if ($buscar and $guardar == true ){
                    //die('actualizo y guardo');

                    $transaccion->commit();
                    $conn->close();

                      $enviarNumeroSolicitud = new EnviarEmailSolicitud;

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Cambio de placa';

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

                      $actualizarPlaca = self::actualizarPlaca($conn,$conexion, $model);

                          if ($buscar and $guardar and $actualizarPlaca == true ){
                           // die('los tres son verdad');

                          $transaccion->commit();
                          $conn->close();

                          $enviarNumeroSolicitud = new EnviarEmailSolicitud;

                         $login = yii::$app->user->identity->login;

                         $solicitud = 'Cambio de Placa';

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
