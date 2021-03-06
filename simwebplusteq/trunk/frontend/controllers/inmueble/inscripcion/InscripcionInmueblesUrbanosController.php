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
 *  @date 29-02-2016
 * 
 *  @class InmueblesUrbanosController
 *  @brief Clase que permite controlar la solicitud del registro o inscripcion de inmuebles urbanos
 *  en el lado del contribuyente,
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
namespace frontend\controllers\inmueble\inscripcion;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
use frontend\models\inmueble\inscripcion\InscripcionInmueblesUrbanosForm;
use frontend\models\inmueble\registroinmueble\InmueblesRegistrosForm;
use frontend\models\inmueble\registroinmueble\InmueblesRegistros;

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
class InscripcionInmueblesUrbanosController extends Controller
{
    public $layout="layout-main";
    public $conn;
    public $conexion;
    public $transaccion;

/* 
tablas: solicitudes_contribuyente, sl_inmuebles, config_tipos_solicitudes

*/

     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionInscripcionInmueblesUrbanos()
     { 
       
         $idConfig = yii::$app->request->get('id');

         $_SESSION['id'] = $idConfig; 

         if ( isset(Yii::$app->user->identity->id_contribuyente) ) {
         //Creamos la instancia con el model de validación
         $model = new InscripcionInmueblesUrbanosForm();
        
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
         
   
         if ($model->load(Yii::$app->request->post()) ){

              
              

              if($model->validate()){ 

                 //condicionales     
                   $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();

                   
                if (!\Yii::$app->user->isGuest){                                    
                      

                     $guardo = self::GuardarInscripcion($model);
                    // $guardoRegistro = self::GuardarRegistroInmueble($modelRegistro);

                     if($guardo == true){ 


                          $envio = self::EnviarCorreo($guardo, $requisitos);
                          //$envioRegistro = self::EnviarCorreo($guardoRegistro, $requisitos);

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
              $buscar = new ParametroSolicitud($_SESSION['id']);
                   $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]); 
                   $rutaAyuda = Yii::$app->ayuda->getRutaAyuda($config['tipo_solicitud'], 'frontend');
              return $this->render('inscripcion-inmuebles-urbanos', ['model' => $model, 'rutaAyuda' => $rutaAyuda, ]);  

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
     public function GuardarInscripcion($model)
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

            //$tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();
     
            if ($nivelAprobacion['nivel_aprobacion'] == 1){
                  $estatus = 1;
                } else {
                  $estatus = 0;
                }
            $arrayDatos1 = [  'id_contribuyente' => $model->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'], //$idConf
                              'impuesto' => 2,
                              'id_impuesto' => null,
                              'tipo_solicitud' => $config['tipo_solicitud'],
                              'usuario' => yii::$app->user->identity->login,
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                              'nro_control' => 0,
                              'firma_digital' => null,
                              'estatus' => $estatus,
                              'inactivo' => 0,
                          ];  
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                
                $result = $conexion->getLastInsertID();


                    //$id_impuesto = $model->id_impuesto;                   //clave principal de la tabla no sale en el formulario identificador del inpuesto inmobiliario
                $id_contribuyente = $model->id_contribuyente;         //identidad del contribuyente
                $ano_inicio = $model->ano_inicio;                     //anio de inicio
                $direccion = $model->direccion;                       //direccion
                //$av_calle_esq_dom = $model->av_calle_esq_dom;         //avenida. calle. esquina. domicilio
                $casa_edf_qta_dom = $model->casa_edf_qta_dom;         //casa. edificio. quinta. domicilio
                $piso_nivel_no_dom = $model->piso_nivel_no_dom;       //piso. nivel. numero. domicilio
                $apto_dom = $model->apto_dom;                         //apartamento. domicilio
                $medidor = $model->medidor;                           //medidor
                $observacion = $model->observacion;                   //observaciones
                $tipo_ejido = $model->tipo_ejido;                     //tipo ejido

                $arrayDatos2 = [    'id_contribuyente' => $id_contribuyente,
                                    'nro_solicitud' => $result,
                                    'ano_inicio' => $ano_inicio,
                                    'direccion' => $direccion,
                                    'medidor' => $medidor,
                                    'observacion' => $observacion,
                                    'tipo_ejido' => $tipo_ejido,
                                  //'av_calle_esq_dom' => $av_calle_esq_dom,
                                    'casa_edf_qta_dom' => $casa_edf_qta_dom,
                                    'piso_nivel_no_dom' => $piso_nivel_no_dom,
                                    'apto_dom' => $apto_dom, 
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ]; 

            
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

                        $arrayDatos3 = [    'id_contribuyente' => $id_contribuyente,
                                            'ano_inicio' => $ano_inicio,
                                            'direccion' => $direccion,
                                            'medidor' => $medidor,
                                            'observacion' => $observacion,
                                            'tipo_ejido' => $tipo_ejido,
                                          //'av_calle_esq_dom' => $av_calle_esq_dom,
                                            'casa_edf_qta_dom' => $casa_edf_qta_dom,
                                            'piso_nivel_no_dom' => $piso_nivel_no_dom,
                                            'apto_dom' => $apto_dom,
                                       ]; 

                        $tableName3 = 'inmuebles';

                        if ( $conn->guardarRegistro($conexion, $tableName3,  $arrayDatos3) ){

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
                
                 return false;
             }   
            
          } catch ( Exception $e ) {
              //echo $e->errorInfo[2];
          } 
                       
     }


     /**
      * [GuardarInscripcion description] Metodo que se encarga de guardar los datos de la solicitud 
      * de inscripcion del inmueble del contribuyente
      * @param [type] $model [description] arreglo de datos del formulario de inscripcion del
      * inmueble
      */
     public function GuardarRegistroInmueble($model,$modelRegistro)
     {
            $buscar = new ParametroSolicitud(118);

            $nivelAprobacion = $buscar->getParametroSolicitud(["nivel_aprobacion"]);
            $config = $buscar->getParametroSolicitud([
                                'id_config_solicitud',
                                'tipo_solicitud',
                                'impuesto',
                                'nivel_aprobacion'
                          ]);
            $datosContribuyente = self::DatosContribuyente();
            $_SESSION['datosContribuyente']= $datosContribuyente;

            if($_SESSION['datosContribuyente']['email'] == null){
                        
               return MensajeController::actionMensaje(924);
                        
            }
            

          

            //$avaluos=self::actionCalcularAvaluos($modelAvaluo); 

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            try {

               
                
                

                $tableName1 = 'solicitudes_contribuyente'; 

                $arrayDatos1 = [  'id_contribuyente' => $_SESSION['idContribuyente'],
                                  'id_config_solicitud' => $config['id_config_solicitud'],
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
                

                if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();
              

                $arrayDatos2 = [    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    'nro_solicitud' => $result,
                                    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'fecha' => $modelRegistro->fecha,
                                    'id_tipo_documento_inmueble' => $modelRegistro->documento_propiedad,
                                    'num_reg' => $modelRegistro->num_reg,
                                    'reg_mercantil' => $modelRegistro->reg_mercantil,
                                    'valor_documental'=> $modelRegistro->valor_documental,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),

                                    'tomo' => $modelRegistro->tomo,
                                    'protocolo' => $modelRegistro->protocolo,                                    
                                    'folio' => $modelRegistro->folio,

                                    'nro_matricula' => $modelRegistro->nro_matriculado,
                                    'asiento_registral' => $modelRegistro->asiento_registral,
                               
                                ]; 

                 $model->nro_solicitud = $arrayDatos2['nro_solicitud'];
                 $resultProceso = self::actionEjecutaProcesoSolicitud($conn, $conexion, $model, $config); 
                 $tableName2 = 'sl_inmuebles_registros'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        
                        $tipoError = 0;  
                        $todoBien = true; 

                    } else {
                
                        $avaluoConstruccion = $model->metros_construccion * $model->valor_construccion;
                        $avaluoTerreno = $model->metros_terreno * $model->valor_terreno;

                        $arrayDatos3 = [    
                                    'id_impuesto' => $_SESSION['datosInmueble']['id_impuesto'],
                                    'id_contribuyente' => $_SESSION['idContribuyente'],
                                    'fecha' => $modelRegistro->fecha,
                                    'id_tipo_documento_inmueble' => $modelRegistro->documento_propiedad,
                                    'num_reg' => $modelRegistro->num_reg,
                                    'reg_mercantil' => $modelRegistro->reg_mercantil,
                                    'valor_documental'=> $modelRegistro->valor_documental,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),

                                    'tomo' => $modelRegistro->tomo,
                                    'protocolo' => $modelRegistro->protocolo,                                    
                                    'folio' => $modelRegistro->folio,

                                    'nro_matricula' => $modelRegistro->nro_matriculado,
                                    'asiento_registral' => $modelRegistro->asiento_registral,
                               
                                            
                                    
                                        ]; 

            
                        $tableName3 = 'inmuebles_registros';
                         


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
         $datosContribuyente = self::DatosContribuyente();
        
         $email = $datosContribuyente['email'];

         $solicitud = 'Inscripcion de Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitudInscripcion($email, $solicitud, $nro_solicitud, $documento)){

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