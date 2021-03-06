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
 *  @file DocumentoRequisitoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-098-2015
 *
 *  @class DocumentoRequisistoForm
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

	namespace backend\models\solicitud\negacion;

 	use Yii;
 	use backend\models\utilidad\causanegacionsolicitud\CausaNegacionSolicitudForm;
	use yii\base\Model;

	/**
	* 	Clase base del formulario
	*/
	class NegacionSolicitudForm extends Model
	{
		public $causa;
		public $observacion;
		public $nro_solicitud;


		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['causa', 'observacion', 'nro_solicitud'],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	['causa', 'integer',
	        	 'message' => Yii::t('backend','{attribute} must be a number')],
	        ];
	    }



	    /***/
	    public function attributeLabels()
	    {
	    	return [
	    		'causa' => Yii::t('backend', 'Cause'),
	    		'observacion' => Yii::t('backend', 'Note'),
	    	];
	    }



	    /***/
	    public function listaCausasNegacion()
	    {
	    	$causa = New CausaNegacionSolicitudForm();
	    	$lista = $causa->getListaCausasNegacion();
	    	return isset($lista) ? $lista : null;
	    }


	}
?>