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
 *  @file RegistrarNivelesPresupuestariosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 22/09/2016
 * 
 *  @class RegistrarNivelesPresupuestariosForm
 *  @brief Clase que contiene las rules para validacion  del formulario de registro de niveles presupuestarios
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
namespace backend\models\presupuesto\nivelespresupuesto\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class RegistrarNivelesPresupuestariosForm extends Model
{


    public $nivel_contable;
    public $descripcion;
    public $ingreso_propio;

    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['nivel_contable', 'descripcion', 'ingreso_propio'], 'required'],

            ['nivel_contable', 'integer'],

            ['nivel_contable', 'verificarNivelContable'],
            
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
                

        'nivel_contable' => Yii::t('frontend', 'Nivel Contable'),
        'descripcion' => Yii::t('frontend', 'Descripcion'), 
        'ingreso_propio' => Yii::t('frontend', 'Ingreso Propio'),
               
                



              
                
        ];
    }
    /**
     * [verificarNivelContable description] metodo que verifica que el nivel contable no exista en la tabla
     * @param  [type] $attribute [description] atributo
     * @param  [type] $params    [description] parametro
     * @return [type]            [description] retorna mensaje de error si el nivel contable ya existe
     */
    public function verificarNivelContable($attribute, $params)
    {
         $busqueda = NivelesContables::find()
                                        ->where([

                                      'nivel_contable' => $this->nivel_contable,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('frontend', 'This countable level is already in use' ));
              }else{
                return false;
              }

    }

   // atributos de la tabla niveles_contables
    public function attributeNivelesContables()
    {

       return [

        'nivel_contable',
        'descripcion',
        'ingreso_propio',

        ];
    }
}
