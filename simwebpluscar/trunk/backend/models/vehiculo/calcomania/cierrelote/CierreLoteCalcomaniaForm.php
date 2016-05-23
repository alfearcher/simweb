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
 *  @file CierreLoteForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 20/05/2016
 * 
 *  @class CierreLoteForm
 *  @brief Clase que contiene las rules para la verificacion del cierre de lote de calcomanias
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
namespace backend\models\vehiculo\calcomania\cierrelote;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\calcomania\calcomaniamodelo\Calcomania;


/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class CierreLoteCalcomaniaForm extends Model
{
    public $ano_impositivo;
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ['ano_impositivo', 'required'],
        ['ano_impositivo', 'integer'],
        ['ano_impositivo', 'validarLongitud'],       
        ]; 
    } 

    public function validarLongitud($attribute, $params)
    {
      

        $longitud = strlen($this->ano_impositivo);

          if ($longitud > 4 ){
            $this->addError($attribute, Yii::t('frontend', 'Año Impositivo must not have more than 4 characters'));
          } else if ($longitud < 4 ){ 
            $this->addError($attribute, Yii::t('frontend', 'Año Impositivo must not have less than 4 characters'));
          }
    
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
                'ano_impositivo' => Yii::t('backend', 'Año Impositivo'), 
                
                
        ];
    }

    public function buscarCalcomania($model)
    { 
  
        $query = Calcomania::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        'pagination' => [
        'pagesize' => 100,
        ],   
        ]);
        $query->where([
            'ano_impositivo' => $model->ano_impositivo,
            'estatus' => 0,
            'entregado' => 0,
            ])
      
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
