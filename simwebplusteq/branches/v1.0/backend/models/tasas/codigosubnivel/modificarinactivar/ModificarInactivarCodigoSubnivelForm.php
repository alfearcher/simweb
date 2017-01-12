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
 *  @file ModificarInactivarCodigoSubnivelForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 15/10/2016
 * 
 *  @class ModificarInactivarCodigoSubnivelForm
 *  @brief Clase que contiene las rules para validacion  del formulario de modificacion de codigo subnivel
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
namespace backend\models\tasas\codigosubnivel\modificarinactivar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\tasa\Tasa;
use common\models\tasas\GrupoSubnivel;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class ModificarInactivarCodigoSubnivelForm extends Model
{

    public $descripcion;



  
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

           ['descripcion', 'required'],

           ['descripcion', 'string'],

           ['descripcion', 'verificarDescripcionSubnivel' ],
            

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
     * [busquedaGrupoSubnivel description] metodo que retorna un dataprovider con la informacion de los grupos subniveles activos
     * @return [type] [description] retorna un dataprovider
     */
    public function busquedaGrupoSubnivel(){


        $query = GrupoSubnivel::find();

                                
                             
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
