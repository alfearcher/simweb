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
use common\enviaremail\EnviarEmailSolicitud;
use common\mensaje\MensajeController;
use frontend\models\inmueble\ConfiguracionTiposSolicitudes;
session_start();
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class InscripcionInmueblesUrbanosController extends Controller
{

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
                  
                if (!\Yii::$app->user->isGuest){                                      
                      

                     $guardo = self::GuardarInscripcion($model);

                     if($guardo == true){

                          $salvo = self::BeginSave($guardo = true);

                          if($salvo == true){

                              $envio = self::EnviarCorreo();

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
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
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
           

            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $arrayDatos1 = [  'id_contribuyente' => $model->id_contribuyente,
                              'id_config_solicitud' => 68,
                              'impuesto' => 2,
                              'id_impuesto' => null,
                              'tipo_solicitud' => $tipoSolicitud,
                              'usuario' => yii::$app->user->identity->login,
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => 0,
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
                                ]; 

            
                 $tableName2 = 'sl_inmuebles'; 

                if ( $conn->guardarRegistro($conexion, $tableName2,  $arrayDatos2) ){

                      return true;

                } else {
                      
                      return false;
                }

            }else{ 
                
                return false;
            }   

                       
     }


     public function DatosConfiguracionTiposSolicitudes()
     {

         $buscar = ConfiguracionTiposSolicitudes::find()->where("impuesto=:impuesto", [":impuesto" => 2])
                                                        ->andwhere("descripcion=:descripcion", [":descripcion" => 'REGISTRO NUEVO'])
                                                        ->asArray()->all();


         return $buscar[0]["id_tipo_solicitud"];                                                 

     }

     public function BeginSave($guardo)
     {    
          if($guardo==true) {
            
              $transaccion->commit(); 
              $conexion->close(); 
              $tipoError = 0; 
              return true;

          } else {
            
              $transaccion->roolBack();
              $conexion->close();
              $tipoError = 0; 
              return false;
          }
     }

/**
 * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
 * con el estatus del proceso
 */
     public function EnviarCorreo()
     {
         $email = yii::$app->user->identity->login;

         $solicitud = 'Inscripcion de Inmueble';

         $enviarEmail = new EnviarEmailSolicitud();
        
         if ($enviarEmail->enviarEmail($email, $solicitud)){

             return true;
         } else {

             return false;
         }


     }


}