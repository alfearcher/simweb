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
 *  @file SolicitudDetalleForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-03-2016
 *
 *  @class SolicitudDetalleForm
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

	namespace backend\models\configuracion\detallesolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\detallesolicitud\SolicitudDetalle;

	/**
	* 	Clase
	*/
	class SolicitudDetalleForm extends SolicitudDetalle
	{
		public $id_config_solic_detalle;
		public $id_config_solicitud;
		public $id_proceso;
		public $ejecutar_en;
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
	        	[['id_proceso', 'id_config_solicitud', 'inactivo', 'ejecutar_en'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_proceso', 'inactivo',],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['ejecutar_en'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	     		[['inactivo'], 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_config_solic_detalle' => Yii::t('backend', 'Register No.'),
	        	'id_config_solicitud' => Yii::t('backend', 'Request No.'),
	            'id_proceso' => Yii::t('backend', 'Proccess No.'),
	            'ejecutar_en' => Yii::t('backend', 'Execute in'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }


	    /***/
	    public function findSolicitudDetalle($inactivo = 0)
	    {
	    	$findModel = SolicitudDetalle::find()->where(['inactivo' => $inactivo])->all();
	    	return $findModel;
	    }



	    /***/
	    public function totalSolicitudDetalle($idConfigSolicitud)
	    {
	    	$findModel = SolicitudDetalle::find()->where([
	    												'id_config_solicitud' => $idConfigSolicitud,
	    												'inactivo' => 0
	    												])->count();

	    	return $findModel;
	    }



	    /***/
	    public function getDataProvider($idConfigSolicitud)
	    {
	    	$query = SolicitudDetalle::find()->where(['id_config_solicitud' => $idConfigSolicitud]);

	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    	]);

	    	return $dataProvider;
	    }


	}
?>