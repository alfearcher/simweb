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
 *  @file PreguntaSeguridadContribuyenteController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class PreguntaSeguridadContribuyenteController
 *  @brief Controlador crear las preguntas de seguridad de los usuarios tanto naturales como juridicos.
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

    /**
    *
    * metodo que levanta la vista para la seleccion del tipo de registro a realizarse
    * 
    * @return retorna la vista para seleccionar el tipo de registro que desee hacer
    */
    public function actionCrearPreguntaSeguridadContribuyente($id_contribuyente)
    {
      
        $model = New PreguntaSeguridadContribuyenteForm();
         
        $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                      
                $resultado = self::guardarPreguntaSeguridad($id_contribuyente,$model);
                      
                if ($resultado == true ){
                      
                return MensajeController::actionMensaje(Yii::t('frontend','We have saved your security answers'));
                
                }else{
                          return MensajeController::actionMensaje(Yii::t('frontend','An error ocurred while we were trying to save this'));
                }
                      
                }
                        
            }
            return $this->render('/usuario/pregunta-seguridad-contribuyente' , ['model' => $model]);

    }

    /**
     * [guardarPreguntaSeguridad description] Metodo que guarda las preguntas y las respuestas de seguridad de cada contribuyente en la
     * tabla preg_seg_contribuyentes
     * @param  [type] $id_contribuyente [description] id del contribuyente que desea guardar sus preguntas de seguridad
     * @param  [type] $model            [description] modelo que contiene las preguntas de seguridad
     * @return [type]                   [description] redireccionamiento a la cuenta del usuario luego de haber guardado las preguntas de seguridad
     */
    public function guardarPreguntaSeguridad($id_contribuyente, $model){

        $buscar = new Afiliaciones();

        $datos = $buscar->buscarDatos($id_contribuyente);
        
        $tabla = 'preg_seg_contribuyentes';

        $arregloDatos = [];
        
        $arregloCampo = PreguntaSeguridadContribuyenteForm::attributeContribuyentes();

            foreach ($arregloCampo as $key=>$value){

                $arregloDatos[$value] = 0;
            }
          
            $arrayColumna = [
                            'usuario' , 'id_contribuyente' , 'pregunta', 'respuesta', 'inactivo', 'tipo_pregunta',
                            ];
                           
            $arrayValores = [ 
                            [$datos->login, $datos->id_contribuyente, $model->pregunta1, $model->respuesta1,  0,  0],
                            [$datos->login, $datos->id_contribuyente,$model->pregunta2, $model->respuesta2  , 0, 1],
                            [$datos->login, $datos->id_contribuyente,$model->pregunta3, $model->respuesta3 , 0, 2],
                            ];
                           
            $conexion = new ConexionController();

            $conn = $conexion->initConectar('db');
         
            $conn->open();

            $transaccion = $conn->beginTransaction();

                if ($conexion->guardarLoteRegistrosPreguntas($conn, $tabla, $arrayColumna, $arrayValores)){

                    $resultado = true;

                    $transaccion->commit();
                    $conn->close();
                    return true;
             
                        return $this->redirect(['/site/index']);
            
                }else {
                   
                    $transaccion->rollback();
                    $conn->close();
                    return false;
                }

   }

    

}
  
  

  ?>


