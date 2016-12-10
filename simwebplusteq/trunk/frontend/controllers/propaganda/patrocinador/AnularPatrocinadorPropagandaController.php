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
 *  @file AnularPatrocinadorPropagandaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 12/09/16
 * 
 *  @class AnularPatrocinadorPropagandaController
 *  @brief Controlador que renderiza la vista para anular un patrocinador a una o varias propagandas asignadas al contribuyente
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

namespace frontend\controllers\propaganda\patrocinador;

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
use backend\models\propaganda\Propaganda;
use backend\models\propaganda\PropagandaForm;
use frontend\models\propaganda\patrocinador\AnularPatrocinadorPropagandaForm;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
/**
 * Site controller
 */

session_start();




class AnularPatrocinadorPropagandaController extends Controller
{



    
  public $layout = 'layout-main';
  
  /**
   * [actionSeleccion description] Metodo que renderiza la vista para la seleccion de las propagandas asociadas al contribuyente que poseen un patrocinador activo
 *
   */
  public function actionSeleccion($errorCheck = "")
  {
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idConfig = yii::$app->request->get('id');
      $_SESSION['id'] = $idConfig;

      $model = New AnularPatrocinadorPropagandaForm();

      $dataProvider = $model->getDataProviderRelacion($idContribuyente);
      
      //die(var_dump($modelRelacion));

          return $this->render('/propaganda/patrocinador/seleccionar-propaganda-patrocinador', [
                                                              'dataProvider' => $dataProvider,
                                                              'errorCheck' => $errorCheck,
          ]);

  }







  /**
   * [actionVerificarPropaganda description] Metodo que realiza la verificacion de la propaganda y a su vez realiza la busqueda de la misma para almacenar todos los datos de las propagandas encontradas
   * @return [type] [description]
   */
  public function actionVerificarPropaganda()
  {
    
      $todoBien = true;
      $errorCheck = ""; 
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idPropaganda = yii::$app->request->post('chk-verificar-propaganda');
      //die(var_dump($idPropaganda));
      //
      $_SESSION['idPropaganda'] = $idPropaganda;


      $validacion = new AnularPatrocinadorPropagandaForm();

      
      if ($validacion->validarCheck(yii::$app->request->post('chk-verificar-propaganda')) == true){

          $buscar = $validacion->busquedaIdImpuesto($idPropaganda);

          if($buscar == true){
       
              $_SESSION['idImpuesto'] = $buscar; 
          }else{

            return MensajeController::actionMensaje(900);

          }

            foreach($buscar as $key=>$value){

                $value;

                    $verificarSolicitud = $validacion->verificarSolicitudPatrocinio($_SESSION['id'], $value['id_impuesto']); 

                if($verificarSolicitud == true){

                    $todoBien = false;
                }

            }

            if($todoBien == true){

              // die($value['id_impuesto']);


        
          return $this->redirect(['causa-observacion']);
        
          }else{

            return MensajeController::actionMensaje(403);
          }

              die('no existe vehiculo asociado a ese ID');
         
       }else{
          $errorCheck = "Please select an Advertising";
          return $this->redirect(['seleccion' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
  }


  public function actionCausaObservacion()
  {
     // die('llegue');

     
           
            $postData = yii::$app->request->post();

            $model = New AnularPatrocinadorPropagandaForm();

            
            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }
           
            if ( $model->load($postData) ) {
           
            

               if ($model->validate()){

                
                  $buscarActualizar = self::beginSave("buscarActualizar", $model);

                      if($buscarActualizar ==  true){
                        return MensajeController::actionMensaje(200);
                      }else{
                        return MensajeController::actionMensaje(920);
                      }
                 

              }
          }  
         
          return $this->render('/propaganda/patrocinador/causa-observacion', [
                                                              'model' => $model,
                                                             
                                                             
            ]);
  
  }


   

  /**
   * [buscarNumeroSolicitud description] Metodo que realiza la busqueda del numero de solicitud en la tabla solicitudes contribuyente
   * @param  [type] $conn     [description] parametro de conexion
   * @param  [type] $conexion [description] parametro de conexion
   * @param  [type] $value    [description] ids de los impuestos
   * @return [type]           [description] retorna el numero de solicitud en caso que todo guarde correctamente
   */
  public function buscarNumeroSolicitud($conn, $conexion, $value)
  {
  // die(var_dump($value));
      //die('hola');  
      $buscar = new ParametroSolicitud($_SESSION['id']);
     
      

        $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

        $resultado = $buscar->getParametroSolicitud(["tipo_solicitud", "impuesto", "nivel_aprobacion"]);

     
    
      $idImpuesto = $value;
      $datos = yii::$app->user->identity;
      $tabla = 'solicitudes_contribuyente';
      $arregloDatos = [];
      $arregloCampo = RegistrarVehiculoForm::attributeSolicitudContribuyente();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] = 0;
      }

      $arregloDatos['impuesto'] = $resultado['impuesto'];
     
      $arregloDatos['id_config_solicitud'] = $_SESSION['id'];

      $arregloDatos['id_impuesto'] = $idImpuesto;

    // die($arregloDatos['id_impuesto']);

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


    /**
     * [guardarSolicitudPatrocinador description] Metodo que realiza el guardado de los datos en la tabla sl_propagandas_patrocinadores
     * @param  [type] $conn         [description] parametro de conexion
     * @param  [type] $conexion     [description] parametro de conexion
     * @param  [type] $idSolicitud  [description] nro de solicitud
     * @param  [type] $idPropaganda [description] id de las propagandas
     * @return [type]               [description] retorna true o false 
     */
    public function guardarSolicitudAnulacion($conn, $conexion , $idSolicitud, $idImpuesto, $model, $idPatrocinador)
    {
     //die('llegue a aguardar');
    
        $buscar = new ParametroSolicitud($_SESSION['id']);
       
        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion", "impuesto"]);
        $nroSolicitud = $idSolicitud;
        $resultado = false;
        $datos = yii::$app->user->identity;
        $tabla = 'sl_anulaciones_patrocinadores';
        $arregloDatos = [];
        $arregloCampo = AnularPatrocinadorPropagandaForm::attributeSlAnulacionesPatrocinadores();

            foreach ($arregloCampo as $key=>$value){

                $arregloDatos[$value] =0;
            }
      

      

            $arregloDatos['nro_solicitud'] = $nroSolicitud;



            $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

            $arregloDatos['id_impuesto'] = $idImpuesto;

            $arregloDatos['id_patrocinador'] = $idPatrocinador;

             //die(var_dump($arregloDatos['id_impuesto']).'hola');

            $arregloDatos['impuesto'] = $nivelAprobacion['impuesto'];

            $arregloDatos['causa_desincorporacion'] = $model->causa;

            $arregloDatos['observacion'] = $model->observacion;

            $arregloDatos['usuario'] = $datos->login;

            $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

            
          

            $arregloDatos['usuario'] = $datos->login;

            if ($nivelAprobacion == 1){

            $arregloDatos['estatus'] = 1;

            }else{

            $arregloDatos['estatus'] = 0;
            }

            if($nivelAprobacion == 1){ 
              

            $arregloDatos['fecha_hora_proceso'] = date('Y-m-d h:m:i');

            }else{
           

            $arregloDatos['fecha_hora_proceso'] = 0;
            }

            $arregloDatos['origen'] = 'LAN';

            
      
                if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

                    $resultado = true;
                    return $resultado;


                }

    }

