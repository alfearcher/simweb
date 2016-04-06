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
use common\enviaremail\EnviarEmailSolicitud;
use common\mensaje\MensajeController;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;

session_start(); 
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class DesincorporacionInmueblesUrbanosController extends Controller
{
   
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
                    
                     $guardo = self::GuardarCambios($model, $datos);

                     if($todoBien){

                             if($guardo == true){ 

                                  $envio = self::EnviarCorreo($guardo);

                                  if($envio == true){ 

                                      return MensajeController::actionMensaje(100); 

                                  } else { 
                                    
                                      return MensajeController::actionMensaje(920);

                                  } 

                              } else {

                                    return MensajeController::actionMensaje(920);
                              } 

                     } else {

                            return MensajeController::actionMensaje(920);
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
     public function GuardarCambios($model, $datos)
     {
            die(var_dump($datos));
            $buscar = new ParametroSolicitud($_SESSION['id']);

            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            $conn = New ConexionController();
            $conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();
            

            try {

            foreach($datosVehiculo as $key => $value){
            
            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $tableName1 = 'solicitudes_contribuyente'; 

            $arrayDatos1 = [  'id_contribuyente' => $datos->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'],
                              'impuesto' => 2,
                              'id_impuesto' => $datos->id_impuesto,
                              'tipo_solicitud' => $tipoSolicitud,
                              'usuario' => yii::$app->user->identity->login,
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => $nivelAprobacion,
                              'nro_control' => 0,
                              'firma_digital' => null,
                              'estatus' => 0,
                              'inactivo' => 0,
                          ];  
            

            if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();


                $arrayDatos2 = [    'id_contribuyente' => $datos->id_contribuyente,
                                    'id_impuesto' => $datos->id_impuesto,
                                    'nro_solicitud' => $result,
                                    'inactivo' => 1,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ]; 

            
                 $tableName2 = 'sl_inmuebles'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        $todoBien == true;
                         

                    } else {

                        $arrayDatos3 = [    'id_contribuyente' => $datos->id_contribuyente,
                                            'ano_inicio' => $model->ano_inicio,
                                            'direccion' => $model->direccion,
                                            'medidor' => $model->medidor,
                                            'observacion' => $model->observacion,
                                            'tipo_ejido' => $model->tipo_ejido,
                                            'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                            'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                            'apto_dom' => $model->apto_dom,
                                    
                                        ]; 

            
                        $tableName3 = 'inmuebles';
                        $arrayCondition = ['id_impuesto'=>$datos->id_impuesto];

                        if ( $conn->modificarRegistro($conexion, $tableName3,  $arrayDatos3, $arrayCondition) ){

                              $todoBien == true; 

                        } else {
            
                              $todoBien = false; 

                        }
                  }
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
     public function EnviarCorreo($guardo)
     {
         $email = yii::$app->user->identity->login;

         $solicitud = 'Actualizacion de Datos del Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new EnviarEmailSolicitud();
        
         if ($enviarEmail->enviarEmail($email, $solicitud, $nro_solicitud)){

             return true; 
         } else { 

             return false; 
         }


     }

}






