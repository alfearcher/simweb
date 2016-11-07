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
 *  @file DeudasContribuyenteController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 25/10/16
 * 
 *  @class DeudasContribuyenteController
 *  @brief Controlador que renderiza la vista el detalle de las deudas de un contribuyente
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

namespace backend\controllers\deudas\deudascontribuyente;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\cargarpresupuesto\registrar\CargarPresupuestoForm;
use common\models\deuda\DeudaSearch;
use backend\models\deudas\deudascontribuyente\DeudasContribuyenteForm;
use yii\data\ArrayDataProvider;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class DeudasContribuyenteController extends Controller
{
  
  public $layout = 'layout-main';

  /**
   * [actionVerificarDeudasContribuyente description] metodo que renderiza las vistas con las deudas del contribuyente
   * @return [type] [description] renderizacion de vista con deudas del contribuyente
   */
  public function actionVerificarDeudasContribuyente()
  { 
      

      if (isset($_SESSION['idContribuyente'])){ 
        
        $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);

      $dataProvider = $model->getDeudaGeneralPorImpuesto();
     // die(var_dump($dataProvider));  
      foreach($dataProvider as $key=>$value){

    
      

        $array[] = [
          'impuesto' => $value['impuesto'],
          'descripcion' => $value['descripcion'],
          'monto' => $value['t'],
        ]; 

      }

     


      //die(var_dump($st));
     // die(var_dump($array));

     
        $dataProvider = new ArrayDataProvider([
            'allModels' => $array,
           // 'Models' => $st,
            'sort' => [
                 
            
            
            ],
          
            
        ]);

          return $this->render('/deudas/deudascontribuyente/view-deuda-general', [
            'dataProvider' => $dataProvider,
          

            ]);
      
      }else{

        return MensajeController::actionMensaje(938);

     }
  
  }

/**
 * [actionVerificarImpuesto description] metodo que recibe el impuesto y determina que vista renderizara
 * @param  [type] $searchRecibo [description]
 * @param  [type] $postJson     [description]
 * @return [type]               [description]
 */
  public function actionVerificarImpuesto()
  {

      $variables = yii::$app->request->post('id');
      $postJson = $variables;

    $html = null;
      //$impuestos = [2,3];
      // Lo siguiente crea un objeto json.
      $jsonObj = json_decode($postJson);
      //die(var_dump($jsonObj));

      if ( $jsonObj->{'impuesto'} == 9 ) {
       // die('es 9');
        $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 10 ) {
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 11 ) {
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 1 ) {
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});
      
     } elseif ( $jsonObj->{'impuesto'} == 3 ) {
     // die('bueno');
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaVehiculo($jsonObj->{'impuesto'});
     
     } elseif ( $jsonObj->{'impuesto'} == 2 ) {
     // die('bueno');
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaVehiculo($jsonObj->{'impuesto'});
     } elseif ( $jsonObj->{'impuesto'} == 12 ) {
     // die('bueno');
       
          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaVehiculo($jsonObj->{'impuesto'});
     } 


    
      return $html;
      
  }

    /**
     * [actionGetViewDeudaTasa description] metodo que renderiza la vista de la deuda especifica de las tasas
     * @param  [type] $impuesto [description] impuesto de la tasa
     * @return [type]           [description] retorna la vista
     */
    public function actionGetViewDeudaTasa($impuesto)
    { 

      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento

      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda segun Impuesto');
     
      $provider = $model->getDetalleDeudaTasa($impuesto);
    // die(var_dump($provider));
          foreach($provider as $key=>$value){

              $monto = ($value['monto'] + $value['recargo'] + $value['interes']) - ($value['descuento'] - $value['monto_reconocimiento']);

      

              $array[] = [
              'planilla' => $value['pagos']['planilla'],
              'impuesto' => $value['tasa']['descripcion'],
              'ano_impositivo' => $value['ano_impositivo'],
              'periodo' => $value['trimestre'],
              'unidad' => $value['exigibilidad']['unidad'],
              'monto' => $monto,
              ]; 

          }

            
              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [
                 
            
            
                  ],
          
            
              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-especifica-tasa', [
                'dataProvider' => $dataProvider,
          

              ]);
    }

    /**
     * [actionGetViewDeudaVehiculo description] metodo que renderiza la vista con la deuda del vehiculo general
     * @param  [type] $impuesto [description] impuesto del vehiculo
     * @return [type]           [description] retorna la vista con la deuda
     */
    public function actionGetViewDeudaVehiculo($impuesto)
    { 

      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento

      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda segun Impuesto');
    
      $provider = $model->getDeudaPorImpuestoPeriodo($impuesto);
     //die(var_dump($provider));
          foreach($provider as $key=>$value){

             

      

              $array[] = [
              'impuesto' => $value['impuesto'],
              'descripcion' => $value['descripcion'],
              'monto' => $value['t'],
              'tipo' => $value['tipo'],
              ]; 

          }

            
              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [
                 
            
            
                  ],
          
            
              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-general-vehiculo', [
                'dataProvider' => $dataProvider,
          

              ]);
    }



  

 

   
   
 


}



    



    

   


    

    



?>
