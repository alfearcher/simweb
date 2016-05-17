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
 *  @date 16/05/2016
 * 
 *  @class AsignarCalcomaniaContribuyenteController
 *  @brief Controlador que renderiza la vista con el el formulario para asignar calcomanias a contribuyentes
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
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\deshabilitarfuncionario\FuncionarioSearch;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\vehiculo\calcomania\deshabilitarfuncionario\DeshabilitarForm;
use backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente\BusquedaNaturalForm;
/**
 * Site controller
 */

session_start();





class AsignarCalcomaniaContribuyenteController extends Controller
{



    
  public $layout = 'layout-main';


  /**
   * [actionSeleccionarTipoContribuyente description] funcion que renderiza la vista para seleccionar el tipo
   * de contribuyente que se desea buscar
   * @return [type] [description] retorna la vista donde se selecciona el tipo de contribuyente
   */
  public function actionSeleccionarTipoContribuyente()
  {
     return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-tipo-contribuyente');
  }
   
 /**
  * [actionBusquedaContribuyente description] metodo que realiza la busqueda del contribuyente natural 
  * 
  */
  public function actionBusquedaNatural()
  {
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
                        $buscarVehiculo = self::buscarVehiculo($buscarNatural);
                      }else{
                        return MensajeController::actionMensaje(990); 
                      }  
                  
              }
            }
                    
                  
                
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/busqueda-natural', [
                                                              'model' => $model,

                                                              
            ]);
  }

  public function buscarNatural($model)
  {
     $buscar = new BusquedaNaturalForm();

        $buscarNatural = $buscar->buscarNatural($model);

            if($buscarNatural ==  true){
              return $buscarNatural;
            }else{
              return false;
            }
  }

  public function buscarVehiculo($model)
  {
       $searchModel = new BusquedaNaturalForm();

          $dataProvider = $searchModel->buscarVehiculo($model);
         

          return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-vehiculo', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                ]); 
  }

  public function actionDeshabilitarFuncionario()
  {
   // die('deshabilitar');
    $errorCheck = ""; 
     
      $idFuncionario = yii::$app->request->post('chk-deshabilitar-funcionario');
      //die(var_dump($idFuncionario));
      $_SESSION['idFuncionario'] = $idFuncionario;

  
      $validacion = new FuncionarioSearch();

       if ($validacion->validarCheck(yii::$app->request->post('chk-deshabilitar-funcionario')) == true){
        
        return $this->redirect(['aceptar-desincorporacion']);
           
        
          
       }else{
          $errorCheck = "Please select an Oficcer";
          return $this->redirect(['busqueda-funcionario' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
  }

  /**
   * [AceptarDesincorporacion description] metodo que verifica si desea deshabilitar los funcionarios seleccionados
   */
  public function actionAceptarDesincorporacion()
  {
  
      $model = new DeshabilitarForm();

             $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                 $deshabilitarFuncionario = self::beginSave();

                    if ($deshabilitarFuncionario == true){
                        return MensajeController::actionMensaje(200); 
                    }else{
                        return MensajeController::actionMensaje(920);
                    }
                  
              }
            }
                    
                  
                
            return $this->render('/vehiculo/calcomania/deshabilitarfuncionario/aceptar-desincorporacion', [
                                                              'model' => $model,

                                                              
            ]);
            
  }
  /**
   * [actualizarDesincorporacion description] metodo que realiza la actualizacion del estatus del funcionario 
   * @param  [type] $conn     [description] instancia a la conexion
   * @param  [type] $conexion [description] instancia a la conexion
   * @return [type]           [description] retorna true si todo el proceso se cumple
   */
  public function deshabilitarFuncionario($conn, $conexion)
  {
    // die('llego a deshabilitar');
      $tableName = 'funcionario_calcomania';
      $arregloCondition = ['id_funcionario' => $_SESSION['idFuncionario']]; //id del funcionario
      
     
      $arregloDatos['estatus'] = 1;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

            if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

            return true;
              
          }
         

  }
  


  /**
   * [actionBeginSave description] metodo que realiza los procesos del conexion controller y actualiza el estatus del funcionario
   * @return [type] [description] si retorna true, actualiza el estatus del funcionario pero si retorna false envia un mensaje de error
   * 
   */
  public function beginSave()
  {
   // die('llego a begin');
  $idFuncionario = $_SESSION['idFuncionario'];
     $todoBien = true;

      $conexion = new ConexionController();

      

        $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

          foreach($idFuncionario as $key => $value){

                
                $deshabilitarFuncionario = self::deshabilitarFuncionario($conn, $conexion);


                    if ($deshabilitarFuncionario == true ){
                           // die('deshabilito');
                            $todoBien == true;
                            
                    }
                        
                      
                    if($todoBien == true){
                      //die('esta todo bien');
                       
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
