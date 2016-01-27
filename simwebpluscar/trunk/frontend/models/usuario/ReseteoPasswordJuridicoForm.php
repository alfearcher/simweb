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
 *  @file User.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class User
 *  @brief Clase que permite loguear al usuario comparando sus datos de acceso al sistema.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  findIdentity
 *  findIdentityByAccesToken
 *  findByUsername
 *  getId
 *  getAuthkey
 *  validateAuthkey
 *  validatePassword
 *  isUserAdmin
 *  isUserFuncionario
 *  isUserSimple
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

class ReseteoPasswordJuridicoForm extends Model
{
     
    public $password1;
    public $password2;

    


      public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['password1',  'password2'], 'required' ],
            [['password1'], 'match', 'pattern' => "/^.{6,50}$/", 'message' => Yii::t('frontend', 'Minimum 6 and maximum 50 characters')],
            ['password2', 'compare', 'compareAttribute' => 'password1', 'message' => Yii::t('frontend', 'The passwords do not match')],//los password no coinciden
           
                  
           
           
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