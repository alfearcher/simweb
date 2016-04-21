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
namespace backend\models\vehiculo\calcomania\administrarfuncionario;

use Yii;
use yii\base\Model;








class BusquedaFuncionarioForm extends Model
{


  public $cedula;
 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            ['cedula','required'],
             
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
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                
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