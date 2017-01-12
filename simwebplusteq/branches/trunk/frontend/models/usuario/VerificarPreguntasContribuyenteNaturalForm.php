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
 * @file VerificarPreguntasContribuyenteNaturalForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 28-01-2016
 * 
 *  @class VerificarPreguntasContribuyenteNaturalForm
 *  @brief Clase que contiene las rules y los metodos para la validacion de las preguntas de seguridad del usuario natural
 * 
 *  
 * 
 *  
 *  
 *  @property
 *  rules
 *  attributeLabels
 *  buscarIdAfiliaciones
 *  validarEmailRif
 *  buscarIdContribuyente
 *  buscarPreguntaSeguridadNatural
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

class VerificarPreguntasContribuyenteNaturalForm extends CrearUsuarioNatural
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
             ['cedula', 'integer'],
            [['naturaleza',  'cedula', 'email'], 'required' ],
            [['cedula'], 'validarLongitud'],
            ['cedula' , 'validarEmailRif'],
           
             
            // ['email' , 'validarEmail'],
                  
           
           
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


    public function validarLongitud($attribute, $params)
      {
       // die('llegue');

       $longitud = strlen($this->naturaleza.$this->cedula.$this->tipo);


      // die($longitud));

        if ($longitud > 9){
          $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 10 characters'));
       
        }
      }


    /**
     * [validarEmailRif description] metodo que busca el email y el rif en la tabla contribuyentes para verificar si posee preguntas de seguridad
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] retorna un mensaje de error en caso de no encontrar al usuario en la tabla
     */
    public function validarEmailRif($attribute, $params)
    { 
        
        $validar = CrearUsuarioNatural::find() 
                                ->where([
                                'naturaleza' => $this->naturaleza,
                                'cedula' => $this->cedula,
                                'email' => $this->email,
                                'tipo_naturaleza' => 0,
                                'inactivo' => 0,
                              
                                ])
                                ->all();

            if($validar == null){
        
          
                $this->addError($attribute, Yii::t('frontend', 'This user does not exists' ));
        
            }else{
                
                return false;
            }

    }
      /**
     * [buscarIdContribuyente description] Metodo que busca el id del contribuyente en la tabla contribuyentes
     * @param  [type] $model [description] modelo que trae la informacion del contribuyente desde la tabla contribuyentes
     * @return [type]        [description] retorna una respuesta con la informacion buscada en caso de encontrarla, sino retorna false.
     */
    public function buscarIdContribuyente($model)
    {

        $validarPregunta = CrearUsuarioNatural::find() 
                                ->where([
                                
                                'naturaleza' => $model->naturaleza,
                                'cedula' => $model->cedula,
                                'tipo' => 0,
                                'tipo_naturaleza' =>0,
                              
                                ])
                                ->one();
                               
            if($validarPregunta != null){
        
                return $validarPregunta;  
        
            } else {

                return false;

            }
    } 

    /**
     * [buscarIdAfiliaciones description] Metodo que busca el id del contribuyente en la tabla afiliaciones
     * @param  [type] $model [description] modelo que trae la informacion del contribuyente desde la tabla contribuyentes
     * y se utiliza el email para buscar en la tabla afiliaciones
     * @return [type]        [description] retorna una respuesta con la informacion buscada en caso de encontrarla, sino retorna false.
     */
    public function buscarIdAfiliaciones($id_contribuyente)
    {

        $afiliacion = new Afiliacion();

        $validarPregunta = Afiliacion::find() 
                                ->where([
                                
                                
                                'id_contribuyente' => $id_contribuyente,

                                
                              
                                ])
                                ->one();
                               
            if($validarPregunta != null){
        
                return $validarPregunta;  
        
            } else {

                return false;
            }
    } 

      /**
     * [buscarPreguntaSeguridadNatural description] metodo que busca las preguntas de seguridad asociadas al id del contribuyente enviado
     * @param  [type] $id [description] id del contribuyente para buscar preguntas de seguridad asociadas a ese usuario.
     * @return [type]     [description] retorna las preguntas de seguridad encontradas en caso de que asi sea.
     */    
    public function buscarPreguntaSeguridad($id_contribuyente){ 
        
        $validarPreguntaSeguridad = PreguntaSeguridadContribuyente::find() 
                                ->where([
                                
                                'id_contribuyente' => $id_contribuyente,
                                'inactivo' => 0,
                              
                                ])
                                ->all();

            if($validarPreguntaSeguridad != null){
        
                return $validarPreguntaSeguridad;  
        
            } else {

                return false;
        
            } 
    }
   

 }

     
 

 