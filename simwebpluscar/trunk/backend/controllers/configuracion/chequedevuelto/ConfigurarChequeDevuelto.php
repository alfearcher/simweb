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
 *  @file ConfigurarConveniosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 05-10-2016
 * 
 *  @class configurarConveniosController
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
namespace backend\controllers\configuracion\chequedevuelto;


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
use backend\models\inmueble\IntegracionInmueblesForm;
use backend\models\configuracion\convenios\ConfigChequeDevuelto;
use backend\models\configuracion\convenios\ConfigChequeDevueltoSearch;
use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class ConfigurarChequeDevueltoController extends Controller
{   

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
    public function actionCreate()
     { 
         
         if ( isset( $_SESSION['idContribuyente'] ) ) {
         //Creamos la instancia con el model de validación
         $model = new ConfigConvenios();

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

                          // $envio = self::EnviarCorreo($guardo, $requisitos);

                          // if($envio == true){ 

                              return MensajeController::actionMensaje(100);

                          // } else { 
                            
                          //     return MensajeController::actionMensaje(920);

                          // }

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
              return $this->render('create', ['model' => $model, 'datos'=>$datos]);  

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
//die(var_dump($datos));
            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            
            try {
            // $tableName1 = 'solicitudes_contribuyente'; 

            // $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            // $arrayDatos1 = [  'id_contribuyente' => $datos->id_contribuyente,
            //                   'id_config_solicitud' => $_SESSION['id'],
            //                   'impuesto' => 2,
            //                   'id_impuesto' => $datos->id_impuesto,
            //                   'tipo_solicitud' => $tipoSolicitud,
            //                   'usuario' => yii::$app->user->identity->login,
            //                   'fecha_hora_creacion' => date('Y-m-d h:i:s'),
            //                   'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
            //                   'nro_control' => 0,
            //                   'firma_digital' => null,
            //                   'estatus' => 1,
            //                   'inactivo' => 0,
            //               ];  
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            // if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
            //     $result = $conexion->getLastInsertID();

                // $arrayCampos2 = ['id_contribuyente','nro_solicitud','ano_inicio','direccion','medidor','observacion',
                //                  'tipo_ejido', 'casa_edf_qta_dom', 'piso_nivel_no_dom', 'apto_dom', 'fecha_creacion'];

                // $arrayDatos2 = [   [$datos->id_contribuyente, $result, $model->ano_inicio,
                //                    $model->direccion, $model->medidor, $model->observacion, $model->tipo_ejido,
                //                    $model->casa_edf_qta_dom, $model->piso_nivel_no_dom, $model->apto_dom,
                //                    date('Y-m-d h:i:s')],

                //                    [$datos->id_contribuyente, $result, $model->ano_inicio,
                //                    $model->direccion1, $model->medidor1, $model->observacion1, $model->tipo_ejido1,
                //                    $model->casa_edf_qta_dom1, $model->piso_nivel_no_dom1, $model->apto_dom1,
                //                    date('Y-m-d h:i:s')],
                //                 ]; 

                // $arrayDatosInactivar2 = [    'id_contribuyente' => $datos->id_contribuyente,
                //                     'id_impuesto' => $datos->id_impuesto,
                //                     'nro_solicitud' => $result,
                //                     'inactivo' => 1,
                //                     'fecha_creacion' => date('Y-m-d h:i:s'),
                //                 ];

            
                //  $tableName2 = 'sl_inmuebles'; 

                // if ( $conn->guardarLoteRegistros($conexion, $tableName2, $arrayCampos2,  $arrayDatos2) and $conn->guardarRegistro($conexion, $tableName2,  $arrayDatosInactivar2)){

                //     if ($nivelAprobacion['nivel_aprobacion'] != 1){

                //         $transaccion->commit(); 
                //         $conexion->close(); 
                //         $tipoError = 0;  
                //         return $result; 

                //     } else {
                        $arrayCampos3 = ['id_contribuyente','ano_inicio','direccion','medidor','observacion',
                                         'tipo_ejido', 'casa_edf_qta_dom', 'piso_nivel_no_dom', 'apto_dom'];

                        $arrayDatos3 = [    [$datos->id_contribuyente, $model->ano_inicio, $model->direccion,
                                            $model->medidor, $model->observacion, $model->tipo_ejido, 
                                            $model->casa_edf_qta_dom, $model->piso_nivel_no_dom, $model->apto_dom],

                                            [$datos->id_contribuyente, $model->ano_inicio1, $model->direccion1,
                                            $model->medidor1, $model->observacion1, $model->tipo_ejido1, 
                                            $model->casa_edf_qta_dom1, $model->piso_nivel_no_dom1, $model->apto_dom1],
                                    
                                        ];  
                        $arrayDatosInactivacion3 = [    
                                                    'inactivo' => 1,
                                            
                                                ];   

                    
                       
                        //$arrayConditionInactivacion3 = ['id_impuesto'=>$model->id_impuesto];


                        $tableName3 = 'inmuebles';
                        $arrayCondition = ['id_impuesto'=>$datos->id_impuesto];

                        if ( $conn->guardarLoteRegistros($conexion, $tableName3,  $arrayCampos3, $arrayDatos3) and $conn->modificarRegistro($conexion, $tableName3,  $arrayDatosInactivacion3, $arrayCondition) ){

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
                  //}


            //     } else {
            
            //         $transaccion->rollBack(); 
            //         $conexion->close(); 
            //         $tipoError = 0; 
            //         return false; 

            //     }

            // }else{ 
                
            //     return false;
            // }   
            
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
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'DESINTEGRACION DE PARCELA'])
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

         $solicitud = 'Cheque devuelto';     

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
