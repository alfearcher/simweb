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
 *  @class CrearUsuarioController
 *  @brief Controlador para crear usuario tanto juridico como natural
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
use frontend\models\usuario\VerificarPreguntasContribuyenteNaturalForm;
use frontend\models\usuario\VerificarPreguntasContribuyenteJuridicoForm;



/**
 * Site controller
 */
class CambiarPasswordContribuyenteController extends Controller
{
   
    public $layout = "layout-login";

    //-- INICIO ACTIONSELECCIONARTIPOUSUARIO -->

    /**
     *
     * metodo que levanta la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de registro que desee hacer
     */
   public function actionSeleccionarTipoContribuyente(){

    return $this->render('/usuario/seleccionar-tipo-recuperar-password');
   }

    public function actionCambiarPasswordNatural()
    {

            $model = New VerificarPreguntasContribuyenteNaturalForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                        $buscarId = new VerificarPreguntasContribuyenteNaturalForm();

                     $p = $buscarId::buscarId($model);
                     if ($p == true){
                      $q =  $buscarId::buscarPreguntaSeguridad($p);

                      if ($q == true){ 
                        return $this->redirect(['/site/index']);
                    }else{
                        die('dirigirse a la alcaldia');
                     }
                    }

                    }
                        
                }
              return $this->render('/usuario/verificar-preguntas-seguridad-natural' , ['model' => $model]);

      } 

       public function actionCambiarPasswordJuridico()
    {

            $model = New VerificarPreguntasContribuyenteJuridicoForm();

              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                //die('llegue2');
                
                if ( $model->load($postData) ) {

                    if ($model->validate()){

                      //return self::actionBuscarRif($model->naturaleza, $model->cedula,$model->tipo );

                      //return $this->redirect(['juridico']);

                          //return $this->redirect(['buscar-rif']);
                    }
                        
                }
              return $this->render('/usuario/verificar-preguntas-seguridad-juridico' , ['model' => $model]);

      } 

   
      

    //-- FIN ACTIONSELECCIONARTIPOUSUARIO -->

    

}

?>
