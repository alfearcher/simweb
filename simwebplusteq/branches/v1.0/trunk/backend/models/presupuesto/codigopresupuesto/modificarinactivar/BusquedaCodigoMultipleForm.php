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


            ['codigo', 'verificarCodigo'],
            
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
     * @return [type]        [description] redirecciona a una busqueda alterna para completar la relacion
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

    /**
     * [busquedaNivelPresupuestoCodigo description] metodo que realiza el nivel presupuestario en base al codigo contable
     * @param  [type] $nivel [description] codigo presupuestario
     * @return [type]        [description] redirecciona a una busqueda alterna para completar la relacion
     */
    public function busquedaNivelPresupuestoCodigo($codigo)
    { 
     //die($nivel.'hola');
        $query = self::buscarCodigoContableRelacionPorCodigo($codigo);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

             'pagination' => [
        'pagesize' => 10,
        ], 
           
        ]);
        
        $query->all();
  
        return $dataProvider;

       
    }


    /**
     * [buscarCodigoContableRelacion description] metodo que realiza la relacion entre la tabla codigos_contables y niveles_presupuesto
     * @param  [type] $nivel [description] nivel presupuestario para enlazar las tablas
     * @return [type]        [description] retorna la relacion
     */
    public function buscarCodigoContableRelacion($nivel)
    {

        $buscar = CodigosContables::find()
                                    ->where([
                                    'codigos_contables.nivel_contable' => $nivel,
                                    'inactivo' => 0,
                                    ])
                                    ->joinWith('nivelPresupuesto');
                                    //die(var_dump($buscar));
                                return $buscar;
    }

    /**
     * [buscarCodigoContableRelacionPorCodigo description] metodo que realiza la relacion entre la tabla codigos_contables y niveles_presupuesto 
     * pero hace la busqueda en base al codigo presupuestario
     * @param  [type] $nivel [description] nivel presupuestario para enlazar las tablas
     * @return [type]        [description] retorna la relacion
     */
    public function buscarCodigoContableRelacionPorCodigo($codigo)
    {

             $buscar = CodigosContables::find()
                                    ->where([
                                    'codigos_contables.codigo' => $codigo,
                                    'inactivo' => 0,
                                    ]);
                                   // ->joinWith('nivelPresupuesto');
                                   // die(var_dump($buscar));
                                return $buscar;

    }

    /**
     * [verificarCodigo description] metodo que verifica si el codigo contable ya existe
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje si el codigo existe
     */
    public function verificarCodigo($attribute, $params)
    {
         $busqueda = CodigosContables::find()
                                        ->where([

                                      'codigo' => $this->codigo,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda == null){

                $this->addError($attribute, Yii::t('frontend', 'Este codigo no existe' ));
              }else{
                return false;
              }

    }

    


    
    


   



    
      
    
    
    
   

    }