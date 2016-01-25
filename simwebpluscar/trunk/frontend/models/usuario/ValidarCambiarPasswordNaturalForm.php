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
            [['pregunta1',  'respuesta1', 'pregunta2', 'respuesta2', 'pregunta3', 'respuesta3', 'id_contribuyente'], 'required' ],
            ['respuesta1' , 'buscarPreguntasNatural1'],
            ['respuesta2' , 'buscarPreguntasNatural2'],
            ['respuesta3' , 'buscarPreguntasNatural3'],
           
                  
           
           
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
	
    public function buscarPreguntasNatural1($attribute, $params){


        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta1,
                                               'tipo_pregunta' => 0,
                                               'inactivo' => 0,
                                               

                                                ]);
                                               // ->one();
                                                //die(var_dump($buscarNatural));



        if($buscarNatural->count()== 0){
        
         $this->addError($attribute, Yii::t('frontend', 'The answer does not match'));

        } 

    }

       public function buscarPreguntasNatural2($attribute, $params){


        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta2,
                                               'tipo_pregunta' => 1,
                                               'inactivo' => 0,
                                               

                                                ]);
                                               // ->one();





        if($buscarNatural->count()== 0){
        
            $this->addError($attribute, Yii::t('frontend', 'The answer does not match'));
        
         }
     }

       public function buscarPreguntasNatural3($attribute, $params){


        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta3,
                                               'tipo_pregunta' => 2,
                                               'inactivo' => 0,
                                               

                                                ]);
                                                //->all();
                                                
                                              //  die(var_dump($buscarNatural));



        if($buscarNatural->count()== 0){
        
           $this->addError($attribute, Yii::t('frontend', 'The answer does not match')); 
        
        } 

    }
    
    
   

 }
 ?>

     
 

 