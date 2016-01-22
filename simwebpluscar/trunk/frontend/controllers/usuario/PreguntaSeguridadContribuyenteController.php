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
use frontend\models\usuario\PreguntaSeguridadContribuyenteForm;
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
use frontend\models\usuario\Afiliaciones;
use common\conexion\ConexionController;
use frontend\controllers\mensaje\MensajeController;
use yii\helpers\Url;
/**
 * Site controller
 */
class PreguntaSeguridadContribuyenteController extends Controller
{
   
    public $layout = "layout-login";

    //-- INICIO ACTIONSELECCIONARTIPOUSUARIO -->

    /**
     *
     * metodo que levanta la vista para la seleccion del tipo de registro a realizarse
     * 
     * @return retorna la vista para seleccionar el tipo de registro que desee hacer
     */
    public function actionCrearPreguntaSeguridadContribuyente($id_contribuyente)
    {
      //die($id_contribuyente);
         $model = New PreguntaSeguridadContribuyenteForm();
         
         
              $postData = Yii::$app->request->post();

              if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
              }

                if ( $model->load($postData) ) {

                    if ($model->validate()){
                      //die('metodo guardar');
                      
                      $resultado = self::guardarPreguntaSeguridad($id_contribuyente,$model);
                      if ($resultado == true ){
                      return MensajeController::actionMensaje('We have saved your security answers');
                        }else{
                          return MensajeController::actionMensaje('An error ocurred while we were trying to save this');
                        }
                      
                    }
                        
                }
              return $this->render('/usuario/pregunta-seguridad-contribuyente' , ['model' => $model]);

    }

       
      public function guardarPreguntaSeguridad($id_contribuyente, $model){

        $buscar = new Afiliaciones();

        $datos = $buscar->buscarDatos($id_contribuyente);
      // die(var_dump($datos));

         $tabla = 'preg_seg_contribuyentes';

        //die(var_dump($model));
        $arregloDatos = [];
        $arregloCampo = PreguntaSeguridadContribuyenteForm::attributeContribuyentes();

          foreach ($arregloCampo as $key=>$value){

            $arregloDatos[$value] = 0;
          }
          
          //die($);
         
          $arrayColumna = [
                          'usuario' , 'id_contribuyente' , 'pregunta', 'respuesta', 'inactivo', 'tipo_pregunta',
                         
                          
                            ];
                           // die(var_dump($arrayColumna));


          $arrayValores = [ 
                            [$datos->login, $datos->id_contribuyente, $model->pregunta1, $model->respuesta1,  0,  0],
                            [$datos->login, $datos->id_contribuyente,$model->pregunta2, $model->respuesta2  , 0, 1],
                            [$datos->login, $datos->id_contribuyente,$model->pregunta3, $model->respuesta3 , 0, 2],
                          ];
                            //die(var_dump($arrayValores));
          
          

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
         
          $conn->open();

          $transaccion = $conn->beginTransaction();

           if ($conexion->guardarLoteRegistrosPreguntas($conn, $tabla, $arrayColumna, $arrayValores)){

              $resultado = true;

             $transaccion->commit();
           // die('guardo');
            //  $conn->close();
              return true;
             //die('guardo');
             // $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['index']). "'>";  
               
               return $this->redirect(['/site/index']);
             // die('guardo');
            }else {
              $transaccion->rollback();
               $conn->close();
               return false;
             die('no guardo');
            }

      }

    

}
  
  

  ?>


