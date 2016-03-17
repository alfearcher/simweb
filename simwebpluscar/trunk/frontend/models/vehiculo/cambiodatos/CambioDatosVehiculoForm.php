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
 *  @date 08/03/2016
 * 
 *  @class CambioDatosVehiculoForm
 *  @brief Clase contiene las rules y metodos para realizar el cambio de vehiculo
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
namespace frontend\models\vehiculo\cambiodatos;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\Afiliacion;





class CambioDatosVehiculoForm extends Model
{

  public $placavieja;
  public $placa;
  public $marcavieja;
  public $marca;
  public $modeloviejo;
  public $modelo;
  public $colorviejo;
  public $color;
  public $no_ejes_viejo;
  public $no_ejes;
  public $nro_puestos_viejo;
  public $nro_puestos;
  public $pesoviejo;
  public $peso;
  public $nro_cilindros_viejo;
  public $nro_cilindros;
  public $precio_inicial_viejo;
  public $precio_inicial;
  public $capacidadvieja;
  public $capacidad;
  public $medida_cap_vieja;
  public $medida_cap;




     
  

    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
              [['marcavieja', 'marca','modeloviejo', 'modelo', 'colorviejo','color','no_ejes_viejo','no_ejes',
              'nro_puestos_viejo','nro_puestos','pesoviejo','peso','nro_cilindros_viejo','nro_cilindros','precio_inicial_viejo','precio_inicial',
              'capacidadvieja','capacidad','medida_cap_vieja','medida_cap'],'required'],
             
            




           
           
        ];
    } 
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'marcavieja' => Yii::t('frontend', 'Marca Antigua'), 
                'marca' => Yii::t('frontend', 'Marca'), 
                'modeloviejo' => Yii::t('frontend', 'Modelo Antiguo'),
                'modelo' => Yii::t('frontend', 'Modelo'),
                'colorviejo' => yii::t('frontend', 'Color Antiguo'),
                'color' => yii::t('frontend', 'Color'),
                'no_ejes_viejo' => yii::t('frontend', 'Nro. ejes Antiguo') ,
                'no_ejes' => yii::t('frontend', 'Nro. ejes'),
                'nro_puestos_viejo' => yii::t('frontend', 'Nro. puesto Antiguo') ,
                'nro_puestos' => yii::t('frontend', 'Nro. puesto') ,
                'pesoviejo' => yii::t('frontend', 'Peso Antiguo') ,
                'peso' => yii::t('frontend', 'Peso') ,
                'nro_cilindros_viejo' => yii::t('frontend', 'Nro. Cilindros Antiuguo') ,
                'nro_cilndros' => yii::t('frontend', 'Nro. Cilindros') ,
                'precio_inicial_viejo' => yii::t('frontend', 'Precio Inicial Antiguo') ,
                'precio_inicial' => yii::t('frontend', 'Precio Inicial') ,
                'capacidadvieja' => yii::t('frontend', 'Capacidad Antigua') ,
                'capacidad' => yii::t('frontend', 'Capacidad') ,
                'mediad_cap_vieja' => yii::t('frontend', 'Medida de capacidad Antigua') ,
                'medida_cap' => yii::t('frontend', 'Medidad de capacidad') ,



              
                
        ];
    }



    
      
    
    
    
   

    }