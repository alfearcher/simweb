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
 *  @file VehiculoSearch.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 08/03/2016
 * 
 *  @class VehiculoSearch
 *  @brief Clase que contiene las rules para validacion y contiene metodo que realiza la busqueda con un dataprovider para verificar
 *  la cantidad de vehiculos que tiene el contribuyente. 
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
namespace frontend\models\vehiculo\cambiodatos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;



/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class VehiculoSearch extends BusquedaVehiculos
{

    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search()
    { 
       // die('llegue a search');

        $idContribuyente = yii::$app->user->identity->id_contribuyente;


        $query = BusquedaVehiculos::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
            'id_contribuyente' =>  $idContribuyente,
            'status_vehiculo' => 0,
            ]);
        
        return $dataProvider;

       
    }

    public function BusquedaVehiculo($idVehiculo, $idContribuyente)
    {

        $buscarVehiculo = BusquedaVehiculos::find()
                                            ->where([
                                            'id_vehiculo' => $idVehiculo,
                                            'id_contribuyente' => $idContribuyente,
                                            'status_vehiculo' => 0,

                                                ])
                                            ->all();

                if ($buscarVehiculo == true){
                    
                    return $buscarVehiculo;
                }
    }
}
