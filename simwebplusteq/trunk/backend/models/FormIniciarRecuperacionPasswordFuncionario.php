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
 *  @file FormIniciarRecuperacionPasswordFuncionario.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class FormIniciarRecuperacionPasswordFuncionario
 *  @brief Clase que permite validar el username formulario iniciarrecuperacionpasswordfuncionario, 
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


class FormIniciarRecuperacionPasswordFuncionario extends Model{
  
    public $username;
    
     
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            
            ['username', 'required', 'message' => Yii::t('backend', 'required field')],//campo requerido
            ['username', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//Mínimo 5 y máximo 80 caracteres
            ['username', 'username_existe'],
			['username', 'username_registro'],
			
           
        ];
    }
   
    public function username_existe($attribute, $params)
    {
   
         //Buscar el email en la tabla PreguntasUsuarios
         $table = Users::find()->where("username=:username", [":username" => $this->username]);
   
         //Si el email existe mostrar el error
         if ($table->count() == 0){

                 $this->addError($attribute, Yii::t('backend', 'The user does not exist'));
         }
    }
	
	public function username_registro($attribute, $params)
    {
  
          //Buscar el email en la tabla 
          
          $table = PreguntasUsuarios::find()->where("usuario=:username", [":username" => $this->username])->andWhere("estatus=:estatus", ["estatus" => 0]); 
		 
		  //Si la consulta no cuenta (0) mostrar el error
		  if ($table->count() == 0){
            
                    $this->addError($attribute, Yii::t('backend', 'The user has not already assigned secret questions'));
	      }
		 
		 
    }
	
	
}