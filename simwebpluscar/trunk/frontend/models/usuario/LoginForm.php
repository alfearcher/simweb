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
 *  @file LoginForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class LoginForm
 *  @brief Clase que permite validar cada uno de los datos del formulario login.
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
 *  validatePassword
 *  login
 *  getUser
 *
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use Yii;
use yii\base\Model;

/**
 * LoginForm es el model del login de acceso.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;
    public $salt;
	
	
    private $_user = false;


    /**
     * Metodo que retorna los roles de validacion.
     */
    public function rules()
    {
        return [
            // username y password son requeridos
            [['email', 'password'], 'required', 'message' => 'Campo requerido'],
            // rememberMe es un valor booleano
            ['rememberMe', 'boolean'],
            // password es validado por validatePassword()
            ['password', 'validatePassword'],
        ];
    }
	/*
	 *  Metodo que retorna los nombres de los atributos
	 */
	public function attributeLabels()
{ 
    return [
      'email' => Yii::t('frontend', 'Email'),
      'password' => Yii::t('frontend', 'Password'),
	  'rememberMe' => Yii::t('frontend', 'RememberMe'),
              ];
}

    /**
     * Validates the password.
     * Este metodo realiza la validation del password.
     */
    public function validatePassword($attribute, $params)
    {   
	   
	    //-----salt-------
		$password = $this->password + $this->salt;
		
		if (!$this->hasErrors()) {
            $afiliaciones = $this->getAfiliaciones();

            if (!$afiliaciones || !$afiliaciones->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario incorrecto.');
            }
        } //  fin validate password */                          
    }

    
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAfiliaciones(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    
    public function getAfiliaciones()
    {
        if ($this->_user === false) {
            $this->_user = Afiliaciones::findByUsername($this->email);
        }

        return $this->_user;
    }
}
