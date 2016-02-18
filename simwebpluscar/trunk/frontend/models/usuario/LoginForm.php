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
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 11-01-2016
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
use common\models\utilidades\Utilidad;
use frontend\models\usuario\Afiliaciones;

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

     public function login()
    {
      //  die('llegue a loginform');
        if ($this->validate()) {
           // die(var_dump(Yii::$app->user->identity));
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }
	
    public function getUser()
    {
        
       // die($this->login);
        if ($this->_user === false) {
            $this->_user = Afiliaciones::findByUsername($this->email);
        }

        return $this->_user;
    }
    
   
}
