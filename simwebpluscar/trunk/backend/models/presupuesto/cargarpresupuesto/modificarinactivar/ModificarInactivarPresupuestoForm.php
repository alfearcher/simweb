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
 *  @file ModificarInactivarPresupuestoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/09/2016
 * 
 *  @class ModificarInactivarPresupuestoForm
 *  @brief Clase que contiene las rules para validacion de la modificacion e inactivacion de los presupuestos
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
namespace backend\models\presupuesto\cargarpresupuesto\modificarinactivar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use common\models\presupuesto\cargarpresupuesto\PresupuestosDetalle;
/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class ModificarInactivarPresupuestoForm extends Model
{


    public $monto;

 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['monto', 'required'],
            ['monto' , 'integer'],

           // [['nuevo_nivel_contable'], 'verificarAsignacion'],

            

            //['codigo', 'verificarCodigo'],

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
                

        'monto' => Yii::t('frontend', 'Monto'),
        
       
                



              
                
        ];
    }
    



   
   
  /**
   * [busquedaRelacionPresupuesto description]metodo que realiza la busqueda con relaciones de la tabla presupues_detalle , codigos_contables y ordenanzas_presupuesto
   * @return [type]       [description] retorna la relacion de las tablas para ser renderizada por otro metodo en un grid
   */
    public function busquedaRelacionPresupuesto()
    {

        $model = PresupuestosDetalle::Find()
                                ->where([
                                  'presupuestos_detalle.inactivo' => 0
                                ])
                                ->joinWith('codigoPresupuesto')
                                ->joinWith('ordenanzaPresupuesto');
                               
                            return $model;
    }

 

    /**
     * [busquedaPresupuesto description] metodo que realiza un dataprovider en base a una busqueda con relaciones entre varias tablas
     * @return [type] [description] retorna dataprovider
     */
    public function busquedaPresupuesto()
    {

        $query = self::busquedaRelacionPresupuesto();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
          'pagination' => [
        'pageSize' => 10,
        ],
        ]);
        $query->where([
            
            ])
        ->all();
         
          
        return $dataProvider;


    }
}
