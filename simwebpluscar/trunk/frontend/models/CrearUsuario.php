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
 *  @file CrearUsuario.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class RegistrarUsuario
 *  @brief Modelo para crear usuario. 
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
 *  email_existe
 *  username_existe
 *  
 *
 *  @inherits
 *  
 */ 

namespace frontend\models;
use Yii;
use yii\base\Model;
use common\models\Users;


class CrearUsuario extends Model{
  
    public $username;
    public $email;
    public $password;
    public $password_repeat;
     
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['username', 'email', 'password', 'password_repeat'], 'required', 'message' => Yii::t('backend', 'Required field')],//campo requerido
            ['username', 'match', 'pattern' => "/^.{3,50}$/", 'message' => Yii::t('backend', 'Minimum 3 and maximum 50 characters')],//minimo 3 y maximo 50 caracteres
            ['username', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => Yii::t('backend', 'Only letters and numbers are accepted')],//Sólo se aceptan letras y números
            ['username', 'username_existe'],
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//minimo 5 y maximo 80 caracteres
            ['email', 'email', 'message' => 'Formato no válido'],
            ['email', 'email_existe'],
            ['password', 'match', 'pattern' => "/^.{8,16}$/", 'message' => Yii::t('backend', 'Minimum 6 and maximum 16 characters')],//minimo 6 y maximo 16 caracteres
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('backend', 'The passwords do not match')],//los password no coinciden
        ];
    }
	
    // nombre de etiquetas
	 public function attributeLabels()
    {
        return [
		    'username' => Yii::t('frontend', 'Your username'),
            'email' => Yii::t('frontend', 'Your email address'), 
            'password' => Yii::t('frontend', 'Your password new'),
            'password_repeat' => Yii::t('frontend', 'Password repeat'),
        ];
    }

	
    public function email_existe($attribute, $params)
    {
   
       //Buscar el email en la tabla
       $table = Users::find()->where("email=:email", [":email" => $this->email]);
   
       //Si el email existe mostrar el error
       if ($table->count() == 1){

                $this->addError($attribute, Yii::t('frontend', 'The selected email exists'));
       }
    }
  
    public function username_existe($attribute, $params)
    {
       //Buscar el username en la tabla
       $table = Users::find()->where("username=:username", [":username" => $this->username]);
   
       //Si el username existe mostrar el error
       if ($table->count() == 1){
        
               $this->addError($attribute, Yii::t('frontend', 'The selected username exists'));
       }
    }
  
 
}

 ?>