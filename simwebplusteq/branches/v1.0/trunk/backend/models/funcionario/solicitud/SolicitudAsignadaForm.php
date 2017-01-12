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
 *  @file SolicitudAsignadaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-04-2016
 *
 *  @class SolicitudAsignadaForm
 *  @brief Clase Modelo del formulario de busqueda de las solicitudes.
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
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;


	/**
	 *	Clase principal del formulario que se utiliza el funcionario para la busqueda de las solicitudes
	 *	eleboradas.
	 */
	class SolicitudAsignadaForm extends Model
	{
		public $nro_solicitud;
	    public $impuesto;
	    public $tipo_solicitud;
	    public $fecha_desde;
	    public $fecha_hasta;

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
	        	//[['fecha_desde', 'fecha_hasta'], 'date', 'message' => Yii::t('backend', '{attribute} is date')],
	        	[['fecha_hasta'], 'required',
	        	  'when' => function($model) {
	        	  				if ( $model->fecha_desde != null ) {
	        	  					return true;
	        	  				}
	        				}
	        	, 'message' => Yii::t('backend', '{attribute} is required')],
	        	[['fecha_desde'], 'required',
	        	  'when' => function($model) {
	        	  				if ( $model->fecha_hasta != null ) {
	        	  					return true;
	        	  				}
	        				}
	        	, 'message' => Yii::t('backend', '{attribute} is required')],
	        	[['fecha_hasta'], 'compare',
	        	  'compareAttribute' => 'fecha_desde', 'operator' => '>='],
	        	[['fecha_desde', 'fecha_hasta'], 'default', 'value' => null],
	        	[['impuesto'], 'required',
	        	  'when' => function($model) {
	        	  				if ( $model->tipo_solicitud != null ) {
	        	  					return true;
	        	  				}
	        				}
	        	, 'message' => Yii::t('backend', '{attribute} is required')],
	        	[['impuesto', 'tipo_solicitud'], 'integer', 'message' => Yii::t('backend', 'Select {attribute}')],
	        	[['nro_solicitud'], 'integer', 'message' => Yii::t('backend', '{attribute} not valid')],
	        ];
	    }



	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	        	'nro_solicitud' => Yii::t('backend', 'Nro. Request'),
	        	'impuesto' => Yii::t('backend', 'Tax'),
	            'tipo_solicitud' => Yii::t('backend', 'Request'),
	            'fecha_desde' => Yii::t('backend', 'Start date'),
	            'fecha_hasta' => Yii::t('backend', 'End date'),
	        ];
	    }







	}
?>