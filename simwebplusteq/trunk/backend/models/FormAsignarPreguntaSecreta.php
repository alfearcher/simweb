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
 *  @file FormAsignarPreguntaSecreta.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class FormAsignarPreguntaSecreta
 *  @brief Clase que permite validar cada uno de los datos del formulario asignarpreguntasecreta, 
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
 *  usuario_existe
 *  
 *
 *  @inherits
 *  
 */ 
namespace backend\models;
use Yii;
use yii\base\Model;
use backend\models\PreguntasUsuarios;
use common\models\Users;

class FormAsignarPreguntaSecreta extends Model{
  
    public $email;
    public $usuario;
    public $pregunta_secreta1;
    public $respuesta_secreta1;
    public $pregunta_secreta2;
    public $respuesta_secreta2;
     
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['usuario', 'pregunta_secreta1', 'pregunta_secreta2', 'respuesta_secreta1', 'respuesta_secreta2'], 'required', 'message' => Yii::t('backend', 'Required field')],//campo requerido
            [['pregunta_secreta1', 'pregunta_secreta2', 'respuesta_secreta1', 'respuesta_secreta2'], 'match', 'pattern' => "/^.{3,50}$/", 'message' => Yii::t('backend', 'Minimum 3 and maximum 50 characters')],//minimo 3 y maximo 50 caracteres
            [['pregunta_secreta1', 'pregunta_secreta2', 'respuesta_secreta1', 'respuesta_secreta2'], 'match', 'pattern' => "/^[0-9 a-z]+$/i", 'message' => Yii::t('backend', 'Accepted only letters, numbers and spaces')],//Sólo se aceptan letras, números y espacios en blanco
            ['usuario', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//minimo 5 y maximo 80 caracteres
            ['usuario', 'usuario_existe'],
			      
           
           
        ];
    } 
	
	// nombre de etiquetas
	 public function attributeLabels()
    {
        return [
                'usuario' => Yii::t('backend', 'Your Username'), // para multiples idiomas
                'pregunta_secreta1' => Yii::t('backend', 'First Question Security'), //'Primera Pregunta de Seguridad',
                'pregunta_secreta2' => Yii::t('backend', 'Second Question Security'), //'Segunda Pregunta de Seguridad',
                'respuesta_secreta1' => Yii::t('backend', 'Security Answer'), //'Primera Respuesta de seguridad',
			    'respuesta_secreta2' => Yii::t('backend', 'Security Answer'), //'Segunda Respuesta de seguridad',
        ];
    }
    
	
//--------VALIDACIONES DEL EMAIL--------
//
// 
    public function usuario_existe($attribute, $params)
    {
  
          //Buscar el email en la tabla 
		  
          $table = PreguntasUsuarios::find()->where("usuario=:usuario", [":usuario" => $this->usuario])->andWhere("estatus=:estatus", ["estatus" => 0]); //->andWhere("estatus=:estatus", [":estatus" => 0]);
		      //Si el email  existe mostrar el error
		 
		      if ($table->count() == 2){
                
                $this->addError($attribute, Yii::t('backend', 'The user has already assigned secret questions'));
	        }
		 
		 
    }
	
	 
	
   
		
}