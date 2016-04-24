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
 *  @file FuncionarioForm.php
 *  
 *  @author Jose Rafael Perez Teran
 * 
 *  @date 07-07-2015
 * 
 *  @class FuncionarioForm
 *  @brief Clase Modelo del formulario de creacion de funcionarios, mantiene las reglas de validacion
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *  
 *  
 *  @inherits
 *  
 */


namespace backend\models\funcionario;

use Yii;
use yii\base\Model;
//use yii\db\ActiveRecord;
use backend\models\funcionario\Funcionario;
use common\conexion\ConexionController;

/**
 * This is the model class for table "funcionarios".
 *
 * @property string $id_funcionario
 * @property string $entes_ente
 * @property string $ci
 * @property string $apellidos
 * @property string $nombres
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $status_funcionario         //  1 => inactivo
 * @property string $en_uso
 * @property string $login
 * @property string $clave11
 * @property string $niveles_nivel
 * @property string $cargo
 * @property string $vigencia
 * @property string $id_departamento
 * @property string $id_unidad
 * @property string $email
 * @property string $celular
 * @property string $naturaleza
 */

	/**
	 *	Clase principal del formulario _form vista de funcionario.
	 */
	class FuncionarioForm extends Funcionario
	{

	    public $id_funcionario;
	    public $entes_ente;
	    public $ci;
	    public $apellidos;
	    public $nombres;
	    public $fecha_inicio;
	    public $fecha_fin;
	    public $status_funcionario;         //  1 => inactivo
	    public $en_uso;
	    public $login;
	    public $clave11;
	    public $niveles_nivel;
	    public $cargo;
	    public $vigencia;
	    public $id_departamento;
	    public $id_unidad;
	    public $email;
	    public $celular;
	    public $naturaleza;
	    public $fecha_inclusion;


    	/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



    	/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario _form
    	 */
	    public function rules()
	    {
	        return [
	            [['naturaleza','ci','email','id_departamento', 'id_unidad','cargo', 'apellidos', 'nombres', 'niveles_nivel', 'fecha_inicio'],'required'],
	            [['ci', 'id_departamento', 'id_unidad'], 'integer'],
	            ['email', 'email'],
	            ['email', 'filter','filter'=>'strtolower'],
	            ['ci', 'unique'],
	            [['celular'], 'string'],
	           // [['fecha_inicio'], 'date', 'format' => 'd-M-yyyy'],
	            ['fecha_inicio', 'fechaInicioValida'],
	            //['fecha_inicio', 'date'=>'dd-MM-yyyy'],
	            [['login', 'clave11'], 'default', 'value' => null],
	            ['en_uso', 'default', 'value' => 0],
	            ['niveles_nivel', 'default', 'value' => 0],
	             // status_funcionario default value = 1, funcionario inactivo
	            ['status_funcionario', 'default', 'value' => 0],
	            ['entes_ente', 'default', 'value' => Yii::$app->ente->getEnte()],
	            [['fecha_fin', 'vigencia'], 'default', 'value' => '0000-00-00'],
	            ['fecha_inclusion', 'default', 'value' => date('Y-m-d')],

	        ];
	    }


	    /**
	    *	Valida que el valor del campo fecha_inicio no sea mayor a la fecha actaul.
	    *
	    * 	@return boolean, true o false
	    */
	    public static function fechaInicioValida($attribute, $params)
	    {
	    	$fechaActual = date('Y-m-d');		// Fecha actual, hoy.

	    	//die(var_dump(parent::getAttributes($attribute)));

	    	/*if ( $fechaActual < $this->params ) {

	    	}*/

	    	return true;
	    }



	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	            'id_funcionario' => Yii::t('backend', 'Id Funcionario'),
	            'entes_ente' => Yii::t('backend', 'Entes Ente'),
	            'ci' => Yii::t('backend', 'Identity Card Number'),
	            'apellidos' => Yii::t('backend', 'Last Name'),
	            'nombres' => Yii::t('backend', 'Name'),
	            'fecha_inicio' => Yii::t('backend', 'Date of Admission'),
	            'fecha_fin' => Yii::t('backend', 'End Start'),
	            'status_funcionario' => Yii::t('backend', 'Status Funcionario'),
	            'en_uso' => Yii::t('backend', 'En Uso'),
	            'login' => Yii::t('backend', 'Login'),
	            'clave11' => Yii::t('backend', 'Clave11'),
	            'niveles_nivel' => Yii::t('backend', 'User Level'),
	            'cargo' => Yii::t('backend', 'Post Office'),
	            'vigencia' => Yii::t('backend', 'Vality Date'),
	            'id_departamento' => Yii::t('backend', 'Departament'),
	            'id_unidad' => Yii::t('backend', 'Work Unit'),
	            'email' => Yii::t('backend', 'Email'),
	            'celular' => Yii::t('backend', 'Mobile Phone'),
	            'naturaleza' => Yii::t('backend', 'Nature'),
	        ];
	    }


	}
?>