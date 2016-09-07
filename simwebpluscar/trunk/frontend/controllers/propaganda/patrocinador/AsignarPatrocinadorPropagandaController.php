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
 *  @file AsignarPatrocinadorPropagandaController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 18/07/16
 * 
 *  @class AsignarPatrocinadorPropagandaController
 *  @brief Controlador que renderiza la vista para asignar un patrocinador para una o varias propagandas asignadas al contribuyente
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
use frontend\models\propaganda\patrocinador\AsignarPatrocinadorPropagandaForm;
use frontend\models\propaganda\patrocinador\BusquedaPatrocinadorNaturalForm;
use yii\data\ArrayDataProvider;
use frontend\models\vehiculo\registrar\RegistrarVehiculoForm;
/**
 * Site controller
 */

session_start();




class AsignarPatrocinadorPropagandaController extends Controller
{



    
  public $layout = 'layout-main';
  
  /**
   * [actionSeleccion description] Metodo que renderiza la vista para la seleccion del año impositivo de la propaganda
 *
   */
  public function actionSeleccion()
  {
      $idConfig = yii::$app->request->get('id');
      $_SESSION['id'] = $idConfig;
      $model = New AsignarPatrocinadorPropagandaForm();
      $postData = yii::$app->request->post();
          if ( $model->load($postData) && Yii::$app->request->isAjax ){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
          }
           
              if ( $model->load($postData) ) {
                  
                  if ($model->validate()){

                      $_SESSION['anoImpositivo'] = $model->ano_impositivo;  
                      
                      return $this->redirect(['buscar-propaganda']);
           
                  }  
          
                      
              }
          return $this->render('/propaganda/patrocinador/seleccionar-ano-impositivo', [
                                                              'model' => $model,
          ]);
  }





