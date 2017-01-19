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
 *  @file InscripcionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-07-2015
 * 
 *  @class InmueblesUrbanosController
 *  @brief Clase que permite controlar el registro o inscripcion de inmuebles urbanos,
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  randKey
 *  InscripcionInmueblesUrbanos
 *
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\inmueble;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
use backend\models\inmueble\InscripcionInmueblesUrbanosForm;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use backend\models\inmueble\AvaluoCatastralForm;
use backend\models\inmueble\HistoricoAvaluoSearch;
use backend\models\inmueble\InmueblesRegistrosForm;
use backend\models\inmueble\InmueblesRegistros;
use backend\models\inmueble\TarifasAvaluos;


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
class RenovacionCertificadoCatastralInmueblesUrbanosController extends Controller
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

          $idConfig = 6;
          $_SESSION['id'] = $idConfig;
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
           
          $datosInmueble = InmueblesConsulta::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->one();

           $_SESSION['datosInmueble'] = $datosInmueble; 

          $datosIRegistros = InmueblesRegistros::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            //->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all();

          $_SESSION['datosIRegistros'] = $datosIRegistros; 
          
          $datosHAvaluos = HistoricoAvaluoSearch::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            //->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all(); 

          $_SESSION['datosHAvaluos'] = $datosHAvaluos; 
          

          if ($datosHAvaluos != null) {
                
                foreach ($datosHAvaluos as $key => $value) {
                                            
                } 
                $añoUltimoAvaluo = explode('-', $value['fecha']);
                $_SESSION['anioAvaluo'] = $añoUltimoAvaluo;
                $_SESSION['datosUAvaluos'] = $value; 
                
          } else {
                $value = false;
                // no presenta historico de avaluos
                // buscaremos fecha en inmuebles registros
                if ($datosIRegistros != null) {

                    foreach ($datosIRegistros as $key => $valueIn) {
                                            
                    } 
                    $añoUltimoRegistro = explode('-', $registros['fecha']);
                    $_SESSION['anioRegistro'] = $añoUltimoRegistro;
                    $_SESSION['datosURegistros'] = $valueIn;
                } else {
                  $valueIn = false; 
                  //no posee registros de inmueble
                  
                } 
          } 
                                         

        return $this->render('view', [
            'modelInmueble' => $datosInmueble, 'modelHAvaluos' => $value,
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
     public function actionRenovacionCertificadoCatastralInmuebles()
     { 
       
         
         if ( isset($_SESSION['idContribuyente'] ) ) {
         //Creamos la instancia con el model de validación
         $model = new InscripcionInmueblesUrbanosForm();
          $modelAvaluo = new AvaluoCatastralForm();
          $modelRegistro = new InmueblesRegistrosForm();

         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         }
         if ($modelAvaluo->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelAvaluo); 
         }
         if ($modelRegistro->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelRegistro); 
         }
   
         if ($model->load(Yii::$app->request->post()) && $modelAvaluo->load(Yii::$app->request->post()) && $modelRegistro->load(Yii::$app->request->post()) ){


              $isValid = $model->validate();
              $isValid = $modelAvaluo->validate() && $isValid;
              $isValid = $modelRegistro->validate() && $isValid;
              if($isValid ){ 
                
//&& $modelAvaluo->validate() && $modelRegistro->validate()
                                         //condicionales     
                                           $documento = new DocumentoSolicitud();
                        
                                           $requisitos = $documento->documentos();
                        
                                         
                                        if (!\Yii::$app->user->isGuest){                                    
                                              
                                             
                                             $guardo = self::GuardarInscripcion($model,$modelAvaluo,$modelRegistro);
                                             
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
              return $this->render('renovacion-certificado-catastral-inmuebles', ['model' => $model, 'modelAvaluo' => $modelAvaluo, 'modelRegistro'=>$modelRegistro]);  

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
     public function GuardarInscripcion($model,$modelAvaluo,$modelRegistro)
     {
            $buscar = new ParametroSolicitud($_SESSION['id']);

            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]);
            $datosContribuyente = self::DatosContribuyente();
            $_SESSION['datosContribuyente']= $datosContribuyente;

            $avaluos=self::actionCalcularAvaluos($modelAvaluo); 

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            try {

                foreach($avaluos as $key => $value){
                
                

                $tableName1 = 'solicitudes_contribuyente'; 

                $arrayDatos1 = [  'id_contribuyente' => $_SESSION['idContribuyente'],
                                  'id_config_solicitud' => $config['id_config_solicitud'],
                                  'impuesto' => 2,
                                  'id_impuesto' => $value['id_impuesto'],
                                  'tipo_solicitud' => $config['tipo_solicitud'],
                                  'usuario' => $datosContribuyente['email'],
                                  'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                                  'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                                  'nro_control' => 0,
                                  'firma_digital' => null,
                                  'estatus' => 0,
                                  'inactivo' => 0,
                              ];  
                

                if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();
                

                $arrayDatos2 = [    'id_impuesto' => $value['id_impuesto'],
                                    'nro_solicitud' => $result,
                                    'fecha' => $value['fecha'],
                                    'mts' => $value['mts'],
                                    'valor_por_mts2' => $value['valor_por_mts2'],
                                    'mts2_terreno' => $value['mts2_terreno'],
                                    'valor_por_mts2_terreno' => $value['valor_por_mts2_terreno'],
                                    'valor' => $value['valor'],
                                    'id_uso_inmueble' => $value['id_uso_inmueble'],
                                    'tipo_inmueble' => $value['tipo_inmueble'],
                                    'clase_inmueble' => $value['clase_inmueble'],
                                    'id_tipologia_zona' => $value['id_tipologia_zona'],
                                    
                                ]; 


            
                 $tableName2 = 'sl_historico_avaluos'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        
                        $tipoError = 0;  
                        $todoBien = true; 

                    } else {
                
                        $avaluoConstruccion = $model->metros_construccion * $model->valor_construccion;
                        $avaluoTerreno = $model->metros_terreno * $model->valor_terreno;

                        $arrayDatos3 = [    'id_impuesto' => $value['id_impuesto'],
                                            'fecha' => $value['fecha'],
                                            'mts' => $value['mts'],
                                            'valor_por_mts2' => $value['valor_por_mts2'],
                                            'mts2_terreno' => $value['mts2_terreno'],
                                            'valor_por_mts2_terreno' => $value['valor_por_mts2_terreno'],
                                            'valor' => $value['valor'],
                                            'id_uso_inmueble' => $value['id_uso_inmueble'],
                                            'tipo_inmueble' => $value['tipo_inmueble'],
                                            'clase_inmueble' => $value['clase_inmueble'],
                                            'id_tipologia_zona' => $value['id_tipologia_zona'],
                                            
                                    
                                        ]; 

            
                        $tableName3 = 'historico_avaluos';
                         


                        if ( $conn->guardarRegistro($conexion, $tableName3,  $arrayDatos3) ){

                             
                              $tipoError = 0; 
                              $todoBien = true; 

                        } else {
            
                              
                              $tipoError = 0; 
                              $todoBien = false;

                        }
                  }


                } else {
            
                     
                    $tipoError = 0; 
                    $todoBien = false; 

                }

            }else{ 
                $tipoError = 0;
                $todoBien = false;
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
     * [DatosContribuyente] metodo que busca los datos del contribuyente en 
     * la tabla contribuyente
     */
     public function actionCalcularAvaluos($model)
     {


        if ($_SESSION['anioAvaluo'][0] != null) {
         
        
            if ($_SESSION['anioAvaluo'][0] <= 2011) {
              $anioImpositivo = '2012';
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, $anioImpositivo );
              //se calcula 2012 y 2017
              $arrayDatos1 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => '2012-01-01',
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $tarifaAvaluos['valor_construccion'],
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $tarifaAvaluos['valor_terreno'],
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 

              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'], $model->id_tipologia_zona, date('Y') );
              $arrayDatos2 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => date('Y-m-d'),
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $tarifaAvaluos['valor_construccion'],
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $tarifaAvaluos['valor_terreno'],
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 

            $arrayDatos = ['avaluo1'=> $arrayDatos1, 'avaluo2'=> $arrayDatos2];
            } elseif($_SESSION['anioAvaluo'][0] >= 2012 and $_SESSION['anioAvaluo'][0] < 2017 ) {
            
              // se calcula 2017 nada mas 
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, date('Y') );
              $arrayDatos = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => date('Y-m-d'),
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 
              
            }
          
       } elseif($_SESSION['anioRegistro']!=null) {

            $anioImpositivo = '2018';
            $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, $anioImpositivo );
            $arrayDatos1 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => '2008-01-01',
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 
              $anioImpositivo = '2012';
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, $anioImpositivo );
              $arrayDatos2 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                   
                                    'fecha' => '2012-01-01',
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, date('Y') );
              $arrayDatos3 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => date('Y-m-d'),
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 


            $arrayDatos = ['avaluo1'=> $arrayDatos1, 'avaluo2'=> $arrayDatos2, 'avaluo3' => $arrayDatos3];

       } elseif($_SESSION['anioRegistro']==null) {

            $anioImpositivo = '2018';
            $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, $anioImpositivo );
            $arrayDatos1 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => '2008-01-01',
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 
              $anioImpositivo = '2012';
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, $anioImpositivo );
              $arrayDatos2 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => '2012-01-01',
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ];
              $tarifaAvaluos = self::TarifaAvaluos($_SESSION['datosInmueble']['manzana_limite'],$model->id_tipologia_zona, date('Y') ); 
              $arrayDatos3 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    
                                    'fecha' => date('Y-m-d'),
                                    'mts' => $model->metros_construccion,
                                    'valor_por_mts2' => $model->valor_construccion,
                                    'mts2_terreno' => $model->metros_terreno,
                                    'valor_por_mts2_terreno' => $model->valor_construccion,
                                    'valor' => $tarifaAvaluos['valor_construccion']*$model->metros_construccion + $tarifaAvaluos['valor_terreno']*$model->metros_terreno,
                                    'id_uso_inmueble' => $model->id_uso_inmueble,
                                    'tipo_inmueble'   => $model->tipo_inmueble, 
                                    'clase_inmueble' => $model->clase_inmueble,
                                    'id_tipologia_zona' => $model->id_tipologia_zona,
                                    
                                ]; 


            $arrayDatos = ['avaluo1'=> $arrayDatos1, 'avaluo2'=> $arrayDatos2, 'avaluo3' => $arrayDatos3];
       }



      return $arrayDatos;                                           

     }

     /**
     * [DatosContribuyente] metodo que busca los datos del contribuyente en 
     * la tabla contribuyente
     */
     public function TarifaAvaluos($manzana_limite,$id_tipologia_zona, $date)
     {

         $buscar = TarifasAvaluos::find()->where("manzana_limite=:manzana_limite", [":manzana_limite" => $manzana_limite])
                                            ->AndWhere("id_tipologia_zona=:id_tipologia_zona", [":id_tipologia_zona" => $id_tipologia_zona])
                                            ->AndWhere("ano_impositivo=:ano_impositivo", [":ano_impositivo" => $date])
                                            ->asArray()->all();

         if($buscar[0] ==true){

            return $buscar[0]; 
         }
         return $buscar = false;                                              

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
     * [DatosConfiguracionTiposSolicitudes description] metodo que busca el tipo de solicitud en 
     * la tabla config_tipos_solicitudes
     */
     public function DatosConfiguracionTiposSolicitudes()
     {

         $buscar = ConfiguracionTiposSolicitudes::find()->where("impuesto=:impuesto", [":impuesto" => 2])
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'REGISTRO NUEVO'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

     } 


    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $documento)
     {
         $email = $_SESSION['datosContribuyente']['email'];

         $solicitud = 'Inscripcion de Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitudInscripcion($email, $solicitud, $nro_solicitud, $documento)){

             return true;
         } else { 

             return false; 
         }


     }

     


}