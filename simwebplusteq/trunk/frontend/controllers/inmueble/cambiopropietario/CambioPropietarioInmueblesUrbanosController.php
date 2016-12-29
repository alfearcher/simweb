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
 *  @file CambioPropietarioInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 12-04-2016
 * 
 *  @class CambioPropietarioInmueblesUrbanosController
 *  @brief Clase que permite controlar el cambio de propietario del inmueble urbano, 
 *  el cambio ha propiedad horizontal
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  CambioPropietarioInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble\cambiopropietario;


use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\CambioPropietarioInmueblesForm;
use backend\models\ContribuyentesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;

error_reporting(0);
session_start();
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */

class CambioPropietarioInmueblesUrbanosController extends Controller
{   
public $layout="layout-main";
    public $conn;
    public $conexion;
    public $transaccion; 
 
     /**
     * Lists all Inmuebles models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $searchModel = new InmueblesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);  
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    }  

    /**
     * Displays a single Inmuebles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) 
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 


   


    /**
     * [actionSeleccionarTipoCambioPropietario description]
     * @return [type] [description]
     */
    public function actionSeleccionarTipoCambioPropietario()
    {
        $idConfig = yii::$app->request->get('id');
        
        $_SESSION['id'] = $idConfig;

        //die($idConfig);

        return $this->render('seleccionar-tipo-cambio-propietario');
    } 

     /**
     * [actionSeleccionarTipoContribuyente description]
     * @return [type] [description]
     */
    public function actionSeleccionarTipoContribuyente()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $searchModel = new InmueblesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 


