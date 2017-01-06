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


     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionInscripcionInmueblesUrbanos2()
     {
          $_SESSION['id'] = 68;
         if ( isset( $_SESSION['idContribuyente'] ) ) {
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
   
         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){

                 //condicionales     
                  
                if (!\Yii::$app->user->isGuest){                                      
            

                     $id_impuesto = $model->id_impuesto;                   //clave principal de la tabla no sale en el formulario identificador del inpuesto inmobiliario
                     $id_contribuyente = $model->id_contribuyente;         //identidad del contribuyente
                     $ano_inicio = $model->ano_inicio;                     //anio de inicio
                     $direccion = $model->direccion;                       //direccion
                     $manzana_limite = $model->manzana_limite;             //etiqueta oculta que se genera con el formulario de catastro
                     


                     $liquidado = $model->liquidado; 
                     $id_habitante = $model->id_habitante;
                     $lote_1 = 0;
                     $lote_2 = 0;
                     $nivel = $model->nivel;                               //nivel
                     $lote_3 = 0;

                     $av_calle_esq_dom = $model->av_calle_esq_dom;         //avenida. calle. esquina. domicilio
                     $casa_edf_qta_dom = $model->casa_edf_qta_dom;         //casa. edificio. quinta. domicilio
                     $piso_nivel_no_dom = $model->piso_nivel_no_dom;       //piso. nivel. numero. domicilio
                     $apto_dom = $model->apto_dom;                         //apartamento. domicilio

                     $tlf_hab = $model->tlf_hab;                           //telefono habitacion
                     $medidor = $model->medidor;                           //medidor
                     $id_sim = $model->id_sim;                             //id_sim
                     $observacion = $model->observacion;                   //observaciones
                     $inactivo = $model->inactivo;                         //inactivo
                     $catastro1 = $model->catastro;                         //catastro generado por el formulario de catastro
                     $id_habitante = $model->id_habitante;                 //identificacion del abitante
                     $tipo_ejido = $model->tipo_ejido;                     //tipo ejido
                     $propiedad_horizontal = $model->propiedad_horizontal; //propiedad horizontal
                     

                     $estado_catastro = $model->estado_catastro;           //Estado catastro
                     $municipio_catastro = $model->municipio_catastro;     //Municipio catastro
                     $parroquia_catastro = $model->parroquia_catastro;     //Parroquia catastro
                     $ambito_catastro = $model->ambito_catastro;           //Ambito catastro
                     $sector_catastro = $model->sector_catastro;           //Sector catastro
                     $manzana_catastro = $model->manzana_catastro;         //Manzana catastro

                     $catastro1 = array(['estado' => $estado_catastro, 'municipio'=> $municipio_catastro, 'parroquia'=>$parroquia_catastro, 'ambito'=>$ambito_catastro, 'sector'=>$sector_catastro, 'manzana' =>$manzana_catastro]);
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

                   //--------------TRY---------------
                        $arrayDatos = ['id_contribuyente' => $id_contribuyente,
                                       'ano_inicio' => $ano_inicio,
                                       'direccion' => $direccion,
                                       'manzana_limite' => $manzana_limite,
                                       'nivel' => $nivel,
                                       //direcciones
                                       'av_calle_esq_dom' => $av_calle_esq_dom,
                                       'casa_edf_qta_dom' => $casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $piso_nivel_no_dom,
                                       'apto_dom' => $apto_dom,
                                       //otros datos
                                       'tlf_hab' => $tlf_hab,
                                       'medidor' => $medidor,
                                       'id_sim' => $id_sim,
                                       'observacion' => $observacion,
                                       'inactivo' => $inactivo,
                                       'catastro' => $catastro,
                                       'id_habitante' => $id_habitante,
                                       'tipo_ejido' => $tipo_ejido,
                                       'propiedad_horizontal' => $propiedad_horizontal,
                                       //catastro inmueble
                                       'estado_catastro' => $estado_catastro,
                                       'municipio_catastro' => $municipio_catastro,
                                       'parroquia_catastro' => $parroquia_catastro,
                                       'ambito_catastro' => $ambito_catastro,
                                       'sector_catastro' => $sector_catastro,
                                       'manzana_catastro' => $manzana_catastro,
                                       //parcelas 
                                       'parcela_catastro' => $parcela_catastro,
                                       'subparcela_catastro' => $subparcela_catastro,
                                       'nivel_catastro' => $nivel_catastro,
                                       'unidad_catastro' => $unidad_catastro,

                                       'liquidado' => $liquidado, 
                                       'lote_1' => $lote_1,
                                       'lote_2' => $lote_2,
                                       'lote_3' => $lote_3, 
                                       'nivel' => $nivel,
                                       ]; 

                        $tableName = 'inmuebles'; 


                        $conn = New ConexionController();

                        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                        $this->conexion->open();  

                        $transaccion = $this->conexion->beginTransaction();

                        if ( $conn->guardarRegistro($this->conexion, $tableName,  $arrayDatos) ){  

                            $transaccion->commit();  
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'SUCCESSFUL REGISTRATION OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
            
                        }else{ 

                            $transaccion->rollBack();
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                        $this->conexion->close(); 


                   }else{  

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{
                
                   $model->getErrors(); 
              }
         }
              return $this->render('inscripcion-inmuebles-urbanos', ['model' => $model, ]);  

        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles




     public function actionInscripcionInmueblesUrbanos()
     { 
       
         

         $_SESSION['id'] = 68;
         if ( isset($_SESSION['idContribuyente'] ) ) {
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
   
         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){ 

                 //condicionales     
                   $documento = new DocumentoSolicitud();

                   $requisitos = $documento->documentos();

                 
                if (!\Yii::$app->user->isGuest){                                    
                      

                     $guardo = self::GuardarInscripcion($model);

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
              return $this->render('inscripcion-inmuebles-urbanos', ['model' => $model, ]);  

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
            $datosContribuyente = self::DatosContribuyente();
            $_SESSION['datosContribuyente']= $datosContribuyente;

            try {
            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();
     
            
            $arrayDatos1 = [  'id_contribuyente' => $model->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'], //$idConf
                              'impuesto' => 2,
                              'id_impuesto' => null,
                              'tipo_solicitud' => $tipoSolicitud,
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

                $catastro1 = array(['estado' => $model->estado_catastro, 'municipio'=> $model->municipio_catastro, 'parroquia'=>$model->parroquia_catastro, 'ambito'=>$model->ambito_catastro, 'sector'=>$model->sector_catastro, 'manzana' =>$model->manzana_catastro]);
                     $catastro = "".$catastro1[0]['estado']."-".$catastro1[0]['municipio']."-".$catastro1[0]['parroquia']."-".$catastro1[0]['ambito']."-".$catastro1[0]['sector']."-".$catastro1[0]['manzana']."";
                     
                     
                     if ($model->propiedad_horizontal == 0) {

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
                

                $arrayDatos2 = [       'nro_solicitud' => $result,
                                       'id_contribuyente' => $_SESSION['idContribuyente'],
                                       'ano_inicio' => $model->ano_inicio,
                                       'direccion' => $model->direccion,
                                       'manzana_limite' => $model->manzana_limite,
                                       'nivel' => $model->nivel,
                                       //direcciones
                                       'av_calle_esq_dom' => $model->av_calle_esq_dom,
                                       'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                       'apto_dom' => $model->apto_dom,
                                       //otros datos
                                       'tlf_hab' => $model->tlf_hab,
                                       'medidor' => $model->medidor,
                                       'id_sim' => 0,
                                       'observacion' => $model->observacion,
                                       'inactivo' => $model->inactivo,
                                       'catastro' => $catastro,
                                       'id_habitante' => $model->id_habitante,
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
                                       'parcela_catastro' => $model->parcela_catastro,
                                       'subparcela_catastro' => $subparcela_catastro,
                                       'nivel_catastro' => $nivel_catastro,
                                       'unidad_catastro' => $unidad_catastro,

                                       'liquidado' => $model->liquidado, 
                                       'lote_1' => 0,
                                       'lote_2' => 0,
                                       'lote_3' => 0, 
                                       'nivel' => $model->nivel,
                                    
                                ]; 

            
                 $tableName2 = 'sl_inmuebles'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){


                        $transaccion->commit(); 
                        $conexion->close(); 
                        $tipoError = 0; 
                        return $result; 

                    } else { 

                        $arrayDatos3 = [    'id_contribuyente' => $_SESSION['idContribuyente'],
                                       'ano_inicio' => $model->ano_inicio,
                                       'direccion' => $model->direccion,
                                       'manzana_limite' => $model->manzana_limite,
                                       'nivel' => $model->nivel,
                                       //direcciones
                                       'av_calle_esq_dom' => $model->av_calle_esq_dom,
                                       'casa_edf_qta_dom' => $model->casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $model->piso_nivel_no_dom,
                                       'apto_dom' => $model->apto_dom,
                                       //otros datos
                                       'tlf_hab' => $model->tlf_hab,
                                       'medidor' => $model->medidor,
                                       'id_sim' => 0,
                                       'observacion' => $model->observacion,
                                       'inactivo' => $model->inactivo,
                                       'catastro' => $catastro,
                                       'id_habitante' => $model->id_habitante,
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
                                       'parcela_catastro' => $model->parcela_catastro,
                                       'subparcela_catastro' => $subparcela_catastro,
                                       'nivel_catastro' => $nivel_catastro,
                                       'unidad_catastro' => $unidad_catastro,

                                       'liquidado' => $model->liquidado, 
                                       'lote_1' => 0,
                                       'lote_2' => 0,
                                       'lote_3' => 0, 
                                       'nivel' => $model->nivel,
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
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'REGISTRO NUEVO DE INMUEBLES URBANOS'])
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