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
 *  @file SerialReferenciaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class SerialReferenciaForm
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

	namespace backend\models\recibo\pago\individual;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;

	/**
	* Clase base del formulario
	*/
	class SerialReferenciaForm extends SerialReferenciaUsuario
	{
		public $recibo;
		public $serial;
		public $monto_edocuenta;
		public $fecha_edocuenta;



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
	        	[['serial', 'monto_edocuenta',
	        	  'fecha_edocuenta',],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['serial',],
	        	  'integer',
	        	  'message' => Yii::t('backend', 'El serial no es valido')],
	        	// [['monto_edocuenta',],
	        	//   'double',
	        	//   'message' => Yii::t('backend', 'El monto no es valido')],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'serial' => Yii::t('backend', 'Serial(Referencia)'),
	        	'monto_edocuenta' => Yii::t('backend', 'Monto'),
	        	'fecha_edocuenta' => Yii::t('backend', 'Fecha(Edo. Cuenta)'),
	        ];
	    }


	}
?>