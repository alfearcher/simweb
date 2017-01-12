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
 *  @file TipoSolicitudForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class TipoSolicitudForm
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

	namespace backend\models\configuracion\tiposolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;

	/**
	* 	Clase
	*/
	class TipoSolicitudForm extends TipoSolicitud
	{
		public $id_tipo_solicitud;
		public $impuesto;
		public $descripcion;
		public $cont_mostrar;
		public $general;
		public $controlador;
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
	        	[['id_tipo_solicitud', 'impuesto',
	        	  'cont_mostrar', 'general',
	        	  'inactivo','fecha_desde'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_tipo_solicitud', 'impuesto',
	        	  'cont_mostrar', 'inactivo',],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['controlador'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	     		[['inactivo', 'cont_mostrar'], 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_tipo_solicitud' => Yii::t('backend', 'Register No.'),
	            'impuesto' => Yii::t('backend', 'Tax'),
	            'descripcion' => Yii::t('backend', 'Description'),
	            'cont_mostrar' => Yii::t('backend', 'Show taxpayer'),
	            'general' => Yii::t('backend', 'General'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }


	    /***/
	    public function findTipoSolicitud($impuesto = 0)
	    {
	    	if ( $impuesto == 0 ) {
	    		$findModel = TipoSolicitud::find()->orderBy([
	    													'impuesto' => SORT_ASC,
	    													'descripcion' => SORT_ASC,
	    													])
	    										  ->all();
	    	} else {
	    		$findModel = TipoSolicitud::find()->where([
	    													'impuesto' => $impuesto,
	    													'inactivo' => 0,
	    												  ])
	    										  ->orderBy([
	    													'impuesto' => SORT_ASC,
	    													'descripcion' => SORT_ASC,
	    													])
	    										  ->all();
	    	}

	    	return $findModel;
	    }



	    /***/
	    public function totalTipoSolicitud($impuesto = 0)
	    {
	    	if ( $impuesto == 0 ) {
	    		$findModel = TipoSolicitud::find()->count();
	    	} else {
	    		$findModel = TipoSolicitud::find()->where([
	    													'impuesto' => $impuesto,
	    													'inactivo' => 0,
	    												  ])
	    										  ->count();
	    	}

	    	return $findModel;
	    }


	}
?>