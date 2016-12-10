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
 *  @file ModificarNivelesPresupuestoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 23/09/16
 * 
 *  @class ModificarNivelesPresupuestoController
 *  @brief Controlador que renderiza la vista con el formulario para la modificacion de datos de la tabla niveles_contables
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

namespace backend\controllers\presupuesto\nivelespresupuesto\modificar;

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
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarNivelesPresupuestoController extends Controller
{
  
  public $layout = 'layout-main';

  /**
   * [actionVistaSeleccion description] metodo que renderiza el formulario para seleccionar los diferentes niveles presupuestarios
   * @return [type] [description] retorna el formulario
   */
  public function actionVistaSeleccion()
  {


  		$busqueda = new ModificarNivelesPresupuestoForm();
  		$dataProvider = $busqueda->busquedaNivelesPresupuestarios();


  			return $this->render('/presupuesto/nivelespresupuesto/modificar/vista-seleccion', [

  															'dataProvider' => $dataProvider,

  																]);
  }

  /**
   * [actionVerificarNivelContable description] metodo que realiza la busqueda de niveles contables
   * @return [type] [description] redirecciona hacia otro metodo de modificacion
   */
  public function actionVerificarNivelContable()
  {

  		$nivelContable = yii::$app->request->post('id');
  		$_SESSION['idContable'] = $nivelContable;
  		//die(var_dump($nivelContable));
  			$busqueda = new ModificarNivelesPresupuestoForm();
  			$model = $busqueda->busquedaNiveles($nivelContable);

  				if($model == true){
  					$_SESSION['datosNiveles'] = $model;

  					return $this->redirect(['modificar-niveles-contables']);
  				}else{
  					return MensajeController::actionMensaje(920);
  				}
  }				
   
    /**
     * [actionModificarNivelesContables description] metodo que renderiza formulario para la modificacion de niveles contables
     * @return [type] [description] retorna el formulario
     */
  	public function actionModificarNivelesContables()
  	{
   		$datos = $_SESSION['datosNiveles'];

     	$model = new ModificarNivelesPresupuestoForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             // die('valido el postdata');

               if ($model->validate()){


               
                   $modificar = self::beginSave("modificar", $model);

                      if($modificar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }    
                      
                     
                
              }
            }
            
            return $this->render('/presupuesto/nivelespresupuesto/modificar/formulario-modificar-niveles-presupuestos', [
                                                              'model' => $model,
                                                              'datos' => $datos,
                                                             
                                                           
            ]);
  }


    /**
     * [modificarNivelesContablesMaestro description] metodo que realiza la modificacion de la informacion ingresada por el funcionario en la tabla niveles contables
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @param  [type] $model    [description] informacion enviada por el funcionario desde el formulario
     * @return [type]           [description] retorna true si el proceso modifica y false si el proceso da error
     */
    public function modificarNivelesContablesMaestro($conn, $conexion, $model)
    {

    	$tableName = 'niveles_contables';
      	
      	$arregloCondition = ['nivel_contable' => $_SESSION['idContable']];
     
    		$arregloDatos['nivel_contable'] = strtoupper($model->nivel_contable);

     		$arregloDatos['descripcion'] = $model->descripcion;

        	$arregloDatos['ingreso_propio'] = $model->ingreso_propio;

		      $conexion = new ConexionController();

		      $conn = $conexion->initConectar('db');
		         
		      $conn->open();

      

          			if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

              			return true;
              
          			}
      
     

    }

   
    /**
     * [beginSave description] metodo padre que redirecciona a otros metodos para realizar tanto guardado, como modificacion de procesos
     * @param  [type] $var   [description] variable tipo string que define la redireccion entre metodos
     * @param  [type] $model [description] informacion enviada desde el formulario
     * @return [type]        [description] retorna true o false
     */
    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "modificar"){
            

              $modificar = self::modificarNivelesContablesMaestro($conn, $conexion, $model);

             
              if ($modificar == true){

                

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
