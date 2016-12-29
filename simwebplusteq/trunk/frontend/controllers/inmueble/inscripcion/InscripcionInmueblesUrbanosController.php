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
             

            try {
            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();
     
            
            $arrayDatos1 = [  'id_contribuyente' => $model->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'], //$idConf
                              'impuesto' => 2,
                              'id_impuesto' => null,
                              'tipo_solicitud' => $tipoSolicitud,
                              'usuario' => yii::$app->user->identity->login,
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                              'nro_control' => 0,
                              'firma_digital' => null,
                              'estatus' => 0,
                              'inactivo' => 0,
                          ];  
            
die(var_dump($arrayDatos1));
            $conn = New ConexionController();
            $conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
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
         $email = yii::$app->user->identity->login;

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