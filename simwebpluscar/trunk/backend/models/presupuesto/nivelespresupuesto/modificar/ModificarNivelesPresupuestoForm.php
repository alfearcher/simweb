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
 *  @file ModificarNivelesPresupuestariosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 23/09/2016
 * 
 *  @class ModificarNivelesPresupuestariosForm
 *  @brief Clase que contiene las rules para validacion  del formulario de modificacion de niveles presupuestarios
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
namespace backend\models\presupuesto\nivelespresupuesto\modificar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;


class ModificarNivelesPresupuestoForm extends Model
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

            [[ 'descripcion', 'ingreso_propio'], 'required'],

            //['nivel_contable', 'integer'],

           // ['nivel_contable', 'verificarNivelContable'],
            
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
     * [busquedaNivelesPresupuestarios description] metodo que realiza la busqueda en la tabla niveles_contables para renderizar el dataprovider
     * @return [type] [description] retorna el dataprovider con la informacion encontrada, o retorna false.
     */
    public function busquedaNivelesPresupuestarios()
    {




        $query = NivelesContables::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
            'estatus' => 0,
            ])
        ->all();
         
          
        return $dataProvider;


    }

    /**
     * [busquedaNiveles description] metodo que realiza la busqueda de los niveles presupuestarios
     * @param  [type] $nivelContable [description] nivel contable para realizar la busqueda
     * @return [type]                [description] retorna la informacion o retorna false
     */
    public function busquedaNiveles($nivelContable)
    {

       $buscar = NivelesContables::find()
                                  ->where([

                                    'nivel_contable' => $nivelContable,
                                    'estatus' => 0,
                                    ])
                                  ->all();

                      if($buscar == true){
                        return $buscar;
                      }else{
                        return false;
                      }
    }

    /**
     * [verificarNivelContable description] metodo que realiza la busqueda del nivel contable para verificar que no exista
     * @param  [type] $attribute [description] atributo
     * @param  [type] $params    [description] parametro
     * @return [type]            [description] retorna mensaje de error si consigue el nivel contable
     */
    public function verificarNivelContable($attribute, $params)
    {
         $busqueda = NivelesContables::find()
                                        ->where([

                                      'nivel_contable' => $this->nivel_contable,
                                      'estatus' => 0,

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
