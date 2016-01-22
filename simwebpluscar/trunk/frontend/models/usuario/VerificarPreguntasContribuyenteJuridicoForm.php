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
use yii\base\Model;
use frontend\models\usuario\Afiliacion;

class VerificarPreguntasContribuyenteJuridicoForm extends Afiliacion 
{
     
    public $id_contribuyente;
	public $usuario;
    public $email;
    public $naturaleza;
    public $cedula;
    public $tipo;
    


      public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['naturaleza',  'cedula', 'tipo', 'email'], 'required' ],
           
                  
           
           
        ];
    } 
    
    // nombre de etiquetas
     public function attributeLabels()
    {
        return [
                //'usuario' => Yii::t('frontend', 'Your Username'), // para multiples idiomas
                'naturaleza' => Yii::t('frontend', 'Naturaleza'), //'Primera Pregunta de Seguridad',
                'cedula' => Yii::t('frontend', 'Cedula'), //'Primera Pregunta de Seguridad',
                'tipo' => Yii::t('frontend', 'Tipo'), //'Primera Pregunta de Seguridad',
                'email' => Yii::t('frontend', 'Email '), //'Segunda Pregunta de Seguridad',
                
        ];
    }
	

    
    // public function ValidarPreguntaSeguridad($model, $id_contribuyente){

    //    // die(var_dump($model));

    //     $validarPregunta = PreguntaSeguridadContribuyente::find()
    //                             ->where([
    //                             'usuario' => $model->email,
    //                             'id_contribuyente' => $id_contribuyente,
                              
    //                             'inactivo' => 0,
    //                             ])
    //                             ->one();



    //     if($validarPregunta != null){
        
    //         return $validarPregunta;  
        
    //     } else {

    //     return false;
    //     //die('no encontro ');

    //     } 
   
}

     
 

 