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

        return $this->redirect(['busqueda-lote']);
  }
   
    /**
     * [actionBusquedaLote description] Metodo que realiza la busqueda de los lotes de calcomanias activas.
     *
     */
    public function actionBusquedaLote()
    {
    // die('llegue a lote calcomania');
    
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

    public function actionSeleccionarCalcomania()
    {

          $idLote = $_SESSION['idLote'];

          $busquedaLote = LoteSearch::find()
                                      ->where([
                                      'id_lote_calcomania' => $idLote,
                                      ])
                                      ->all();

          $rangoInicial = $busquedaLote[0]->rango_inicial;
          $rangoFinal = $busquedaLote[0]->rango_final;

          $rango = range($rangoInicial,$rangoFinal);

          foreach($rango as $key=>$value){
           
              $hola[] = $value;

          }
          //die(var_dump($hola));


        $provider = new ArrayDataProvider([
            'allModels' => $hola,
            'sort' => [
       
            ],
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

              return $this->render('/vehiculo/calcomania/administrarcalcomaniafuncionario/seleccionar-calcomania', [
                                                                                            'provider' => $provider,
                                                                                            ]);   
    }
    

    public function guardarFuncionario($conn, $conexion)
    {
      $idUser = yii::$app->user->identity->id_user;
      $datosFuncionario = $_SESSION['datosFuncionario'];
      $resultado = false;
      $datos = yii::$app->user->identity;
      $tabla = 'funcionario_calcomania';
      $arregloDatos = [];
      $arregloCampo = BusquedaFuncionarioForm::attributeFuncionarioCalcomania();

      foreach ($arregloCampo as $key=>$value){

          $arregloDatos[$value] =0;
      }

      $arregloDatos['id_funcionario'] = $datosFuncionario[0]->id_funcionario;
      //die($arregloDatos['id_funcionario']);
      
      $arregloDatos['estatus'] = 0;
      
      $arregloDatos['usuario'] = $idUser;
      
      $arregloDatos['fecha_hora'] = date('Y-m-d h:m:i');


          if ($conexion->guardarRegistro($conn, $tabla, $arregloDatos )){

          return true;
          }

    }

    /**
     * [beginSave description] metodo que realiza el guardado del funcionario en la tabla funcionario_calcomania
     * @param  [type] $var [description] variable que recibe en forma de string para comenzar el guardado
     * @return [type]      [description] retorna true si el commit se realiza y false si hay un roll back
     */
    public function beginSave($var)
    {
      //die('llegue a beginsave');
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if($var == "guardarFuncionario"){
            //die('llegue a var');

              $buscar = self::guardarFuncionario($conn, $conexion);

                  if ($buscar == true){

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
