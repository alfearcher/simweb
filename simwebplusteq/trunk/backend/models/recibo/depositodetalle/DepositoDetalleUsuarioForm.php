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
 *  @file DepositoDetalleUsuarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-11-2016
 *
 *  @class DepositoDetalleUsuarioForm
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

	namespace backend\models\recibo\depositodetalle;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;


	/**
	* 	Clase base del formulario
	*/
	class DepositoDetalleUsuarioForm extends DepositoDetalleUsuario
	{
		public $linea;			// Autoincremental
		public $recibo;
		public $id_forma;
		public $deposito;
		public $fecha;
		public $cuenta;
		public $cheque;
		public $monto;
		public $conciliado;
		public $estatus;
		public $codigo_banco;
		public $cuenta_deposito;
		public $codigo_cuenta;
		public $tipo_deposito;
		public $id_banco;
		public $usuario;


		const SCENARIO_EFECTIVO = 'efectivo';
		const SCENARIO_CHEQUE = 'cheque';
		const SCENARIO_DEPOSITO = 'deposito';
		const SCENARIO_TARJETA = 'tarjeta';



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	// return Model::scenarios();

        	return [

        		self::SCENARIO_EFECTIVO => [
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'usuario',
        		],
        		self::SCENARIO_CHEQUE => [
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'cuenta',
        				'codigo_cuenta',
        				'cheque',
        				'usuario',
        		],
        		self::SCENARIO_TARJETA => [
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'cuenta',
        				'cheque',
        				'tipo_deposito',
        				'id_banco',
        				'usuario',
        		],
        		self::SCENARIO_DEPOSITO => [
        				'recibo',
        				'id_forma',
        				'deposito',
        				'fecha',
        				'monto',
        				'usuario',
        		],
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['recibo', 'id_forma',
	        	  'fecha', 'monto',
	        	  'usuario',],
	        	  'required', 'on' => 'efectivo',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'fecha', 'monto',
	        	  'cheque', 'cuenta',
	        	  'codigo_cuenta', 'usuario',],
	        	  'required', 'on' => 'cheque',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'fecha', 'monto',
	        	  'cheque', 'cuenta',
	        	  'tipo_deposito', 'id_banco',
	        	  'usuario',],
	        	  'required', 'on' => 'tarjeta',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'fecha', 'monto',
	        	  'deposito', 'usuario',],
	        	  'required', 'on' => 'deposito',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'estatus', 'codigo_banco',],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute} must be integer')],
	        	[['cheque', 'cuenta',
	        	  'usuario',],
	        	  'string',
	        	  'message' => Yii::t('backend','{attribute} must be string')],
	        	//[['monto'],'formatter' => ],
	        	// ['monto',
	        	//  'double',
	        	//  'message' => Yii::t('backend','{attribute} must be double')],
	        	[['estatus', 'conciliado',
	        	  'codigo_banco', 'deposito'], 'default', 'value' => 0],
	        	[['codigo_cuenta'],
	        	  'string',
	        	  'max' => 4,
	        	  'message' => Yii::t('backend', 'Debe contener 4 digitos')],
	        	[['codigo_cuenta'],
	        	  'string',
	        	  'min' => 4,
	        	  'message' => Yii::t('backend', 'Debe contener 4 digitos')],
	        	[['cuenta'],
	        	  'string',
	        	  'max' => 21,
	        	  'message' => Yii::t('backend', 'Debe contener 4 digitos')],

	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [

	        	'recibo' => Yii::t('backend','Nro. Recibo'),
	        	'id_forma' => Yii::t('backend','Fomra de Pago'),
	        	'deposito' => Yii::t('backend','Nro. Deposito'),
	        	'fecha' => Yii::t('backend','fecha'),
	        	'cheque' => Yii::t('backend','Nro. Cheque'),
	        	'cuenta' => Yii::t('backend','Nro. Cuenta'),
	        	'monto' => Yii::t('backend','Monto'),

	        ];
	    }






	}
?>