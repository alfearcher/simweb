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
 *  @file InactivarNivelesPresupuestariosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 23/09/2016
 * 
 *  @class InactivarNivelesPresupuestariosForm
 *  @brief Clase que contiene las rules y metodos para la inactivacion de los niveles contables 
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
namespace backend\models\presupuesto\nivelespresupuesto\inactivar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;


class InactivarNivelesPresupuestoForm extends Model
{



    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            
            
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
     * [busquedaNiveles description] metodo que realiza la busqueda del nivel contable en la tabla niveles_contables
     * @param  [type] $nivelContable [description] id del nivel contable para realizar la busqueda
     * @return [type]                [description] retorna el nivel contable
     */
    public function busquedaNiveles($nivelContable)
    {

       $buscar = NivelesContables::find()
                                  ->select('nivel_contable')
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


    //metodo que valida si el chekbox esta seteado
     public function validarCheck($postCheck)
    {
        //die($postCheck);
        
        if (count($postCheck) > 0){
            //die('lo selecciono');
            return true;
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
