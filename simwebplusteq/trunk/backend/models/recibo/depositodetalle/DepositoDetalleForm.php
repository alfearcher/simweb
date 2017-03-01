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
 *  @file DepositoDetalleForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-11-2016
 *
 *  @class DepositoDetalleForm
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
	use backend\models\recibo\depositodetalle\DepositoDetalle;


	/**
	* 	Clase base del formulario
	*/
	class DepositoDetalleForm extends DepositoDetalle
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
        		],
        		self::SCENARIO_CHEQUE => [
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'cuenta',
        				'cheque',
        		],
        		self::SCENARIO_TARJETA => [
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'cuenta',
        				'cheque',
        		],
        		self::SCENARIO_DEPOSITO => [
        				'recibo',
        				'id_forma',
        				'deposito',
        				'fecha',
        				'monto',
        		],
        	];
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