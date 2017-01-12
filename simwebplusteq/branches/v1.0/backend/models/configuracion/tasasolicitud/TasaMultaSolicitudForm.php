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
 *  @file TasaMultaSolicitudForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-05-2016
 *
 *  @class TasaMultaSolicitudForm
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

	namespace backend\models\configuracion\tasasolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\tasasolicitud\TasaMultaSolicitud;

	/**
	* 	Clase
	*/
	class TasaMultaSolicitudForm extends TasaMultaSolicitud
	{
		public $id_config_solic_tasa_multa;
		public $id_config_solic_detalle;
		public $id_impuesto;
		public $nro_veces_liquidar;			// Numero de veces que se debe liquidar la tasa.
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
	        	[['id_config_solic_detalle', 'id_impuesto',
	        	  'nro_veces_liquidar', 'inactivo'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_config_solic_detalle', 'id_impuesto',
	        	  'nro_veces_liquidar', 'inactivo',],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	     		[['inactivo'], 'default', 'value' => 0],
	     		[['nro_veces_liquidar'], 'default', 'value' => 1],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	 'id_config_solic_tasa_multa' => Yii::t('backend', 'Register No.'),
	            'id_config_solic_detalle' => Yii::t('backend', 'Porceso'),
	            'id_impuesto' => Yii::t('backend', 'Tax'),
	            'nro_veces_liquidar' => Yii::t('backend', 'Description'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }

	}
?>