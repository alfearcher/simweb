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
 *  @date 13/05/2016
 * 
 *  @class BusquedaNaturalForm.php
 *  @brief Clase contiene las rules y metodos para la busqueda de contribuyentes naturales para luego asignarles la calcomania
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
use backend\models\vehiculo\VehiculosForm;
use yii\data\ActiveDataProvider;









class BusquedaNaturalForm extends Model
{

  public $naturaleza;
  public $cedula;

 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['cedula','naturaleza'],'required'],
             
            [['cedula'],'integer'],

            //['tipo', 'validarTipo'] //para utilizar en la busqueda de usuario juridico
            ['cedula', 'validarLongitud'],
             
            
        ];
    } 

    public function validarLongitud($attribute, $params)
    {
      

        $longitud = strlen($this->cedula);

          if ($longitud > 9 ){
            $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 9 characters'));
          } else if ($longitud < 6 ){ 
            $this->addError($attribute, Yii::t('frontend', 'The rif must not have less than 6 characters'));
          }
    
    }

    // public function validarTipo($attribute,$params) //para utilizarlo en la busqueda de persona natural
    // {
    //     $longitud = strlen($this->tipo);

    //         if($longitud > 1){
    //             $this->addError($attribute, Yii::t('backend', 'Tipo must not have more than 1 character');
    //         }
    // }
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                'naturaleza' => Yii::t('backend', 'Naturaleza'),
                'tipo' => Yii::t('backend','Tipo'),
                
        ];
    }

    public function buscarNatural($model)
    {
        //die(var_dump($model));
        $buscar = ContribuyenteBase::find()
                                 ->where([
                                  'naturaleza' => $model->naturaleza,
                                  'cedula' => $model->cedula,
                                  'tipo_naturaleza' => 0,
                                  'inactivo' => 0,

                                  ])
                                 ->all();

                  if ($buscar == true){
                    return $buscar;
                  }else{
                    return false;
                  }
             
    }
    public function vehiculoSearch($model)
    {  
      //die(var_dump($model[0]->id_contribuyente));
        $query = VehiculosForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        
            $query->where([
                'id_contribuyente' => $model[0]->id_contribuyente,
               // die($model[0]->id_contribuyente),
                'status_vehiculo' => 0,
            ])
  
            ->all();
      
                return $dataProvider;
    
       }
  

    
      
    
    
    
   

    }