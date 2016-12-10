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
 *  @date 30/05/2016
 * 
 *  @class CalcomaniaSearch
 *  @brief Clase que contiene las rules para validacion y contiene metodo que realiza la busqueda con un dataprovider para verificar
 *  la cantidad de calcomanias asignadas que tiene el contribuyente. 
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
namespace frontend\models\vehiculo\calcomania;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;


/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class CalcomaniaSearch extends Calcomania
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


        $query = Calcomania::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
            'id_contribuyente' =>  $idContribuyente,
            'estatus' => 0,
            'entregado' => 1,
            'ano_impositivo' => date('Y'),
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
