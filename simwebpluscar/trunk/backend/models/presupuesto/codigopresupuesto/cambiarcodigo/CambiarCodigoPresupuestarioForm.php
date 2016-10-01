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
 *  @file CambiarCodigoPresupuestarioForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/09/2016
 * 
 *  @class CambiarCodigoPresupuestarioForm
 *  @brief Clase que contiene las rules para validacion  del formulario de modificacion de codigos de presupuesto
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
namespace backend\models\presupuesto\codigopresupuesto\cambiarcodigo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class CambiarCodigoPresupuestarioForm extends Model
{


    public $codigo;
    public $nivel_contable;
    public $nuevo_nivel_contable;
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['nuevo_nivel_contable', 'required'],

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
                

        'codigo' => Yii::t('frontend', 'Codigo'),
         'nuevo_nivel_contable' => Yii::t('frontend', 'Nuevo Nivel Contable'),
        'nivel_contable' => Yii::t('frontend', 'Nivel Contable Actual'),
       
                



              
                
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

    /**
     * [busquedaDatosCodigoPresupuestario description] metodo que realiza la busqueda de los datos en base al id codigo de la tabla codigos_presupuestarios
     * @param  [type] $dato [description] id codigo de la tabla
     * @return [type]       [description] retorna la informacion buscada o false
     */
    public function busquedaDatosCodigoPresupuestario($dato)
    {

        $model = CodigosContables::Find()
                                ->where([
                                    'id_codigo' => $dato,
                                    'inactivo' => 0,


                                    ])
                                ->all();

                if($model == true){
                    return $model;
                }else{
                    return false;
                }
    }

    //atributos de la tabla codigos_contables
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
     * [busquedaCodigosPresupuestarios description] metodo que retorna el dataprovider con la informacion de los codigos contables
     * @return [type] [description] retorna el dataprovider con la informacion
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
