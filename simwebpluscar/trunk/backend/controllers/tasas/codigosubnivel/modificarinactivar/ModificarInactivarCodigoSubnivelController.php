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
 *  @file ModificarInactivarCodigoSubnivelController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/16
 * 
 *  @class ModificarInactivarCodigoSubnivelController
 *  @brief Controlador que contiene los metodos para la modificacion e inactivacion de los grupos subniveles
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

namespace backend\controllers\tasas\codigosubnivel\modificarinactivar;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\BusquedaCodigoMultipleForm;
use backend\models\tasas\modificarinactivar\ModificarInactivarTasasForm;
use backend\models\tasas\codigosubnivel\modificarinactivar\ModificarInactivarCodigoSubnivelForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ModificarInactivarCodigoSubnivelController extends Controller
{


  
  public $layout = 'layout-main';


  /**
   * [modificarCodigoSubnivel description] metodo que redirecciona hacia otros metodos para la modificacion del grupo subnivel
   * @return [type] [description] retorna el formulario para la modificacion
   */
  public function actionModificarCodigoSubnivel(){

      $model = new ModificarInactivarCodigoSubnivelForm();

          $dataProvider = $model->busquedaGrupoSubnivel();

              return $this->render('/tasas/codigosubnivel/modificarinactivar/view-modificar-inactivar-grupo-subivel', [

                                            'dataProvider' => $dataProvider,

                ]);
  }
   
    /**
   * [verificarGrupoSubnivelModificar description] metodo que verifica el id del grupo subnivel y redirecciona a otro metodo para su modificacion
   * @return [type] [description] redirecciona a otro metodo para la modificacion del grupo subnivel
   */
  public function actionVerificarGrupoSubnivelModificar(){

      $idGrupoSubnivel = yii::$app->request->get('value');
     // die($idGrupoSubnivel);
          $_SESSION['idGrupo'] = $idGrupoSubnivel;

              return $this->redirect(['modificar-grupo-subnivel']);
  }



  /**
   * [actionModificarGrupoSubnivel description] metodo que renderiza la vista con el formulario para modificar la descripcion del grupo subnivel
   * @return [type] [description] renderiza el formulario con la vista de modificacion
   */
  public function actionModificarGrupoSubnivel()
  {

          
            $postData = yii::$app->request->post();

            $model = New ModificarInactivarCodigoSubnivelForm();

            
            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }
           
            if ( $model->load($postData) ) {
             // die(var_dump($postData));
            

               if ($model->validate()){
               
                  $modificar = self::beginSave("modificar" , $model);

                      if($modificar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }

                
              }
          }  
          
            return $this->render('/tasas/codigosubnivel/modificarinactivar/view-modificar-grupo-subnivel', [
                                                              'model' => $model,
                                                             
                                                             
            ]);
  

  }
  /**
   * [actionInactivarGrupoSubnivel description] metodo que inactiva el grupo subnivel seleccionado
   * @return [type] [description] retorna true o false
   */
  public function actionInactivarGrupoSubnivel(){

      $idGrupoSubnivel = yii::$app->request->get('value');

          $_SESSION['idGrupo'] = $idGrupoSubnivel;

              $inactivar = self::beginSave("inactivar" , 0);

                  if($inactivar == true){
                          return MensajeController::actionMensaje(200);
                      }else{
                          return MensajeController::actionMensaje(920);
                      }
  }


 /**
  * [modificarGrupoSubnivel description] metodo que realiza el update en la tabla grupos_subniveles con la informacion enviada desde el modelo
  * @param  [type] $conn     [description] parametro de conexion
  * @param  [type] $conexion [description] parametro de conexion
  * @param  [type] $model    [description] modelo que contiene los datos
  * @return [type]           [description] retorna true si modifica y false si no.
  */
    public function modificarGrupoSubnivel($conn, $conexion, $model)
    {
       $idGrupoSubnivel = $_SESSION['idGrupo'];

      
        $tableName = 'grupos_subniveles';
        
        $arregloCondition = ['grupo_subnivel' => $idGrupoSubnivel];
     
        
        $arregloDatos['descripcion'] = $model->descripcion;

      
          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

    /**
     * [inactivarGrupoSubnivel description] metodo que realiza el update de la tabla grupo_subnivel e inactiva el grupo, dejandolo en estatus 1
     * @param  [type] $conn     [description] parametro de conexion
     * @param  [type] $conexion [description] parametro de conexion
     * @return [type]           [description] retorna true si modifica y false si no.
     */
    public function inactivarGrupoSubnivel($conn, $conexion)
    {
       $idGrupoSubnivel = $_SESSION['idGrupo'];

      
        $tableName = 'grupos_subniveles';
        
        $arregloCondition = ['grupo_subnivel' => $idGrupoSubnivel];
     
        $arregloDatos['inactivo'] = 1;


         

          $conexion = new ConexionController();

          $conn = $conexion->initConectar('db');
             
          $conn->open();

      

                if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

             

                    return true;
              
                }

    }

   
    /**
     * [beginSave description] metodo padre que direcciona hacia los metodos de modificacion o inactivacion de grupos subniveles
     * @param  [type] $var   [description] variable tipo string que sirve para el redireccionamiento de metodos de inactivacion o modificacion
     * @param  [type] $model [description] datos enviados desde los formularios
     * @return [type]        [description] retorna true si el proceso se cumple y false si no se cumple
     */
    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "modificar"){
            

              $modificar = self::modificarGrupoSubnivel($conn, $conexion, $model);

             
              if ($modificar == true){

                

                    $transaccion->commit();
                    $conn->close();

                     
                    return true;


              }else{

                    $transaccion->rollback();
                    $conn->close();
                    return false;
              }
                  
                 
          }else if($var == "inactivar"){

               $inactivar = self::actionInactivarGrupoSubnivel($conn, $conexion);

             
              if ($inactivar == true){

                

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
