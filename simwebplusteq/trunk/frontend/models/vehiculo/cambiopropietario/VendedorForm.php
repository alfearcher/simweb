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
 *  @file CompradorForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/04/2016
 * 
 *  @class CompradorForm
 *  @brief Clase contiene las rules y metodos para realizar el validar la placa y el año de traspaso del vehiculo
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
namespace frontend\models\vehiculo\cambiopropietario;

use Yii;
use yii\base\Model;
use frontend\models\vehiculo\cambioplaca\BusquedaVehiculos;





class VendedorForm extends Model
{


  public $vehiculo;
  public $ano_traspaso;



     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
              [['vehiculo', 'ano_traspaso'],'required'],
              ['ano_traspaso', 'validarTraspaso'],
              
              



           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'vehiculo' => Yii::t('frontend', 'Mis vehiculos'),
                'ano_traspaso' => Yii::t('frontend', 'Año de Traspaso'), 
                



              
                
        ];
    }

    public function validarTraspaso($attribute, $params)
    {

      

       $buscarVehiculo = BusquedaVehiculos::find()
                                            ->where([
                                            
                                            'placa' => $this->vehiculo,
                                            //die($this->vehiculo),
                                            'status_vehiculo' => 0,

                                                ])
                                            ->all();

                                            //die(var_dump($buscarVehiculo[0]->id_vehiculo));

                if ($buscarVehiculo == true and $this->ano_traspaso < $buscarVehiculo[0]->ano_vehiculo ){

                      $this->addError($attribute, Yii::t('frontend', 'Año de traspaso must not be lower than año de Vehiculo' ));
                   
                }else{
                
                    return false;
                
                }
                
    }

    

    
    
      
    
    
    
   

    }