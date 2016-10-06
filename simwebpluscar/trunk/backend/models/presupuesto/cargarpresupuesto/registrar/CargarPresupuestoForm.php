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
 *  @file CargarPresupuestoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/10/2016
 * 
 *  @class CargarPresupuestoForm
 *  @brief Clase que contiene las rules para validacion  del formulario de carga de presupuesto
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
namespace backend\models\presupuesto\cargarpresupuesto\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\presupuesto\ordenanzas\OrdenanzaPresupuesto;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class CargarPresupuestoForm extends Model
{


    public $monto;
 
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['monto', 'required'],

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
     * [buscarNivelPresupuesto description] metodo que realiza la busqueda de la descripcion del nivel de presupuesto en la tabla niveles_contables
     * @param  [type] $nivel [description] id del nivel de presupuesto
     * @return [type]        [description] retorna la descripcion del nivel de presupuesto
     */
    public function buscarNivelPresupuesto($nivel)
    {
      //die($nivel);
        $busqueda = NivelesContables::findOne($nivel);
        //die(var_dump($busqueda->descripcion));
        return $busqueda->descripcion;
    }



    //atributos de la tabla presupuestos_detalle
    public function attributePresupuestosDetalle()
    {

       return [

        'id_presupuesto',
        'id_codigo',
        'monto',
        'inactivo',
        
        


        ];
    }

    /**
     * [busquedaOrdenanzaPresupuesto description] metodo que realiza la busqueda de las ordenanzas de presupuesto
     * @return [type] [description] retorna el dataprovider con la informacion buscada
     */
    public function busquedaOrdenanzaPresupuesto()
    {

        $query = OrdenanzaPresupuesto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        $query->where([
            'inactivo' => 0,
            ])
        ->all();
         
          
        return $dataProvider;


    }

    /**
     * [busquedaCodigoPresupuestario description] metodo que realiza la busqueda de los codigos contables 
     * @return [type] [description] retorna el dataprovider con la informacion buscada
     */
    public function busquedaCodigoPresupuestario()
    {

           $query = CodigosContables::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
            'inactivo' => 0,
            ])
        ->all();
         
          
        return $dataProvider;
    }
}
