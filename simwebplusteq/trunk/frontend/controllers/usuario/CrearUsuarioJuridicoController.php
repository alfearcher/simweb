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
 *  @file CrearUsuarioJuridicoController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 13/01/2016
 *
 *  @class CrearUsuarioJuridicoController
 *  @brief Controlador para crear usuario Juridico
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
use frontend\models\CrearUsuarioForm;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\controllers\mensaje\MensajeController;
use common\conexion\ConexionController;
use common\seguridad\Seguridad;
use common\enviaremail\EnviarEmailJuridico;
use frontend\models\usuario\CargaDatosBasicosForm;
use common\models\utilidades\Utilidad;

session_start();

class CrearUsuarioJuridicoController extends Controller
{

    public $layout = "layout-login";



      /**
       * Este metodo se utiliza para levantar el formulario para la busqueda de la persona Juridica
       *
       * @return retorna el modelo que valida el formulario y renderiza la vista al mismo
       *
       */
    public function actionCrearUsuarioJuridico()
    {

        $model = New CrearUsuarioJuridicoForm();

        $postData = Yii::$app->request->post();

        if ( $model->load($postData) && Yii::$app->request->isAjax ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($postData) ){

            if ($model->validate()){

            return self::actionBuscarRif($model->naturaleza, $model->cedula,$model->tipo );

            }

        }
        return $this->render('/usuario/crear-usuario-juridico' , ['model' => $model]);

    }

     /**
    * Este metodo se utiliza para buscar el rif en la base de datos y en caso de existir, te lleva a la vistadonde se elecciona la empresa a registrar
    *
    * @param $naturaleza [string] trae la naturaleza del usuario como venezolano, juridico, gubernamental
    * @param $cedula [int] trae la cedula del usuario a registrar
    * @param $tipo [int] trae el numero de tipo que se coloca al final del rif
    * @return retorna la vista contribuyente-encontrado
    *
    */
    public function actionBuscarRif($naturaleza, $cedula, $tipo)
    {

        $_SESSION['rifJuridico'] = [
                                  'naturaleza' => $naturaleza,
                                  'cedula' => $cedula,
                                  'tipo' => $tipo,

                                    ];

        $dataProvider = CrearUsuarioJuridicoForm::obtenerDataProviderRif($naturaleza, $cedula, $tipo);

        $posts = $dataProvider->getModels();

            if (count($posts) == 0){

                $model = new CargaDatosBasicosForm();

                $model->naturaleza = $naturaleza;

                return $this->redirect(['juridico',


                                        ]);

            }else{

                return $this->render('/usuario/contribuyente-encontrado' , ['dataProvider' => $dataProvider, 'naturaleza'=>$naturaleza, 'cedula'=> $cedula,'tipo'=> $tipo ]);

            }
    }

