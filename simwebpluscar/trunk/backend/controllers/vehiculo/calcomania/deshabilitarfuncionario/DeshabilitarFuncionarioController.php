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
 *  @file DeshabilitarFuncionarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/04/2016
 * 
 *  @class DeshabilitarFuncionarioController
 *  @brief Controlador que renderiza la vista con el el formulario para deshabilitar a los funcionarios funcionario en el modulo calcomania.
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

namespace backend\controllers\vehiculo\calcomania\deshabilitarfuncionario;

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
  

/**
 * Site controller
 */

session_start();





class DeshabilitarFuncionarioController extends Controller
{



    
  public $layout = 'layout-main';
   
  /**
   * [actionBusquedaFuncionario description] metodo que realiza la busqueda del funcionario para deshabilitarlo
   * @return [type] [description] retorna un dataprovider con todos los funcionarios activos
   */
  public function actionBusquedaFuncionario($errorCheck = "")
  {
    
      if(isset(yii::$app->user->identity->id_user)){
          
          $searchModel = new FuncionarioSearch();

          $dataProvider = $searchModel->search();
          //die(var_dump($dataProvider));

          return $this->render('/vehiculo/calcomania/deshabilitarfuncionario/vista-seleccion', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                'errorCheck' => $errorCheck,
                                                ]); 
      }else{
          echo "No existe User";
      }
  }

  public function actionDeshabilitarFuncionario()
  {
   $todoBien = false;
    $errorCheck = ""; 
     
      $idFuncionario = yii::$app->request->post('chk-deshabilitar-funcionario');
     
      $_SESSION['idFuncionario'] = $idFuncionario;
     // die(var_dump($idFuncionario));

          $buscarFuncionario = new DeshabilitarForm();
          //die($value);
          foreach ($idFuncionario as $key => $value[]) {
          $value; 
          //die(var_dump($value));
          $buscar = $buscarFuncionario->busqueda($value);
          
              if ($buscar == true){
                $_SESSION['login'] = $buscar; 

                $todoBien = true;
              }
         }
            
       // die(var_dump($buscar));

       if($todoBien){


       
        
                
    

              
  
      $validacion = new FuncionarioSearch();

       if ($validacion->validarCheck(yii::$app->request->post('chk-deshabilitar-funcionario')) == true){
        
        return $this->redirect(['aceptar-desincorporacion']);
           
        
          
       }else{
          $errorCheck = "Please select an Oficcer";
          return $this->redirect(['busqueda-funcionario' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
    }
     
     
  }

  /**
   * [AceptarDesincorporacion description] metodo que verifica si desea deshabilitar los funcionarios seleccionados
   */
  public function actionAceptarDesincorporacion()
  {
      $login[] = $_SESSION['login'];
      //die(var_dump($login));  
      $todoBien = false;
      $idFuncionarios = $_SESSION['idFuncionario'];
      $model = new DeshabilitarForm();

             $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                  $verificarFuncionario = new DeshabilitarForm();
                
                  foreach($login as $key => $value){
                  
                  $value[0]['login'];
                 // die(var_dump($value[0]['login']));
                  $verificar = $verificarFuncionario->verificarFuncionarioLote($value[0]['login']);
                      if($verificar == true){
                        //die('es verdad');


                      
                      $todoBien = true;
                    }

                   }

                    if($todoBien == true){

                       return MensajeController::actionMensaje(899);
                    }else{


                    $deshabilitarFuncionario = self::beginSave();

                    if ($deshabilitarFuncionario == true){
                        return MensajeController::actionMensaje(200); 
                    }else{
                        return MensajeController::actionMensaje(920);
                    }
                  
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
