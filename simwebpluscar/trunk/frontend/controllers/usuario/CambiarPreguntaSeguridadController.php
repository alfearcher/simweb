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
 *  @file CambiarPreguntaSeguridadController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/02/16
 * 
 *  @class CambiarPreguntaSeguridadController.php
 *  @brief Controlador que renderiza vista para realizar el cambio de la pregunta de seguridad
 *  
 * 
 *  
 *  
 *  @property
 *
 *
 *  
 *
 *  @inherits
 *  
 */ 

namespace frontend\controllers\usuario;

use Yii;

use common\models\LoginForm;
use frontend\models\usuario\CrearUsuarioNaturalForm;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use frontend\models\usuario\CrearUsuarioJuridico;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\MostrarPreguntaSeguridadForm;
use frontend\models\usuario\ReseteoPasswordForm;
use common\models\utilidades\Utilidad;
use common\seguridad\Seguridad;
use common\conexion\ConexionController;
use frontend\models\usuario\CrearUsuarioNatural;
use common\enviaremail\EnviarEmailCambioClave;
use common\mensaje\MensajeController;
use frontend\models\usuario\CambiarPreguntaSeguridadForm;
use frontend\models\usuario\PreguntaSeguridadContribuyenteForm;



/**
 * Site controller
 */

session_start();



class CambiarPreguntaSeguridadController extends Controller
{



    
   public $layout = 'layout-main';
   
    /**
     *
     * metodo que realiza la busqueda de las preguntas de seguridad del contribuyente una vez que este esta dentro de su cuenta,
     * para mostrarlas y asi poder cambiar su password desde adentro de su cuenta
     * 
     * @return retorna la vista con las preguntas de seguridad del contribuyente
     */
    public function actionBuscarCambiarPreguntaSeguridad()
    {

  
     
        $datosContribuyente = yii::$app->user->identity;

        $buscarPreguntas = PreguntaSeguridadContribuyente::find()
                                                    ->where([
                                                    'id_contribuyente' => $datosContribuyente->id_contribuyente,
                                                    'inactivo' => 0,

                                                        ])
                                                    ->all();
        

        $model = new CambiarPreguntaSeguridadForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){


                $cambiarEstatus = self::cambiarEstatusPreguntas($model);

                  if($cambiarEstatus == true){
                   return MensajeController::actionMensaje(400);
                  }else{
                   return MensajeController::actionMensaje(400);
                  }

                }
                

              
            }
            return $this->render('/usuario/cambiar-pregunta-seguridad', [
                                                        'model' => $model,
                                                        'preguntaSeguridad' => $buscarPreguntas,
            ]);  
    }

    public function cambiarEstatusPreguntas($model)
    {

   // die('volvi a llegar al principio');
    
      $datosContribuyente = yii::$app->user->identity;

      $buscarPreguntas = PreguntaSeguridadContribuyente::find()
                                                    ->where([
                                                    'id_contribuyente' => $datosContribuyente->id_contribuyente,
                                                    'inactivo' => 0,


                                                        ])
                                                    ->all();

                    //die(var_dump($buscarPreguntas));
      
      $conexion = new ConexionController;

      $tableName = 'preg_seg_contribuyentes';

      $arrayDatos = ['inactivo' => 1];

      $arrayCondition = ['id_contribuyente' => $datosContribuyente->id_contribuyente,
                         'inactivo' => 0];

      $conn = $conexion->initConectar('db');
         
      $conn->open();

      $transaccion = $conn->beginTransaction();

      if ($conexion->modificarRegistro($conn, $tableName, $arrayDatos, $arrayCondition)){

           // $guardarPreguntas = self::guardarNuevasPreguntas($model);


          $idContribuyente = yii::$app->user->identity->id_contribuyente;
          $login = yii::$app->user->identity->login;
        
          $tabla = 'preg_seg_contribuyentes';

       
        
       
          
            $arrayColumna = [
                            'usuario' , 'id_contribuyente' , 'pregunta', 'respuesta', 'inactivo', 'tipo_pregunta',
                            ];
                           
            $arrayValores = [ 
                            [$login, $idContribuyente, $model['pregunta1'], $model['respuesta1'],  0,  0],
                            [$login, $idContribuyente, $model['pregunta2'], $model['respuesta2']  , 0, 1],
                            [$login, $idContribuyente, $model['pregunta3'], $model['respuesta3'] , 0, 2],
                            ];

                if ($conexion->guardarLoteRegistros($conn, $tabla, $arrayColumna, $arrayValores)){
                 // die('actualizo y guardo');
                   // $transaccion->commit();
                    $conn->close();

                    
                    return true;



                   
                }else{

                    $transaccion->rollback();
                    $conn->close();
                    return false;
                }

      }
         
  }

  public function enviarMensaje()
  {
    return MensajeController::actionMensaje(400); 
  }


  
                    
                    
    


    
    

    

}

?>
