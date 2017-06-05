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
 *  @file AdministrarFuncionarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 20/04/2016
 * 
 *  @class AdministrarFuncionarioController
 *  @brief Controlador que renderiza la vista con el el formulario para la busqueda del funcionario en el modulo calcomania
 *  y controla la insercion de los funcionarios activos que administraran las calcomanias.
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

namespace backend\controllers\vehiculo\calcomania\administrarfuncionario;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\configuracion\solicitud\DocumentoSolicitud;
use common\enviaremail\PlantillaEmail;
use backend\models\vehiculo\calcomania\administrarfuncionario\BusquedaFuncionarioForm;
use backend\models\vehiculo\calcomania\administrarfuncionario\MostrarDatosFuncionarioForm;
use backend\models\funcionario\Funcionario;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
/**
 * Site controller
 */

session_start();



 

class AdministrarFuncionarioController extends Controller
{



    
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaFuncionario description] Metodo que renderiza el formulario para realizar la busqueda de funcionarios activos
   * @return [type] [description] Retorna el modelo de la cedula para buscar en la tabla funcionarios.
   */
    public function actionBusquedaFuncionario()
    {
    //die('llegue a funcionario');
            $model = new BusquedaFuncionarioForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                   $busquedaFuncionario = self::busquedaFuncionario($model);

                      if($busquedaFuncionario == true){

                        $_SESSION['datosFuncionario'] = $busquedaFuncionario;

                        return $this->redirect(['datos-funcionario']);
                      }else{
                        return MensajeController::actionMensaje(990);
                      }
                
              }
            }
            
            return $this->render('/vehiculo/calcomania/administrarfuncionario/busqueda-funcionario', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
            
             
    }
    /**
     * [busquedaFuncionario description] metodo que realiza la busqueda de funcionarios activos en la tabla funcionarios mediante la cedula
     * @param  [type] $model [description] modelo que trae la cedula ingresada para la busqueda del funcionario
     * @return [type]        [description] retorna true o false dependiendo si consigue al funcionario
     */
    public function busquedaFuncionario($model)
    {
      
      $busquedaFuncionario = Funcionario::find()
                                        ->where([
                                        'ci' => $model->cedula,
                                        'status_funcionario' => 0,
                                        ])
                                        ->all();

      if ($busquedaFuncionario == true){
        return $busquedaFuncionario;
      }else{
        return false;
      }
    }
    /**
     * [actionDatosFuncionario description] metodo que renderiza la vista con los datos del funcionario encontrado con la cedula ingresada
     * @return [type] [description] retorna la vista con los datos del funcionario
     */
    public function actionDatosFuncionario()
    {

      $datosFuncionario = $_SESSION['datosFuncionario']; //variable de sesion con los datos del funcionario encontrado.
     
       $model = new MostrarDatosFuncionarioForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){

                    $verificarSolicitud = self::verificarSolicitud();

                        if ($verificarSolicitud == true){

                           return MensajeController::actionMensaje(994);

                        }else{


                            $guardarFuncionario = self::beginSave("guardarFuncionario");

                                if ($guardarFuncionario == true){
                                    return MensajeController::actionMensaje(100);
                                }else{
                                    return MensajeController::actionMensaje(920);
                                }

                        }
                }
            }
                
            return $this->render('/vehiculo/calcomania/administrarfuncionario/mostrar-datos-funcionario', [
                                                        'model' => $model,
                                                        'datosFuncionario' => $datosFuncionario,

            ]);   
    }
    /**
     * [verificarSolicitud description] metodo que verificar si el funcionario ya se encuentra activo para administrar calcomania
     * @return [type] [description] retorna true si el funcionario esta activo y false si el funcionario esta inactivo.
     */
    public function verificarSolicitud()
    { 
      $idFuncionario = $_SESSION['datosFuncionario'];
       
      $busquedaFuncionario = FuncionarioCalcomania::find()
                                        ->where([
                                        'id_funcionario' => $idFuncionario[0]->id_funcionario,
                                        'estatus' => 0,
                                        ])
                                        ->all();

      if ($busquedaFuncionario == true){
        return $busquedaFuncionario;
      }else{
        return false;
      }
    }

    public function guardarFuncionario($conn, $conexion)
    {
      $idUser = yii::$app->user->identity->id_user;
      $datosFuncionario = $_SESSION['datosFuncionario'];
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'funcionario_calcomania';
      $arregloDatos = [];
      $arregloCampo = BusquedaFuncionarioForm::attributeFuncionarioCalcomania();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['id_funcionario'] = $datosFuncionario[0]->id_funcionario;
      //die($arregloDatos['id_funcionario']);
      
      $arregloDatos['estatus'] = 0;
      
      $arregloDatos['usuario'] = $idUser;
      
      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

          return true;
          }

    }

    /**
     * [beginSave description] metodo que realiza el guardado del funcionario en la tabla funcionario_calcomania
     * @param  [type] $var [description] variable que recibe en forma de string para comenzar el guardado
     * @return [type]      [description] retorna true si el commit se realiza y false si hay un roll back
     */
    public function beginSave($var)
    {
      //die('llegue a beginsave');
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if($var == "guardarFuncionario"){
            //die('llegue a var');

              $buscar = self::guardarFuncionario($conn, $conexion);

                  if ($buscar == true){

                    $transaccion->commit();
                    $conn->close();

                    return true;
                  
                  }else{

                      $transaccion->rollback();
                      $conn->close();
                      return false;
                  }

          }
   }                  


              

         

    
    
 

    

 
              
            
}
    



    

   


    

    



?>