    public function actionCambioPropietarioInmueblesVendedor()
     { 


         if ( isset(Yii::$app->user->identity->id_contribuyente) ) {

         $datos = $_SESSION['datosInmueble']; 
         //Creamos la instancia con el model de validación
         $model = new DesincorporacionInmueblesForm();

         $postData = Yii::$app->request->post();
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null; 
         $url = null; 
         $tipoError = null; 
         $todoBien = true; 
    
         //Validación mediante ajax
         if ($model->load($postData) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
   
         if ($model->load($postData)){ 

              if($model->validate()){ 

                 //condicionales    
                  $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();

                if (!\Yii::$app->user->isGuest){ 


                     foreach($datos as $key => $value) {
                     
                          $value['id_impuesto']; 
                          //die($value['id_vehiculo']);
                          $verificarSolicitud = self::verificarSolicitud($value['id_impuesto'] , $_SESSION['id']);
                          if($verificarSolicitud == true){
                              //die(var_dump($value['id_vehiculo']));
                              $todoBien = false;
                          
                           } 
                     }
                    
                     

                     if($todoBien == true){
                             
                             $guardo = self::GuardarCambios($model, $datos);
                             if($guardo == true){ 

                                  $envio = self::EnviarCorreo($guardo, $requisitos);

                                  if($envio == true){ 

                                      return MensajeController::actionMensaje(100); 

                                  } else { 
                                    
                                      return MensajeController::actionMensaje(920);

                                  } 

                              } else {

                                    return MensajeController::actionMensaje(920);
                              } 

                     } else {

                            return MensajeController::actionMensaje(900);
                     } 

                   }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{ 
                
                   $model->getErrors(); 
              }
         }              
         
              return $this->render('cambio-propietario-inmuebles-vendedor', ['model' => $model, 'datos'=>$datos]);  

        }  else {
                    echo "No hay Contribuyente Registrado!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['site/login'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles

     public function actionCambioPropietarioInmueblesComprador()
     { 


         if ( isset(Yii::$app->user->identity->id_contribuyente) ) {

         $datos = $_SESSION['datosInmueble']; 
         //Creamos la instancia con el model de validación
         $model = new DesincorporacionInmueblesForm();

         $postData = Yii::$app->request->post();
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null; 
         $url = null; 
         $tipoError = null; 
         $todoBien = true; 
    
         //Validación mediante ajax
         if ($model->load($postData) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
   
         if ($model->load($postData)){ 

              if($model->validate()){ 

                 //condicionales    
                  $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();

                if (!\Yii::$app->user->isGuest){ 


                     foreach($datos as $key => $value) {
                     
                          $value['id_impuesto']; 
                          //die($value['id_vehiculo']);
                          $verificarSolicitud = self::verificarSolicitud($value['id_impuesto'] , $_SESSION['id']);
                          if($verificarSolicitud == true){
                              //die(var_dump($value['id_vehiculo']));
                              $todoBien = false;
                          
                           } 
                     }
                    
                     

                     if($todoBien == true){
                             
                             $guardo = self::GuardarCambios($model, $datos);
                             if($guardo == true){ 

                                  $envio = self::EnviarCorreo($guardo, $requisitos);

                                  if($envio == true){ 

                                      return MensajeController::actionMensaje(100); 

                                  } else { 
                                    
                                      return MensajeController::actionMensaje(920);

                                  } 

                              } else {

                                    return MensajeController::actionMensaje(920);
                              } 

                     } else {

                            return MensajeController::actionMensaje(900);
                     } 

                   }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{ 
                
                   $model->getErrors(); 
              }
         }              
         
              return $this->render('cambio-propietario-inmuebles-comprador', ['model' => $model, 'datos'=>$datos]);  

        }  else {
                    echo "No hay Contribuyente Registrado!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['site/login'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles

     /**
      * [GuardarCambios description] Metodo que se encarga de guardar los datos de la solicitud 
      * de desincorporacion del inmueble del contribuyente
      * @param [type] $model [description] arreglo de datos del formulario de desincorporacion del
      * inmueble
      * @param [type] $datos [description] arreglo de datos del contribuyente 
      */
     public function GuardarCambios($model, $datosInmueble)
     {
            
            $todoBien = true; 
            
            $buscar = new ParametroSolicitud($_SESSION['id']);

            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();
            

            try {

                foreach($datosInmueble as $key => $value){
                
                

                $tableName1 = 'solicitudes_contribuyente'; 

                $arrayDatos1 = [  'id_contribuyente' => $_SESSION['idContribuyente'],
                                  'id_config_solicitud' => $_SESSION['id'],
                                  'impuesto' => 2,
                                  'id_impuesto' => $value['id_impuesto'],
                                  'tipo_solicitud' => $tipoSolicitud,
                                  'usuario' => yii::$app->user->identity->login,
                                  'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                                  'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                                  'nro_control' => 0,
                                  'firma_digital' => null,
                                  'estatus' => 0,
                                  'inactivo' => 0,
                              ];  
                

                if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                    $result = $conexion->getLastInsertID(); 


                    $arrayDatos2 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                        'id_impuesto' => $value['id_impuesto'],
                                        'nro_solicitud' => $result,
                                        'inactivo' => 1,
                                        'fecha_creacion' => date('Y-m-d h:i:s'),
                                    ]; 

                
                     $tableName2 = 'sl_inmuebles'; 

                    if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ) { 

                          $tableName4 = 'sl_desincorporaciones';
                          $arrayDatos4 = [
                                          'nro_solicitud'=>$result,
                                          'id_contribuyente'=>$_SESSION['idContribuyente'],
                                          'id_impuesto'=>$value['id_impuesto'],
                                          'impuesto'=>2,
                                          'causa_desincorporacion'=>$model->causa,
                                          'observacion'=>$model->observacion,
                                          'fecha_hora'=> date('Y-m-d h:m:i'),
                                          'inactivo'=> 0,
                    
                          ]; 
          

                          if($conn->guardarRegistro($conexion, $tableName4,  $arrayDatos4)){



                            if ($nivelAprobacion['nivel_aprobacion'] != 1){

                                $todoBien == true;
                                 

                            } else { 

                                $arrayDatos3 = [    
                                                    'inactivo' => 1,
                                            
                                                ]; 

                    
                                $tableName3 = 'inmuebles';
                                $arrayCondition = ['id_impuesto'=>$value['id_impuesto']];

                                if ( $conn->modificarRegistro($conexion, $tableName3,  $arrayDatos3, $arrayCondition) ){

                                      $todoBien == true; 

                                } else {
                    
                                      $todoBien = false; 
                                      break;

                                }
                            }
                          
                        } else {

                          $todoBien = false; 
                          break;
                        }

                    } else {

                      $todoBien = false; 
                      break;

                    }
                  
                  }else{ 
                    $todoBien == false;
                    break; 
                  }
                } /// fin del foreach 

                if ($todoBien == true){
                    
                    $transaccion->commit();  
                    $conexion->close(); 
                    $tipoError = 0; 
                    return $result;

                } else {
                
                    $transaccion->rollBack(); 
                    $conexion->close(); 
                    $tipoError = 0; 
                    return false; 

                }
                  
               
          
          } catch ( Exception $e ) {
              //echo $e->errorInfo[2];
          } 
                       
     }
/**
 * [verificarSolicitud description]
 * @param  [type] $idInmueble [description] datos del inmueble 
 * @param  [type] $idConfig   [description] id configuracion de la solicuitud de desincorporacion del inmueble
 * @return [type]             [description]
 */
     public function verificarSolicitud($idInmueble,$idConfig)
    {
      $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idInmueble,
                                          'id_config_solicitud' => $idConfig,
                                          'inactivo' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
             return true;
            }else{
             return false;
            }
    }

    /**
     * [DatosConfiguracionTiposSolicitudes description] metodo que busca el tipo de solicitud en 
     * la tabla config_tipos_solicitudes
     */
     public function DatosConfiguracionTiposSolicitudes()
     {

         $buscar = ConfiguracionTiposSolicitudes::find()->where("impuesto=:impuesto", [":impuesto" => 2])
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'ACTUALIZACION DE DATOS'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

     } 


    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $documento)
     {
         $email = yii::$app->user->identity->login;

         $solicitud = 'Cambio de Propietario';

         $nro_solicitud = $guardo; 

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitud($email, $solicitud, $nro_solicitud, $documento)){

             return true; 
         } else { 

             return false; 
         }


     }

    /**
     *Metodo: CambioPropietarioInmuebles
     *Actualiza los datos del numero catastral del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/ 
    public function actionCambioPropietarioInmueblesno()
    { 
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $modelContribuyente = $this->findModelContribuyente($id_contribuyente);
        

        $model = $this->findModel($id_impuesto); 


         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         //
         $msg = null; 
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
         if ($modelContribuyente->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelContribuyente); 
         } 

         $datosCambio = Yii::$app->request->post("InmueblesUrbanosForm");
         $btn = Yii::$app->request->post();


         if ($model->load(Yii::$app->request->post())){
            //if ($modelContribuyente->load(Yii::$app->request->post())){

              //if($modelContribuyente->validate()){ 
           
                if($model->validate()){ 
 
                 //condicionales     
                
                if (!\Yii::$app->user->isGuest){   
                     

               
/*
CONTENIDO VENDEDOR (SELLER)
*/

                if ($datosCambio["operacion"] == 1) { 
                                    
                    if ($datosCambio["tipo_naturaleza1"] == 0) {
                        $tipo = $datosCambio["tipoBuscar1"];
                    } else { 
                        $tipo = 0; 
                    }  

                    $modelParametros = ContribuyentesForm::find()->where(['naturaleza'=>$datosCambio["naturalezaBuscar1"]])
                                                                 ->andWhere(['cedula'=>$datosCambio["cedulaBuscar1"]])
                                                                 ->andWhere(['tipo'=>$tipo])->asArray()->all();                                         


                    if ($btn['AcceptSeller']!=null) {

                        $id_contribuyenteVendedor = $datosCambio["id_contribuyente"];
                        $id_impuestoVenta = $datosCambio["direccion"];
                        $ano_traspaso = $datosCambio["ano_traspaso"];

                        $id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];


                        //--------------TRY---------------
                        $arrayDatos = [ 
                                        'id_contribuyente' => $id_contribuyenteComprador,
                                      ]; 
                        

                        $tableName = 'inmuebles'; 

                        $arrayCondition = ['id_impuesto' => $id_impuestoVenta,]; 

//echo'<pre>'; var_dump($datosCambio); echo '</pre>'; die('hola seller 2'); 
                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                        $this->conexion->open(); 

                        $transaccion = $this->conexion->beginTransaction(); 

                        if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){

                            $transaccion->commit(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'SUCCESSFUL UPDATE DATA OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }else{ 

                            $transaccion->roolBack(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN UPDATE THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                        $this->conexion->close();
                    } 
                }
/*
FIN SELLER
*/


/*
CONTENIDO DEL COMPRADOR (BUYER)
*/  
       
                if ($datosCambio["operacion"] == 2) {
                    //echo'<pre>'; var_dump($btn['Next']); echo '</pre>'; die();
                    if ($btn['NextBuyer']!=null) {
                        $contador = 1;

                        $datosVContribuyente = ContribuyentesForm::find()->where(['naturaleza'=>$datosCambio["naturalezaBuscar"]])
                                                                     ->andWhere(['cedula'=>$datosCambio["cedulaBuscar"]])
                                                                     ->andWhere(['tipo'=>$datosCambio["tipoBuscar"]])->asArray()->all();  

   
                    }
                    if ($btn['NextBuyer']!=null) {
                        
                        if ($datosCambio["datosVendedor"]!=null) {
                            
                     
                            $contador = $contador+1;
                            $datosVInmueble = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$datosCambio["datosVendedor"]])->asArray()->all(); 

                         
                        }
                    }
                    if ($btn['AcceptBuyer']!=null) {
                        $id_contribuyenteComprador = $datosCambio["id_contribuyente"];

                        $ano_traspaso = $datosCambio["ano_traspaso"];
                        $id_contribuyenteVendedor = $datosCambio["datosVendedor"];
                        $id_impuestoVendedor = $datosCambio["inmuebleVendedor"];
                        //$id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];  


                        //--------------TRY---------------
                        $arrayDatos = [ 
                                        'id_contribuyente' => $id_contribuyenteComprador,
                                      ]; 

                        $tableName = 'inmuebles';

                        $arrayCondition = ['id_impuesto' => $id_impuestoVendedor,]; 
//echo'<pre>'; var_dump($datosCambio); echo '</pre>'; die('aqui buyer 1'); 
                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                        $this->conexion->open(); 

                        $transaccion = $this->conexion->beginTransaction(); 

                        if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){

                            $transaccion->commit(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'SUCCESSFUL UPDATE DATA OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }else{  

                            $transaccion->rollBack();  
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN UPDATE THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                        $this->conexion->close();                 

                        
                    } 
                }
/*
FIN BUYER
*/
                 }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                 } 

              }else{ 
                $model->getErrors();
                    //echo var_dump($btn);exit();
                    /*if ($model->tipo_naturaleza1 =="") {

                        $model->addError('tipo_naturaleza1', Yii::t('backend', 'prueba') ); 
                    }
                    if ($model->tipo_naturaleza =="") {

                        $model->addError('cedulaBuscar', Yii::t('backend', 'prueba') ); 
                    }*/
                   
              } 
            /*}else{ 

                   $model->getErrors(); 
              }
         }*/
        }
   
         return $this->render('cambio-propietario-inmuebles', [
                'model' => $model, 'modelContribuyente' => $modelContribuyente, 'modelBuscar' =>$modelBuscar, 'datosVContribuyente'=>$datosVContribuyente,
                'datosVInmueble'=>$datosVInmueble,
            ]); 
        
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 
    

    /**
     * Finds the Inmuebles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inmuebles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    { 
        if (($model = InmueblesUrbanosForm::findOne($id)) !== null) {

            return $model; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
    } 
    
    /**
     * Finds the Contribuyentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contribuyente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModelContribuyente($id)
    {//echo'<pre>'; var_dump($_SESSION['idContribuyente']); echo '</pre>'; die('hola');
        if (($modelContribuyente = ContribuyentesForm::findOne($id)) !== null) {
            
            return $modelContribuyente; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

