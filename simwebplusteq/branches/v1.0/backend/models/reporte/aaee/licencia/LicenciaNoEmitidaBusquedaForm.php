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
 *  @file LicenciaEmitidaBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class LicenciaEmitidaBusquedaForm
 *  @brief Clase Modelo del formulario para buscar las licencias emitidas.
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

	namespace backend\models\reporte\aaee\licencia;

 	use Yii;
	use yii\base\Model;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de los contribuyente de actividad economica que no han
	* solicitado la emision de su licencia.
	*/
	class LicenciaNoEmitidaBusquedaForm extends Model
	{
		public $tipo_licencia;
		public $id_contribuyente;
		public $todos_contribuyentes;
		/**
		 * Causas de la no emision de la licencia, seleccion del funcionario.
		 * @var array
		 */
		public $chkCausa;



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
	        	[['id_contribuyente'],
	        	  'required',
	        	  'when' => function($model) {
								if ( $model->todos_contribuyentes == 0 ) {
									return true;
								} else {
									return false;
								}
							},
				  'message' => Yii::t('backend', '{attribute} is required')
	        	],
	        	[['tipo_licencia',],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['tipo_licencia', 'todos_contribuyentes', 'chkCausa'],
	        	  'safe'],
	        	[['id_contribuyente', 'todos_contribuyentes',],
	        	  'integer',
	        	  'message' => Yii::t('backend', '{attribute} not is valid')],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'tipo_licencia' => Yii::t('backend', 'Tipo de Licencia'),
	        	'todos_contribuyentes' => Yii::t('backend', 'Todos'),
	        	'id_contribuyente' => Yii::t('backend', 'Id Contribuyente'),
	        ];
	    }

	}
?>