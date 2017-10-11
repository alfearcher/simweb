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
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\funcionario\Funcionario;

/**
 * LoginForm es el model del login de acceso.
 */
class LoginForm extends Model
{
    public $username;
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
            [['username', 'password'], 'required', 'message' => 'Campo requerido'],
            // rememberMe es un valor booleano
            ['rememberMe', 'boolean'],
            ['username', 'validateFuncionarioExiste'],
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
      'username' => Yii::t('backend', 'Username'),
      'password' => Yii::t('backend', 'Password'),
	  'rememberMe' => Yii::t('backend', 'RememberMe'),
              ];
}

    /**
     * Validates the password.
     * Este metodo realiza la validation del password.
     */
    public function validatePassword($attribute, $params)
    {   
	   
	    //-----salt-------
		//$password = $this->password;
		
		if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) { 
                $this->addError($attribute, 'Usuario o password incorrecto.');
            }
        } //  fin validate password */                          
    } 

    
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Validates the password.
     * Este metodo realiza la validation del password.
     */
    public function validateFuncionarioExiste($attribute, $params)
    {   
       
        //-----salt-------
        $username = $this->username;
        $buscar = Funcionario::find()->where("login=:login", [":login" => $username])
                                     ->asArray()->all();

        
        if ($buscar == null){
           
                  $this->addError($attribute, Yii::t('backend', 'El funcionario no se encuentra registrado')); 
        } else {
                  
                   $vigencia = $buscar[0]['vigencia'];

                   if(date('Y-m-d') > $vigencia ){

                          if ($buscar[0]['status_funcionario'] == 1) {

                              $this->addError($attribute, Yii::t('backend', 'El funcionario se encuentra inactivo'));
                         }
 
                          $this->addError($attribute, Yii::t('backend', 'El funcionario no se encuentra vigente para el registro'));

                     } else {

                          if ($buscar[0]['status_funcionario'] == 1) {

                              $this->addError($attribute, Yii::t('backend', 'El funcionario se encuentra inactivo'));
                          }

                    }
        }                         
    }

     
} 
