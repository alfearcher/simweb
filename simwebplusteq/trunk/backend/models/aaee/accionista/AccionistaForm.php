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
 *  @file AccionistaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-08-2015
 *
 *  @class AccionistaForm
 *  @brief Clase Modelo del formulario para
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

	namespace backend\models\aaee\accionista;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\accionista\Accionista;

	/**
	* 	Clase
	*/
	class AccionistaForm extends Accionista
	{
		public $id_accionista;
		public $naturaleza_acc;
		public $cedula_acc;
		public $nombres_acc;
		public $apellidos_acc;
		public $domicilio;
		public $fecha_acc;					// Fecha en que inicio como accionista de la empresa.
		public $id_contribuyente;			// Razon Social de la cual es accionista.
		public $nro_acciones;
		public $porcentaje_acciones;
		public $observacion;
		public $inactivo;

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['naturaleza_acc', 'cedula_acc', 'nombres_acc', 'nombres_acc', 'apellidos_acc','fecha_acc', 'domicilio', 'id_contribuyente',
	        	  'nro_acciones', 'porcentaje_acciones'], 'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_contribuyente', 'nro_acciones', 'cedula_acc'], 'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['porcentaje_acciones'], 'double', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['nombres_acc', 'apellidos_acc', 'naturaleza_acc'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['inactivo', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	     		['cedula_acc', 'string', 'max' => 8, 'message' => Yii::t('backend', '{attribute} must be long 8')],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'naturaleza_acc' => Yii::t('backend', 'Natural'),
	            'cedula_acc' => Yii::t('backend', 'DNI'),
	            'nombres_acc' => Yii::t('backend', 'First Name'),
	            'apellidos_acc' => Yii::t('backend', 'Last Name'),
	            'domiclio' => Yii::t('backend', 'home'),
	            'fecha_acc' => Yii::t('backend', 'Date'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_acciones' => Yii::t('backend', 'Actions Number'),
	            'porcentaje_acciones' => Yii::t('backend', 'Porcentaje of Actions'),
	            'observacion' => Yii::t('backend', 'Note'),
	            'inactivo' => Yii::t('backend', 'Inactive'),
	            'id_accionista'  => Yii::t('backend', 'Record'),

	        ];
	    }
	}
?>