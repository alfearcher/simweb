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
 *  @file CambiarPreguntaSeguridadForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 26-02-2016
 * 
 *  @class CambiarPreguntaSeguridadForm
 *  @brief Clase contiene las rules y metodos para cambiar las preguntas de seguridad del usuario
 * 
 *  
 * 
 *  
 *  
 * @property
 *
 *  
 *  @method
 * rules
 * attributeLabels
 * compararPreguntas1
 * compararPreguntas2
 * compararPreguntas3
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





class CambiarPreguntaSeguridadForm extends Model
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
            [[  'pregunta1', 'respuesta1',  'pregunta2','respuesta2' ,'pregunta3','respuesta3'], 'required' ],
           ['pregunta1' , 'compararPreguntas1'],
           ['pregunta2', 'compararPreguntas2'],
            ['pregunta3', 'compararPreguntas3'],
           
                  
           
           
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
      
   
      /**
     * [compararPreguntas1 description] metodo que valida las preguntas de seguridad para que no se envien dos o todas las preguntas iguales
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] retorna mensajes de error indicando que alguna pregunta esta repetida
     */
    public function compararPreguntas1($attribute, $params){ 

        if ($this->pregunta1 == $this->pregunta2 and $this->pregunta1 == $this->pregunta3){

            $this->addError($attribute, Yii::t('frontend', 'All questions are repeated'));
        }

        if ($this->pregunta1 ==  $this->pregunta2){

            $this->addError($attribute, Yii::t('frontend', 'first and second questios are repeated'));
        }

        if ($this->pregunta1 == $this->pregunta3){

            $this->addError($attribute, Yii::t('frontend', 'first and third questions are repeated'));

        }


        
     }

      /**
     * [compararPreguntas2 description] metodo que valida las preguntas de seguridad para que no se envien dos o todas las preguntas iguales
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] retorna mensajes de error indicando que alguna pregunta esta repetida
     */
     public function compararPreguntas2($attribute, $params){ 

        if ($this->pregunta2 == $this->pregunta1 and $this->pregunta2 == $this->pregunta3){

            $this->addError($attribute, Yii::t('frontend', 'All questions are repeated'));
        }

        if ($this->pregunta2 ==  $this->pregunta3){

            $this->addError($attribute, Yii::t('frontend', 'Second and third questios are repeated'));
        }

        if ($this->pregunta2 == $this->pregunta1){

            $this->addError($attribute, Yii::t('frontend', 'first and Second questions are repeated'));

        }


        
     }

      /**
     * [compararPreguntas3 description] metodo que valida las preguntas de seguridad para que no se envien dos o todas las preguntas iguales
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] retorna mensajes de error indicando que alguna pregunta esta repetida
     */
     public function compararPreguntas3($attribute, $params){ 

        if ($this->pregunta3 == $this->pregunta2 and $this->pregunta3 == $this->pregunta1){

            $this->addError($attribute, Yii::t('frontend', 'All questions are repeated'));
        }

        if ($this->pregunta3 ==  $this->pregunta2){

            $this->addError($attribute, Yii::t('frontend', 'Second and third questios are repeated'));
        }

        if ($this->pregunta3 == $this->pregunta1){

            $this->addError($attribute, Yii::t('frontend', 'first and third questions are repeated'));

        }


        
     }
    
   

    }