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
 *  @file ReseteoPasswordForm.php
 *  
 *  @author Manuel Aljenadro Zapata Canelon
 * 
 *  @date 25-02-2016
 * 
 *  @class ReseteoPasswordForm.php
 *  @brief Clase contiene las rules y las validaciones para el formulario de reseteo de password.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\Afiliacion;

class MensajeRecuperarForm extends Model
{
     
    public $nivel;
    


    /**
     * Metodo que retorna los roles de validacion.
     */
    public function rules()
    {
        return [
            
            [['nivel'], 'required', 'message' => 'Campo requerido'],
            

        ];
    }

    


    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                //'usuario' => Yii::t('frontend', 'Your Username'), // para multiples idiomas
                'password1' => Yii::t('frontend', 'Password'), //'Primera Pregunta de Seguridad',
                'password2' => Yii::t('frontend', 'Repeat Password'), //'Primera Pregunta de Seguridad',
               
               
        ];
    }

}
?>