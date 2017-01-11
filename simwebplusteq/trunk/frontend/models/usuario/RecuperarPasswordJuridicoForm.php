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
 *  @file VerificarPreguntasContribuyenteJuridicoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 28-01-2016
 * 
 *  @class VerificarPreguntasContribuyenteJuridicoForm
 *  @brief Clase que contiene las rules y los metodos para la validacion de las preguntas de seguridad del usuario juridico
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
 *  buscarPreguntaSeguridadJuridico
 *  
 *  @method
 *  
 *  
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use frontend\models\usuario\Afiliacion;
use frontend\models\usuario\CrearUsuarioNatural;
use frontend\models\usuario\PreguntaSeguridadContribuyente;
use yii\data\ActiveDataProvider;

class RecuperarPasswordJuridicoForm extends CrearUsuarioNatural
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
            [['cedula','tipo'],'integer'],
            [['naturaleza',  'cedula', 'tipo', 'email'], 'required' ],
            [['cedula'], 'validarLongitud'],
            ['cedula' ,'validarRif'],
            ['cedula' ,'validarEmailRif'],
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

    public function validarLongitud($attribute, $params)
      {
        //die('llegue');

       $longitud = strlen($this->naturaleza.$this->cedula.$this->tipo);


      // die($longitud));

        if ($longitud > 10){
          $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 10 characters'));
       
        }
      }

	  /**
     * [buscarIdAfiliaciones description] Metodo que busca el id del contribuyente en la tabla afiliaciones
     * @param  [type] $model [description] modelo que trae la informacion del contribuyente desde la tabla contribuyentes
     * y se utiliza el email para buscar en la tabla afiliaciones
     * @return [type]        [description] retorna una respuesta con la informacion buscada en caso de encontrarla, sino retorna false.
     */
    public function buscarIdAfiliaciones($model)
    {

        $validar = Afiliacion::find() 
                                 ->where([
                                
                                'login' => $model->email,
                                
                                ])
                                ->all();
                                
            if($validar != null){
        
                return $validar;  
        
            }else{

                return false;
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
                                'tipo' => $this->tipo,
                                'tipo_naturaleza' => 1,
                                'inactivo' => 0,
                              
                                ])
                                ->all();

            if($validar == null){
        
                $this->addError($attribute, Yii::t('frontend', 'El correo registrado no coincide, dirijase a la Alcaldia' ));
        
            }else{
                return false;
            }

    }
    /**
     * [validarEmailRif description] metodo que busca el email y el rif en la tabla contribuyentes para verificar si posee preguntas de seguridad
     * @param  [type] $attribute [description] atributos necesarios para enviar mensaje de error
     * @param  [type] $params    [description] parametros necesarios para enviar mensaje de error
     * @return [type]            [description] retorna un mensaje de error en caso de no encontrar al usuario en la tabla
     */
    public function validarRif($attribute, $params)
    { 
        
        $validar = CrearUsuarioNatural::find() 
                                ->where([
                                'naturaleza' => $this->naturaleza,
                                'cedula' => $this->cedula,
                                'tipo' => $this->tipo,
                                'tipo_naturaleza' => 1,
                                'inactivo' => 0,
                              
                                ])
                                ->all();

            if($validar == null){
        
                $this->addError($attribute, Yii::t('frontend', 'RIF invalido, verifique los datos si son correcto, ingrese por la opcion CREAR USUARIO' ));
        
            }else{
                return false;
            }

    }


    /**
     * 
     * [buscarIdContribuyente description] metodo que busca al usuario juridico en la tabla contribuyentes de manera masiva
     * llevando a comparacion todos los id contribuyentes previamente buscados en afiliacion
     * @param  [type] $idsContribuyente [description] ids de los contribuyentes encontrados y enviados para buscar en la tabla contribuyentes
     * @return [type]                   [description] retorna un data provider con las empresas asociadas a esos id que se buscaron previamente
     */
    public function buscarIdContribuyente($idsContribuyente)
    {
        //die(var_dump($idsContribuyente));

        $query = CrearUsuarioNatural::find();
                                       

                                      // die(var_dump($query));
           
        $dataProvider = new ActiveDataProvider([

                               'query' => $query

                                               ]);
          //  die(var_dump($dataProvider));
         $query->where(['tipo_naturaleza' => 1 , 'inactivo' => 0]);
        $query->andFilterWhere(['in', 'id_contribuyente' , $idsContribuyente]);

            
            return $dataProvider; 
                               
                     
    }
    /**
     * [buscarPreguntaSeguridadJuridico description] metodo que busca las preguntas de seguridad asociadas al id del contribuyente enviado
     * @param  [type] $id [description] id del contribuyente para buscar preguntas de seguridad asociadas a ese usuario.
     * @return [type]     [description] retorna las preguntas de seguridad encontradas en caso de que asi sea.
     */
    public function buscarPreguntaSeguridadJuridico($id)
    {
       
        $validarPreguntaSeguridad = PreguntaSeguridadContribuyente::find() 
                                ->where([
                                
                                'id_contribuyente' => $id,
                               // die($id),
                                'inactivo' => 0,
                              
                                ])
                                ->all();

            if($validarPreguntaSeguridad != null){
                
                return $validarPreguntaSeguridad;  
        
            } else {

                return false;
            } 
    } 

    /**
     * [buscarIdContribuyente description] Metodo que busca el id del contribuyente en la tabla contribuyentes
     * @param  [type] $model [description] modelo que trae la informacion del contribuyente desde la tabla contribuyentes
     * @return [type]        [description] retorna una respuesta con la informacion buscada en caso de encontrarla, sino retorna false.
     */
    public function buscarContribuyenteDatos($id)
    {

        $validarPregunta = CrearUsuarioNatural::find() 
                                ->where([
                                
                                'id_contribuyente' => $id,
                                
                              
                                ])
                                ->one();
                               
            if($validarPregunta != null){
        
                return $validarPregunta;  
        
            } else {

                return false;

            }
    } 
    
   
}

     
 

 