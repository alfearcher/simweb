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
 *  @file FormRecuperarPasswordFuncionario.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class FormRecuperarPasswordFuncionario
 *  @brief Clase que permite validar cada una de las preguntas de seguridad del formulario recuperarpasswordfuncionario, 
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
 *  username_existe
 *  username_registro
 *  preguntav1
 *  respuestav1
 *  preguntav2
 *  respuestav2
 *
 *  @inherits
 *  
 */
namespace backend\models;
use Yii;
use yii\base\Model;
use backend\models\PreguntasUsuarios;
use common\models\Users;


class FormRecuperarPasswordFuncionario extends Model{
  
    
    public $user;
    public $pregunta1;
    public $respuesta1;
	public $pregunta2;
    public $respuesta2;
	
	
     
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['pregunta1', 'user', 'pregunta2', 'respuesta1', 'respuesta2'], 'required', 'message' => Yii::t('backend', 'Required field')],//campo requerido
            [['pregunta1', 'pregunta2', 'respuesta1', 'respuesta2'], 'match', 'pattern' => "/^.{3,50}$/", 'message' => Yii::t('backend', 'Minimum 3 and maximum 50 characters')],//minimo 3 y maximo 50 caracteres
            [['pregunta1', 'pregunta2', 'respuesta1', 'respuesta2'], 'match', 'pattern' => "/^[0-9 a-z]+$/i", 'message' => Yii::t('backend', 'Only letters and numbers are accepted')],//Sólo se aceptan letras y números
            
            ['user', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//minimo 5 y maximo 80 caracteres
            ['user', 'username_existe'],
			['user', 'username_registro'],
			['pregunta1', 'preguntav1'],
			['respuesta1', 'respuestav1'],
			['pregunta2', 'preguntav2'],
			['respuesta2', 'respuestav2'],
           
        ];
    }

    public function attributeLabels()
    {
        return [
                'user' => Yii::t('backend', 'Your Username'), 
                'pregunta1' => Yii::t('backend', 'Question Security'), //'Primera Pregunta de Seguridad',
                'pregunta2' => Yii::t('backend', 'Question Security'), //'Segunda Pregunta de Seguridad',
                'respuesta1' => Yii::t('backend', 'Security Answer'), //'Primera Respuesta de seguridad',
			    'respuesta2' => Yii::t('backend', 'Security Answer'), //'Segunda Respuesta de seguridad',
        ];
    }
   
    public function username_existe($attribute, $params)
    {
   
         //Buscar el email en la tabla PreguntasUsuarios
         $table = Users::find()->where("username=:username", [":username" => $this->user]);
   
         //Si el email existe mostrar el error
         if ($table->count() == 0){

                 $this->addError($attribute, Yii::t('backend', 'The user does not exist'));
         }
    }
	
	public function username_registro($attribute, $params)
    {
  
          //Buscar el email en la tabla 
		  
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->user])->andWhere("estatus=:estatus", ["estatus" => 0]); 
		  
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0) {

                    $this->addError($attribute, Yii::t('backend', 'The user has not already assigned secret questions'));
	      }
		 
		 
    }
	//-------VALIDACIONES DE LAS PREGUNTAS DE USUARIOS--------
	//
	//
	public function preguntav1($attribute, $params)
    {
  
          //Buscar el usuario y la pregunta1 en la tabla 
		  
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->user])
		                                    ->andwhere("tipo_pregunta=:tipo_pregunta",["tipo_pregunta" => 0])
										    ->andwhere("pregunta=:pregunta1", [":pregunta1" => $this->pregunta1])
											->andWhere("estatus=:estatus", ["estatus" => 0]); 
		 
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0){

                    $this->addError($attribute, Yii::t('backend', 'The selected question is not assigned by the user as first security question'));
	      }
    }
	public function respuestav1($attribute, $params)
    {
  
          //Buscar el usuario y la respuesta1 en la tabla 
	      
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->user])
		                                    ->andwhere("tipo_pregunta=:tipo_pregunta",["tipo_pregunta" => 0])
										    ->andwhere("respuesta=:respuesta1", [":respuesta1" => $this->respuesta1])
											->andWhere("estatus=:estatus", ["estatus" => 0]); 
		
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0){

                    $this->addError($attribute, Yii::t('backend', 'The answer is not correct'));
	      }
    }
	public function preguntav2($attribute, $params)
    {
  
          //Buscar el usuario y la pregunta2 en la tabla 
		  
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->user])
		                                    ->andwhere("tipo_pregunta=:tipo_pregunta",["tipo_pregunta" => 1])
										    ->andwhere("pregunta=:pregunta2", [":pregunta2" => $this->pregunta2])
											->andWhere("estatus=:estatus", ["estatus" => 0]); 
		  
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0){

                    $this->addError($attribute, Yii::t('backend', 'The selected question is not assigned by the user as the second security question'));
	      }
    }
	public function respuestav2($attribute, $params)
    {
  
          //Buscar el usuario y la respuesta2 en la tabla 
		  
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->user])
		                                    ->andwhere("tipo_pregunta=:tipo_pregunta",["tipo_pregunta" => 1])
										    ->andwhere("respuesta=:respuesta2", [":respuesta2" => $this->respuesta2])
											->andWhere("estatus=:estatus", ["estatus" => 0]); 
		 
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0){
		  	
                    $this->addError($attribute, Yii::t('backend', 'The answer is not correct'));
	      }
    }
	
}