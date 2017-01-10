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
 *  @file DesincorporacionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 08-03-2016
 * 
 *  @class DesincorporacionInmueblesUrbanosController
 *  @brief Clase que permite controlar la solicitud de desincorporacion de inmuebles urbanos
 *  en el lado del contribuyente.
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  View
 *  Index
 *  DesincorporacionInmuebles
 *  GuardarCambios
 *  verificarSolicitud
 *  DatosConfiguracionTiposSolicitudes
 *  EnviarCorreo
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble\desincorporacion;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
 
use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
use frontend\models\inmueble\desincorporacion\DesincorporacionInmueblesForm;
use frontend\models\inmueble\InmueblesSearch;
use frontend\models\inmueble\InmueblesConsulta;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;

//use common\models\Users;

// mandar url
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;
// active record consultas..
use yii\db\ActiveRecord;
use common\conexion\ConexionController;
use common\enviaremail\PlantillaEmail;
use common\mensaje\MensajeController;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;
use common\models\configuracion\solicitud\ParametroSolicitud;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\models\contribuyente\ContribuyenteBase;
use common\models\configuracion\solicitud\SolicitudProcesoEvento;

session_start(); 
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class DesincorporacionInmueblesUrbanosController extends Controller
{
    public $layout="layout-main";
    public $conn;
    public $conexion;
    public $transaccion; 

/* 
tablas: solicitudes_contribuyente, sl_inmuebles, config_tipos_solicitudes

*/
      /**
     * Lists all Inmuebles models.
     * @return mixed
     */
    public function actionIndex($errorCheck = "")
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {

            $idConfig = yii::$app->request->get('id');
        
            $_SESSION['id'] = $idConfig;

            $searchModel = new InmueblesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'errorCheck' => $errorCheck,
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
    public function actionView()                                                    
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {

          $errorCheck = ""; 
          $idContribuyente = yii::$app->user->identity->id_contribuyente;
          $idInmueble = yii::$app->request->post('chk-desincorporar-inmueble');
          //die(var_dump($idInmueble));
          $_SESSION['idInmueble'] = $idInmueble;

          //$idInmueble = yii::$app->request->post('id');
          $model = new DesincorporacionInmueblesForm();

          if ($model->validarCheck(yii::$app->request->post('chk-desincorporar-inmueble')) == true){

          $modelsearch = new InmueblesSearch();
          $datos = $modelsearch->busquedaInmueble($idInmueble, $idContribuyente);

          // $datos = InmueblesConsulta::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
          //                                   ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
          //                                   ->one();
              if ($datos == true){ 
           
        
                 $_SESSION['datosInmueble'] = $datos;

              return $this->redirect(['desincorporacion-inmuebles']);
        
              }else{

                 echo "No hay Inmueble asociado al Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
              } 
          }else{
              $errorCheck = "Please select a Property";
              return $this->redirect(['index' , 'errorCheck' => $errorCheck]); 

                                                                                             
          } 



         return $this->render('view', [
             'model' => $datos,
         ]);
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        } 
    } 

    
     /**
     *REGISTRO (desincorporacion) INMUEBLES URBANOS
     *Metodo para generar las solicitudes de desincorporacion
     *@return model, datos
     **/
     public function actionDesincorporacionInmuebles()
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

                            return MensajeController::actionMensaje(972);
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
         
              return $this->render('desincorporacion-inmuebles', ['model' => $model, 'datos'=>$datos]);  

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
            $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]);
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
                     //$resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $config);
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
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'DESINCORPORACION (DUPLICADO Y/O NO EXISTENTE)'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

     } 


    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $documento)
     {
         $datosContribuyente = self::DatosContribuyente();
        
         $email = $datosContribuyente['email'];

         $solicitud = 'Desincorporacion (DUPLICADO Y/O NO EXISTENTE)';

         $nro_solicitud = $guardo; 

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitud($email, $solicitud, $nro_solicitud, $documento)){

             return true; 
         } else { 

             return false; 
         }


     }

     /**
     * [DatosContribuyente] metodo que busca los datos del contribuyente en 
     * la tabla contribuyente
     */
     public function DatosContribuyente()
     {

         $buscar = ContribuyenteBase::find()->where("id_contribuyente=:idContribuyente", [":idContribuyente" => $_SESSION['idContribuyente']])
                                                        ->asArray()->all();


         return $buscar[0];                                              

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






