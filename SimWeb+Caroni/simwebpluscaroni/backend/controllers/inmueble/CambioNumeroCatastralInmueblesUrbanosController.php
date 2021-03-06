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
 *  @file CambioNumeroCatastralInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class CambioNumeroCatastralInmueblesUrbanosController
 *  @brief Clase que permite controlar el cambio de otros datos del inmueble urbano, 
 *  el cambio ha propiedad horizontal
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  CambioNumeroCatastralInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\inmueble;

use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\CambioNumeroCatastralInmueblesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use backend\models\inmueble\AvaluoCatastralForm;
use backend\models\inmueble\InmueblesRegistrosForm;
use backend\models\inmueble\InmueblesRegistros;
use backend\models\inmueble\HistoricoAvaluoSearch;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;
use common\models\contribuyente\ContribuyenteBase;
use common\enviaremail\PlantillaEmail;
use common\mensaje\MensajeController;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;
use common\models\configuracion\solicitud\ParametroSolicitud;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\SolicitudProcesoEvento;

use backend\models\inmueble\Estados;
use backend\models\inmueble\Municipios;
use backend\models\inmueble\Parroquias;
session_start();
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for Inmuebles model.
 */
class CambioNumeroCatastralInmueblesUrbanosController extends Controller
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

          $idConfig = 7;
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
           
          $datos = InmueblesConsulta::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->one();
          $_SESSION['datos'] = $datos;


          $datosInmueble = InmueblesConsulta::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->one();

           $_SESSION['datosInmueble'] = $datosInmueble; 

          $datosIRegistros = InmueblesRegistros::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            //->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all();

          $_SESSION['datosIRegistros'] = $datosIRegistros; 
          
          $datosHAvaluos = HistoricoAvaluoSearch::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])->asArray()
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all(); 

          $_SESSION['datosHAvaluos'] = $datosHAvaluos; 

          
          
          if ($datosHAvaluos != null) {
                
                foreach ($datosHAvaluos as $key => $value) {
                                            
                } 
                $añoUltimoAvaluo = explode('-', $value['fecha']);
                $_SESSION['anioAvaluo'] = $añoUltimoAvaluo;
                $_SESSION['datosUAvaluos'] = $value; 

                if ($datosIRegistros != null) {

                    foreach ($datosIRegistros as $key => $valueIn) {
                                            
                    } 
                    
                    $añoUltimoRegistro = explode('-', $valueIn['fecha']);
                    $_SESSION['anioRegistro'] = $añoUltimoRegistro;
                    $_SESSION['datosURegistros'] = $valueIn;
                } else {
                 
                  $valueIn = new InmueblesRegistrosForm(); 
                  $_SESSION['datosURegistros'] = $valueIn; 
                  $_SESSION['anioRegistro'] = null; 
                  
                  //no posee registros de inmueble
                  
                } 
                
          } else {
                $value = new AvaluoCatastralForm();
                $_SESSION['datosUAvaluos'] = $value; 
                // no presenta historico de avaluos
                // buscaremos fecha en inmuebles registros
                if ($datosIRegistros != null) {

                    foreach ($datosIRegistros as $key => $valueIn) {
                                            
                    } 
                    $añoUltimoRegistro = explode('-', $registros['fecha']);
                    $_SESSION['anioRegistro'] = $añoUltimoRegistro;
                    $_SESSION['datosURegistros'] = $valueIn;
                } else {
                  $valueIn = new InmueblesRegistrosForm(); 
                  $_SESSION['datosURegistros'] = $valueIn; 
                  $_SESSION['anioRegistro'] = null; 
                  //no posee registros de inmueble
                  
                } 
          } 

      


           $verificarSolicitud = self::verificarSolicitud($datosInmueble['id_impuesto'] , $_SESSION['id']);
           

           if($verificarSolicitud == true){
                
                return MensajeController::actionMensaje(923);                
                          
            }



        return $this->render('view', [
            'modelInmueble' => $datosInmueble, 'modelHAvaluos' => $value,
        ]);
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    }
    
    /**
     *Metodo: CambioDeNumeroCatastralInmuebles
     *Actualiza los datos del numero catastral del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/
     public function actionCambioDeNumeroCatastralInmuebles()
     { 
         
         if ( isset($_SESSION['idContribuyente']) ) {
         //Creamos la instancia con el model de validación
         $model = new CambioNumeroCatastralInmueblesForm();
         $modelAvaluo = new AvaluoCatastralForm();
          $modelRegistro = new InmueblesRegistrosForm();

         $datos = $_SESSION['datos'];
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
         $todoBien = true;
    
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


              if($isValid){ 

                 //condicionales     
                  $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();



                if (!\Yii::$app->user->isGuest){                                      
                      
                   
                     
                     // foreach($datos as $key => $value) {
                     
                     //     $value; 
                     //      die($value['id_vehiculo']);
                     //      $verificarSolicitud = self::verificarSolicitud($value , $_SESSION['id']);
              
                     //      if($verificarSolicitud == true){
                     //          //die(var_dump($value['id_vehiculo']));
                     //          $todoBien = false;
                          
                     //       } 
                     // }

                    //if($todoBien == true){
                     $guardo = self::GuardarCambios($model, $modelAvaluo, $modelRegistro);

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
                     // } else {

                     //         return MensajeController::actionMensaje(900);
                     //  } 

                   }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{ 
                
                   $model->getErrors(); 
              }
         }    
              $buscar = new ParametroSolicitud($_SESSION['id']);
                   $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]); 
                   $rutaAyuda = Yii::$app->ayuda->getRutaAyuda($config['tipo_solicitud'], 'backend');
              return $this->render('cambio-de-numero-catastral-inmuebles', ['model' => $model, 'datos'=>$datos, 'modelAvaluo' => $modelAvaluo, 'modelRegistro'=>$modelRegistro, 'rutaAyuda' => $rutaAyuda,]);  

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
     public function GuardarCambios($model, $modelAvaluo, $modelRegistro)
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
            try {
            $tableName1 = 'solicitudes_contribuyente'; 

               

            $arrayDatos1 = [  'id_contribuyente' => $_SESSION['idContribuyente'],
                              'id_config_solicitud' => $_SESSION['id'],
                              'impuesto' => 2,
                              'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                              'tipo_solicitud' => $config['tipo_solicitud'],
                              'usuario' => $datosContribuyente['email'],
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
                
                $estado_catastro = $model->estado_catastro; 
                $municipio_catastro = $model->municipio_catastro; 
                $parroquia_catastro = $model->parroquia_catastro; 
                $ambito_catastro = $model->ambito_catastro; 
                $sector_catastro = $model->sector_catastro; 
                $manzana_catastro = $model->manzana_catastro; 
                $catastro1 = array(['estado' => $estado_catastro, 'municipio'=> $municipio_catastro, 'parroquia'=>$parroquia_catastro, 'ambito'=>$ambito_catastro, 'sector'=>$sector_catastro, 'manzana' =>$manzana_catastro]);
                $catastro = "".$catastro1[0]['estado']."-".$catastro1[0]['municipio']."-".$catastro1[0]['parroquia']."-".$catastro1[0]['ambito']."-".$catastro1[0]['sector']."-".$catastro1[0]['manzana']."";

                if($model->propiedad_horizontal == 1){
                $arrayDatos2 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    'nro_solicitud' => $result,
                                    'estado_catastro' => $model->estado_catastro,
                                    'municipio_catastro' => $model->municipio_catastro,
                                    'parroquia_catastro' => $model->parroquia_catastro,
                                    'ambito_catastro' => $model->ambito_catastro,
                                    'sector_catastro' => $model->sector_catastro,
                                    'manzana_catastro' => $model->manzana_catastro,
                                       //catastro
                                    'propiedad_horizontal' => $model->propiedad_horizontal,
                                    'parcela_catastro' => $model->parcela_catastro,
                                    'subparcela_catastro' => $model->subparcela_catastro,
                                    'nivel_catastro' => $model->nivel_catastro,
                                    'unidad_catastro' => $model->unidad_catastro,
                                    'manzana_limite' => $model->manzana_catastro,
                                    'catastro' => $catastro,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ]; 

                } else {
                $arrayDatos2 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    'nro_solicitud' => $result,
                                    'estado_catastro' => $model->estado_catastro,
                                    'municipio_catastro' => $model->municipio_catastro,
                                    'parroquia_catastro' => $model->parroquia_catastro,
                                    'ambito_catastro' => $model->ambito_catastro,
                                    'sector_catastro' => $model->sector_catastro,
                                    'manzana_catastro' => $model->manzana_catastro,
                                       //catastro
                                    'propiedad_horizontal' => $model->propiedad_horizontal,
                                    'parcela_catastro' => $model->parcela_catastro,
                                    'subparcela_catastro' => 0,
                                    'nivel_catastro' => 0,
                                    'unidad_catastro' => 0,
                                    'manzana_limite' => $model->manzana_catastro,
                                    'catastro' => $catastro,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ]; 

                }
                 $tableName2 = 'sl_inmuebles'; 

                 $model->nro_solicitud = $arrayDatos2['nro_solicitud'];
                 $resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $config); 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        $transaccion->commit(); 
                        $conexion->close(); 
                        $tipoError = 0;  
                        return $result; 

                    } else {
                        if($model->propiedad_horizontal == 1){
                        $arrayDatos3 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                            'estado_catastro' => $model->estado_catastro,
                                            'municipio_catastro' => $model->municipio_catastro,
                                            'parroquia_catastro' => $model->parroquia_catastro,
                                            'ambito_catastro' => $model->ambito_catastro,
                                            'sector_catastro' => $model->sector_catastro,
                                            'manzana_catastro' => $model->manzana_catastro,
                                            'propiedad_horizontal' => $model->propiedad_horizontal,
                                            'parcela_catastro' => $model->parcela_catastro,
                                            'subparcela_catastro' => $model->subparcela_catastro,
                                            'nivel_catastro' => $model->nivel_catastro,
                                            'unidad_catastro' => $model->unidad_catastro,
                                            'manzana_limite' => $model->manzana_catastro,
                                               //catastro
                                            'catastro' => $catastro,
                                    
                                        ]; 
                        } else {
                          $arrayDatos3 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                            'estado_catastro' => $model->estado_catastro,
                                            'municipio_catastro' => $model->municipio_catastro,
                                            'parroquia_catastro' => $model->parroquia_catastro,
                                            'ambito_catastro' => $model->ambito_catastro,
                                            'sector_catastro' => $model->sector_catastro,
                                            'manzana_catastro' => $model->manzana_catastro,
                                            'propiedad_horizontal' => $model->propiedad_horizontal,
                                            'parcela_catastro' => $model->parcela_catastro,
                                            'subparcela_catastro' => 0,
                                            'nivel_catastro' => 0,
                                            'unidad_catastro' => 0,
                                            'manzana_limite' => $model->manzana_catastro,
                                               //catastro
                                            'catastro' => $catastro,
                                        ];
                        }
            
                        $tableName3 = 'inmuebles';
                        $arrayCondition = ['id_impuesto'=>$_SESSION['datosInmueble']['id_impuesto']];

                        if ( $conn->modificarRegistro($conexion, $tableName3,  $arrayDatos3, $arrayCondition) ){

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
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'CAMBIO DE NUMERO CATASTRAL'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                              

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
                                          'estatus' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
             return true;
            }else{
             return false;
            }
    }

    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $requisitos)
     {
         $email = $_SESSION['datosContribuyente']['email'];

         $solicitud = 'Cambio de Número Catastral';

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
        if (($model = CambioNumeroCatastralInmueblesForm::findOne($id)) !== null) {
            return $model; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
         * Metodo que permite renderizar un combo de lista de municipios
         * para el numero catastral.
         * @param  Integer $i identificador del estado.
         * @return Renderiza una vista con un combo de municipios.
         */
        public function actionListMunicipio($i)
        {
            

                $ListMunicipios = Municipios::find()->where('estado =:estado', [':estado' => $i])
                                                    //->andwhere('inactivo =:inactivo', [':inactivo' => 0])
                                                    ->all();
           

            if ( $ListMunicipios > 0 ) {
                echo "<option value='0'>" . "Select..." . "</option>";
                foreach ( $ListMunicipios as $solicitud ) {
                    echo "<option value='" . $solicitud->municipio . "'>" . $solicitud->nombre . "</option>";
                }
            } else {
                echo "<option> - </option>";
            }
        }

    public function actionSelectmunicipio()
        {
                 $id_uno= $_POST['estados']['estado']; 
             $lista = Municipios::findAll('idestado = :id_uno',array(':id_uno' => $id_uno));
             $lista = ArrayHelper::map($lista, 'municipio', 'nombre');

                        echo CHtml::tag('option',array('value' => ''),'Seleccione un Municipio...',true);
                    foreach($lista as $valor => $municipio)
            {
                echo CHtml::tag('option',array('value' => $valor),CHtml::encode($municipio), true);
            }

        }



    public function actionSelectMunicipio2() {
        $id = (int) $_POST ['estados']['nombre'];
         $lista = ArrayHelper::map(Municipios::model()->findAll('estado =:id', [':id'=>$id]), 'municipio', 'nombre');

         echo ArrayHelper::map('option', ['value'=>''], '-- Seleccione Municipio --', true);

        foreach ($lista as $valor=>$municipio) {
            echo Html::endTag('option', ['value'=>$valor], Html::encode($municipio), true);
        }
    }

    public function actionSelectParroquia() {
        $id = (int) $_POST ['TblEstructura']['idmunicipio'];
        $lista = CHtml::listData(Parroquia::model()->findAll('idmunicipio =:id', [':id'=>$id]), 'id', 'parroquia');

        echo CHtml::tag('option', array('value'=>''), '-- Seleccione Parroquia --', true);

        foreach ($lista as $valor => $parroquia) {
            echo CHtml::tag('option', array('value'=>$valor), CHtml::encode($parroquia), true);
        }
    }
}
