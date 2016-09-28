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
 *  @file BusquedaCodigoMultipleForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 28/09/2016
 * 
 *  @class BusquedaCodigoMultipleForm
 *  @brief Clase contiene las rules y metodos para la busqueda multiple de codigos presupuestarios
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
namespace backend\models\presupuesto\codigopresupuesto\modificarinactivar;

use Yii;
use yii\base\Model;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use yii\data\ActiveDataProvider;
use common\models\presupuesto\codigopresupuesto\CodigosContables;





class BusquedaCodigoMultipleForm extends Model
{



 
    public $nivel_contable;
    public $codigo;
    
    


    const SCENARIO_SEARCH_NIVEL_CONTABLE = 'search_nivel';
    const SCENARIO_SEARCH_CODIGO_CONTABLE = 'search_codigo';
  
       


    public function scenarios()
        {
            //bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH_NIVEL_CONTABLE => [
                                'nivel_contable',
                               
                ],
                self::SCENARIO_SEARCH_CODIGO_CONTABLE => [
                                'codigo',
                                
                ],
              
               
            ];
        }
 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['nivel_contable'],
                  'required', 'on' => 'search_nivel', 'message' => Yii::t('backend', '{attribute} is required')],
             
             [['codigo'],
                  'required', 'on' => 'search_codigo', 'message' => Yii::t('backend', '{attribute} is required')],
            

            ['codigo', 'integer'],


            
        ];
    } 

    
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'nivel_contable' => Yii::t('backend', 'Nivel Contable'),
                'codigo' => Yii::t('backend', 'Codigo'), 
                

        ];
    }
    

    //contiene todos los campos de la tabla funcionario calcomania
    public function attributeCodigosContables()
    {
        return [
                'codigo',
                'descripcion',
                'nivel_contable',
                'monto',
                'inactivo',
                'codigo_contable',
        ];
    }

    /**
     * [search description] funcion que realiza la busqueda de calcomanias por funcionario y año impositivo en la tabla calcomanias
     * @param  [type] $model [description] modelo que contiene la informacion para realizar la busqueda
     * @return [type]        [description] retorna true si consigue la informacion y false si no la consigue
     */
    public function busquedaNivelPresupuesto($nivel)
    { 
     //die($nivel.'hola');
        $query = self::buscarCodigoContableRelacion($nivel);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

             'pagination' => [
        'pagesize' => 10,
        ], 
           
        ]);
        
        $query->all();
  
        return $dataProvider;

       
    }

    public function buscarCodigoContableRelacion($nivel)
    {

        $buscar = CodigosContables::find()
                                    ->where([
                                    'codigos_contables.nivel_contable' => $nivel,
                                    ])
                                    ->joinWith('nivelPresupuesto');

                                return $buscar;
    }
    


    
    


   



    
      
    
    
    
   

    }