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
 *  @file BuscarFuncionarioUsuarioForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 03-05-2016
 * 
 *  @class BuscarFuncionarioUsuarioForm
 *  @brief Clase que permite validar la cedula del formulario buscarfuncionariousuarioform, 
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
 *  cedulaExiste
 *
 *
 *  @inherits
 *  
 */
namespace backend\models;
use Yii;
use yii\base\Model;
use backend\models\funcionario\Funcionario;
use common\models\Users;


class BuscarFuncionarioUsuarioForm extends Model{
  
    public $cedula;
    
     
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            
            ['cedula', 'required', 'message' => Yii::t('backend', 'required field')],//campo requerido
            ['cedula', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('backend', 'Minimum 5 and maximum 80 characters')],//Mínimo 5 y máximo 80 caracteres
            ['cedula', 'cedulaExiste'],
			//['cedula', 'username_registro'],
			
           
        ];
    }
   /**
    * [cedulaExiste description: busqueda de la cedula del funcionario]
    * @param  [type] $attribute [description] variable que contiene el mensaje de error
    * @param  [type] $params    [description] parametro para controlar el error
    * @return [type] $id        [description] id del funcionario
    */
    public function cedulaExiste($attribute, $params)
    {
   
         //Buscar el email en la tabla PreguntasUsuarios
         $table = Funcionario::find()->where(['ci'=>$this->cedula])->asArray()->all();
                                                
   die(var_dump($table));
         //Si la cedula no existe mostrar el error
         if ($table == false){

                 $this->addError($attribute, Yii::t('backend', 'The user does not exist'));
         }else{

           $id = $table[0]['id_funcionario'];
           $_SESSION['idFuncionario'] = $id;

         }
    }
	
	
	
	
}