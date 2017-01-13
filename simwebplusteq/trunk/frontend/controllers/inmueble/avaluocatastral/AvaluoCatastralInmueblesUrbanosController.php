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
 *  @file AvaluoCatastralInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class AvaluoCatastralInmueblesUrbanosController
 *  @brief Clase que permite controlar el avaluo catastral del inmueble urbano 
 *  
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  AvaluoCatastralInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble\avaluocatastral;
error_reporting(0);
session_start();
use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\ContribuyentesForm;
use frontend\models\inmueble\avaluocatastral\AvaluoCatastralForm;

use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;

use common\enviaremail\PlantillaEmail;
use common\mensaje\MensajeController;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;
use common\models\configuracion\solicitud\ParametroSolicitud;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\models\contribuyente\ContribuyenteBase;
use common\models\configuracion\solicitud\SolicitudProcesoEvento;
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class AvaluoCatastralInmueblesUrbanosController extends Controller
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
    public function actionIndex()
    {
        $idConfig = yii::$app->request->get('id');

         $_SESSION['id'] = $idConfig;

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
    public function actionView()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {


          $idInmueble = yii::$app->request->post('id');
           
          $datos = InmueblesConsulta::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->one();
          $_SESSION['datos'] = $datos;

        return $this->render('view', [
            'model' => $datos,
        ]);
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 

    
     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionAvaluoCatastralInmuebles()
     { 
         
         if ( isset($_SESSION['idContribuyente'] ) ) {
         //Creamos la instancia con el model de validación
         $model = new AvaluoCatastralForm();

         $datos = $_SESSION['datos'];
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         }
   
         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){ 

                 //condicionales     
                  $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();

                if (!\Yii::$app->user->isGuest){                                      
                      

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

                   }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{ 
                
                   $model->getErrors(); 
              }
         }
              return $this->render('avaluo-catastral-inmuebles', ['model' => $model, 'datos'=>$datos]);  

        }  else {
                    echo "No hay Contribuyente Registrado!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['site/login'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles

    

     /**
      * [GuardarInscripcion description] Metodo que se encarga de guardar los datos de la solicitud 
      * de inscripcion del inmueble del contribuyente
      * @param [type] $model [description] arreglo de datos del formulario de inscripcion del
      * inmueble
      */
     public function GuardarCambios($model, $datos)
     {
            $buscar = new ParametroSolicitud($_SESSION['id']);

            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]);
            try {
            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $arrayDatos1 = [  'id_contribuyente' => $datos->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'],
                              'impuesto' => 2,
                              'id_impuesto' => $datos->id_impuesto,
                              'tipo_solicitud' => $tipoSolicitud,
                              'usuario' => yii::$app->user->identity->login,
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                              'nro_control' => 0,
                              'firma_digital' => null,
                              'estatus' => 0,
                              'inactivo' => 0,
                          ];  
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();
                

                $avaluoConstruccion = $model->metros_construccion * $model->valor_construccion;
                $avaluoTerreno = $model->metros_terreno * $model->valor_terreno;

                $arrayDatos2 = [    'id_impuesto' => $datos->id_impuesto,
                                    'nro_solicitud' => $result,
                                    'fecha' => date('Y-m-d'),
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $avaluoConstruccion + $avaluoTerreno,
                                    
                                ]; 

            $model->nro_solicitud = $arrayDatos2['nro_solicitud'];
                 $tableName2 = 'sl_historico_avaluos'; 
                $resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $config); 
                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        $transaccion->commit(); 
                        $conexion->close(); 
                        $tipoError = 0;  
                        return $result; 

                    } else {
                
                        $avaluoConstruccion = $model->metros_construccion * $model->valor_construccion;
                        $avaluoTerreno = $model->metros_terreno * $model->valor_terreno;

                        $arrayDatos3 = [    'id_impuesto' => $datos->id_impuesto,
                                            'nro_solicitud' => $result,
                                            'fecha' => date('Y-m-d'),
                                            'mts' => $model->metros_construccion,
                                            'valor_por_mts2' => $model->valor_construccion,
                                            'mts2_terreno' => $model->metros_terreno,
                                            'valor_por_mts2_terreno' => $model->valor_construccion,
                                            'valor' => $avaluoConstruccion + $avaluoTerreno,
                                            'inactivo' => 0,
                                            'fecha_creador' => date('Y'),
                                            'usuario_creador' => $_SESSION['idContribuyente'],
                                            
                                    
                                        ]; 

            
                        $tableName3 = 'historico_avaluos';
                         


                        if ( $conn->guardarRegistro($conexion, $tableName3,  $arrayDatos3) ){

                              $transaccion->commit();  
                              $conexion->close(); 
                              $tipoError = 0; 
                              return true; 

                        } else {
            
                              $transaccion->rollBack(); 
                              $conexion->close(); 
                              $tipoError = 0; 
                              return false; 

                        }
                  }


                } else {
            
                    $transaccion->rollBack(); 
                    $conexion->close(); 
                    $tipoError = 0; 
                    return false; 

                }

            }else{ 
                
                return false;
            }   


                
 
            
          } catch ( Exception $e ) {
              //echo $e->errorInfo[2];
          } 
                       
     }

    /**
     * [DatosConfiguracionTiposSolicitudes description] metodo que busca el tipo de solicitud en 
     * la tabla config_tipos_solicitudes
     */
     public function DatosConfiguracionTiposSolicitudes()
     {

         $buscar = ConfiguracionTiposSolicitudes::find()->where("impuesto=:impuesto", [":impuesto" => 2])
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'REGISTRO DE AVALUO CATASTRAL'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

     } 


    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $requisitos)
     {

         $datosContribuyente = self::DatosContribuyente();
        
         $email = $datosContribuyente['email'];

         $solicitud = 'Avaluo Catastral del Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitudInscripcion($email, $solicitud, $nro_solicitud, $requisitos)){

             return true; 
         } else { 

             return false; 
         }


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
     * Finds the Inmuebles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inmuebles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    // protected function findModel($id)
    // { 
    //     if (($model = AvaluoCatastralForm::findOne($id)) !== null) {

    //         return $model; 
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     } 
    // } 
    
    /**
     * Finds the Contribuyentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contribuyente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   /* public function findModelContribuyente($id)
    {//echo'<pre>'; var_dump($_SESSION['idContribuyente']); echo '</pre>'; die('hola');
        if (($modelContribuyente = ContribuyentesForm::findOne($id)) !== null) {
            
            return $modelContribuyente; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
}

