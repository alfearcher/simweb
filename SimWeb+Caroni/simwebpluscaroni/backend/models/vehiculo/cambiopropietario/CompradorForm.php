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
namespace backend\models\vehiculo\cambiopropietario;

use Yii;
use yii\base\Model;
use backend\models\vehiculo\cambioplaca\BusquedaVehiculos;





class CompradorForm extends Model
{


  public $placa;
  public $ano_traspaso;



     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
              [['placa', 'ano_traspaso'],'required'],
              ['ano_traspaso', 'validarTraspaso'],
              [['placa'], 'match' , 'pattern' => "/^[a-zA-Z0-9]+$/", 'message' => Yii::t('frontend', '{attribute} must be an alphanumeric')],
              ['placa', 'string' , 'min' => 6, 'max' => 7],
              



           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'placa' => Yii::t('frontend', 'Placa'),
                'ano_traspaso' => Yii::t('frontend', 'Año de Traspaso'), 
                



              
                
        ];
    }

    public function attributeSlCambioPropietario()
    {
        return [
                'id_impuesto',
                'impuesto',
                'id_impuesto',
                'id_propietario',
                'id_comprador',
                'usuario',
                'fecha_hora',
                'estatus',
                'nro_solicitud',
                'origen',
                'fecha_hora_proceso',

                



              
                
        ];
    }

    public function validarTraspaso($attribute, $params)
    {

      

       $buscarVehiculo = BusquedaVehiculos::find()
                                            ->where([
                                            
                                            'placa' => $this->placa,
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