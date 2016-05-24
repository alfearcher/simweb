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
 *  @file BusquedaNaturalForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 14/05/2016
 * 
 *  @class BusquedaNaturalForm
 *  @brief Clase contiene las rules y metodos para la busqueda de contribuyentes naturales para la asignacion de calcomanias 
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




class BusquedaNaturalForm extends Model
{

  public $naturaleza;
  public $cedula;
  public $id;
  

  const SCENARIO_SEARCH_NATURAL = 'search_natural';
  const SCENARIO_SEARCH_ID = 'search_id';

 
     public function scenarios()
        {
            //bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH_NATURAL => [
                                'naturaleza',
                                'cedula'
                ],
                self::SCENARIO_SEARCH_ID => [
                                'id',
                               
                ],
             
            ];
        }
 

    public function rules()
    {   //validaciones requeridas para el formulario de busqueda de contribuyentes  

    // die('llegue a las rules');

        return [
           [['naturaleza', 'cedula'],
                'required', 'on' => 'search_natural', 'message' => Yii::t('backend', '{attribute} is required')],

            [['id'],
                'required', 'on' => 'search_id', 'message' => Yii::t('backend', '{attribute} is required')],
             
            [['cedula','id'],'integer'],

            ['cedula', 'validarLongitud'],
             
            
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

    public function buscarNatural($model)
    {


        $buscar = CrearUsuarioNatural::find()
                                    ->where([
                                        'naturaleza' =>$model->naturaleza,
                                        'cedula' => $model->cedula,
                                        'tipo_naturaleza' => 0,
                                        'inactivo' => 0,
                                    ])
                                    ->one();

                if($buscar == true){
                    
                    return $buscar;
                }else{
                    return false;
                }
    }

    public function buscarId($model)
    {
        //die(var_dump($model));
         $buscar = CrearUsuarioNatural::find()
                                    ->where([
                                        'id_contribuyente' => $model->id,
                                        'tipo_naturaleza' => 0,
                                        'inactivo' => 0,
                                    ])
                                    ->one();

                if($buscar == true){
                    
                    return $buscar;
                }else{
                    return false;
                }
    }




    public function buscarVehiculo($model)
    {
     $query = VehiculosSearch::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
            'id_contribuyente' => $model->id_contribuyente,
            'status_vehiculo' => 0,
            ])
  
        ->all();
       // die(var_dump($query));

        //die(var_dump($query));
        return $dataProvider;

    }

    public function buscarPlaca($model)
    {


        $buscar = VehiculosSearch::find()
                                    ->where([
                                        'id_vehiculo' =>$model,
                                        'status_vehiculo' => 0,
                                        
                                    ])
                                    ->all();

                if($buscar == true){
                    
                    return $buscar;
                }else{
                    return false;
                }
    }

    public function buscarPlacaCalcomania($model)
    {
       // die($model);


        $buscar = CalcomaniaEntregada::find()
                                    ->where([
                                        'placa' =>$model,
                                        'status' => 0,
                                        
                                    ])
                                    ->all();

                if($buscar == true){
                    
                    return true;
                }else{
                    return false;
                }
    }

    public function buscarIdCalcomania($model)
    {
        //die($model);
        $buscar = Calcomania::find()
                            ->where([

                                'nro_calcomania' => $model,
                                'ano_impositivo' => date('Y'),
                                ])
                            ->all();

            if($buscar == true){
               // die(var_dump($buscar));
                return $buscar;
            }else{
                return false;
            }
    }

    public function verificarCalcomaniaEntregada($model)
    {

        
        $buscar = CalcomaniaEntregada::find()
                                    ->where([
                                    'id_vehiculo' => $model,
                                    'ano_impositivo' => date('Y'),
                                    'status' => 0,

                                      ])
                                    ->all();

                            if($buscar == true){
                                return true;
                            }else{
                                return false;
                            }
    }

    public function searchRango()
    {
        $datos = yii::$app->user->identity->id_funcionario;
        
        $query = Calcomania::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->filterWhere([
            'id_funcionario' => $datos,
            'entregado' => 0,
            'estatus' => 0,

            ])
        
        ->all();


       
    
  
        return $dataProvider;
    }
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                'naturaleza' => Yii::t('backend', 'Naturaleza'),
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