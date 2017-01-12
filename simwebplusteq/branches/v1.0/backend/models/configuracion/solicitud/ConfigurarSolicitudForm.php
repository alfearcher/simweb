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

		public $ejecutar_en;
		public $combo;

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
	        	[['impuesto',
	        	  'tipo_solicitud', 'nivel_aprobacion',
	        	  'fecha_desde', 'solo_funcionario'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_config_solicitud', 'impuesto',
	        	  'tipo_solicitud', 'inactivo',
	        	  'solo_funcionario', 'nivel_aprobacion'], 'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['observacion'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	          	[['fecha_hora'], 'default', 'value' => date('Y-m-d H:i:s')],
	          	[['fecha_desde', 'fecha_hasta'], 'default', 'value' => null],
	     		[['inactivo', 'solo_funcionario'], 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	     		//['fecha_desde', 'compararFecha'],
	     		// ['fecha_desde',
	     		//  'compare',
	     		//  'compareAttribute' => 'fecha_hasta',
	     		//  'operator' => '<=',
	     		//  'message' => Yii::t('backend', '{attribute} must be no less that ')],
	        ];
	    }



	    /***/
	    public function compararFecha($attribute, $params)
	    {
	    	if ( date($this->attribute) && !date($model->fecha_hasta) ) {
	    		return true;
	    	}
	    	$this->addError($attribute,'mamamamm');
	    	return false;
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
	            'fecha_desde' => Yii::t('backend', 'Starting Date'),
	            'fecha_hasta' => Yii::t('backend', 'Expiration Date'),
	            'solo_funcionario' => Yii::t('backend', 'Like Public Officer'),

	        ];
	    }



	    /***/
	    public function findConfigurarSolicitud($idConfigSolicitud)
	    {
	    	if ( $idConfigSolicitud == 0 ) {
	    		$findModel = ConfigurarSolicitud::find();
	    	} else {
	    		$findModel = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $idConfigSolicitud]);
	    	}
	    	return $findModel;
	    }



	    /**
	     * [validarRangoFecha description]
	     * @param  [type] $model [description]
	     * @return [type]        [description]
	     */
	    public function validarRangoFecha($model)
	    {
	    	if ( isset($this->fecha_desde) ) {
	    		if ( isset($this->fecha_hasta) ) {
	    			if ( $this->fecha_hasta < $this->fecha_desde ) {
	    				$model->addError('fecha_hasta', Yii::t('backend', $model->getAttributeLabel('fecha_hasta') . ' no debe ser anterior a la ' . $model->getAttributeLabel('fecha_desde')));
	    				return false;
	    			}
	    		}
	    	}
	    	return true;
	    }




	    /**
	     * Metedo que valida si al seleccionar un proceso en la configuracion
	     * de la solicitud se determina cuando se va a ejecutar dicho proceso,
	     * si en la creacion, aprobacion o negacion de la solicitud. Esto se
	     * detremina si se ha seleccionado una opcion del combo
	     * @param  array  $items Las opciones selccionadas,determinada por enteros.
	     * @param  $model Modelo del formulario.
	     * @return boolean Retorna true o false.
	     */
	    public function validarProcesoSeleccion($items = [], $model)
	    {
	    	if ( count($items) > 0 ) {
	    		foreach ($items as $key => $value) {
	    			if ( $value == '' || $value == null ) {
	    				$model->addError('ejecutar_en', Yii::t('backend', 'No ha indicado el proceso a ejecutar.'));
	    				return false;
	    			}
	    		}
	    	}
	    	return true;
	    }
	}
?>