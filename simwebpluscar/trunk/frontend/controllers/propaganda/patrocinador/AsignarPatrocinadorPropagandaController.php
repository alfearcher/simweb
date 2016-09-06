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


/**
 * Site controller
 */

session_start();




class AsignarPatrocinadorPropagandaController extends Controller
{



    
  public $layout = 'layout-main';
  
  /**
   * [actionSeleccion description] Metodo que renderiza la vista para la seleccion del año impositivo de la propaganda
 
   */
  public function actionSeleccion()
  {
      $idConfig = yii::$app->request->get('id');
      $_SESSION['id'] = $idConfig;
      $model = New BusquedaPatrocinadorNaturalForm();
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
    die('llegue');

     
           
            $postData = yii::$app->request->post();

            $model = New BusquedaPatrocinadorNaturalForm();

            
            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }
           
            if ( $model->load($postData) ) {
           
            

               if ($model->validate()){
              

                 

              }
          }  
         
          return $this->render('/propaganda/modificarpropaganda/formulario-modificar-propaganda', [
                                                              'model' => $model,
                                                             
                                                             
            ]);
  
  }


  /**
   * [actionBusquedaPatrocinador description] Metodo que realiza la busqueda del contribuyente patrocinador de la propaganda, tanto
   * natural como juridico
   */
  public function actionBusquedaPatrocinador()
  {
          //die(var_dump($_SESSION['datosPropaganda']));
            $datosPropaganda = $_SESSION['datosPropaganda'];
            $postData = yii::$app->request->post();

            $model = New AsignarPatrocinadorPropagandaForm();

            
            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }
           
            if ( $model->load($postData) ) {
             // die(var_dump($postData));
            

               if ($model->validate()){
               //die('valido');

                 

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
