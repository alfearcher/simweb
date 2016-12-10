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
 *  @date 20/04/2019
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

namespace backend\controllers\vehiculo\calcomania;

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
use backend\models\vehiculo\calcomania\BusquedaFuncionarioForm;
use backend\models\funcionario\Funcionario;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



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
                        return $this->redirect(['datos-funcionario']);
                      }else{
                        return MensajeController::actionMensaje(990);
                      }
                
              }
            }
            
            return $this->render('/vehiculo/calcomania/busqueda-funcionario', [
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
        return true;
      }else{
        return false;
      }
    }

    public function actionDatosFuncionario()
    {
      die('llegue a datos funcionario');
    }

    

 
              
            
}
    



    

   


    

    



?>
