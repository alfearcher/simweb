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
 *  @file DesintegracionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 08-03-2016
 * 
 *  @class DesintegracionInmueblesUrbanosController
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
 *  View
 *  Index
 *  CambiosOtrosDatosInmueblesUrbanos
 *  GuardarCambios
 *  DatosConfiguracionTiposSolicitudes
 *  EnviarCorreo
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
use backend\models\inmueble\DesintegracionInmueblesForm;
use frontend\models\inmueble\InmueblesSearch;
use frontend\models\inmueble\InmueblesConsulta;
use common\models\contribuyente\ContribuyenteBase;
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
class DesintegracionInmueblesUrbanosController extends Controller
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

         $_SESSION['id'] = 106;

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
     public function actionDesintegracionInmuebles()
     { 
         
         if ( isset( $_SESSION['idContribuyente'] ) ) {
         //Creamos la instancia con el model de validación
         $model = new DesintegracionInmueblesForm();

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
              return $this->render('desintegracion-inmuebles', ['model' => $model, 'datos'=>$datos]);  

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
            $datosContribuyente = self::DatosContribuyente();
            $_SESSION['datosContribuyente']= $datosContribuyente;
            try {
            $tableName1 = 'solicitudes_contribuyente'; 

            $tipoSolicitud = self::DatosConfiguracionTiposSolicitudes();

            $arrayDatos1 = [  'id_contribuyente' => $datos->id_contribuyente,
                              'id_config_solicitud' => $_SESSION['id'],
                              'impuesto' => 2,
                              'id_impuesto' => $datos->id_impuesto,
                              'tipo_solicitud' => $tipoSolicitud,
                              'usuario' => $datosContribuyente['email'],
                              'fecha_hora_creacion' => date('Y-m-d h:i:s'),
                              'nivel_aprobacion' => $nivelAprobacion["nivel_aprobacion"],
                              'nro_control' => 0,
                              'firma_digital' => null,
                              'estatus' => 1,
                              'inactivo' => 0,
                          ];  
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();

            if ( $conn->guardarRegistro($conexion, $tableName1,  $arrayDatos1) ){  
                $result = $conexion->getLastInsertID();

                $arrayCampos2 = ['id_contribuyente','nro_solicitud','ano_inicio','direccion','medidor','observacion',
                                 'tipo_ejido', 'casa_edf_qta_dom', 'piso_nivel_no_dom', 'apto_dom', 'fecha_creacion'];

                $arrayDatos2 = [   [$datos->id_contribuyente, $result, $model->ano_inicio,
                                   $model->direccion, $model->medidor, $model->observacion, $model->tipo_ejido,
                                   $model->casa_edf_qta_dom, $model->piso_nivel_no_dom, $model->apto_dom,
                                   date('Y-m-d h:i:s')],

                                   [$datos->id_contribuyente, $result, $model->ano_inicio,
                                   $model->direccion1, $model->medidor1, $model->observacion1, $model->tipo_ejido1,
                                   $model->casa_edf_qta_dom1, $model->piso_nivel_no_dom1, $model->apto_dom1,
                                   date('Y-m-d h:i:s')],
                                ]; 

                $arrayDatosInactivar2 = [    'id_contribuyente' => $datos->id_contribuyente,
                                    'id_impuesto' => $datos->id_impuesto,
                                    'nro_solicitud' => $result,
                                    'inactivo' => 1,
                                    'fecha_creacion' => date('Y-m-d h:i:s'),
                                ];

            
                 $tableName2 = 'sl_inmuebles'; 

                if ( $conn->guardarLoteRegistros($conexion, $tableName2, $arrayCampos2,  $arrayDatos2) and $conn->guardarRegistro($conexion, $tableName2,  $arrayDatosInactivar2)){

                    if ($nivelAprobacion['nivel_aprobacion'] != 1){

                        $transaccion->commit(); 
                        $conexion->close(); 
                        $tipoError = 0;  
                        return $result; 

                    } else {
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
         $email = $_SESSION['datosContribuyente']['email'];

         $solicitud = 'Actualizacion de Datos del Inmueble';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitud($email, $solicitud, $nro_solicitud, $requisitos)){

             return true; 
         } else { 

             return false; 
         }


     }

}