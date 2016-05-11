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
 *  @file BusquedaMultipleForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 10/05/2016
 * 
 *  @class BusquedaMultipleForm
 *  @brief Clase contiene las rules y metodos para la busqueda multiple de calcomanias para desactivarlas
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
namespace backend\models\vehiculo\calcomania\administrarlotecalcomania;

use Yii;
use yii\base\Model;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use yii\data\ActiveDataProvider;






class BusquedaMultipleForm extends Model
{



 
    public $ano_impositivo;
    public $id_funcionario;
    public $ano_impositivo2;
    public $nro_calcomania;


        const SCENARIO_SEARCH_FUNCIONARIO = 'search_funcionario';
        const SCENARIO_SEARCH_CALCOMANIA = 'search_calcomania';
        const SCENARIO_SEARCH_RANGO = 'search_rango';


    public function scenarios()
        {
            //bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH_FUNCIONARIO => [
                                'ano_impositivo',
                                'id_funcionario'
                ],
                self::SCENARIO_SEARCH_CALCOMANIA => [
                                'ano_impositivo2',
                                'nro_calcomania',
                ],
                self::SCENARIO_SEARCH_RANGO => [
                                '',
                                '',
                ],
               
            ];
        }
 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['ano_impositivo', 'id_funcionario'],
                  'required', 'on' => 'search_funcionario', 'message' => Yii::t('backend', '{attribute} is required')],
             
             [['ano_impositivo2', 'nro_calcomania'],
                  'required', 'on' => 'search_calcomania', 'message' => Yii::t('backend', '{attribute} is required')],
             ['nro_calcomania', 'integer'],
            
        ];
    } 

    
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'ano_impositivo' => Yii::t('backend', 'Año Impositivo'),
                'id_funcionario' => Yii::t('backend', 'Funcionario'), 
                'ano_impositivo2' => yii::t('backend', 'Año Impositivo'),
                'nro_calcomania' => yii::t('backend', 'Nro de Calcomania'),
        ];
    }
    

    //contiene todos los campos de la tabla funcionario calcomania
    public function attributeFuncionarioCalcomania()
    {
        return [
                'id_funcionario',
                'estatus',
                'usuario',
                'fecha_hora',
        ];
    }

    public function search($model)
    { 
    // die(var_dump($model));
        $query = Calcomania::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->filterWhere([
            'id_funcionario' => $model->id_funcionario,
            'ano_impositivo' => $model->ano_impositivo,
            'entregado' => 0,
            'estatus' => 0,
            ]);
  
        
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