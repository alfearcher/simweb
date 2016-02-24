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
 *  @file OpcionCrearUsuarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class OpcionCrearUsuarioController
 *  @brief Controlador que renderiza vista para la seleccion del tipo de usuario que se desea crear, ya sea natural o juridico.
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

/**
 * Site controller
 */

session_start();



class MostrarPreguntaSeguridadController extends Controller
{



    
   public $layout = 'layoutbase';
    /**
     *
     * metodo que realiza la busqueda de las preguntas de seguridad del contribuyente una vez que este esta dentro de su cuenta,
     * para mostrarlas y asi poder cambiar su password desde adentro de su cuenta
     * 
     * @return retorna la vista con las preguntas de seguridad del contribuyente
     */
    public function actionBuscarMostrarPreguntaSeguridad()
    {
    

     // $datosContribuyente = $_SESSION['sesion'];

      //die(var_dump($datosContribuyente->id_contribuyente));
      //die(var_dump(yii::$app->user->identity));



    $buscarPreguntas = PreguntaSeguridadContribuyente::find()
                                                    ->where([
                                                    'id_contribuyente' => $datosContribuyente->id_contribuyente,
                                                    'inactivo' => 0,

                                                        ])
                                                    ->all();
    //die(var_dump($buscarPreguntas));
    //
      $_SESSION['preguntaSeguridad'] = $buscarPreguntas;


        $model = new MostrarPreguntaSeguridadForm();

        return $this->render('/usuario/mostrar-pregunta-seguridad', [
                                                                            'model' => $model,
                                                                            'preguntaSeguridad' => $buscarPreguntas,
            ]);
 

    

}




    

    

}

?>
