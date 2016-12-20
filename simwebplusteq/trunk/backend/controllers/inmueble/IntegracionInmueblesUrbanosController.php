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
namespace backend\controllers\inmueble;
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

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;
use common\mensaje\MensajeController;
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
                
               
                     $catastro1 = array(['estado' => $model->estado_catastro, 'municipio'=> $model->municipio_catastro, 'parroquia'=>$model->parroquia_catastro, 'ambito'=>$model->ambito_catastro, 'sector'=>$model->sector_catastro, 'manzana' =>$model->manzana_catastro]);
                     $catastro = "".$catastro1[0]['estado']."-".$catastro1[0]['municipio']."-".$catastro1[0]['parroquia']."-".$catastro1[0]['ambito']."-".$catastro1[0]['sector']."-".$catastro1[0]['manzana']."";
                     
                     
                     if ($propiedad_horizontal == 0) {

                          $parcela_catastro = $model->parcela_catastro;                                     //Parcela catastro
                          $subparcela_catastro = 0;                                                         //Sub parcela catastro
                          $nivel_catastro = 0;                                                              //Nivel catastro
                          $unidad_catastro = 0;                                                             //Unidad catastro     
                     }else{ 

                          $parcela_catastro = $model->parcela_catastro;                                     //Parcela catastro
                          $subparcela_catastro = $model->subparcela_catastro;                               //Sub parcela catastro
                          $nivel_c1 = $model->nivela;
                          $nivel_c2 = $model->nivelb;
                          $nivel_catastro1 = array(['nivela' =>$nivel_c1 , 'nivelb'=>$nivel_c2 ]);              //Nivel catastro
                          $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";
                          $unidad_catastro = $model->unidad_catastro;                                       //Unidad catastro  
                          
                     }

                      $arrayDatos = ['id_contribuyente' =>  $_SESSION['idContribuyente'],
                                       'ano_inicio' => $model->ano_inicio,
                                       'direccion' => $model->direccion,
                                       'manzana_limite' => 0,
                                       'nivel' => 0,
                                       //direcciones
                                       'av_calle_esq_dom' => $model->av_calle_esq_dom,
                                       'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                       'apto_dom' => $model->apto_dom,
                                       //otros datos
                                       'tlf_hab' => $model->tlf_hab,
                                       'medidor' => $model->medidor,
                                       'observacion' => $model->observacion,
                                       'inactivo' => 0,
                                       'catastro' => $catastro,
                                       'id_habitante' => 0,
                                       'tipo_ejido' => $model->tipo_ejido,
                                       'propiedad_horizontal' => $model->propiedad_horizontal,
                                       //catastro inmueble
                                       'estado_catastro' => $model->estado_catastro,
                                       'municipio_catastro' => $model->municipio_catastro,
                                       'parroquia_catastro' => $model->parroquia_catastro,
                                       'ambito_catastro' => $model->ambito_catastro,
                                       'sector_catastro' => $model->sector_catastro,
                                       'manzana_catastro' => $model->manzana_catastro,
                                       //parcelas 
                                       'parcela_catastro' => $parcela_catastro,
                                       'subparcela_catastro' => $subparcela_catastro,
                                       'nivel_catastro' => $nivel_catastro,
                                       'unidad_catastro' => $unidad_catastro,

                                       'liquidado' => null, 
                                       'lote_1' => 0,
                                       'lote_2' => 0,
                                       'lote_3' => 0, 
                                       
                                       ]; 
                                                
                        $arrayDatosInactivacion3 = [    
                                                    'inactivo' => 1,
                                            
                                                ]; 

                    
                       
                        //$arrayConditionInactivacion3 = ['id_impuesto'=>$model->id_impuesto];


                        $tableName3 = 'inmuebles';
                        $arrayCondition1 = ['id_impuesto'=>$model->direccion1];
                        $arrayCondition2 = ['id_impuesto'=>$model->direccion2];

                        if ( $conn->guardarRegistro($conexion, $tableName3,  $arrayDatos) and $conn->modificarRegistro($conexion, $tableName3,  $arrayDatosInactivacion3, $arrayCondition1) and $conn->modificarRegistro($conexion, $tableName3,  $arrayDatosInactivacion3, $arrayCondition2) ){

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

