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
use common\models\contribuyente\ContribuyenteBase;
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
                        $_SESSION['datos'] = $buscarNatural;
                        
                         return $this->redirect(['buscar-vehiculo']);

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

  public function actionBuscarVehiculo()
  {
    $model = $_SESSION['datos'];
       $searchModel = new BusquedaNaturalForm();

          $dataProvider = $searchModel->buscarVehiculo($model);
         

          return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-vehiculo', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                ]); 
  }

  public function actionVerificarVehiculo()
  {
    $idVehiculo = yii::$app->request->post('id');
    $_SESSION['idVehiculo'] = $idVehiculo;
    
        $buscar = new BusquedaNaturalForm();

            $buscarVehiculo = $buscar->buscarPlaca($idVehiculo);

            if($buscarVehiculo == true){

              $_SESSION['datosVehiculo'] = $buscarVehiculo;

                  $buscarCalcomania = $buscar->verificarCalcomaniaEntregada($idVehiculo);

                      if($buscarCalcomania == true){
                          return MensajeController::actionMensaje(997);  
                      }else{
                          return $this->redirect(['seleccionar-calcomania']);
                      }

            }
  }

  
  public function actionSeleccionarCalcomania()
  {
   
       

        $searchModel = new BusquedaNaturalForm();

            $dataProvider = $searchModel->searchRango();

            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/seleccionar-rango-calcomania', [
                                                                                  'searchModel' => $searchModel,
                                                                                  'dataProvider' => $dataProvider,
                                                                                

                                                                                          ]);
    
  }


  public function actionVerificarCalcomanias()
  {
  
      $idCalcomanias = yii::$app->request->post('id');
      $_SESSION['idCalcomania'] = $idCalcomanias;

        $guardarActualizar = self::beginSave("guardarActualizar");
            
  }
 
  public function actualizarCalcomania($conn, $conexion)
  {
      $idCalcomania = $_SESSION['idCalcomania'];
      $idVehiculo = $_SESSION['idVehiculo'];
      $idContribuyente = $_SESSION['datos']->id_contribuyente;
      $placa = $_SESSION['datosVehiculo']->placa;
      $tableName = 'calcomanias';
      $arregloCondition = ['id_calcomania' => $idCalcomania]; //id de la calcomania
      
     
      $arregloDatos['entregado'] = 1;
      $arregloDatos['fecha_entrega'] = date('Y-m-d h:m:i');
      $arregloDatos['id_vehiculo'] = $idVehiculo;
      $arregloDatos['id_contribuyente'] = $idContribuyente;
      $arregloDatos['placa'] = $placa;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');
         
      $conn->open();

            if ($conexion->modificarRegistro($conn, $tableName, $arregloDatos, $arregloCondition)){

            return true;
              
          }
         

  }

    public function guardarCalcomania($conn, $conexion)
    {
      $datos = yii::$app->user->identity;
      $resultado = false;
      $tabla = 'calcomanias_entregadas';
      $arregloDatos = [];
      $arregloCampo = BuscarNaturalForm::attributeLoteCalcomaniasEntregadas();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['ano_impositivo'] = $model->ano_impositivo;
     
      
      $arregloDatos['rango_inicial'] = $model->rango_inicial;
      
      $arregloDatos['rango_final'] = $model->rango_final;

      $arregloDatos['rango_final'] = $model->rango_final;

      $arregloDatos['inactivo'] = 0;

      $arregloDatos['usuario'] = $datos->email;
      //die($arregloDatos['usuario']);
      
      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

          return true;
          }

    }
  


  
  public function beginSave()
  {
    die('llego a begin');
  
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

         
                
                $actualizarCalcomania = self::actualizarCalcomania($conn, $conexion);


                    if ($actualizarCalcomania == true ){

                        $guardarCalcomania = self::guardarCalcomania($conn, $conexion);
                        
                            if($actualizarCalcomania and $guardarCalcomania == true){

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
