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
 *  @file SolicitudProcesoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class SolicitudProcesoForm
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

	namespace backend\models\configuracion\procesosolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\procesosolicitud\SolicitudProceso;

	/**
	* 	Clase
	*/
	class SolicitudProcesoForm extends SolicitudProceso
	{
		public $id_proceso;
		public $descripcion;
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
	        	[['id_proceso', 'descripcion', 'inactivo'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_proceso', 'inactivo',],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['descripcion'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
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
	            'id_proceso' => Yii::t('backend', 'Register No.'),
	            'descripcion' => Yii::t('backend', 'Description'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }


	    /***/
	    public function findSolicitudProceso()
	    {
	    	$findModel = SolicitudProceso::find()->where(['inactivo' => 0 ])->orderBy(['descripcion' => SORT_ASC])->all();
	    	return $findModel;
	    }



	    /***/
	    public function totalSolicitudProceso()
	    {
	    	$findModel = SolicitudProceso::find()->where(['inactivo' => 0 ])->count();

	    	return $findModel;
	    }



	    public function getDataProvider()
	    {
	    	$query = SolicitudProceso::find();

	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    	]);

	    	return $dataProvider;
	    }


	}
?>