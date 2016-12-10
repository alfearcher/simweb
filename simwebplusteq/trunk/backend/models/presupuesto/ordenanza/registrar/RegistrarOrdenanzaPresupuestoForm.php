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
 *  @file RegistrarOrdenanzaPresupuestoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/09/2016
 * 
 *  @class RegistrarOrdenanzaPresupuestoForm
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
namespace backend\models\presupuesto\ordenanza\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


use common\models\presupuesto\ordenanzas\OrdenanzaPresupuesto;
/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class RegistrarOrdenanzaPresupuestoForm extends Model
{


    public $nro_presupuesto;
    public $ano_impositivo;
    public $fecha_desde;
    public $fecha_hasta;
    public $descripcion;
    public $observacion;
    public $inactivo;
    public $fecha_modificacion;


    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['nro_presupuesto',  'fecha_desde', 'ano_impositivo', 'fecha_hasta'], 'required'],

           // ['nro_presupuesto', 'verificarNroPresupuesto'],

             ['observacion', 'default', 'value' => 0],

             ['ano_impositivo', 'verificarAnoImpositivo'],

            ['fecha_desde', 'verificarFechaDesde'],

           ['fecha_hasta', 'verificarFechaHasta'],


           
             ['fecha_desde',
           'compare',
           'compareAttribute' => 'fecha_hasta',
           'operator' => '<=',
           'message' => Yii::t('backend', '{attribute} no puede ser mayor ' . self::attributeLabels()['fecha_hasta'])],

            

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
                

        'nro_presupuesto' => Yii::t('frontend', 'Nro Presupuesto'),
        'fecha_desde' => Yii::t('frontend', 'Fecha Inicial'), 
        'fecha_hasta' => Yii::t('frontend', 'Fecha Final'),
        
        'observacion' => Yii::t('frontend','Observacion'),
               
              



              
                
        ];
    }
   /**
    * [verificarFechaDesde description] metodo que verifica que la fecha inicial no sea menor al año impositivo
    * @param  [type] $attribute [description] atributo 
    * @param  [type] $params    [description] parametros
    * @return [type]            [description] retorna mensaje de error
    */
    public function verificarFechaDesde($attribute, $params){

            
                $fecha = date("Y", strtotime($this->fecha_desde));
                //die($this->ano_impositivo);
                if($fecha != $this->ano_impositivo){ 

                $this->addError($attribute, Yii::t('frontend', 'Fecha Inicial no coincide con año impositivo'));
                
                }

    }



    /**
     * [verificarFechaHasta description] metodo que verifica que la fecha final no sea mayor al año impositivo
     * @param  [type] $attribute [description]
     * @param  [type] $params    [description]
     * @return [type]            [description]
     */
    public function verificarFechaHasta($attribute, $params){

     
            $fecha = date("Y", strtotime($this->fecha_hasta));
                
            if($fecha != $this->ano_impositivo){ 

            $this->addError($attribute, Yii::t('frontend', 'Fecha Final no coincide con año impositivo'));
            
            }

    }

    /**
     * [verificarAnoImpositivo description] metodo que verifica que el año impositivo no exista en la tabla para evitar registrar dos presupuestos en un año con el mismo año impositivo
     * @param  [type] $attribute [description] atributo
     * @param  [type] $params    [description] parametro
     * @return [type]            [description] devuelve mensaje de error 
     */
    public function verificarAnoImpositivo($attribute, $params){
    

        $busqueda = OrdenanzaPresupuesto::find()
                                            ->where([
                                            
                                            'ano_impositivo' => $this->ano_impositivo,

                                                ])
                                            ->all();

        if($busqueda == true){
            
        $this->addError($attribute, Yii::t('frontend', 'Ya este año tiene un presupuesto registrado'));
        }
        
        
    }
    /**
     * [busquedaOrdenanzaPresupuesto description] metodo que devuelve datos de la tabla ordenanzas presupuestos
      * @return [type] [description] devuelve un dataprovider con la informacion de la tabla
     */
    public function busquedaOrdenanzaPresupuesto()
    {


           $query = OrdenanzaPresupuesto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           'pagination' => [
        'pageSize' => 5,
    ],
        ]);
        $query->where([
            'inactivo' => 0,
            ])
        ->all();
         
        
        return $dataProvider;


        
    }

   //atributos de la tabla ordenanza_presupuesto
    public function attributeOrdenanzasPresupuesto()
    {

       return [

        'nro_presupuesto',
        'ano_impositivo',
        'fecha_desde',
        'fecha_hasta',
        'descripcion',
        'observacion',
        'inactivo',
        'fecha_modificacion',


        ];
    }
}
