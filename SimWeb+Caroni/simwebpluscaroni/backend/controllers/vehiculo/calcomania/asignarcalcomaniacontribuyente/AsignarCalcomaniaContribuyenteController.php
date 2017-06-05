<?php

/**
 * 
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
use backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente\BusquedaJuridicoForm;
use common\models\contribuyente\ContribuyenteBase;
use backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente\VerificarTransaccionForm;
/**
 * Site controller
 */

session_start();





class AsignarCalcomaniaContribuyenteController extends Controller
{

   const SCENARIO_SEARCH_NATURAL = 'search_natural';
   const SCENARIO_SEARCH_ID = 'search_id';
   const SCENARIO_SEARCH_ID_JURIDICO = 'search_id_juridico';
   const SCENARIO_SEARCH_JURIDICO = 'search_juridico';


    
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
        //die(var_dump($postData));
          if(isset($postData['btn-busqueda-natural'])){ 
              $model->scenario = self::SCENARIO_SEARCH_NATURAL;
          }elseif(isset($postData['btn-busqueda-id'])){
              $model->scenario = self::SCENARIO_SEARCH_ID;
          }

           

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {


              if(isset($postData['btn-busqueda-natural'])){ 
              
                if ($model->validate()){
                 
                 $buscarNatural = self::buscarNatural($model);
                      
                      if($buscarNatural == true){
                        $_SESSION['datos'] = $buscarNatural;
                        
                         return $this->redirect(['buscar-vehiculo']);
                  
                      }else{
                        return MensajeController::actionMensaje(990); 
                      }  
                    

                    }

              }elseif(isset($postData['btn-busqueda-id'])){
                  if ($model->validate()){
                  
                      $buscarId = self::buscarId($model);
                      
                      if($buscarId == true){
                        $_SESSION['datos'] = $buscarId;
                        
                         return $this->redirect(['buscar-vehiculo']);
                  
                      }else{
                        return MensajeController::actionMensaje(990); 
                      }  
                  }

              }
                    
            }       
                
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/busqueda-natural', [
                                                              'model' => $model,

                                                              
            ]);
  }



  public function actionBusquedaJuridico()
  {
      $model = new BusquedaJuridicoForm();
        $postData = Yii::$app->request->post();
        //die(var_dump($postData));
          if(isset($postData['btn-busqueda-juridico'])){ 
              $model->scenario = self::SCENARIO_SEARCH_JURIDICO;
          }elseif(isset($postData['btn-busqueda-id-juridico'])){
              $model->scenario = self::SCENARIO_SEARCH_ID_JURIDICO;
          }

           

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {


              if(isset($postData['btn-busqueda-juridico'])){ 
              
                if ($model->validate()){
                 
                 $buscarJuridico = self::buscarJuridico($model);
                      
                      if($buscarJuridico == true){
                        $_SESSION['datos'] = $buscarJuridico;
                        
                         return $this->redirect(['buscar-vehiculo']);
                  
                      }else{
                        return MensajeController::actionMensaje(990); 
                      }  
                    

                }

              }elseif(isset($postData['btn-busqueda-id-juridico'])){
                  if ($model->validate()){
                  
                      $buscarIdJuridico = self::buscarIdJuridico($model);
                      
                      if($buscarIdJuridico == true){
                        $_SESSION['datos'] = $buscarIdJuridico;
                        
                         return $this->redirect(['buscar-vehiculo']);
                  
                      }else{
                        return MensajeController::actionMensaje(990); 
                      }  
                  }

              }
                    
            }       
                
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/busqueda-juridico', [
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

  public function buscarJuridico($model)
  {
      $buscar = new BusquedaJuridicoForm();

        $buscarJuridico = $buscar->buscarJuridico($model);

             if($buscarJuridico ==  true){
               return $buscarJuridico;
             }else{
               return false;
            }
  }

  public function buscarId($model)
  {
      $buscar = new BusquedaNaturalForm();

        $buscarId = $buscar->buscarId($model);

             if($buscarId ==  true){
               return $buscarId;
             }else{
               return false;
            }
  }

    public function buscarIdJuridico($model)
  {
      $buscar = new BusquedaJuridicoForm();

        $buscarIdJuridico = $buscar->buscarIdJuridico($model);

             if($buscarIdJuridico ==  true){
               return $buscarIdJuridico;
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
      ($idCalcomanias);
      $_SESSION['idCalcomania'] = $idCalcomanias;

      return $this->redirect(['mostrar-datos']);

       
            
  }

  public function actionMostrarDatos()
  { 
  
  $idCalcomania = $_SESSION['idCalcomania'];
  $datos = $_SESSION['datosVehiculo'];

        $model = new VerificarTransaccionForm();

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {
             

               if ($model->validate()){

                     $guardarActualizar = self::beginSave("guardarActualizar");

                     if($guardarActualizar == true){
                        return MensajeController::actionMensaje(100);  
                     }else{
                        return MensajeController::actionMensaje(920);  
                     }
                }
            }
            
            return $this->render('/vehiculo/calcomania/asignarcalcomaniacontribuyente/verificar-transaccion', [
                                                              'model' => $model,
                                                              'datos' => $datos,
                                                              'idCalcomania' => $idCalcomania,
                                                             
                                                           
            ]);
            

  }
 
  public function actualizarCalcomania($conn, $conexion)
  {
      $nroCalcomania = $_SESSION['idCalcomania'];
      $buscar = new BusquedaNaturalForm();
      $buscarIdCalcomania = $buscar->buscarIdCalcomania($nroCalcomania);

      if ($buscarIdCalcomania == true)
      {
       // die($buscarIdCalcomania[0]->id_calcomania);
        $idCalcomania = $buscarIdCalcomania[0]->id_calcomania;
      }else{
        return false;
      }

      
      //die($idCalcomania);
      $idVehiculo = $_SESSION['idVehiculo'];
      $idContribuyente = $_SESSION['datos']->id_contribuyente;
      $placa = $_SESSION['datosVehiculo'][0]->placa;
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

      //die('llegue a guardar');
      $datos = yii::$app->user->identity->username;
      $idContribuyente = $_SESSION['datos']->id_contribuyente;
      $idVehiculo = $_SESSION['idVehiculo'];
      $nroCalcomania = $_SESSION['idCalcomania'];
      $placa = $_SESSION['datosVehiculo'][0]->placa;


      $buscar = new BusquedaNaturalForm();
      $buscarPlaca = $buscar->buscarPlacaCalcomania($placa);
      //die($buscarPlaca);

         

      $tabla = 'calcomanias_entregadas';
      $arregloDatos = [];

      $arregloCampo = BusquedaNaturalForm::attributeLoteCalcomaniasEntregadas();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['id_contribuyente'] = $idContribuyente;
     // die($arregloDatos['id_contribuyente']);
     
      $arregloDatos['id_vehiculo'] = $idVehiculo;
      
      $arregloDatos['nro_calcomania'] = $nroCalcomania;

      $arregloDatos['ano_impositivo'] = date('Y');

      $arregloDatos['fecha_entrega'] = date('Y-m-d h:m:i');

      $arregloDatos['login'] = $datos;

      if($buscarPlaca == true){

      $arregloDatos['tipo_entrega'] = 'RENOVACION';
      
      }else{
      
      $arregloDatos['tipo_entrega'] = 'NUEVO';
      
      }

      $arregloDatos['status'] = 0;

      $arregloDatos['placa'] = $placa; 
      //die($arregloDatos['placa']);    

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

          return true;
          }

    }
  


  
  public function beginSave()
  {
   
  
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

        $conn->open();

        $transaccion = $conn->beginTransaction();

         
                
                $actualizarCalcomania = self::actualizarCalcomania($conn, $conexion);


                    if ($actualizarCalcomania == true ){
                      //die('actualizo');

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
