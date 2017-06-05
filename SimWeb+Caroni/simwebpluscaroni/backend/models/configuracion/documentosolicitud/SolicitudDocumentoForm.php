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
 *  @file SolicitudDocumentoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-03-2016
 *
 *  @class SolicitudDocumentoForm
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

	namespace backend\models\configuracion\documentosolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumento;

	/**
	* 	Clase
	*/
	class SolicitudDocumentoForm extends SolicitudDocumento
	{
		public $id_config_solic_doc;
		public $id_config_solicitud;
		public $id_documento;
		public $adjuntar_electronico;
		public $original;
		public $copia;
		public $nro_copias;
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
	        	[['id_documento', 'id_config_solicitud'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_documento', 'inactivo',
	        	  'id_config_solicitud', 'adjuntar_electronico',
	        	  '$original', 'nro_copias',
	        	  'copia'],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	     		[['inactivo', 'original', 'copia',
	     		  'nro_copias', 'adjuntar_electronico'],
	     		  'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_config_solic_doc' => Yii::t('backend', 'Register No.'),
	        	'id_config_solicitud' => Yii::t('backend', 'Request No.'),
	            'id_documento' => Yii::t('backend', 'Doc No.'),
	            'adjuntar_electronico' => Yii::t('backend', 'Adjuntar'),
	            'original' => Yii::t('backend', 'Origianl'),
	            'copia' => Yii::t('backend', 'Copia'),
	            'nro_copias' => Yii::t('backend', 'Numero Copias'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }


	    /***/
	    public function findSolicitudDocumento($inactivo = 0)
	    {
	    	$findModel = SolicitudDocumento::find()->where(['inactivo' => $inactivo])->all();
	    	return $findModel;
	    }



	    /***/
	    public function totalSolicitudDocumento($idConfigSolicitud)
	    {
	    	$findModel = SolicitudDocumento::find()->where([
	    												'id_config_solicitud' => $idConfigSolicitud,
	    												'inactivo' => 0
	    												])->count();

	    	return $findModel;
	    }



	    /***/
	    public function getDataProvider($idConfigSolicitud)
	    {
	    	$query = SolicitudDocumento::find()->where(['id_config_solicitud' => $idConfigSolicitud]);

	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    	]);

	    	return $dataProvider;
	    }


	}
?>