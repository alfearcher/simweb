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
 *  @file ConfigurarSolicitudForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class ConfigurarSolicitudForm
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

	namespace backend\models\configuracion\solicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\solicitud\ConfigurarSolicitud;

	/**
	* 	Clase
	*/
	class ConfigurarSolicitudForm extends ConfigurarSolicitud
	{
		public $id_config_solicitud;
		public $impuesto;
		public $tipo_solicitud;
		public $nivel_aprobacion;
		public $inactivo;
		public $usuario;
		public $fecha_hora;
		public $observacion;
		public $fecha_desde;
		public $fecha_hasta;
		public $solo_funcionario;

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
	        	[['id_config_solicitud', 'impuesto',
	        	  'tipo_solicitud', 'nivel_aprobacion',
	        	  'inactivo','fecha_desde', 'solo_funcionario',
	        	  'usuario'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_config_solicitud', 'impuesto',
	        	  'tipo_solicitud', 'inactivo',
	        	  'solo_funcionario', 'nivel_aprobacion'], 'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['observacion'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	          	[['fecha_hora', 'fecha_desde'], 'default', 'value' => date('Y-m-d H:i:s')],
	          	['fecha_hasta', 'default', 'value' => '0000-00-00'],
	     		[['inactivo', 'solo_funcionario'], 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_config_solicitud' => Yii::t('backend', 'Register No.'),
	            'impuesto' => Yii::t('backend', 'Tax'),
	            'tipo_solicitud' => Yii::t('backend', 'Type of Request'),
	            'nivel_aprobacion' => Yii::t('backend', 'Level Approval'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	            'usuario' => Yii::t('backend', 'User'),
	            'fecha_hora' => Yii::t('backend', 'Date/Hour'),
	            'observacion' => Yii::t('backend', 'Observation'),
	            'fecha_desde' => Yii::t('backend', 'Begin Date'),
	            'fecha_hasta' => Yii::t('backend', 'End Date'),
	            'solo_funcionario' => Yii::t('backend', 'solo fun'),

	        ];
	    }
	}
?>