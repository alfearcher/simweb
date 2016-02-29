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
 *  @file MostrarPreguntaSeguridadController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29/02/16
 * 
 *  @class RegistrarVehiculoController
 *  @brief Controlador que renderiza vista con el formulario para el registro de vehiculo
 * 
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

namespace frontend\controllers\vehiculo\registrar;

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

/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class RegistrarVehiculoController extends Controller
{



    
   public $layout = 'layoutbase';
   
    /**
     *
     * metodo que realiza la busqueda de las preguntas de seguridad del contribuyente una vez que este esta dentro de su cuenta,
     * para mostrarlas y asi poder cambiar su password desde adentro de su cuenta
     * 
     * @return retorna la vista con las preguntas de seguridad del contribuyente
     */
    public function actionRegistrarVehiculo()
    {

        //die($idContribuyente);
        if(isset(yii::$app->user->identity->id_contribuyente)){



          die('usuario existe');
        $datosContribuyente = yii::$app->user->identity;

        $buscarPreguntas = PreguntaSeguridadContribuyente::find()
                                                    ->where([
                                                    'id_contribuyente' => $datosContribuyente->id_contribuyente,
                                                    'inactivo' => 0,

                                                        ])
                                                    ->all();
        

        $model = new MostrarPreguntaSeguridadForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                //  die('llegue a mostrar');

                   return $this->redirect (['/usuario/mostrar-pregunta-seguridad/reseteo-password',
                                                                          'id_contribuyente' => $datosContribuyente->id_contribuyente,



                                                                                                          ]);

                }
                

              
            }
            return $this->render('/usuario/mostrar-pregunta-seguridad', [
                                                        'model' => $model,
                                                        'preguntaSeguridad' => $buscarPreguntas,
            ]);  

          }else{

            die('no existe user alvaro');
          }
    }

    

   


    

    

}

?>
