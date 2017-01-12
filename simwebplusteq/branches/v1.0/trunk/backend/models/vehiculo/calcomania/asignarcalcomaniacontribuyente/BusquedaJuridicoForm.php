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
  * 
 *  @file BusquedaJuridicoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 18/05/2016
 * 
 *  @class BusquedaJuridicoForm
 *  @brief Clase contiene las rules y metodos para la busqueda de contribuyentes juridicos para la asignacion de calcomanias
 * 
 *  
 * 
 *  
 *  
 * @property
 *
 *  
 *  @method
 * rules
 * attributeLabels
 *
 *  
 *  @inherits
 *  
 */
namespace backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente;

use Yii;
use yii\base\Model;
use common\models\contribuyente\ContribuyenteBase;
use backend\models\vehiculo\VehiculosSearch;
use yii\data\ActiveDataProvider;
use frontend\models\usuario\CrearUsuarioNatural;
use common\models\calcomania\calcomaniaentregada\CalcomaniaEntregada;
use common\models\calcomania\calcomaniamodelo\Calcomania;




class BusquedaJuridicoForm extends Model
{

  public $naturaleza;
  public $cedula;
  public $tipo;
  public $id;

   const SCENARIO_SEARCH_ID_JURIDICO = 'search_id_juridico';
   const SCENARIO_SEARCH_JURIDICO = 'search_juridico';

 

 
     public function scenarios()
        {
            //bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH_JURIDICO => [
                                'naturaleza',
                                'cedula',
                                'tipo',
                ],
                self::SCENARIO_SEARCH_ID_JURIDICO => [
                                'id',
                               
                ],
             
            ];
        }
 

    public function rules()
    {   //validaciones requeridas para el formulario de busqueda de contribuyentes  

    // die('llegue a las rules');

        return [
           [['naturaleza', 'cedula','tipo'],
                'required', 'on' => 'search_juridico', 'message' => Yii::t('backend', '{attribute} is required')],

            [['id'],
                'required', 'on' => 'search_id_juridico', 'message' => Yii::t('backend', '{attribute} is required')],
             
            [['cedula','id', 'tipo'],'integer'],

            ['cedula', 'validarLongitud'],

            ['tipo', 'validarTipo'],


             
            
        ];
    } 

    public function validarLongitud($attribute, $params)
    {
      

        $longitud = strlen($this->cedula);

          if ($longitud > 8 ){
            $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 9 characters'));
          } else if ($longitud < 6 ){ 
            $this->addError($attribute, Yii::t('frontend', 'The rif must not have less than 6 characters'));
          }
    
    }

     public function validarTipo($attribute, $params)
    {
      

        $longitud = strlen($this->tipo);

          if ($longitud > 1 ){
            $this->addError($attribute, Yii::t('frontend', 'Tipo must not have more than 1 characters'));
          }else{
            return false;
          }
    
    }

    public function buscarJuridico($model)
    {


        $buscar = CrearUsuarioNatural::find()
                                    ->where([
                                        'naturaleza' =>$model->naturaleza,
                                        'cedula' => $model->cedula,
                                        'tipo' => $model->tipo,
                                        'tipo_naturaleza' => 1,
                                        'inactivo' => 0,
                                    ])
                                    ->one();

                if($buscar == true){
                    
                    return $buscar;
                }else{
                    return false;
                }
    }

    public function buscarIdJuridico($model)
    {
        //die(var_dump($model));
         $buscar = CrearUsuarioNatural::find()
                                    ->where([
                                        'id_contribuyente' => $model->id,
                                        'tipo_naturaleza' => 1,
                                        'inactivo' => 0,
                                    ])
                                    ->one();

                if($buscar == true){
                    
                    return $buscar;
                }else{
                    return false;
                }
    }




    

    

    
   

   
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                'naturaleza' => Yii::t('backend', 'Naturaleza'),
                'tipo' => Yii::t('backend', 'Tipo'),
                'id' => yii::t('backend', 'ID'),
                
        ];
    }
    

    //contiene todos los campos de la tabla funcionario calcomania
    public function attributeFuncionarioCalcomania()
    {
        return [
                'id_funcionario',
                'estatus',
                'usuario',
                'fecha_hora',
        ];
    }

    public function attributeLoteCalcomaniasEntregadas()
    {
        return [
                'id_contribuyente',
                'id_vehiculo',
                'nro_calcomania',
                'ano_impositivo',
                'fecha_entrega',
                'login',
                'tipo_entrega',
                'observacion',
                'planilla',
                'status',
                'placa',
                
                

        ];
    }

    



    
      
    
    
    
   

    }