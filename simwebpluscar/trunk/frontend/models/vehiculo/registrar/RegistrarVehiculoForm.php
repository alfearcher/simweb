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
 *  @file RegistrarVehiculoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29-02-2016
 * 
 *  @class RegistrarVehiculoForm
 *  @brief Clase contiene las rules y metodos para registrar el vehiculo
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
 *  @inherits
 *  
 */
namespace frontend\models\vehiculo\registrar;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\Afiliacion;





class RegistrarVehiculoForm extends RegistrarVehiculo
{
     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  


        return [
            [['placa', 'marca', 'modelo', 'ano_compra' ,'ano_vehiculo', 'clase_vehiculo', 'tipo_vehiculo', 'uso_vehiculo'], 'required' ],
           
                  
           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
               
                'placa' => Yii::t('frontend', 'Placa'), 
                'marca' => Yii::t('frontend', 'Marca'), 
                'modelo' => Yii::t('frontend', 'Modelo'), 
                'ano_compra' => Yii::t('frontend', 'Año de Compra'), 
                'ano_vehiculo' => Yii::t('frontend', 'Año de Vehiculo'), 
                'clase_vehiculo' => Yii::t('frontend', 'Clase de Vehiculo'),
                'tipo_vehiculo' => Yii::t('frontend', 'Tipo de Vehiculo'),
                'uso_vehiculo' => Yii::t('frontend', 'Uso del Vehiculo'),   
                
        ];
    }
      
   
    
    
   

    }