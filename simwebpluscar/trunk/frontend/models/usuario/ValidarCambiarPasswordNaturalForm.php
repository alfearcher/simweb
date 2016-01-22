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
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use frontend\models\usuario\Afiliacion;

class ValidarCambiarPasswordNaturalForm extends Model
{
     
    public $id_contribuyente;
    public $email;
	public $pregunta1;
    public $respuesta1;
    public $pregunta2;
    public $respuesta2;
    public $pregunta3;
    public $respuesta3;
    public $tipo;
    


      public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios     
        return [
            [['pregunta1',  'respuesta1', 'pregunta2', 'respuesta2', 'pregunta3', 'respuesta3'], 'required' ],
           
                  
           
           
        ];
    } 
    
    // nombre de etiquetas
     public function attributeLabels()
    {
        return [
                //'usuario' => Yii::t('frontend', 'Your Username'), // para multiples idiomas
                'naturaleza' => Yii::t('frontend', 'Naturaleza'), //'Primera Pregunta de Seguridad',
                'cedula' => Yii::t('frontend', 'Cedula'), //'Primera Pregunta de Seguridad',
               
                'email' => Yii::t('frontend', 'Email '), //'Segunda Pregunta de Seguridad',
                
        ];
    }
	

    
    // public function buscarIdContribuyente($model){

    //    // die(var_dump($model));

    //     $validarPregunta = CrearUsuarioNatural::find() 
    //                             ->where([
                                
    //                             'naturaleza' => $model->naturaleza,
    //                             'cedula' => $model->cedula,
    //                             'tipo' => 0,
    //                             'tipo_naturaleza' =>0,
                              
    //                             ])
    //                             ->one();
                               
                            

    //     if($validarPregunta != null){
        
    //         return $validarPregunta;  
        
    //     } else {

    //     return false;
    //     //die('no encontro ');

    //     }
    //      } 


    //      public function buscarIdAfiliaciones($id_contribuyente){

    //         $afiliacion = new Afiliacion();

    //     $validarPregunta = Afiliacion::find() 
    //                             ->where([
                                
                                
    //                             'id_contribuyente' => $id_contribuyente,

                                
                              
    //                             ])
    //                             ->one();
                               
                            

    //     if($validarPregunta != null){
        
    //         return $validarPregunta;  
        
    //     } else {

    //     return false;
    //     //die('no encontro ');

    //     }
    //      } 

         

    //     public function buscarPreguntaSeguridad($id_contribuyente){ 
    //         //die($id_contribuyente);

          

    //         $validarPreguntaSeguridad = PreguntaSeguridadContribuyente::find() 
    //                             ->where([
                                
    //                             'id_contribuyente' => $id_contribuyente,
                                
                              
    //                             ])
    //                             ->all();

    //                             //die(var_dump($validarPreguntaSeguridad));
    //              // die(var_dump($validarPreguntaSeguridad));             
                             

    //     if($validarPreguntaSeguridad != null){
        
    //         return $validarPreguntaSeguridad;  
        
    //     } else {

    //     return false;
    //     //die('no encontro ');

    //     } 
    //     }
   

 }

     
 

 