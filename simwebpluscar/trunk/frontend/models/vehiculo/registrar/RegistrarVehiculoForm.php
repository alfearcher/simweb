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
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;





class RegistrarVehiculoForm extends RegistrarVehiculo
{
     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  


        return [
              [['placa', 'marca', 'modelo', 'ano_compra' ,'ano_vehiculo', 'clase_vehiculo', 'tipo_vehiculo',
               'uso_vehiculo', 'color', 'no_ejes', 'nro_puestos', 'fecha_inicio', 'nro_calcomania', 
               'peso', 'nro_cilindros', 'precio_inicial', 'capacidad',  'medida_cap',
                'serial_carroceria', 'serial_motor'], 'required' ],

                ['placa', 'buscarPlaca'],

            [['color'], 'match' , 'pattern' => "/[a-zA-Z]+/", 'message' => Yii::t('frontend', 'Color must have only letters')],
            
            [['no_ejes', 'nro_puestos', 'nro_calcomania' ,'peso','nro_cilindros', 'capacidad', 'exceso_cap' ,
            ],'integer','message' => yii::t('frontend', '{attribute} must be an integer') ] ,    

            ['placa' , 'string' , 'max' => 12 ],
            ['marca' , 'string' , 'max' => 25 ],
            ['modelo' , 'string' , 'max' => 25 ],
            ['color' , 'string' , 'max' => 25 ],
            ['no_ejes', 'integer', 'max' => 12, 'min' => 2 ],
            ['nro_puestos', 'integer', 'max' => 100, 'min' => 2 ],
            //['nro_calcomania', 'integer' ,'max' => 20, 'min' => 1], a la espera de ser utilizado
            ['peso', 'integer', 'max' => 100000, 'min' => 1 ],
            ['nro_cilindros', 'integer', 'max' => 12, 'min' => 2 ],
            ['precio_inicial', 'double'],
            




           
           
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
                'color' => Yii::t('frontend', 'Color'),
                'no_ejes' => Yii::t('frontend', 'Nro. Ejes'),   
                'nro_puestos' => Yii::t('frontend', 'Nro. Puesto'), 
                'fecha_inicio' => Yii::t('frontend', 'Fecha Inicio'), 
                'nro_calcomania' => Yii::t('frontend', 'Nro. Calcomania'), 
                'peso' => Yii::t('frontend', 'Peso (kg)'), 
                'nro_cilindros' => Yii::t('frontend', 'Nro. Cilindros'), 
                'precio_inicial' => Yii::t('frontend', 'Precio Inicial'), 
                'capacidad' => Yii::t('frontend', 'Capacidad'),
                'exceso_cap' => Yii::t('frontend', 'Exceso de Capacidad'),
                'medida_cap' => Yii::t('frontend', 'Medida de Capacidad'),   
                'serial_carroceria' => Yii::t('frontend', 'Serial de Carroceria'), 
                'serial_motor' => Yii::t('frontend', 'Serial de Motor'), 
                
        ];
    }

    public function attributeSolicitudContribuyente()
    {
        return [
               
               'nro_solicitud',
               'id_config_solicitud',
               'impuesto',
               'id_impuesto',
               'tipo_solicitud',
               'usuario',
               'id_contribuyente',
               'fecha_hora_creacion',
               'nivel_aprobacion',
               'nivel_aprobacion',
               'nro_control',
               'firma_digital',
               'estatus',
               'inactivo',
                
                
        ];
    }

    public function attributeSlVehiculo()
    {
        return [
               
              'id_vehiculo',
              'nro_solicitud',
              'id_contribuyente',
              'placa',
              'marca',
              'modelo',
              'color',
              'uso_vehiculo',
              'precio_inicial',
              'fecha_inicio',
              'ano_compra',
              'ano_vehiculo',
              'no_ejes',
              'liquidado',
              'status_vehiculo',
              'exceso_cap',
              'medida_cap',
              'capacidad',
              'nro_puestos',
              'peso',
              'clase_vehiculo',
              'tipo_vehiculo',
              'serial_motor',
              'serial_carroceria',
              'nro_calcomania',
              'estatus_funcionario',
              'user_funcionario',
              'fecha_funcionario',
              'fecha_hora',
              'nro_cilindros',
                
                
        ];
    }

       public function attributeVehiculos()
    {
        return [
               
              'id_vehiculo',
              'id_contribuyente',
              'placa',
              'marca',
              'modelo',
              'color',
              'uso_vehiculo',
              'precio_inicial',
              'fecha_inicio',
              'ano_compra',
              'ano_vehiculo',
              'no_ejes',
              'liquidado',
              'status_vehiculo',
              'exceso_cap',
              'medida_cap',
              'capacidad',
              'nro_puestos',
              'peso',
              'clase_vehiculo',
              'tipo_vehiculo',
              'serial_motor',
              'serial_carroceria',
              'nro_calcomania',
              'nro_cilindros',
                
                
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
      
    
    
    
   

    }    
      
