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
 *  @file MostrarDatosFuncionarioForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/04/2016
 * 
 *  @class MostrarDatosFuncionarioForm
 *  @brief Clase contiene las rules y metodos para mostrar la informacion del funcionario
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








class MostrarDatosFuncionarioForm extends Model
{


  public $nombre;
  public $apellido;
  public $cedula;
 




     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            ['cedula','required'],
             
            
            
            

           
           
        ];
    } 

    
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'cedula' => Yii::t('backend', 'Cedula'), 
                'nombre' => Yii::t('backend', 'Cedula'), 
                'apellido' => Yii::t('backend', 'Cedula'), 

                



              
                
        ];
    }



    
      
    
    
    
   

    }