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
 *  @file GenerarLoteForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/04/2016
 * 
 *  @class GenerarLoteForm
 *  @brief Clase contiene las rules y metodos para validar el formulario para generacion de lote de calcomanias
 * 
 *  
 * 
 *  
 *  
 * @property
 *
 *  
 *  @method
 * rules
 * attributeLabels
 *
 *  
 *  @inherits
 *  
 */
namespace backend\models\vehiculo\calcomania\generarlote;

use Yii;
use yii\base\Model;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;
use yii\data\ActiveDataProvider;






class GenerarLoteForm extends Model
{


  public $ano_impositivo;
  public $rango_inicial;
  public $rango_final;
 




     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['rango_inicial', 'rango_final', 'ano_impositivo'],'required'],
             
            [['rango_inicial', 'rango_final'], 'integer'],
            
            ['rango_inicial', 'validarInicial'],
           
           
        ];
    } 

    
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'ano_impositivo' => Yii::t('backend', 'Año Impositivo'), 
                'rango_inicial' => Yii::t('backend', 'Rango Inicial'), 
                'rango_final' => Yii::t('backend', 'Rango Final'), 

        ];
    }
    /**
     * [attributeLoteCalcomania description] metodo que contiene los campos de la tabla lote_calcomania
     * @return [type] [description] retorna los campos de la tabla
     */
    public function attributeLoteCalcomania()
    {
        return [
            'ano_impositivo',
            'rango_inicial',
            'rango_final',
            'observacion',
            'causa',
            'inactivo',
            'usuario',
            'fecha_hora',
        ];
    }
    /**
     * [validarInicial description] metodo que valida que el rango inicial no sea mayor al rango final
     * @return [type] [description]
     */
    public function validarInicial($attribute, $params)
    {
        if ($this->rango_inicial > $this->rango_final){

            $this->addError($attribute, Yii::t('backend', 'Rango inicial can not be bigger than rango final' ));
        }else{
            return false;
        }
    }

 public function search()
    {
     

  
        $query = LoteSearch::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
           
            'inactivo' => 0,
            ])
  
        ->all();
       // die(var_dump($query));

        
        return $dataProvider;

     }

 


    
      
    
    
    
   

    }