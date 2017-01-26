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
 *  @file DesincorporarPropagandaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 11/08/16
 * 
 *  @class DesincorporarPropagandaController
 *  @brief Controlador que redirecciona a las busquedas y metodos para la desincorporacion de las propagandas comerciales
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

namespace frontend\controllers\propaganda\desincorporarpropaganda;

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
use frontend\models\propaganda\desincorporarpropaganda\DesincorporarPropagandaForm;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;




/**
 * Site controller
 */

session_start();




class DesincorporarPropagandaController extends Controller
{



    
  public $layout = 'layout-main';
   
  /**
   * [actionVistaSeleccion description] Metodo que renderiza la vista para la seleccion de la o las propagandas a desincorporar
   * @param  string $errorCheck [description] Mensaje de error
   * @return [type]             [description] retorna el formulario de seleccion de las propagandas
   */
  public function actionVistaSeleccion($errorCheck = "")
  {
      $idConfig = yii::$app->request->get('id');
      $_SESSION['id'] = $idConfig;

          $searchModel = new DesincorporarPropagandaForm();

          $dataProvider = $searchModel->searchPropaganda();
       

          return $this->render('/propaganda/desincorporarpropaganda/seleccionar-propagandas', [
                                               // 'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                'errorCheck' => $errorCheck,
                                                ]); 
   
  

    
  }

  /**
   * [actionMotivosDesincorporacion description] 
   * @return [type] [description]
   */
  public function actionVerificarDesincorporacion()
  {
    //die('llegue a motivos');
      $errorCheck = ""; 
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idPropaganda = yii::$app->request->post('chk-desincorporar-propaganda');
      //die(var_dump($idPropaganda));
      $_SESSION['idPropaganda'] = $idPropaganda;
//die(var_dump($_SESSION['idPropaganda']));
  
      $validacion = new DesincorporarPropagandaForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-desincorporar-propaganda')) == true){
           $modelsearch = new DesincorporarPropagandaForm();
           $busqueda = $modelsearch->busquedaPropaganda($idPropaganda, $idContribuyente);
      //die(var_dump($busqueda));
          if ($busqueda == true){ 
           
        
              $_SESSION['datosPropaganda'] = $busqueda;
             // die(var_dump($_SESSION['datosPropaganda']));
        
          return $this->redirect(['desincorporar-propaganda']);
        
          }else{

              die('no existe Propaganda asociado a ese ID');
          }
       }else{
          $errorCheck = "Please select an Advertising";
          return $this->redirect(['vista-seleccion' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
     
  }

  public function actionDesincorporarPropaganda()
    {


        $todoBien = true;
      
        if(isset(yii::$app->user->identity->id_contribuyente)){

        

          $datosPropaganda = $_SESSION['idPropaganda']; 
         // die(var_dump($datosVehiculo));
           if (isset($datosPropaganda)){ 


            $model = new DesincorporarPropagandaForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){

                  //die(var_dump($datosPropaganda));
                  
                  foreach($datosPropaganda as $key => $value) {
                     
                     $value;
                     //die($value.'ho');
                    
                     $verificarSolicitud = new DesincorporarPropagandaForm();

                     $verificacion = $verificarSolicitud->verificarSolicitudPropaganda($value , $_SESSION['id']);
                      

                      if($verificacion == true){
                        //die(var_dump($value['id_vehiculo']));
                        $todoBien = false;
                      
                       }
                  }


                    if($todoBien){

                      $buscarActualizar = self::beginSave("buscarActualizar", $model, $datosPropaganda);

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
            
            return $this->render('/propaganda/desincorporarpropaganda/causa-motivos-desincorporacion', [
                                                              'model' => $model,
                                                              

                                                           
            ]);
            
             }else{
               die('no hay datos de Propaganda');
             } 

         }else{

             die('no existe user');

       }
    }



  public function buscarNumeroSolicitud($conn, $conexion)
  {
      //die('hola');  
      $buscar = new ParametroSolicitud($_SESSION['id']);

      

        $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

        $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

     // die(var_dump($resultado["impuesto"]));  
      $datosPropaganda = $_SESSION['datosPropaganda'];
      $impuesto = $resultado['impuesto'];
      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $impuesto;
     
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



    public function guardarSolicitud($conn, $conexion , $model, $idSolicitud,  $idPropaganda)
    {
     
      //die($impuesto);

      $buscar = new ParametroSolicitud($_SESSION['id']);

      $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);
      
      $impuesto = $resultado['impuesto'];
      $datosPropaganda = $_SESSION['datosPropaganda'];
      $numeroSolicitud = $idSolicitud;
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'sl_desincorporaciones';
      $arregloDatos = [];
      $arregloCampo = DesincorporarPropagandaForm::attributeSldesincorporaciones();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }
      //die(var_dump($arregloDatos));

      $arregloDatos['nro_solicitud'] = $numeroSolicitud;

      $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

      $arregloDatos['id_impuesto'] = $idPropaganda;

     //die($arregloDatos['id_impuesto']);

      $arregloDatos['impuesto'] = $impuesto;

     //die($arregloDatos['impuesto']);

      $arregloDatos['usuario'] = $datos->login;

      $arregloDatos['causa_desincorporacion'] = $model->causa;

      $arregloDatos['observacion'] = $model->observacion;

      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

      $arregloDatos['inactivo'] = 0;


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



             $resultado = true;


              return $resultado;

    }

 }

    public function desincorporarPropagandaMaestro($conn, $conexion, $model, $idPropaganda)
    {

      $tableName = 'propagandas';
      $arregloCondition = ['id_impuesto' => $idPropaganda];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['inactivo'] = 1;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      //$transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;
              
          }
         
    


    }

    public function beginSave($var, $model, $datosPropaganda)
    {
      
      $todoBien = true;

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        //die(var_dump($buscar->getParametroSolicitud(["nivel_aprobacion"])));

        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion", "impuesto"]);

       // die(var_dump($nivelAprobacion));

        $conexion = new ConexionController();

        $idSolicitud = 0;

        $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

          if ($var == "buscarActualizar"){

              foreach($datosPropaganda as $key => $value){
              

                $idSolicitud = 0;
                $idSolicitud = self::buscarNumeroSolicitud($conn, $conexion, $model, $value);


                 if ($idSolicitud > 0){
                 // die($idSolicitud);

                    $guardar = self::guardarSolicitud($conn,$conexion, $model, $idSolicitud , $value);

                      if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                          if ($idSolicitud and $guardar == true ){
                            //die('guardo en los dos');
                            $todoBien == true;
                          }
                        
                      
                      }else{


                            $actualizarDesincorporacion = self::desincorporarPropagandMaestro($conn,$conexion, $model, $value);

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

                      $solicitud = 'Desincorporacion de Propaganda';

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

   
    

    

 
              
            
}
    



    

   


    

    



?>
