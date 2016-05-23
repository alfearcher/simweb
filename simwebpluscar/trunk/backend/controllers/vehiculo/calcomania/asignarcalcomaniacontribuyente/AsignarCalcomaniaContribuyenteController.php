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
 *  @file AsignarCalcomaniaContribuyenteController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/05/2016
 * 
 *  @class AsignarCalcomaniaContribuyenteController
 *  @brief Controlador que renderiza la vista para realizar la entrega al contribuyente
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

namespace backend\controllers\vehiculo\calcomania\asignarcalcomaniacontribuyente;

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
use backend\models\vehiculo\calcomania\administrarlotecalcomania\BusquedaMultipleForm;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente\BusquedaNaturalForm;

/**
 * Site controller
 */

session_start();





class AsignarCalcomaniaContribuyenteController extends Controller
{

       
  public $layout = 'layout-main';
  
  
  public function actionSeleccionarTipo()
  {
    return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-tipo-contribuyente');
  }

  public function actionBusquedaNatural()
  {
      $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new BusquedaNaturalForm();
          
              $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                  
                  $buscarNatural = self::buscarNatural($model);

                      if($buscarNatural == true){
                        $_SESSION['datos'] = $buscarNatural;
                        return $this->redirect(['buscar-vehiculo']);
                      }else{
                        return MensajeController::actionMensaje(990);
                      }
                }
            }
            
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/busqueda-contribuyente-natural', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  }
   
  public function actionBusquedaJuridico()
  {
      $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new BusquedaJuridicoForm();
          
              $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {

                if ($model->validate()){
                  
                  $buscarJuridico = self::buscarJuridico($model);

                      if($buscarJuridico == true){
                        $_SESSION['datos'] = $buscarJuridico;
                        return $this->redirect(['buscar-vehiculo']);
                      }else{
                        return MensajeController::actionMensaje(990);
                      }
                }
            }
            
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/busqueda-contribuyente-juridico', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  } 
   
    public function buscarNatural($model)
    {

      $buscar = new BusquedaNaturalForm();

        $buscarNatural = $buscar->buscarNatural($model);
        
            if ($buscarNatural == true){

              return $buscarNatural;
            }else{
              return false;
            }
    }


    public function actionBuscarVehiculo()
    {
        $model = $_SESSION['datos'];

        $searchModel = new BusquedaNaturalForm();

            $dataProvider = $searchModel->vehiculoSearch($model);

            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-vehiculo', [
                                                                                  'searchModel' => $searchModel,
                                                                                  'dataProvider' => $dataProvider,
                                                                               

                                                                                          ]);
    }


    /**
     * [actionBuscarFuncionario description] metodo que instancia el modelo busquedaMultipleForm para realizar la busqueda del funcionario
     * @param  [type] $model [description] modelo que contiene la informacion del ano_impositivo y el id del funcionario
     * @return [type]        [description] devuelve true si consigue informacion y false si no la consigue
     */
    public function actionBuscarFuncionario($errorCheck = "")
    {
      $model = $_SESSION['datos'];


      $searchModel = new BusquedaMultipleForm();

          $dataProvider = $searchModel->search($model);

        
      return $this->render('/vehiculo/calcomania/administrarlotecalcomania/deshabilitar-calcomania', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                'errorCheck' => $errorCheck,
                                                ]);  
      
        
    
    }


    public function actionBuscarCalcomania($errorCheck = "")
    {
        $model = $_SESSION['datos'];

        $searchModel = new BusquedaMultipleForm();

            $dataProvider = $searchModel->searchCalcomania($model);

            return $this->render('/vehiculo/calcomania/administrarlotecalcomania/deshabilitar-calcomania-ano', [
                                                                                  'searchModel' => $searchModel,
                                                                                  'dataProvider' => $dataProvider,
                                                                                  'errorCheck' => $errorCheck,

                                                                                          ]);
    }


    public function actionBuscarRango($errorCheck = "")
    {
        $model = $_SESSION['datos'];

        $searchModel = new BusquedaMultipleForm();

            $dataProvider = $searchModel->searchRango($model);

            return $this->render('/vehiculo/calcomania/administrarlotecalcomania/deshabilitar-calcomania-rango', [
                                                                                  'searchModel' => $searchModel,
                                                                                  'dataProvider' => $dataProvider,
                                                                                  'errorCheck' => $errorCheck,

                                                                                          ]);
    }





    /**
     * [actionVerificarCalcomanias description] metodo que verifica si una calcomania esta seleccionada y redirecciona al proceso de   guardado
     * @return [type] [description] retorna true si el proceso se cumple y false si no se cumple
     */
    public function actionVerificarCalcomanias()
    {

    $errorCheck = ""; 

    $idCalcomanias = yii::$app->request->post('chk-deshabilitar-calcomania');
    // die(var_dump($idCalcomanias));
    $_SESSION['idCalcomanias'] = $idCalcomanias;

  
      $validacion = new BusquedaMultipleForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-deshabilitar-calcomania')) == true){
        
        return $this->redirect(['deshabilitar-calcomania']);
           
        }else{
          $errorCheck = "Please select a Sticker";
              return $this->redirect(['buscar-funcionario' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
    }
    /**
     * [actionVerificarCalcomaniasAno description] metodo que verifica si una calcomania esta seleccionada y redirecciona al guardado
    *
     */
    public function actionVerificarCalcomaniasAno()
    {

    $errorCheck = ""; 

    $idCalcomanias = yii::$app->request->post('chk-deshabilitar-calcomania');
    // die(var_dump($idCalcomanias));
    $_SESSION['idCalcomanias'] = $idCalcomanias;

  
      $validacion = new BusquedaMultipleForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-deshabilitar-calcomania')) == true){
        
        return $this->redirect(['deshabilitar-calcomania']);
           
        }else{
          $errorCheck = "Please select a Sticker";
              return $this->redirect(['buscar-calcomania' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
    }

    public function actionVerificarCalcomaniaRango()
    {

    $errorCheck = ""; 

    $idCalcomanias = yii::$app->request->post('chk-deshabilitar-calcomania');
    // die(var_dump($idCalcomanias));
    $_SESSION['idCalcomanias'] = $idCalcomanias;

  
      $validacion = new BusquedaMultipleForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-deshabilitar-calcomania')) == true){
        
        return $this->redirect(['deshabilitar-calcomania']);
           
        }else{
          $errorCheck = "Please select a Sticker";
              return $this->redirect(['buscar-rango' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
    }



    /**
     * [actionDeshabilitarCalcomania description] metodo que direcciona al begin save y espera respuestas para enviar mensaje de controlador
    *
     */
    public function actionDeshabilitarCalcomania()
    {
      $guardar = self::beginSave("deshabilitarCalcomania");

          if($guardar == true){
            return MensajeController::actionMensaje(200);
          }else{
            return MensajeController::actionMensaje(920);
          }
    }

    /**
     * [deshabilitarCalcomania description] metodo que realiza la deshabilitacion de las calcomanias seleccionadas en la tabla calcomanias
     * @param  [type] $conn         [description] parametro de conexion a base de datos
     * @param  [type] $conexion     [description] parametro de conexion a base de datos
     * @param  [type] $idCalcomania [description] id de las calcomanias que se van a deshabilitar
     * @return [type]               [description]
     */
    public function deshabilitarCalcomania($conn, $conexion)
    {
    //die('llego a deshabilitar'.$idCalcomania);
      $tableName = 'calcomanias';
      $arregloCondition = ['id_calcomania' => $_SESSION['idCalcomanias']]; //id de la calcomania
      
     
      $arregloDatos['estatus'] = 1;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

            if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

            return true;
              
          }
         

  }
    
  
    /**
     * [beginSave description] metodo que realiza la deshabilitacion de la calcomania seleccionada en la tabla calcomanias
     * @param  [type] $var [description] variable que recibe en forma de string para comenzar el guardado
     * @return [type]      [description] retorna true si el commit se realiza y false si hay un roll back
     */
 public function beginSave()
  {
       // die('llego a begin');
  $idCalcomanias = $_SESSION['idCalcomanias'];
      
      $todoBien = true;

      $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');

          $conn->open();

          $transaccion = $conn->beginTransaction();

              foreach($idCalcomanias as $key => $value){

                
                  $deshabilitarCalcomanias = self::deshabilitarCalcomania($conn, $conexion);


                      if ($deshabilitarCalcomanias == true ){
                            //die('deshabilito');
                            $todoBien == true;
                            
                      }
                        
                      
                      if($todoBien == true){
                      
                       
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