    /**
     * [modificarPropagandaMaestro description] metodo que inserta los datos en la tabla maestro, propagandas_patrocinadores, en caso que la solicitud sea de aprobacion directa
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @param  [type] $value    [description] id de las propagandas
     * @return [type]           [description] retorna true o false
     */
    
    public function modificarPropagandasPatrocinadores($conn, $conexion)
    {
      
       
      $idPropagandaPatrocinador = $_SESSION['idPropaganda'];
      $tableName = 'propagandas_patrocinadores';
      $arregloCondition = ['id_propaganda_patrocinador' => $idPropagandaPatrocinador];
      //die(var_dump($arregloCondition));
     

      

      $arregloDatos['estatus'] = 1;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      //$transaccion = $conn->beginTransaction();

          if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             // die('modifico');

              return true;
              
          }
  


    }

    /**
     * [beginSave description] metodo padre que redirecciona a otros metodos los cuales realizan los guardados en las tablas respectivas
     * @param  [type] $var [description] variable tipo string que acciona el guardado
     * @return [type]      [description] retorna true o false y realiza el envio de correo electronico al contribuyente
     */
    public function beginSave($var, $model)
    {
     // die(var_dump($model).'llegue a begin');
     $idImpuesto = $_SESSION['idImpuesto']; 
    // die(var_dump($idImpuesto)); 
    $idPropagandas = $_SESSION['idPropaganda']; 
    $todoBien = true;

      $buscar = new ParametroSolicitud($_SESSION['id']);

        $buscar->getParametroSolicitud(["nivel_aprobacion"]);

       
        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);

     

        $conexion = new ConexionController();

        $idSolicitud = 0;

        $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

          if ($var == "buscarActualizar"){
            //die('llego');
          
                foreach($idImpuesto as $key => $value){
             
                $idSolicitud = 0;
                $idSolicitud = self::buscarNumeroSolicitud($conn, $conexion,$value['id_impuesto']);


                if ($idSolicitud > 0){
                 // die(var_dump($idSolicitud));
               
                  

                    $guardar = self::guardarSolicitudAnulacion($conn,$conexion,$idSolicitud , $value['id_impuesto'], $model, $value['id_patrocinador']);

                      if ($nivelAprobacion['nivel_aprobacion'] != 1){ 

                          if ($idSolicitud and $guardar == true ){
                          
                            $todoBien == true;
                          }
                        
                      
                      }else{


                            $actualizarDesincorporacion = self::insertarPatrocinadorMaestro($conn,$conexion, $value);

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

                      $solicitud = 'Anulacion de Patrocinador en Propagandas';

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
