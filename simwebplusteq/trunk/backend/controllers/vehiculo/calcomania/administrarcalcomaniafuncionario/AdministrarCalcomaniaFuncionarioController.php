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
 *  @file AdministrarCalcomaniaFuncionarioController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 03/05/2016
 * 
 *  @class AdministrarCalcomaniaFuncionarioController
 *  @brief Controlador que renderiza la vista para realizar la asignacion de calcomanias a los funcionarios.
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

namespace backend\controllers\vehiculo\calcomania\administrarcalcomaniafuncionario;

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
use backend\models\vehiculo\calcomania\administrarcalcomaniafuncionario\AdministrarCalcomaniaFuncionarioForm;
use backend\models\vehiculo\calcomania\deshabilitarfuncionario\FuncionarioSearch;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;
use yii\data\ArrayDataProvider;
use common\models\calcomania\calcomaniamodelo\Calcomania;
/**
 * Site controller
 */

session_start();





class AdministrarCalcomaniaFuncionarioController extends Controller
{



    
  public $layout = 'layout-main';

  public function actionSeleccionarFuncionario()
  {
      // die('llegue a lote calcomania');
    
      if(isset(yii::$app->user->identity->id_user)){
          
          $searchModel = new FuncionarioSearch();

          $dataProvider = $searchModel->search();
         

          return $this->render('/vehiculo/calcomania/administrarcalcomaniafuncionario/seleccionar-funcionario', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                ]); 
      }else{
          echo "No existe User";
      }
  }

  public function actionVerificarFuncionario()
  {
    $idFuncionario = yii::$app->request->post('id');
    $_SESSION['idFuncionario'] = $idFuncionario;

        $buscarFuncionario = new AdministrarCalcomaniaFuncionarioForm();

            $busqueda = $buscarFuncionario->buscarLogin($idFuncionario);

                if($busqueda == true){
                  $_SESSION['datosFuncionario'] = $busqueda;
                  //die($_SESSION['datosFuncionario']);
                }

        return $this->redirect(['busqueda-lote']);
  }
   
    /**
     * [actionBusquedaLote description] Metodo que realiza la busqueda de los lotes de calcomanias activas.
     *
     */
    public function actionBusquedaLote()
    {
    die('llegue a lote calcomania');
    
      if(isset(yii::$app->user->identity->id_user)){
          
          $searchModel = new AdministrarCalcomaniaFuncionarioForm();

          $dataProvider = $searchModel->search();
         

          return $this->render('/vehiculo/calcomania/administrarcalcomaniafuncionario/seleccionar-lote-calcomania', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                                ]); 
      }else{
          echo "No existe User";
      }
             
    }
    
    
    public function actionVerificarLote()
    {
      $idLote = yii::$app->request->post('id');
      $_SESSION['idLote'] = $idLote;

        return $this->redirect(['seleccionar-calcomania']);
    }

    public function actionSeleccionarCalcomania($errorCheck = "")
    {
          $buscar = new AdministrarCalcomaniaFuncionarioForm();
          $anoImpositivo = date('Y');
          $idLote = $_SESSION['idLote'];
          $busquedaLote = LoteSearch::find()
                                      ->where([
                                      'id_lote_calcomania' => $idLote,
                                      ])
                                      ->all();

          $rangoInicial = $busquedaLote[0]->rango_inicial;
          $rangoFinal = $busquedaLote[0]->rango_final;

          $rango = range($rangoInicial,$rangoFinal);
          $prueba = [];
          foreach($rango as $key=>$value){
             $buscarCalcomania = $buscar->busquedaCalcomaniaAsignada($anoImpositivo, $value);

                if($buscarCalcomania == false){
                    $prueba[$value] = ['id' => $value, 'Calcomania' => $value];
                } 

          }
          //$p = array_values($prueba);
         // die(var_dump($prueba));
        


        $provider = new ArrayDataProvider([
            'allModels' => $prueba,
            
            'sort' => [
                 
            'attributes' => ['id', 'Calcomania'],
            
            ],
          
            'pagination' => [
            'pageSize' => 1000,
            ],
        ]);

              return $this->render('/vehiculo/calcomania/administrarcalcomaniafuncionario/seleccionar-calcomania', [
                                                                                            'provider' => $provider,
                                                                                            'errorCheck' => $errorCheck,
                                                                                            ]);   
    }

    public function actionVerificarCalcomania()
    {
    $errorCheck = ""; 

    $idCalcomanias = yii::$app->request->post('chk-seleccionar-calcomania');
    // die(var_dump($idCalcomanias));
    $_SESSION['idCalcomanias'] = $idCalcomanias;

  
      $validacion = new AdministrarCalcomaniaFuncionarioForm();

       if ($validacion->validarCheck(yii::$app->request->post('chk-seleccionar-calcomania')) == true){
        
        return $this->redirect(['verificar-calcomania-asignada']);
           
        }else{
          $errorCheck = "Please select a Sticker";
          return $this->redirect(['seleccionar-calcomania' , 'errorCheck' => $errorCheck]); 

                                                                                             
       }
    }

    public function actionVerificarCalcomaniaAsignada()
    {
      
        $idCalcomania = $_SESSION['idCalcomanias'];

        $administrarCalcomania = new AdministrarCalcomaniaFuncionarioForm();

        $buscar = $administrarCalcomania->buscarCalcomania($idCalcomania);

            if($buscar == true){
              return MensajeController::actionMensaje(995);
            }else{
              $guardar = self::beginSave("guardar", $idCalcomania);

                if($guardar == true){
                  return MensajeController::actionMensaje(100);
                }else{
                  return MensajeController::actionMensaje(920);
                }
            }
    }
    

    public function guardarCalcomanias($conn, $conexion, $value)
    {
      $funcionarioAsignado = $_SESSION['datosFuncionario'][0]->login;
      $idFuncionario = $_SESSION['datosFuncionario'][0]->id_funcionario;
      //die($idFuncionario);
      $Calcomania =$value;  
      $idUser = yii::$app->user->identity->id_user;
      $datos = yii::$app->user->identity;
      $tabla = 'calcomanias';
      $arregloDatos = [];
      $arregloCampo = AdministrarCalcomaniaFuncionarioForm::attributeCalcomania();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['nro_calcomania'] = $Calcomania;
      
      $arregloDatos['fecha_creacion_lote'] = date('Y-m-d h:m:i');
      
      $arregloDatos['ano_impositivo'] = date('Y');

      $arregloDatos['usuario_creacion_lote'] = $datos->username;

      $arregloDatos['entregado'] = 0;

      $arregloDatos['estatus'] = 0;

      $arregloDatos['usuario_funcionario'] = $funcionarioAsignado;

      $arregloDatos['id_funcionario'] = $idFuncionario;

          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

              return true;
          }

    }

    /**
     * [beginSave description] metodo que realiza el guardado de las calcomanias asignadas en la tabla calcomanias
     * @param  [type] $var [description] variable que recibe en forma de string para comenzar el guardado
     * @return [type]      [description] retorna true si el commit se realiza y false si hay un roll back
     */
    public function beginSave($var, $idCalcomania)
    {
      $todoBien = true;

      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if($var == "guardar"){
           
              foreach($idCalcomania as $key=>$value)
              { 
              $guardar = self::guardarCalcomanias($conn, $conexion, $value);
              $todoBien == true;

              }

                  if ($todoBien == true){

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