    /**
    *
    * Este metodo se utiliza para levantar el formulario de carga de datos basicos
    * de usuario juridico y a su vez llama al metodo que guarda su informacion en la BD
    *
    * @param  $naturaleza [string] trae la naturaleza del usuario como venezolano, juridico, gubernamental
    * @param  $cedula [int] trae la cedula del usuario a registrar
    * @param  $tipo [int] trae el numero de tipo que se coloca al final del rif
    * @return retorna la vista al formulario de carga de datos basicos de la persona Juridica
    */
    public function actionJuridico()
    {

        $rifJuridico = isset($_SESSION['rifJuridico']) ? $_SESSION['rifJuridico'] : null;
        //die(var_dump($rifJuridico));
         if ($rifJuridico != null){

        $model = new CargaDatosBasicosForm();



        $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                    $resultado = self::beginSave("contribuyente", $model);

                if ($resultado == true){

                    return MensajeController::actionMensaje(Yii::t('frontend', 'We have sent you an email with your new user and password'));

                }else{

                    return MensajeController::actionMensaje(Yii::t('frontend', 'Sorry, there was a problem creating your account'));
                }
                }

            }
            return $this->render('/usuario/formulario-juridico' , ['model' => $model,
                                                                      'rifJuridico' => $rifJuridico,
                                                                      ]);
           }else{
              return MensajeController::actionMensaje(Yii::t('frontend', 'There is no rif associated'));
           }
    }



    /**
    *
    * Metodo que verifica si el usuario no tiene correo electronico para enviar un mensaje , pidiendole
    * que se dirija a la alcaldia, de lo contrario , de no tener cuenta en "afiliacion", se le crea una automatica
    *
    * @param  [int] Se refiere al id del contribuyente
    * @return [string] Retorna un mensaje en pantalla para el usuario
    */
    public function actionValidarJuridico($id)
    {

        $model = CrearUsuarioJuridicoForm::findContribuyente($id);

            if ($model[0]->email == null or trim($model[0]->email) == ""){

                return MensajeController::actionMensaje('Por favor, dirijase a la Alcaldia');

            }else{

                $modelAfiliacion = CrearUsuarioJuridicoForm::findAfiliacion($model[0]->id_contribuyente);

                if ($modelAfiliacion == true){

                  return MensajeController::actionMensaje('Este usuario ya existe');
                   }else{

                    $modelx = new CargaDatosBasicosForm();

                    $modelx->id_contribuyente = $model[0]->id_contribuyente;

                    $modelx->email = $model[0]->email;

                    $guardarAfiliacion = self::beginSave("afiliaciones", $modelx);

                    if ($guardarAfiliacion == true){
                                return MensajeController::actionMensaje('Has sido afiliado, consulta tu correo electrónico');
                            } else{
                                return MensajeController::actionMensaje('Ocurrió un error, al intentar afiliar el contribuyente');
                            }

                }

            }
  }

  /**
  *
  * Modelo para guardar los datos en la tabla afiliaciones y envia un email al usuario con su nuevo "usuario" y "contraseña"
  *
  * @param $model instancia que trae el modelo con los datos del formulario
  * @param $conn instancia de conexion
  * @param $conexion instancia de conexion
  * @return retorna el resultado de la insercion en la tabla afiliaciones
  */
  public function salvarAfiliacion($conn, $conexion, $model)
  {

      $resultado = false;
      $tabla = 'afiliaciones';
      $arregloDatos = [];
      $arregloCampo = CrearUsuarioJuridicoForm::attributeAfiliacion();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $seguridad = new Seguridad();

      $nuevaClave = $seguridad->randKey(6);

      $salt = Utilidad::getUtilidad();

      $password = $nuevaClave.$salt;

      $password_hash = md5($password);

      $arregloDatos['id_contribuyente'] = $model->id_contribuyente;

      $arregloDatos['login'] = $model->email;

      $arregloDatos['password_hash'] = $password_hash;

      $arregloDatos['fecha_hora_afiliacion'] = date('Y-m-d h:m:i');

          if ($conexion->guardarRegistroAfiliacion($conn, $tabla, $arregloDatos)){

              $resultado = true;
          }

          $enviarEmail = new EnviarEmailJuridico();

          $enviarEmail->enviarEmail($model->email, $nuevaClave, $model->razon_social);

          return $resultado;

  }

  /**
  *
  * metodo que guarda la informacion en la tabla contribuyente
  *
  * @param $model instancia que trae el modelo con los datos del formulario
  * @param $conn instancia de conexion
  * @param $conexion instancia de conexion
  * @return retorna el resultado de la insercion en la tabla afiliaciones
  *
  */
  public function salvarContribuyenteJuridico($conn, $conexion, $model)
  {

      $tabla = 'contribuyentes';
      $arregloDatos = [];
      $arregloCampo = CrearUsuarioJuridicoForm::attributeContribuyentes();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['tipo_naturaleza'] = 1;

      $arregloDatos['ente'] = Yii::$app->ente->getEnte();

      $arregloDatos['naturaleza'] = $model->naturaleza;

      $arregloDatos['cedula'] = $model->cedula;

      $arregloDatos['tipo'] = $model->tipo;

      $arregloDatos['razon_social'] = $model->razon_social;

      $arregloDatos['domicilio_fiscal'] = $model->domicilio_fiscal;

      $arregloDatos['email'] = $model->email;

      $arregloDatos['fecha_inclusion'] = date('Y-m-d');

      $arregloDatos['fecha_nac'] = $model->fecha_nac;

      $codigo = $model->codigo;

      $arregloDatos['tlf_ofic'] = $codigo.$model->tlf_ofic;


      $arregloDatos['tlf_ofic_otro'] = $model->tlf_ofic_otro;

      $codigoCelular = $model->codigo3;

      $arregloDatos['tlf_celular'] = $codigoCelular.$model->tlf_celular;

      $idContribuyente = 0;

          if ($conexion->guardarRegistroAfiliacion($conn, $tabla, $arregloDatos )){

              $idContribuyente = $conn->getLastInsertID();

          }
          return $idContribuyente;

  }

  /**
  *
  * Metodo que guarda la informacion de los formularios de carga de datos basicos, tanto en contribuyente, o en contribuyente y afiliaciones a la misma vez
  * segun lo requiera el caso
  *
  * @param $var [string] variable en donde se guardan los parametros "contribuyente" y "afiliaciones"
  * @param $modelo instancia del modelo que trae la informacion del formulario
  * @return retorna mensaje de felicitaciones por haber creado una nueva cuenta en el sistema
  */
  public function beginSave($var, $model)
  {

      $conexion = new ConexionController();

      $idContribuyente = 0;

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "afiliaciones"){

              $respuesta = self::salvarAfiliacion($conn, $conexion, $model);

          if ($respuesta == true){

              $transaccion->commit();
              $conn->close();
              return true;

          }else{

              $transaccion->rollback();
              $conn->close();
              return false;
          }

          }elseif ($var == "contribuyente"){

              $idContribuyente = self::salvarContribuyenteJuridico($conn, $conexion, $model);

              if ($idContribuyente > 0){

                  $model->id_contribuyente = $idContribuyente;
                  $respuesta = self::salvarAfiliacion($conn, $conexion, $model );

              if ($respuesta == true){

                  $transaccion->commit();
                  $conn->close();

                  return true;

              }else {

                  $transaccion->rollback();
                  $conn->close();
                  return false;
                  }



              }
          }

  }


}

?>


