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
 *  @file ModificarCodigoPresupuestariosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/09/2016
 * 
 *  @class ModificarCodigoPresupuestarioForm
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
namespace backend\models\presupuesto\codigopresupuesto\modificarinactivar;

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
class ModificarCodigoPresupuestarioForm extends Model
{


    public $codigo;
    public $descripcion;
    public $nivel_contable;
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['codigo', 'descripcion'], 'required'],

            [['codigo'], 'integer'],

            

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
        'descripcion' => Yii::t('frontend', 'Descripcion'), 
        'nivel_contable' => Yii::t('frontend', 'Nivel Contable'),
       
                



              
                
        ];
    }

    /**
     * [verificarCodigo description] metodo que verifica si el codigo contable ya existe
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje si el codigo existe
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
    // 
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
}
