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
 *  @file IntegracionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class IntegracionInmueblesUrbanosController
 *  @brief Clase que permite controlar la integracion del inmueble urbano, 
 *  
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  IntegracionInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble\integracion;
error_reporting(0);
session_start();
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
use frontend\models\inmueble\integracion\IntegracionInmueblesForm;

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;
use common\mensaje\MensajeController;
use common\enviaremail\PlantillaEmail;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;
use common\models\configuracion\solicitud\ParametroSolicitud;
use common\models\configuracion\solicitud\DocumentoSolicitud;
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class IntegracionInmueblesUrbanosController extends Controller
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
     *Metodo: IntegracionInmuebles
     *Realiza la itegracion del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/
    public function actionIntegracionInmuebles()
    { 
        if ( isset( $_SESSION['idContribuyente'] ) ) {
         //Creamos la instancia con el model de validación
         $model = new IntegracionInmueblesForm();

         $datos = $_SESSION['datos'];

         $idConfig = yii::$app->request->get('id');

         $_SESSION['id'] = $idConfig;
    
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
              return $this->render('integracion-inmuebles', ['model' => $model, 'datos'=>$datos]);  

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
            
            try {
            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $arrayDatos1 = [  'id_contribuyente' => $_SESSION['idContribuyente'],
                              'id_config_solicitud' => $_SESSION['id'],
                              'impuesto' => 2,
                              'id_impuesto' => 0,
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
            $conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();

               $arrayDatos2 = [  'id_contribuyente' =>  $_SESSION['idContribuyente'],
                                       'nro_solicitud'=> $result,
                                       'direccion' => $model->direccion,
                                       'ano_inicio' => $model->ano_inicio,
                                       //direcciones
                                       'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                       'apto_dom' => $model->apto_dom,
                                       //otros datos
                                       'tlf_hab' => $model->tlf_hab,
                                       'medidor' => $model->medidor,
                                       'observacion' => $model->observacion,
                                       'fecha_creacion' => date('Y-m-d h:i:s')
                                       
                                ]; 
                                   

                $arrayDatosInactivarSl1 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'id_impuesto' => $model->direccion1,
                                    'nro_solicitud' => $result,
                                    'inactivo' => 1,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ];
                $arrayDatosInactivarSl2 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'id_impuesto' => $model->direccion2,
                                    'nro_solicitud' => $result,
                                    'inactivo' => 1,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ];

            
                 $tableName2 = 'sl_inmuebles'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2)){
                  if($conn->guardarRegistro($conexion, $tableName2,  $arrayDatosInactivarSl1)) {
                   if($conn->guardarRegistro($conexion, $tableName2,  $arrayDatosInactivarSl2)) {
                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        $transaccion->commit(); 
                        $conexion->close(); 
                        $tipoError = 0;  
                        return $result; 

                    } else {
                
               
                     

                      $arrayDatos3 = [  'id_contribuyente' =>  $_SESSION['idContribuyente'],
                                        'direccion' => $model->direccion,
                                       'ano_inicio' => $model->ano_inicio,
                                       //direcciones
                                       'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                       'apto_dom' => $model->apto_dom,
                                       //otros datos
                                       'tlf_hab' => $model->tlf_hab,
                                       'medidor' => $model->medidor,
                                       'observacion' => $model->observacion,
                                      
                                       
                                ]; 
                                               
                        $arrayDatosInactivacion3 = [    
                                                    'inactivo' => 1,
                                            
                                                ]; 

                        $tableName3 = 'inmuebles';
                        $arrayConditionMaster1 = ['id_impuesto'=>$model->direccion1];
                        $arrayConditionMaster2 = ['id_impuesto'=>$model->direccion2];

                        if ( $conn->guardarRegistro($conexion, $tableName3,  $arrayDatos3) and $conn->modificarRegistro($conexion, $tableName3,  $arrayDatosInactivacion3, $arrayConditionMaster1) and $conn->modificarRegistro($conexion, $tableName3,  $arrayDatosInactivacion3, $arrayConditionMaster2) ){

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
                  }
                  } else {
            
                    $transaccion->rollBack(); 
                    $conexion->close(); 
                    $tipoError = 0; 
                    return false; 

                }
                } else {
            
                    $transaccion->rollBack(); 
                    $conexion->close(); 
                    $tipoError = 0; 
                    return false; 

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
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'INTEGRACION DE PARCELAS'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

     } 


    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $requisitos)
     {
         $email = yii::$app->user->identity->login;

         $solicitud = 'Integracion del Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitud($email, $solicitud, $nro_solicitud, $requisitos)){

             return true; 
         } else { 

             return false; 
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
        if (($model = IntegracionInmueblesForm::findOne($id)) !== null) {

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
