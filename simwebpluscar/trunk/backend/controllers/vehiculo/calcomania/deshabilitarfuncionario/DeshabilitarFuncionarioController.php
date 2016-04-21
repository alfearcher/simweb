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
use backend\models\funcionario\FuncionarioCalcomania;

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
    die('llegue a deshabilitar');
  }
            
        
   
    
 

    

 
              
            
}
    



    

   


    

    



?>
