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
 *  @file TarifasPropagandasForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 16/08/2016
 * 
 *  @class TarifasPropagandasForm
 *  @brief Clase que contiene las rules para validacion del catalogo de las propagandas
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  scenarios
 *  search
 *
 *  
 *
 *  @inherits
 *  
 */ 
namespace common\models\propaganda\tarifaspropaganda;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\propaganda\tarifaspropaganda\TarifasPropagandas;



class TarifasPropagandasForm extends TarifasPropagandas
{



    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        // return [

        //     [['causas', 'observacion'], 'required'],
        // ]; 
    } 

    /**
     * @inheritdoc
     */
   

    
    

public function searchTarifasPropagandas()
    { 
       // die('llegue a search');

       


        $query = TarifasPropagandas::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->all();
         
        return $dataProvider;

       
    }

 

    

  
}
