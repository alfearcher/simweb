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
 *  @file FuncionarioSearch.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/04/2016
 * 
 *  @class FuncionarioSearch
 *  @brief Clase que contiene las rules para validacion y contiene metodo que realiza la busqueda con un dataprovider para verificar
 *  los funcionarios activos.
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
namespace backend\models\vehiculo\calcomania\deshabilitarfuncionario;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\funcionario\Funcionario;


/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class FuncionarioSearch extends Model
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
  
        $query = Funcionario::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
          
            'estatus' => 0,
            ])
        ->joinWith('funcionarioCalcomania')
        ->all();
       // die(var_dump($query));

        
        return $dataProvider;

       
    }

    public function validarCheck($postCheck)
    {
        //die($postCheck);
        
        if (count($postCheck) > 0){
            //die('lo selecciono');
            return true;
        }else{
            return false;
        }
    }

   
}
