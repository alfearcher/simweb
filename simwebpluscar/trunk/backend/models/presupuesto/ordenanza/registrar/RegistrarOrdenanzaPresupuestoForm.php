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
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
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

            [['nro_presupuesto',  'fecha_desde', 'fecha_hasta'], 'required'],

            ['nro_presupuesto', 'verificarNroPresupuesto'],

             ['observacion', 'default', 'value' => 0],

            ['fecha_hasta', 'verificarRangoFechas'],

              ['fecha_hasta', 'verificarFechaMayorMenor'],

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
     * [verificarNroPresupuesto description] metodo que verifica la existencia del nro de impuesto
     * @param  [type] $attribute [description] atributo
     * @param  [type] $params    [description] parametro
     * @return [type]            [description] devuelve un mensae de error si el codigo ya existe
     */
    public function verificarNroPresupuesto($attribute, $params){

         $busqueda = OrdenanzaPresupuesto::find()
                                        ->where([

                                      'nro_presupuesto' => $this->nro_presupuesto,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('frontend', 'Este numero de presupuesto ya existe' ));
              }else{
                return false;
              }

    }

    /**
     * [verificarRangoFechas description] metodo que verifica que las fechas ingresadas no sobrepasen el año impositivo ni sean menores
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si la condicion no se cumple
     */
    public function verificarRangoFechas($attribute, $params){

        $fecha_desde = $this->fecha_desde;
        $fecha_hasta = $this->fecha_hasta;

        if ($fecha_desde > $fecha_hasta){

          $this->addError($attribute, Yii::t('frontend', 'La fecha inicial no puede ser mayor a la fecha final' ));
        
        }

        
        
    }


    public function verificarFechaMayorMenor($attribute, $params){

        $fecha_desde = $this->fecha_desde;
        $fecha_hasta = $this->fecha_hasta;
        $añoDesde = date("Y", strtotime($this->fecha_desde));
        $añoHasta = date("Y", strtotime($this->fecha_hasta));
       // $prueba = '01/01/'.$añoDesde;
       // die($prueba);
        if($fecha_desde < '01/01/'.$añoDesde){

            $this->addError($attribute, Yii::t('frontend', 'La fecha no puede ser ni mayor ni menor al año de registro del presupuesto' ));
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
