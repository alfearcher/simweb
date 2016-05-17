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
 *  @file BusquedaFuncionarioForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 20/04/2016
 * 
 *  @class BusquedaFuncionarioForm
 *  @brief Clase contiene las rules y metodos para la busqueda de funcionarios activos en el modulo de calcomania 
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







class BusquedaNaturalForm extends Model
{

  public $naturaleza;
  public $cedula;

 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['cedula', 'naturaleza'],'required'],
             
            ['cedula','integer'],

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

        $buscar = ContribuyenteBase::find()
                                    ->where([
                                        'naturaleza' =>$model->naturaleza,
                                        'cedula' => $model->cedula,
                                        'tipo_naturaleza' => 0,
                                        'inactivo' => 0,
                                    ])
                                    ->all();

                if($buscar == true){
                    return $buscar;
                }else{
                    return false;
                }
    }

    public function buscarVehiculo($model)
    {
    //die(var_dump($model));
        $query = VehiculosSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->filterWhere([
            'id_contribuyente' => $model->id_contribuyente,
            'status_vehiculo' => 0,
          
        ]);
  
        
       // die(var_dump($query));
       
    


        return $dataProvider;

    }
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                'naturaleza' => Yii::t('backend', 'Naturaleza'),
                
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



    
      
    
    
    
   

    }