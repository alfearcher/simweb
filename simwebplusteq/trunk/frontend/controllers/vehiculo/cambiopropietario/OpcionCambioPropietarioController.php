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
 *  @file OpcionCambioPropietarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 06/04/16
 * 
 *  @class OpcionCambioPropietarioController
 *  @brief Controlador que renderiza vista para la seleccion del tipo de operacion que desea realizar para el cambio de propietario de
 *  un vehiculo , te muestra las opciones comprador y vendedor.
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

namespace frontend\controllers\vehiculo\cambiopropietario;

use Yii;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
session_start();
class OpcionCambioPropietarioController extends Controller
{
   
    public $layout = "layout-main";

   
 
    /**
     *
     * metodo que renderiza la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de registro que desee hacer
     */
    public function actionSeleccionarTipoCambioPropietario()
    {
        $idConfig = yii::$app->request->get('id');
        
        $_SESSION['id'] = $idConfig;

        //die($idConfig);

        return $this->render('/vehiculo/cambiopropietario/seleccionar-tipo-cambio-propietario');
    }   

    

    

}

?>
