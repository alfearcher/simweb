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
 *  @file RegistrarTasasForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 10/10/2016
 * 
 *  @class RegistrarTasasForm
 *  @brief Clase que contiene las rules para validacion  del formulario de registro de tasas
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
namespace backend\models\tasas\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use backend\models\tasa\Tasa;

class RegistrarTasasForm extends Model
{


    public $id_impuesto;
    public $id_codigo;
    public $impuesto;
    public $ano_impositivo;
    public $grupo_subnivel;
    public $codigo; 
    public $descripcion;
    public $monto;
    public $tipo_rango;
    public $inactivo;
    public $cantidad_ut;

    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['impuesto', 'id_codigo', 'ano_impositivo', 'grupo_subnivel', 'tipo_rango', 'codigo', 'descripcion', 'monto', 'tipo_rango', 'cantidad_ut'], 'required'],

            [['codigo' , 'monto' , 'cantidad_ut'], 'integer'],

            ['grupo_subnivel', 'verificarGrupoSubnivel' ],

            ['codigo', 'verificarGrupoEspecifico'],

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
                

        'impuesto' => Yii::t('frontend', 'Impuesto'),
        'id_codigo' => Yii::t('frontend', 'Codigo Contable'),
        'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'),
        'grupo_subnivel' => Yii::t('frontend', 'Grupo Subnivel'),
        'tipo_rango' => Yii::t('frontend', 'Tipo de Rango'),
        'codigo' => Yii::t('frontend', 'Codigo Especifico'),
 
   
       
               
                



              
                
        ];
    }
    
    /**
     * [verificarGrupoSubnivel description] metodo que verifica que un grupo subnivel no se repita dentro de un impuesto
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si consigue la informacion buscada
     */
    public function verificarGrupoSubnivel($attribute, $params)
    {
         $busqueda = Tasa::find()
                                        ->where([

                                      'impuesto' => $this->impuesto,
                                      'grupo_subnivel' => $this->grupo_subnivel,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Este impuesto ya posee este grupo de subnivel' ));
              }else{
                return false;
              }

    }


    public function verificarGrupoEspecifico($attribute, $params)
    {
         $busqueda = Tasa::find()
                                        ->where([

                                      'codigo' => $this->codigo,
                                      'grupo_subnivel' => $this->grupo_subnivel,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Este Grupo de Subnivel ya posee este codigo especifico' ));
              }else{
                return false;
              }

    }


   
    public function attributeVarios()
    {

       return [

        'id_impuesto',
        'id_codigo',
        'impuesto',
        'ano_impositivo',
        'grupo_subnivel',
        'codigo',
        'descripcion',
        'monto',
        'tipo_rango',
        'inactivo',
        'cantidad_ut',


        ];
    }
}
