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
 *  @file CatalogoController.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 16/08/16
 * 
 *  @class CatalogoController
 *  @brief Controlador que renderiza la vista con el catalogo de las propagandas
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

namespace frontend\controllers\propaganda\catalogo;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\propaganda\tarifaspropaganda\TarifasPropagandasForm;




/**
 * Site controller
 */

session_start();




class CatalogoPropagandaController extends Controller
{



    
  public $layout = 'layout-main';
   
  /**
   * [actionVistaSeleccion description] Metodo que renderiza la vista para la seleccion de la o las propagandas a desincorporar
   * @param  string $errorCheck [description] Mensaje de error
   * @return [type]             [description] retorna el formulario de seleccion de las propagandas
   */
  public function actionVistaCatalogoPropaganda()
  {
    //die('llegue a la vista del catalogo');
    

          $searchModel = new TarifasPropagandasForm();

          $dataProvider = $searchModel->searchTarifasPropagandas();
       

          return $this->render('/propaganda/catalogo/vista-catalogo', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                              
                                                ]); 
   
  

    
  }

  
   
    

    

 
              
            
}
    



    

   


    

    



?>
