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
 *  @file FormChangePassword.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class FormChangePassword 
 *  @brief Clase que permite validar cada uno de los datos del formulario changepassword, 
 *  se establecen las reglas para los datos a ingresar y se le asigna el nombre de las etiquetas 
 *  de los campos. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  
 *  
 *
 *  @inherits
 *  
 */ 
namespace backend\models;
use Yii;
use yii\base\Model;
use common\models\Users;

class FormChangePassword extends Model{
  
    public $username;
	public $password;
	public $password_repeat;
    public $recover;
	public $verifyCode;
      
    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required', 'message' => Yii::t('backend', 'Required field')],//campo requerido
            ['password', 'match', 'pattern' => "/^.{8,16}$/", 'message' => Yii::t('backend', 'Minimum 8 and maximum 16 characters')],//minimo 8 y maximo 16 caracteres
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('backend', 'The passwords do not match')],//los password no coinciden
			['verifyCode', 'captcha', 'message' => Yii::t('backend', 'Code Capchat incorrepto')],//Codigo Capchat incorrepto
			
        ];
    }
	
	
	// nombre de etiquetas
	 public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Your username'), 
            'password' => Yii::t('backend', 'Your new password'),
            'password_repeat' => Yii::t('backend', 'Password repeat'),
        ];
    }
	

		

}