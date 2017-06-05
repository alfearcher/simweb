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
 *  @file CambioPlacaVehiculoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29/03/2016
 * 
 *  @class CambioPlacaVehiculoForm
 *  @brief Clase contiene las rules y metodos para realizar el cambio de la placa del vehiculo
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
namespace frontend\models\vehiculo\cambioplaca;

use Yii;
use yii\base\Model;


  



class CambioPlacaVehiculoForm extends Model
{


  public $placavieja;
  public $placa;



     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
              [['placa', 'placavieja'],'required'],
             ['placa', 'buscarPlaca'],
             [['placa'], 'match' , 'pattern' => "/^[a-zA-Z0-9]+$/", 'message' => Yii::t('frontend', '{attribute} must be an alphanumeric')],
              ['placa', 'string' , 'min' => 6, 'max' => 7],




           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'placavieja' => Yii::t('frontend', 'Placa Antigua'),
                'placa' => Yii::t('frontend', 'Placa Nueva'), 
                



              
                
        ];
    }

    /**
     * [buscarPlaca description] Metodo que realiza la busqueda de la placa en la tabla vehiculos para verificar si esta ya existe
     * @return [type] [description]
     */
    public function buscarPlaca($attribute, $params)
    {

     // die('llegue a bnuscar placa');
      $busquedaPlaca = busquedaVehiculos::find()
                                        ->where([

                                      'placa' => $this->placa,
                                      'status_vehiculo' => 0,

                                          ])
                                        ->all();

              if ($busquedaPlaca != null){

                $this->addError($attribute, Yii::t('frontend', 'This car plate is already in Use' ));
              }else{
                return false;
              }
    }

      /**
       * [attributeSlCambioPlaca description] metodo que contiene los campos de la tabla sl_cambios_placas
       * @return [type] [description]
       */
      public function attributeSlCambioPlaca()
      {

          return [
              'nro_solicitud',
              'id_vehiculo',
              'placa_actual',
              'placa_nueva',
              'id_contribuyente',
              'usuario',
              'fecha_hora',
              'estatus',
              'origen',
              'user_funcionario',
              'fecha_hora_proceso',
              

          ];
      }
    
      
    
    
    
   

    }