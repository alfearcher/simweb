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
 *  @file FormCambiarPasswordFuncionario.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class FormCambiarPasswordFuncionario
 *  @brief Clase que permite validar cada uno de los datos del formulario cambiarpasswordfuncionario, 
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
 *  email_registrado
 *  email_iniciado
 *  
 *
 *  @inherits
 *  
 */ 
namespace backend\models;
use Yii;
use yii\base\Model;
use common\models\Users;

class FormCambiarPasswordFuncionario extends Model{
  
    public $email;
	  public $password;
	  public $password_repeat;
    public $recover;
      
    public function rules()
    {
        return [
            [['email', 'password', 'password_repeat'], 'required', 'message' => Yii::t('backend', 'Required field')],//campo requerido
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//minimo 5 y maximo 80 caracteres
            ['email', 'email', 'message' => Yii::t('backend', 'Invalid format')],//Formato no válido
			      ['password', 'match', 'pattern' => "/^.{8,16}$/", 'message' => Yii::t('backend', 'Minimum 8 and maximum 16 characters')],//minimo 8 y maximo 16 caracteres
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('backend', 'The passwords do not match')],//los password no coinciden
			      ['email', 'email_registrado'],
			      ['email', 'email_iniciado'],
        ];
    }
	
	
	// nombre de etiquetas
	  public function attributeLabels()
    {
        return [
            'email' => Yii::t('backend', 'Your email address'), 
            'password' => Yii::t('backend', 'Your new password'),
            'password_repeat' => Yii::t('backend', 'Password repeat'),
        ];
    }
	
//--------VALIDACIONES DEL EMAIL--------
//
//
    public function email_registrado($attribute, $params)
    {
 
          //Buscar el email en la tabla 
          
          $table = Users::find()->where("email=:email", [":email" => $this->email]);
          //Si el email  existe mostrar el error
 
          if ($table->count() == 0){

                   $this->addError($attribute, Yii::t('backend', 'The email entered is not registered as a user'));//El email ingresado no se encuentra registrado como usuario
	        }
	 
	  }
	
	  public function email_iniciado($attribute, $params)
    {
  
          //Buscar el email en la tabla 
          $iniciado = Yii::$app->user->identity->email; 
		  
          $table = Users::find()->where("email=:email", [":email" => $this->email]); 
          //Si el email  existe mostrar el error
		      $email = $this->email;
 
          if ($table->count() == 1){

		           if($email != $iniciado){

                   $this->addError($attribute, Yii::t('backend', 'The entered email is not the users start session'));//El email ingresado no es del usuario que inicio session
	             }      
          }
	    }
		

}