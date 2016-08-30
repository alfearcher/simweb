
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
session_start();
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class TransaccionesInmobiliariasController extends Controller
{

    public $conn;
    public $conexion;
    public $transaccion;


     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionRegistroTransaccionInmobiliaria()
     {

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
            
                     $calculo = New TransaccionInmobiliaria()
                     $añoImpositivo = date('Y');
                     $monto = iniciarCalculoTransaccion($model->precio_inmueble, $añoImpositivo, $model->tipo_transaccion)

                     $id_impuesto = $model->id_impuesto;                  //identificador del inpuesto inmobiliario
                     $id_comprador = $model->id_comprador;                //identidad del contribuyente comprador
                     $id_vendedor = $model->id_vendedor;                  //identidad del contribuyente vendedor
                     $direccion = $model->direccion;                      //direccion
                     $planilla = $model->planilla;                        //planilla de la transaccion
                     $precio_inmueble = $model->precio_inmueble;          // precio del inmueble
                     $tipo_transaccion = $model->tipo_transaccion;        //tipo de transaccion inmobiliaria
                     $usuario = yii::$app->user->identity->username;                          //usuario funcionario
                     $fecha_hora = date('Y-m-d h:i:s');                   //fecha y hora de la transaccion
                     $observacion = $model->observacion;                  //observaciones
                     $inactivo = $model->inactivo;                        //inactivo
                     

                   //--------------TRY---------------
                        $arrayDatos = ['id_comprador' => $id_comprador,
                                       'planilla' => $planilla,
                                       'id_vendedor' => $id_vendedor,
                                       'id_impuesto' => $id_impuesto,
                                       'precio_inmueble' => $precio_inmueble,
                                       'direccion' => $direccion,
                                       'tipo_transaccion' => $tipo_transaccion,
                                       'usuario' => $usuario,
                                       'fecha_hora' => $fecha_hora,
                                       'observacion' => $observacion,
                                       'inactivo' => $inactivo,
                                      ]; 

                        $tableName = 'transacciones_inmobiliarias'; 


                        $conn = New ConexionController();

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
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
              return $this->render('registro-transaccion-inmobiliaria', ['model' => $model, ]);  

        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles

     


}