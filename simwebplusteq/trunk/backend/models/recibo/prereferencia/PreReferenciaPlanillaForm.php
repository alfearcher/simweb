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
 *  @file PreReferenciaPlanillaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-03-2017
 *
 *  @class PreReferenciaPlanillaForm
 *  @brief Clase Modelo del formulario
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

	namespace backend\models\recibo\prereferencia;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;


	/**
	* 	Clase base del formulario
	*/
	class PreReferenciaPlanillaForm extends PreReferenciaPlanilla
	{

		public $id_banco;
		public $cuenta_recaudadora;
		public $fecha_pago;


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
	        	[['id_banco', 'cuenta_recaudodora',
	        	  'fecha_pago'],
	        	'required',
	        	'message' => Yii::t('backend','{attribute} is required'),
	        	]

	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [

	        ];
	    }


	}
?>