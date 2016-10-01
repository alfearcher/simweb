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
 *  @file InactivarNivelesPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 23/09/16
 * 
 *  @class InactivarNivelesPresupuestoController
 *  @brief Controlador que renderiza la vista con el formulario para la inactivacion de datos de la tabla niveles_contables
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

namespace backend\controllers\presupuesto\nivelespresupuesto\inactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use common\enviaremail\PlantillaEmail;
use backend\models\presupuesto\nivelespresupuesto\modificar\ModificarNivelesPresupuestoForm;
use backend\models\presupuesto\nivelespresupuesto\inactivar\InactivarNivelesPresupuestoForm;
/**
 * Site controller
 */

session_start();





class InactivarNivelesPresupuestoController extends Controller
{
  
  public $layout = 'layout-main';

  /**
   * [actionVistaSeleccion description] metodo que renderiza formulario para la seleccion de niveles presupuestarios disponibles
   * @param  string $errorCheck [description] variable de error
   * @return [type]             [description] retorna el formulario
   */
  public function actionVistaSeleccion($errorCheck = "")
  {


  		$busqueda = new InactivarNivelesPresupuestoForm();
  		$dataProvider = $busqueda->busquedaNivelesPresupuestarios();


  			return $this->render('/presupuesto/nivelespresupuesto/inactivar/vista-seleccion', [

  															'dataProvider' => $dataProvider,
                                'errorCheck' => $errorCheck,
  																]);
  }

  /**
   * [actionVerificarNivelContable description] metodo que recibe el post de los niveles contables para guardar en una variable sus valores
   * @return [type] [description] retorna true o false
   */
  public function actionVerificarNivelContable()
  {

  	 $errorCheck = ""; 
     
      $nivelContable = yii::$app->request->post('chk-inactivar-nivel-contable');
  
      $_SESSION['nivelContable'] = $nivelContable;

  
      $validacion = new InactivarNivelesPresupuestoForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-inactivar-nivel-contable')) == true){
           
          
              $inactivar = self::beginSave("inactivar");

              if($inactivar == true){

                return MensajeController::actionMensaje(200);
              }else{

                return MensajeController::actionMensaje(920);
              }
        
        
       }else{
          $errorCheck = "Please select a countable level";
          return $this->redirect(['vista-seleccion' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
  }				
   
  
  	


    /**
     * [InactivarNivelesContables description] metodo que realiza la inactivacion de la informacion ingresada por el funcionario en la tabla niveles contables
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @param  [type] $model    [description] informacion enviada por el funcionario desde el formulario
     * @return [type]           [description] retorna true si el proceso modifica y false si el proceso da error
     */
    public function inactivarNivelesContablesMaestro($conn, $conexion, $value)
    {
     //die($value);

    	$tableName = 'niveles_contables';
      	
      	$arregloCondition = ['nivel_contable' => $value];
     
    		  // die(var_dump($arregloCondition));
        	$arregloDatos['estatus'] = 1;

		      $conexion = new ConexionController();

		      $conn = $conexion->initConectar('db');
		         
		      $conn->open();

      

          			if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

              			return true;
              
          			}
      
     

    }

   
    /**
     * [beginSave description] metodo padre de inactivacion que redirecciona hacia otros metodos encargados de finalizar la inactivacion
     * @param  [type] $var   [description] variable tipo string para la redireccion
     * @param  [type] $model [description] informacion enviada desde el form
     * @return [type]        [description] retorna true o false
     */
    public function beginSave($var)
    {

    $nivelContable = $_SESSION['nivelContable'];
     $todoBien = true;

      $conexion = new ConexionController();

      

        $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

          if($var == "inactivar"){ 

          foreach($nivelContable as $key => $value){
                $value;
               
                $inactivar = self::inactivarNivelesContablesMaestro($conn, $conexion, $value);


                    if ($inactivar == true ){
                           // die('deshabilito');
                            $todoBien = true;
                            
                    }

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
