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
 *  @file ValidarCambiarPasswordJuridicoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 25-01-2016
 * 
 *  @class ValidarCambiarPasswordJuridicoForm
 *  @brief Clase contiene las rules y metodos para las preguntas de seguridad del usuario
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 * rules
 * attributeLabels
 * buscarPreguntasJuridico1
 * buscarPreguntasJuridico2
 * buscarPreguntasJuridico3
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

class ValidarCambiarPasswordJuridicoForm extends Model
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
            ['respuesta1' , 'buscarPreguntasJuridico1'],
            ['respuesta2' , 'buscarPreguntasJuridico2'],
            ['respuesta3' , 'buscarPreguntasJuridico3'],
           
                  
           
           
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
     * [buscarPreguntasJuridico1 description] metodo para buscar la pregunta 1 y respuesta de esa pregunta en la tabla 
     * preg_seg_contribuyentes junto con el id del usuario.
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] si encontro pregunta, salta al otro metodo de validacion, de los contrario envia
     * mensaje de error
     */
    public function buscarPreguntasJuridico1($attribute, $params)
    {

        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta1,
                                               'tipo_pregunta' => 0,
                                               'inactivo' => 0,
                                               

                                                ]);
                                               

            if($buscarNatural->count()== 0){
        
                $this->addError($attribute, Yii::t('frontend', 'The answer does not match'));

            } 

    }

    /**
     * [buscarPreguntasJuridico1 description] metodo para buscar la pregunta 2 y respuesta de esa pregunta en la tabla 
     * preg_seg_contribuyentes junto con el id del usuario.
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] si encontro pregunta, salta al otro metodo de validacion, de los contrario envia
     * mensaje de error
     */
    public function buscarPreguntasJuridico2($attribute, $params)
    {

        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta2,
                                               'tipo_pregunta' => 1,
                                               'inactivo' => 0,
                                               

                                                ]);
                                               

            if($buscarNatural->count()== 0){
        
                $this->addError($attribute, Yii::t('frontend', 'The answer does not match'));
        
            }
    }

    /**
     * [buscarPreguntasJuridico1 description] metodo para buscar la pregunta 3 y respuesta de esa pregunta en la tabla 
     * preg_seg_contribuyentes junto con el id del usuario.
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] si encontro pregunta, salta al otro metodo de validacion, de los contrario envia
     * mensaje de error
     */
    public function buscarPreguntasJuridico3($attribute, $params)
    {

        $buscarNatural = PreguntaSeguridadContribuyente::find()
                                               
                                               ->where([
                                               'id_contribuyente' => $this->id_contribuyente,
                                               'respuesta' => $this->respuesta3,
                                               'tipo_pregunta' => 2,
                                               'inactivo' => 0,
                                               

                                                ]);
                                                
            if($buscarNatural->count()== 0){
        
                $this->addError($attribute, Yii::t('frontend', 'The answer does not match')); 
        
            } 

    }
    
    
   

 }
 ?>

     
 

 