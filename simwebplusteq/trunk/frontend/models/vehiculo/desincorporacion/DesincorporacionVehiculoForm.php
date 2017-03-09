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
 *  @file DesincorporacionVehiculoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 30/03/2016
 * 
 *  @class CambioDatosVehiculoForm
 *  @brief Clase contiene las rules y metodos para realizar la desincorporacion del vehiculo
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
namespace frontend\models\vehiculo\desincorporacion;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\Afiliacion;



 

class DesincorporacionVehiculoForm extends Model
{


  public $motivos;
  public $otrosMotivos;
  




     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
              [['motivos', 'otrosMotivos'],'required'],
              
            




           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'motivos' => Yii::t('frontend', 'Motivo Principal'), 
                'otrosMotivos' => Yii::t('frontend', 'Especifique el motivo de la desincorporacion'), 
                



              
                
        ];
    }

    public function validarCheck($postCheck)
    {
        if (count($postCheck) > 0){

            return true;
        }else{
            return false;
        }
    }

    
      
    public function attributeSldesincorporaciones()
    {


    return [  'nro_solicitud',
              'id_contribuyente',
              'id_impuesto',
              'impuesto',
              'causa_desincorporacion',
              'observacion',
              'usuario',
              'fecha_hora',
              'inactivo',
              
              

              ];
      
    }
    
    
   

    }