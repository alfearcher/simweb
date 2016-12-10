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


	namespace backend\models\funcionario\solicitud;

	use Yii;
	use yii\base\Model;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;


	/**
	 *	Clase principal del formulario.
	 */
	class FuncionarioSolicitudForm extends FuncionarioSolicitud
	{

	    public $id_funcionario_solic;			// Autonumeric
	    public $id_funcionario;
	    public $tipo_solicitud;
	    public $usuario;
	    public $fecha_hora;
	    public $inactivo;
	    public $observacion;


    	/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



    	/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
	            [['tipo_solicitud', 'usuario', 'id_funcionario'],
	              'required',  'message' => Yii::t('backend', '{attribute} is require')],
	            [['id_funcionario_solic', 'id_funcionario',
	              'tipo_solicitud', 'inactivo'],
	              'integer'],
	            ['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	            ['inactivo', 'default', 'value' => 0],

	        ];
	    }



	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	        	'id_funcionario_solic' => Yii::t('backend', 'Register No.'),
	            'id_funcionario' => Yii::t('backend', 'Id Funcionario'),
	            'tipo_solicitud' => Yii::t('backend', 'Type Request'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	            'usuario' => Yii::t('backend', 'User'),
	            'fecha_hora' => Yii::t('backend', 'Time/Hour'),
	            'observacion' => Yii::t('backend', 'Note'),
	        ];
	    }
	}
?>