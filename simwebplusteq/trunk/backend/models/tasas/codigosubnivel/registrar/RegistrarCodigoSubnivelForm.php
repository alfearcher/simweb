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
 *  @file RegistrarCodigoSubnivelForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 15/10/2016
 * 
 *  @class RegistrarCodigoSubnivelForm
 *  @brief Clase que contiene las rules para validacion  del formulario de registro de codigo de subnivel
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
namespace backend\models\tasas\codigosubnivel\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use backend\models\tasa\Tasa;
use common\models\tasas\GrupoSubnivel;

class RegistrarCodigoSubnivelForm extends Model
{

    public $grupo_subnivel;
    public $descripcion;


    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['grupo_subnivel','descripcion'], 'required'],

            [['descripcion'], 'string'],

            ['grupo_subnivel', 'integer'],

            ['descripcion', 'verificarDescripcionSubnivel' ],

            ['grupo_subnivel', 'verificarGrupoSubnivel' ]

          //  ['codigo_contable', 'verificarCodigoContable'],
            
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

        // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                
        'grupo_subnivel' => Yii::t('frontend', 'Grupo Subnivel'),
        'descripcion' => Yii::t('frontend', 'Descripcion'),
     
 
   
       
               
                



              
                
        ];
    }
    
    /**
     * [verificarDescripcionSubnivel description] metodo que verifica que una descripcion del grupo subnivel no se repita
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si consigue la informacion buscada
     */
    public function verificarDescripcionSubnivel($attribute, $params)
    {
         $busqueda = GrupoSubnivel::find()
                                        ->where([

                                      'descripcion' => $this->descripcion,
                                     // 'inactivo' => 0,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Esta Descripcion ya existe' ));
              }else{
                return false;
              }

    }




    /**
     * [verificarGrupoSubnivel description] metodo que verifica que un grupo subnivel  no se repita
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si consigue la informacion buscada
     */
    public function verificarGrupoSubnivel($attribute, $params)
    {
         $busqueda = GrupoSubnivel::find()
                                        ->where([

                                      'grupo_subnivel' => $this->grupo_subnivel,
                                      //    'inactivo' => 0,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Este Grupo Subnivel ya existe' ));
              }else{
                return false;
              }

    }



   
    public function attributeGrupoSubnivel()
    {

       return [

        'grupo_subnivel',
        'descripcion',
        'inactivo',
        


        ];
    }
}
