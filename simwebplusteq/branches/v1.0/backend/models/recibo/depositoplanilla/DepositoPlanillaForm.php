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
 *  @file DepositoPlanillaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-11-2016
 *
 *  @class DepositoPlanillaForm
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

	namespace backend\models\recibo\depositoplanilla;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;


	/**
	* 	Clase base del formulario
	*/
	class DepositoPlanillaForm extends DepositoPlanilla
	{
		public $linea;			// Autoincremental
		public $recibo;
		public $monto;
		public $planilla;
		public $impuesto;		// Descripcion del impuesto.
		public $descripcion;	// Descripcion del contribuyente, apellidos nombres o razon social.
		public $codigo;
		public $estatus;


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
	        	// [['recibo', 'impuesto',
	        	//   'estatus', 'planilla', 'monto'],
	        	//   'require'],
	        	[['recibo', 'planilla',
	        	  'estatus'],
	        	  'integer'],
	        	[['immpuesto', 'descripcion'],
	        	  'string'],
	        	[['estatus', 'codigo'], 'default', 'value' => 0],


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