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








class BusquedaMultipleForm extends Model
{




    public $ano_impositivo;
    public $funcionario;
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
                                'funcionario'
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
            [['ano_impositivo', 'funcionario'],
                  'required', 'on' => 'search_funcionario', 'message' => Yii::t('backend', '{attribute} is require')],
             
             [['ano_impositivo2', 'nro_calcomania'],
                  'required', 'on' => 'search_calcomania', 'message' => Yii::t('backend', '{attribute} is require')],
             
            
        ];
    } 

    
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'ano_impositivo' => Yii::t('backend', 'Año Impositivo'),
                'funcionario' => Yii::t('backend', 'Funcionario'), 

                
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



    
      
    
    
    
   

    }