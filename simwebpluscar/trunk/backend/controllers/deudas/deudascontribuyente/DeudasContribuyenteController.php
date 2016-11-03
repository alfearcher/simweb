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
          
            'pagination' => [
            'pageSize' => 10,
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
   * [actionVerificarImpuesto description] metodo para verificar el impuesto enviado y verificar las deudas mas especificas
   * @return [type] [description]
   */
  public function actionVerificarImpuesto()
  {

      $idContribuyente = $_SESSION['idContribuyente'];
      $impuesto = yii::$app->request->post('id');

          $model = new DeudaSearch($idContribuyente);
          
      $dataProvider = $model->getDeudaGeneralPorImpuesto();
      die(var_dump($dataProvider));
      foreach($dataProvider as $key=>$value){

        $suma1 = $value['tmonto'] + $value['trecargo'] + $value['tinteres'] + $suma1;
        $suma2 = $value['tdescuento'] + $value['tmonto_reconocimiento'] + $suma2;
        $st[$key] = $suma1-$suma2; 
        $total = $st[$key] + $total;
      

        $array[] = [
          'impuesto' => $value['impuesto'],
          'descripcion' => $value['descripcion'],
          'monto' => $st[$key],
        ]; 

      }

     


      //die(var_dump($st));
     // die(var_dump($total));

     
        $dataProvider = new ArrayDataProvider([
            'allModels' => $array,
           // 'Models' => $st,
            'sort' => [
                 
            
            
            ],
          
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

          return $this->render('/deudas/deudascontribuyente/view-deuda-general', [
            'dataProvider' => $dataProvider,
          

            ]);
  }

 

   
    /**
     * [beginSave description] metodo padre de guardado que redirecciona hacia otros metodos encargados de finalizar el guardado
     * @param  [type] $var   [description] variable tipo string para la redireccion
     * @param  [type] $model [description] informacion enviada desde el form
     * @return [type]        [description] retorna true o false
     */
    public function beginSave($var, $model)
    {
     //die('llegue a begin'.var_dump($model));
      $conexion = new ConexionController();

      $conn = $conexion->initConectar('db');

      $conn->open();

      $transaccion = $conn->beginTransaction();

          if ($var == "guardar"){
            
           // die('llegue a guardar');
              $guardar = self::guardarDetallePresupuesto($conn, $conexion, $model);

             
              if ($guardar == true){

                

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
