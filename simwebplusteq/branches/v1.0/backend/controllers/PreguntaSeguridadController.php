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
 *  @file PreguntaSeguridadController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class PreguntaSeguridadController
 *  @brief Clase que permite controlar opciones de registro de funcionarios, cambiar el password para el logueo del funcionario,
 *  registrar las preguntas secretas para la recuperacion del password y el proceso de recuperar el password mediante las preguntas
 *  de seguridad 
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  asignarpresecre
 *  
 *  
 *  @inherits
 *  
 */
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
// pregunta seguridad
use backend\models\FormAsignarPreguntaSecreta;
use backend\models\PreguntasUsuarios;
// mandar url
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;
// active record consultas..
use yii\db\ActiveRecord;
use common\conexion\ConexionController;

class PreguntaSeguridadController extends Controller
{
    public $layout = "layout-login";
    public $conn;
    public $conexion;
    public $transaccion;
/***************************** ASIGNAR PREGUNTAS SECRETAS DE FUNCIONARIOS *******************************
*
* Metodo para asignar las preguntas secretas de las cuentas de usuarios funcionarios
* ruta: pregunta-seguridad/asignarpreguntasecreta
*********************************************************************************************************/
     public function actionAsignarpreguntasecreta()
     {
	     //Creamos la instancia con el model de validación
         $model = new FormAsignarPreguntaSecreta;
         //$model = new LoginForm;
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

                 // Preparamos la consulta para guardar el usuario 
                 $table = new PreguntasUsuarios;
		         $table2 = new PreguntasUsuarios; 
		      
		         
		         $fecha = date("Y-m-d h:i:s"); 
				 
				 //asignar valores a los campos de la tabla de preguntas de usuarios
				 //primer SQL
				 $pregunta1 = $model->pregunta_secreta1;
                 $respuesta1 = $model->respuesta_secreta1;
                 
                 $tipo_pregunta1 = 0; 
		         $estatus = 0;
		         $fecha = $fecha;
		         $usuario = $model->usuario;
				 //segundo SQL
				 $pregunta2 = $model->pregunta_secreta2;
                 $respuesta2 = $model->respuesta_secreta2;
                 $tipo_pregunta2 = 1; 
				
         
				 $table = PreguntasUsuarios::find()->where("usuario=:usuario", [":usuario" => $usuario])->andWhere("estatus=:estatus", ["estatus" => 0]);	             
			     
                 if ($table->count() == 0){    
			           
					    $arrayColumna = ['pregunta','respuesta', 'usuario','estatus','fecha_hora','tipo_pregunta'];

                        $arrayValores = [ [$pregunta1, $respuesta1, $usuario, $estatus, $fecha, $tipo_pregunta1],
                                          [$pregunta2, $respuesta2, $usuario, $estatus, $fecha, $tipo_pregunta2],
                                        ];

                        $tableName = 'preguntas_usuarios';

                        $conn = New ConexionController();

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                        $this->conexion->open();

                        $transaccion = $this->conexion->beginTransaction();

                        if ( $conn->guardarLoteRegistros($this->conexion, $tableName, $arrayColumna, $arrayValores) ){
                            
                            $transaccion->commit();  
                            $tipoError = 0;
                            $msg = Yii::t('backend', 'SUCCESSFUL REGISTRATION OF THE SECURITY QUESTIONS!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/index")."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
            
                        }else{

                            $transaccion->roolBack();
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE SECRET QUESTIONS!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("pregunta-seguridad/asignarpreguntasecreta")."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        } 

                        $this->conexion->close();


                       }else{  

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE SECRET QUESTIONS!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("pregunta-seguridad/asignarpreguntasecreta")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   }
             }else{
                
                   $model->getErrors(); 
             }
        }
             return $this->render("asignarpreguntasecreta", ["model" => $model, "msg" => $msg, "url" => $url]);
     } 
}
