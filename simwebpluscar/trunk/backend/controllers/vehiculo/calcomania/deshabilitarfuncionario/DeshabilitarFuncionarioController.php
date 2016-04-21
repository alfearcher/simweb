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
    $errorCheck = ""; 
      $idContribuyente = yii::$app->user->identity->id_contribuyente;
      $idFuncionario = yii::$app->request->post('chk-deshabilitar-funcionario');
      die(var_dump($idFuncionario));
      $_SESSION['idVehiculo'] = $idVehiculo;
//die(var_dump($_SESSION['idVehiculo']));
  
      $validacion = new DesincorporacionVehiculoForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-desincorporar-vehiculo')) == true){
           $modelsearch = new VehiculoSearch();
           $busqueda = $modelsearch->busquedaVehiculo($idVehiculo, $idContribuyente);
      //die(var_dump($busqueda));
          if ($busqueda == true){ 
           
        
              $_SESSION['datosVehiculo'] = $busqueda;
        
          return $this->redirect(['desincorporar-vehiculo']);
        
          }else{

              die('no existe vehiculo asociado a ese ID');
          }
       }else{
          $errorCheck = "Please select a car";
          return $this->redirect(['vista-seleccion' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
  }
            
        
   
    
 

    

 
              
            
}
    



    

   


    

    



?>
