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
 *  @file User.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class User
 *  @brief Clase que permite loguear al usuario comparando sus datos de acceso al sistema.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  findIdentity
 *  findIdentityByAccesToken
 *  findByUsername
 *  getId
 *  getAuthkey
 *  validateAuthkey
 *  validatePassword
 *  isUserAdmin
 *  isUserFuncionario
 *  isUserSimple
 *  
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use Yii;
use frontend\models\usuario\PreguntaSeguridadContribuyente;

use yii\base\Model;

class PreguntaSeguridadContribuyenteForm extends PreguntaSeguridadContribuyente 
{
     
    public $id_pregunta;
	public $usuario;
    public $password;
    public $id_contribuyente;
    public $pregunta1;
    public $respuesta1;
    public $pregunta2;
    public $respuesta2;
    public $pregunta3;
	public $respuesta3;
	public $inactivo;


      public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['pregunta1', 'pregunta2' , 'pregunta3' , 'respuesta1', 'respuesta2', 'respuesta3'], 'required' ],  
            [['pregunta1', 'pregunta2', 'respuesta1', 'respuesta2'], 'match', 'pattern' => "/^.{3,50}$/", 'message' => Yii::t('frontend', 'Minimum 3 and maximum 50 characters')],//minimo 3 y maximo 50 caracteres
            [['pregunta1', 'pregunta2', 'respuesta1', 'respuesta2'], 'match', 'pattern' => "/^[0-9 a-z]+$/i", 'message' => Yii::t('frontend', 'Accepted only letters, numbers and spaces')],//Sólo se aceptan letras, números y espacios en blanco
            ['usuario', 'match', 'pattern' => "/^.{5,80}$/", 'message' => Yii::t('frontend', 'Minimum 5 and maximum 80 characters')],//minimo 5 y maximo 80 caracteres
           
                  
           
           
        ];
    } 
    
    // nombre de etiquetas
     public function attributeLabels()
    {
        return [
                //'usuario' => Yii::t('frontend', 'Your Username'), // para multiples idiomas
                'pregunta1' => Yii::t('frontend', 'First Security Question '), //'Primera Pregunta de Seguridad',
                'pregunta2' => Yii::t('frontend', 'Second Security Question '), //'Segunda Pregunta de Seguridad',
                'pregunta3' => Yii::t('frontend', 'Third Security Question'), //'Primera Respuesta de seguridad',
                'respuesta1' => Yii::t('frontend', 'Security Answer'), //'Primera Respuesta de seguridad',
                'respuesta2' => Yii::t('frontend', 'Security Answer'), //'Segunda Respuesta de seguridad',
                'respuesta3' => Yii::t('frontend', 'Security Answer'), //'Segunda Respuesta de seguridad',
        ];
    }
	

    
    public function ValidarPreguntaSeguridad($model){

        $validarPregunta = PreguntaSeguridadContribuyente::find()
                                ->where([
                                'usuario' => $model->email,
                                'password' =>$model->password,
                                'inactivo' => 0,
                                ])
                                ->one();


        if($validarPregunta != null){
        
            return $validarPregunta;  
        
        } else {

        return false;
        //die('no encontro ');

        } 
   
}

     public function attributeContribuyentes()
  {

    return [
            
            'id_pregunta',
            'usuario',
            'password',
            'id_contribuyente',
            'pregunta1',
            'respuesta1',
            'pregunta2',
            'respuesta2',
            'pregunta3',
            'respuesta3',
            'inactivo',
            

            ];
  }
 }

 