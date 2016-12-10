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
 *  @file RegistrarCodigoPresupuestariosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26/09/2016
 * 
 *  @class RegistrarCodigoPresupuestariosForm
 *  @brief Clase que contiene las rules para validacion  del formulario de registro de codigos de presupuesto
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
namespace backend\models\presupuesto\codigopresupuesto\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class RegistrarCodigoPresupuestarioForm extends Model
{

    public $id_impuesto;
    public $id_codigo;
    public $impuesto;
    public $ano_impositivo;
    public $grupo_subnivel
    public $codigo;
    public $descripcion;
    public $nivel_contable;
    public $monto;
    public $codigo_contable;


    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['codigo', 'descripcion', 'nivel_contable'], 'required'],

            [['nivel_contable'], 'integer'],

            

            ['codigo', 'verificarCodigo'],

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
                

        'codigo' => Yii::t('frontend', 'Codigo'),
        'descripcion' => Yii::t('frontend', 'Descripcion'), 
        'nivel_contable' => Yii::t('frontend', 'Nivel Contable'),
        'monto' => Yii::t('frontend','Monto'),
        'codigo_contable' => Yii::t('frontend','Codigo Contable'),
               
                



              
                
        ];
    }
    /**
     * [verificarCodigo description] Metodo que verifica si el codigo presupuestario ya existe
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna un mensaje de error si existe
     */
    public function verificarCodigo($attribute, $params)
    {
         $busqueda = CodigosContables::find()
                                        ->where([

                                      'codigo' => $this->codigo,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('frontend', 'Este codigo ya existe' ));
              }else{
                return false;
              }

    }


    //     public function verificarCodigoContable($attribute, $params)
    // {
    //      $busqueda = CodigosContables::find()
    //                                     ->where([

    //                                   'codigo_contable' => $this->codigo_contable,
    //                                  // 'estatus' => 0,

    //                                       ])
    //                                     ->all();

    //           if ($busqueda != null){

    //             $this->addError($attribute, Yii::t('frontend', 'Este codigo contable ya existe' ));
    //           }else{
    //             return false;
    //           }

    // }

   
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
}