  /**
   * [actionBuscarPropaganda description] Metodo que realiza la busqueda por año impositivo e id contribuyente para mostrar las propagandas asociadas al mismo.
   * @return [type] [description] Retorna el formulario con las propagandas activas
   */
  public function actionBuscarPropaganda($errorCheck = "")
  {
   // die('llegue a buscar propaganda');
      $anoImpo = $_SESSION['anoImpositivo'];
     // die($anoImpo);
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      //die($idContribuyente);
      $modelSearch = new AsignarPatrocinadorPropagandaForm();
      $dataProvider = $modelSearch->busquedaPropaganda($anoImpo, $idContribuyente);
     // die(var_dump($model));

          return $this->render('/propaganda/patrocinador/vista-seleccion-propaganda', [
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
    //die('llegue a verificar');
      $todoBien = false;
      $errorCheck = ""; 
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idPropaganda = yii::$app->request->post('chk-verificar-propaganda');
      //die(var_dump($idPropaganda));
      $_SESSION['idPropaganda'] = $idPropaganda;
//die(var_dump($_SESSION['idVehiculo']));
  
      $validacion = new AsignarPatrocinadorPropagandaForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-verificar-propaganda')) == true){
           $modelsearch = new PropagandaForm();
           foreach($idPropaganda as $key => $value){ 
            $value;
            $busqueda[] = $modelsearch->busquedaPropaganda($value, $idContribuyente);
            $todoBien = true;
      //die(var_dump($busqueda));
            }

          if ($todoBien == true){ 
           
        
              $_SESSION['datosPropaganda'] = $busqueda;
              //die(var_dump($busqueda));
        
          return $this->redirect(['busqueda-tipo-patrocinador']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
       }else{
          $errorCheck = "Please select an Advertising";
          return $this->redirect(['buscar-propaganda' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
  }

  /**
   * [busquedaTipoPatrocinador description] Metodo que renderiza la vista para la seleccion de tipo de patrocinador , natural o juridico
  *
   */
  public function actionBusquedaTipoPatrocinador()
  {
      return $this->render('/propaganda/patrocinador/vista-seleccion-tipo-contribuyente');
  }

  /**
   * [actionBusquedaNatural description] Metodo que realiza la busqueda del patrocinador como usuario natural para ver si existe
   * @return [type] [description] retorna la informacion del patrocinador si hace match y retorna un mensaje de error en caso de no 
   * conseguir 
   */
  public function actionBusquedaNatural()
  {
   // die('llegue');

     
           
            $postData = yii::$app->request->post();

            $model = New BusquedaPatrocinadorNaturalForm();

            
            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }
           
            if ( $model->load($postData) ) {
           
            

               if ($model->validate()){
              
                  $buscarPatrocinadorNatural = self::buscarPatrocinador($model);
                      
                      if ($buscarPatrocinadorNatural == true)
                      {
                         $_SESSION['idPatrocinador'] = $buscarPatrocinadorNatural;
                         return $this->redirect(['mostrar-datos-patrocinador']);
                      }else{
                          return MensajeController::actionMensaje(938);
                      }

              }
          }  
         
          return $this->render('/propaganda/patrocinador/busqueda-patrocinador-natural', [
                                                              'model' => $model,
                                                             
                                                             
            ]);
  
  }
  /**
   * [buscarPatrocinador description] metodo que instancia una clase donde se encuentra la funcion de busqueda del patrocinador natural
   * @param  [type] $model [description] modelo que contiene la naturaleza y la cedula del patrocinador
   *
   */
  public function buscarPatrocinador($model)
  {

     $buscar = new BusquedaPatrocinadorNaturalForm();
     $buscarPatrocinadorNatural = $buscar->searchContribuyenteNatural($model);

        if($buscarPatrocinadorNatural){
          return $buscarPatrocinadorNatural[0]->id_contribuyente;
        }else{
          return false;
        }
  }

  public function actionMostrarDatosPatrocinador()
  {


          $idPatrocinador = $_SESSION['idPatrocinador'];
          $idPropagandas = $_SESSION['idPropaganda'];
      
          $buscar = new BusquedaPatrocinadorNaturalForm();

          $dataProvider = $buscar->busquedaPatrocinador($idPatrocinador);
          
        
          $prueba = [];
          foreach($idPropagandas as $key=>$value){
             $buscarPropaganda = $buscar->buscarPropaganda($value);

                if($buscarPropaganda == true){
                    $prueba[$value] = ['id' => $value, 'Propaganda' => $buscarPropaganda];
                } 

          }

  
        


        $provider = new ArrayDataProvider([
            'allModels' => $prueba,
            
            'sort' => [
                 
            'attributes' => ['id', 'Propaganda'],
            
            ],
          
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

                    return $this->render('/propaganda/patrocinador/mostrar-datos-natural', [
                                                                      'provider' => $provider,
                                                                     'dataProvider' => $dataProvider,
                                                                     
                                                                            ]);   


  }
  /**
   * [actionAsignarPatrocinadorNatural description] metodo que verifica si ya las propagandas seleccionadas poseen patrocinador activo y en caso de que no lo posean, realiza el guardado
   
   */
  public function actionAsignarPatrocinadorNatural()
  {
      $todoBien = false;
      $idPatrocinador = $_SESSION['idPatrocinador'];
      $idPropaganda = $_SESSION['idPropaganda'];

      $busquedaPatrocinador = new BusquedaPatrocinadorNaturalForm();
      
      foreach ($idPropaganda as $key => $value){
      
          $verificarPatrocinadorActivo = $busquedaPatrocinador->verificarPatrocinador($idPatrocinador, $value);

          if($verificarPatrocinadorActivo == true){

              $todoBien = true;

          }
      }

      
      if($todoBien == true){
        return MensajeController::actionMensaje(403);
      }else{

          foreach ($idPropaganda as $key => $value){
      
          $verificarPatrocinadorActivoMaestro = $busquedaPatrocinador->verificarPatrocinadorMaestro($idPatrocinador, $value);

          if($verificarPatrocinadorActivoMaestro == true){

              $todoBien = true;

          }
      }

          if ($todoBien == true){

            return MensajeController::actionMensaje(961);

          }else{

            $guardar = self::beginSave("buscarGuardar");
          }



         



      }

  }



 
















  public function buscarNumeroSolicitud($conn, $conexion, $value)
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

      $arregloDatos['id_impuesto'] = $value;

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



    public function guardarSolicitudPatrocinador($conn, $conexion , $idSolicitud, $idPropaganda)
    {
    
        $buscar = new ParametroSolicitud($_SESSION['id']);

        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
     
      
        $datosPropaganda = $_SESSION['datosPropaganda'];
        $nroSolicitud = $idSolicitud;
        $idPatrocinador = $_SESSION['idPatrocinador'];
        $resultado = false;
        $datos = yii::$app->user->identity;
        $tabla = 'sl_propagandas_patrocinadores';
        $arregloDatos = [];
        $arregloCampo = AsignarPatrocinadorPropagandaForm::attributeSlPropagandasPatrocinadores();

            foreach ($arregloCampo as $key=>$value){

                $arregloDatos[$value] =0;
            }
      

      

            $arregloDatos['nro_solicitud'] = $nroSolicitud;

            $arregloDatos['id_impuesto'] = $idPropaganda;

            $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;

            $arregloDatos['id_patrocinador'] = $idPatrocinador;

            $arregloDatos['origen'] = 'LAN';
          

            $arregloDatos['usuario'] = $datos->login;

            $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');

            if($nivelAprobacion == 1){ 
              

            $arregloDatos['fecha_hora_proceso'] = date('Y-m-d h:m:i');

            }else{
           

            $arregloDatos['fecha_hora_proceso'] = 0;
            }

            if ($nivelAprobacion == 1){

            $arregloDatos['estatus'] = 1;

            }else{

            $arregloDatos['estatus'] = 0;
            }

      
                if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

                    $resultado = true;
                    return $resultado;


                }

    }

    public function modificarPropagandaMaestro($conn, $conexion, $value)
    {
      
        $buscar = new ParametroSolicitud($_SESSION['id']);

        $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);

        $datosPropaganda = $_SESSION['datosPropaganda'];
        $idPatrocinador = $_SESSION['idPatrocinador'];
        $resultado = false;
        $datos = yii::$app->user->identity;
        $tabla = 'propagandas_patrocinadores';
        $arregloDatos = [];
        $arregloCampo = AsignarPatrocinadorPropagandaForm::attributePropagandasPatrocinadores();

            foreach ($arregloCampo as $key=>$value){

            $arregloDatos[$value] =0;
            
            }

                $arregloDatos['id_contribuyente'] = $datos->id_contribuyente;
                
                $arregloDatos['id_impuesto'] = $value;

                $arregloDatos['id_patrocinador'] = $idPatrocinador;

                $arregloDatos['estatus'] = 0;
      

                    if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){



                         $resultado = true;
                         return $resultado;


          }
  


    }

    public function beginSave($var)
    {
      
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

          if ($var == "buscarGuardar"){
          

             
                $idSolicitud = 0;
                $idSolicitud = self::buscarNumeroSolicitud($conn, $conexion,$value);


                 if ($idSolicitud > 0){
               
                  foreach($idPropagandas as $key => $value){

                    $guardar = self::guardarSolicitudPatrocinador($conn,$conexion,$idSolicitud , $value);

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

              
              }
             }
             }else{
                $todoBien == false;
                break;
             
              

             
                    if ($todoBien == true){

                    $transaccion->commit();
                    $conn->close();
                    
                      $enviarNumeroSolicitud = new PlantillaEmail();

                      $login = yii::$app->user->identity->login;

                      $solicitud = 'Asignacion de Patrocinador en Propaganda Comercial';

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
