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
 *  @file RegistrarPerfilUsuarioGrupoController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-08-2017
 * 
 *  @class RegistrarPerfilUsuarioGrupoController
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
namespace backend\controllers\usuario;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;

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

use backend\models\usuario\PerfilUsuarioForm;
use backend\models\usuario\PerfilUsuario;
use backend\models\usuario\AutorizacionUsuario;
use backend\models\usuario\RutaAccesoMenu;
use backend\models\usuario\RutaAccesoMenuForm;
use backend\models\usuario\GrupoPerfilUsuarioForm;
use backend\models\usuario\GrupoPerfilUsuario;
session_start();
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class RegistrarPerfilUsuarioGrupoController extends Controller
{
    public $layout="layout-main";
    public $conn;
    public $conexion;
    public $transaccion;

//usuario/registrar-perfil-usuario/registrar-perfil
     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionRegistrarPerfil()
     { 
       
         

         // Se determina si el usuario esta autorixado a utilizar el modulo.
         $autorizado = New  AutorizacionUsuario();   
         $autorizado = $autorizado->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']);

         if ( $autorizado ) {

         //Creamos la instancia con el model de validación
         $model = new PerfilUsuarioForm();
         $modelRuta = new RutaAccesoMenuForm();
         $modelGrupo = new GrupoPerfilUsuarioForm();
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
                   
               

                $rutas = self::actionBuscarRuta(Yii::$app->request->post());

                 
                if (!\Yii::$app->user->isGuest){                                    
                      

                     $guardo = self::GuardarInscripcion(Yii::$app->request->post(), $rutas);

                     if($guardo == true){ 

                            return MensajeController::actionMensaje(100);
                     
                      } else {

                            return MensajeController::actionMensaje(920);
                      } 

                   }else{ 

                        $msg = Yii::t('backend', 'A OCURRIDO UN ERROR AL EJECUTAR LA OPERACION');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{ 
                
                   $model->getErrors(); 
              }
         }
              // $modelParametros = Users::find()->where(['activate'=>1])->asArray()->all(); 

               $modelParametros = $modelGrupo->getListaGrupoAcceso(); //descripcion
               $dataProviderFuncionario = $model->getListaFuncionarios();                                
               //$listaParametros = ArrayHelper::map($modelParametros,'ruta','menu'); 
              return $this->render('/usuario/registrar-perfil-grupo', ['model' => $model, 'rutas' => $modelParametros,'searchModel' => $modelRuta, 'funcionarios'=>$dataProviderFuncionario]);  

        }  else {
                    $this->redirect(['error-operacion', 'cod' => 700]);
        }    
 
     } // cierre del metodo inscripcion de inmuebles

    

     /**
      * [GuardarInscripcion description] Metodo que se encarga de guardar los datos de la solicitud 
      * de inscripcion del inmueble del contribuyente
      * @param [type] $model [description] arreglo de datos del formulario de inscripcion del
      * inmueble
      */
     public function GuardarInscripcion($model, $rutas)
     {
       

            try {
            $tableName1 = 'perfil_usuario';

             
            // $arrayDatos1 = [  'username' => $model->username,
            //                   'ruta' => $model->ruta, 
            //                   'inactivo' => 0,
            //               ];  
            

            $conn = New ConexionController();
            $conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $conexion->open();  
            $transaccion = $conexion->beginTransaction();


             foreach ( $model["chk-funcionario"] as $funcionario ) { 
               
               $buscarUsers = users::find()->where(['id_funcionario'=>$funcionario])
                                           ->andWhere(['activate'=>1])->all();

               $arregloDatos['username'] = $buscarUsers[0]['username'];
            foreach ( $rutas as $ruta ) {
                $arregloDatos['ruta'] = $ruta[0]['ruta'];

                 $arrayDatos1 = [  'username' => $arregloDatos['username'],
                               'ruta' => $arregloDatos['ruta'], 
                               'inactivo' => 0,
                               'usuario' =>Yii::$app->user->identity->username,
                               'fecha_hora'=>date('Y-m-d H:i:s'),
                               'operacion' => 'ASIGNACION',
                          ]; 

                // Se inactiva cualquier solicitud que tenga el funcionario y que coincida con la que se guardara.
                $result = self::actionInactivarFuncionarioRuta($arregloDatos['username'], $arregloDatos['ruta'], $tableName1, $conn, $conexion);
                if ( $result ) {
                    $result = $conn->guardarRegistro($conexion, $tableName1, $arrayDatos1);
                if ( !$result ) { break; }
                } else {
                    break;
                }
            } 
          } //cierre for each
            if ( $result ){  
                
               

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


             
            
          } catch ( Exception $e ) {
              //echo $e->errorInfo[2];
          } 
                       
     }  



    /**
     * [EnviarCorreo description] Metodo que se encarga de enviar un email al contribuyente 
     * con el estatus del proceso
     */
     public function EnviarCorreo($guardo, $documento)
     {
         $email = $_SESSION['datosContribuyente']['email'];

         $solicitud = 'Registrar Perfil de Usuario';

         $nro_solicitud = $guardo;

         $enviarEmail = new PlantillaEmail();
        
         if ($enviarEmail->plantillaEmailSolicitudInscripcion($email, $solicitud, $nro_solicitud, $documento)){

             return true;
         } else { 

             return false; 
         }


     } 

     /**
     * [actionInactivarFuncionarioSolicitud description]
     * @param  [type] $idFuncionario [description]
     * @param  [type] $tipoSolicitud [description]
     * @param  [type] $tabla         [description]
     * @param  [type] $conexionLocal [description]
     * @param  [type] $connLocal     [description]
     * @return [type]                [description]
     */
    public function actionInactivarFuncionarioRuta($Funcionario, $ruta, $tabla, $conexionLocal, $conexion)
    {

     
      $result = false;
      $arregloCondicion = [
          'username' => $Funcionario,
          'ruta'=>$ruta,
          'inactivo' => 0,
      ]; 
      $arregloDatos = ['inactivo' => 1]; 
      $result = $conexionLocal->modificarRegistro($conexion, $tabla, $arregloDatos, $arregloCondicion);

      return $result;
    } 


  



     /**
     * Metodo que renderiza una vista que indica que ocurrio un error en la
     * ejecucion del proceso.
     * @param  integer $cod codigo que permite obtener la descripcion del
     * codigo de la operacion.
     * @return view.
     */
    public function actionErrorOperacion($cod)
    {
      //$varSession = self::actionGetListaSessions();
      //self::actionAnularSession($varSession);
      return MensajeController::actionMensaje($cod);
    }


    /**
     * [actionInactivarFuncionarioSolicitud description]
     * @param  [type] $idFuncionario [description]
     * @param  [type] $tipoSolicitud [description]
     * @param  [type] $tabla         [description]
     * @param  [type] $conexionLocal [description]
     * @param  [type] $connLocal     [description]
     * @return [type]                [description]
     */
    public function actionBuscarRuta($model)
    {

      $ruta = $model["PerfilUsuarioForm"]["ruta"];
      $grupo = GrupoPerfilUsuario::find()
                      ->where("descripcion=:descripcion", [":descripcion" => $ruta])->orderBy('ruta')
                      ->all();

      foreach ( $grupo as $ruta ) { 
               $arregloDatos[] = RutaAccesoMenu::find()
                      ->where("id_ruta_acceso_menu=:ruta", [":ruta" => $ruta['ruta']])
                      ->all();

      }

      return $arregloDatos;
    }



     /**
         * [actionListaImpuestoSolicitud description]
         * @return [type] [description]
         */
        public function actionListaAccesoMenu()
        {
            $caption = Yii::t('backend', 'Listado de Accesos al Menu del Grupo Seleccionado');
            $request = Yii::$app->request;
            $getData = $request->get();
            $grupo = $getData['id'];    // Indice del combo impuesto.
            $modelAcceso = New GrupoPerfilUsuarioForm();
            //$modelAcceso->getDescripcionRutaAcceso($getData);
            $dataProvider = $modelAcceso->getDescripcionRutaAcceso($grupo);

            return $this->renderAjax('/usuario/lista-acceso-menu', [
                                                        'modelSolicitud' => $modelAcceso,
                                                        'dataProvider' => $dataProvider,
                                                        'caption' => $caption,
                ]);

        }

     
     
     


}