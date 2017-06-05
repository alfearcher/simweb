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
 *  @file DeshabilitarLoteForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 27/04/2016
 * 
 *  @class DeshabilitarLoteForm
 *  @brief Clase que contiene las rules para la deshabilitacion de los lotes de calcomania
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
namespace backend\models\vehiculo\calcomania\deshabilitarlote;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;



/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class DeshabilitarLoteForm extends Model
{
    public $causa;
    public $observacion;
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['causa', 'observacion'], 'required'],
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
     * [search description] metodo que realiza la busqueda en la tabla lote_calcomania
     * @return [type] [description] devuelve el modelo de la tabla si lo consigue, sino devuelve false
     */
    
    public function search()
    { 
  
        $query = LoteSearch::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
            'ano_impositivo' => date('Y'),
            'inactivo' => 0,
            ])
  
        ->all();
       // die(var_dump($query));

        
        return $dataProvider;

       
    }

    public function buscarCalcomania($id)
    {
        
     
        $busquedaCalcomania = LoteSearch::find()
                                        ->where([
                                        'id_lote_calcomania' => $id,
                                        'inactivo' => 0,

                                            ])
                                        ->all();

            if($busquedaCalcomania == true){
                return $busquedaCalcomania;
            }else{
                return false;
            }
    }

    



   
}
