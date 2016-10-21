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
 *  @file ReportesTasasController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/16
 * 
 *  @class ReportesTasasController
 *  @brief Controlador que contiene los metodos para la verificacion de reporte de las tasas
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

namespace backend\controllers\tasas\reportes;

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
use backend\models\tasas\reportes\BusquedaMultipleReportesForm;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class ReportesTasasController extends Controller
{

    const SCENARIO_SEARCH_ANO = 'search_ano';
    const SCENARIO_SEARCH_ANO_IMPUESTO = 'search_ano_impuesto';
    const SCENARIO_SEARCH_ANO_IMPUESTO_CODIGO = 'search_ano_impuesto_codigo';
  
  public $layout = 'layout-main';
   
 /**
  * [actionBusquedaMultipleReportes description] metodo que renderiza el formulario para la busqueda multiple de reportes
  * @return [type] [description] renderiza el formulario para realizar la busqueda
  */
  public function actionBusquedaMultipleReportes()
  { //die('llegue');
  $post = yii::$app->request->post();
         // die(var_dump($post));
          $model = new BusquedaMultipleReportesForm();
          
          if(isset($post['btn-busqueda-ano'])){ 
              $model->scenario = self::SCENARIO_SEARCH_ANO;
          
          }elseif(isset($post['btn-busqueda-ano-impuesto'])){
              $model->scenario = self::SCENARIO_SEARCH_ANO_IMPUESTO;
          
          }elseif(isset($post['btn-busqueda-ano-impuesto-codigo'])){
              $model->scenario = self::SCENARIO_SEARCH_ANO_IMPUESTO_CODIGO;
         
          }  

            $postData = Yii::$app->request->post();

            if ( $model->load($postData) && Yii::$app->request->isAjax ){
                  Yii::$app->response->format = Response::FORMAT_JSON;
                  return ActiveForm::validate($model);
            }

            if ( $model->load($postData) ) {


          if(isset($post['btn-busqueda-ano'])){ 
              if ($model->validate()){
               //die(var_dump($model->nivel_contable));
                  $_SESSION['datosAno'] = $model;
                  return $this->redirect(['buscar-reporte-ano']);
 
                  
              }

          }elseif(isset($post['btn-busqueda-ano-impuesto'])){
              if ($model->validate()){
                 $_SESSION['datosAnoImpuesto'] = $model;

                return $this->redirect(['buscar-reporte-ano-impuesto']);
 
              }
        
             

               
          }elseif(isset($post['btn-busqueda-ano-impuesto-codigo'])){
              if($model->validate()){

                $_SESSION['datosAnoImpuestoCodigo'] = $model;
                return $this->redirect(['buscar-reporte-ano-impuesto-codigo']);
 
              }


          }

         }
            
            return $this->render('/tasas/reportes/busqueda-multiple-reportes', [
                                                              'model' => $model,
                                                             
                                                           
            ]);
  
  
  }







 /**
  * [actionBuscarReporteAno description] metodo que renderiza un dataprovider con la informacion de la tasa buscada 
  * @return [type]        [description] retorna el formulario
  */
  public function  actionBuscarReporteAno()
  {
      $anoImpositivo = $_SESSION['datosAno']->ano_impositivo;
    
      $model = new BusquedaMultipleReportesForm();

          $dataProvider = $model->busquedaReporteAno($anoImpositivo);

          return $this->render('/tasas/reportes/mostrar-reportes',[ 
                                  'dataProvider' => $dataProvider,
                                  

            ]);
  }

   /**
  * [actionBuscarReporteAnoImpuesto description] metodo que renderiza un dataprovider con la informacion de la tasa buscada 
  * @return [type]        [description] retorna el formulario
  */
  public function  actionBuscarReporteAnoImpuesto()
  {
      
      $anoImpositivo = $_SESSION['datosAnoImpuesto']->ano_impositivo2;
      $impuesto = $_SESSION['datosAnoImpuesto']->impuesto;
      $model = new BusquedaMultipleReportesForm();

          $dataProvider = $model->busquedaReporteAnoImpuesto($anoImpositivo, $impuesto);

          return $this->render('/tasas/reportes/mostrar-reportes',[ 
                                  'dataProvider' => $dataProvider,
                                  

            ]);
  }
   /**
  * [actionBuscarReporteAnoImpuestoCodigo description] metodo que renderiza un dataprovider con la informacion de la tasa buscada 
  * @return [type]        [description] retorna el formulario
  */
  public function  actionBuscarReporteAnoImpuestoCodigo()
  {
      $anoImpositivo = $_SESSION['datosAnoImpuestoCodigo']->ano_impositivo3; 
      $impuesto = $_SESSION['datosAnoImpuestoCodigo']->impuesto2;
      $codigo = $_SESSION['datosAnoImpuestoCodigo']->codigo;
    
      $model = new BusquedaMultipleReportesForm();

          $dataProvider = $model->busquedaReporteAnoImpuestoCodigo($anoImpositivo, $impuesto, $codigo);

          return $this->render('/tasas/reportes/mostrar-reportes',[ 
                                  'dataProvider' => $dataProvider,
                                  

            ]);
  }






 
   
    
 


}



    



    

   


    

    



?>
