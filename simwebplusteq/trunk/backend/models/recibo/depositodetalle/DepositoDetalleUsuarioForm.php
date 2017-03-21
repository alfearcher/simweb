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
	use backend\models\utilidad\banco\BancoSearch;


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
		public $banco;
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
        				'linea',
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'usuario',
        		],
        		self::SCENARIO_CHEQUE => [
        				'linea',
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
        				'linea',
        				'recibo',
        				'id_forma',
        				'fecha',
        				'monto',
        				'cuenta',
        				'cheque',
        				'tipo_deposito',
        				'banco',
        				'usuario',
        				'codigo_cuenta',
        		],
        		self::SCENARIO_DEPOSITO => [
        				'linea',
        				'recibo',
        				'id_forma',
        				'deposito',
        				'fecha',
        				'usuario',
        		],
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
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
	        	  'usuario', 'codigo_cuenta'],
	        	  'required', 'on' => 'tarjeta',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'fecha',
	        	  'deposito', 'usuario',],
	        	  'required', 'on' => 'deposito',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'estatus', 'codigo_banco',
	        	  'linea',],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute} must be integer')],
	        	[['cheque', 'cuenta',
	        	  'usuario',],
	        	  'string',
	        	  'message' => Yii::t('backend','{attribute} must be string')],
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
	        	// [['cuenta','cheque'],
	        	//   'unique',
	        	//   'targetAttribute' => ['cuenta', 'cheque'],
	        	//   'on' => 'cheque',
	        	//   'message' => Yii::t('backend', 'El numero de cheque ya existe')],
	        	[['cuenta','cheque'],
	        	  'validateCheque',
	        	  'on' => 'cheque',
	        	  'message' => Yii::t('backend', 'El numero de cheque ya existe')],
	        	['codigo_cuenta',
	        	 'validateCodigoCuenta',
	        	 'on' => 'cheque',
	        	 'message' => Yii::t('backend', 'El banco no es valido')],
	        	[['estatus', 'conciliado',
	        	  'codigo_banco', 'deposito'],
	        	  'default',
	        	  'value' => 0,
	        	  'on' => 'efectivo'],
	        	[['cuenta', 'cheque',
	        	  'cuenta_deposito', 'codigo_cuenta'],
	        	  'default',
	        	  'value' => '',
	        	  'on' => 'efectivo'],
	        	[['deposito'],
	        	  'validateDeposito',
	        	  'on' => 'deposito',
	        	  'message' => Yii::t('backend', 'El numero de deposito ya existe')],
	        	// ['monto',
	        	//  'validateEfectivo',
	        	//  'on' => 'efectivo',
	        	//  'message' => Yii::t('backend', 'El efectivo ya existe')],
	        ];
	    }



	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son
	    * las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas
	    * como valor del arreglo.
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




	    /**
	     * Metodo que controla la existencia del numero de codigo del banco.
	     * Este codigo es el asignado a la entidad financiera y que se coloca
	     * delante de los nuemros de cuenta.(4 digitos)
	     * @param string $attribute nombre del atributo.
	     * @return
	     */
	    public function validateCodigoCuenta($attribute)
	    {
	    	$searchBanco = New BancoSearch();
	    	$arregloCondicion = [
	    		'codigo' => $this->codigo_cuenta,
	    	];

	    	if ( !$searchBanco->existeBanco($arregloCondicion) ) {
	    		$this->addError($attribute, Yii::t('backend', 'Banco no valido'));
	    	}
	    }




	    /**
	     * Metodo que controal la existencia del numero de cheque.
	     * @return
	     */
	    public function validateCheque()
	    {
	    	if ( $this->id_forma == 1 ) {
	    		$depositoUsuario = DepositoDetalleUsuario::find()->alias('U')
	    														 ->where('id_forma =:id_forma',
	    														 		['id_forma' => $this->id_forma])
	    		                                                 ->andWhere('cuenta =:cuenta',
	    																['cuenta' => $this->cuenta])
	    														 ->andWhere('cheque =:cheque',
	    														 		['cheque' => $this->cheque])
	    														 ->all();

	    		if ( count($depositoUsuario) > 0 ) {
	    			if ( $this->linea === $depositoUsuario[0]['linea'] ) {
	    				//return true;
	    			} else {
	    				$this->addError('cuenta', Yii::t('backend', 'El cheque ya existe'));
	    				$this->addError('cheque', Yii::t('backend', 'El cheque ya existe'));
	    			}
	    		}
	    	}
	    }



	    /***/
	    public function validateDeposito()
	    {
	    	if ( $this->id_forma == 2 ) {
	    		$depositoUsuario = DepositoDetalleUsuario::find()->alias('U')
	    														 ->where('id_forma =:id_forma',
	    														 		['id_forma' => $this->id_forma])
	    		                                                 ->andWhere('deposito =:deposito',
	    																['deposito' => $this->deposito])
	    														 ->all();

	    		if ( count($depositoUsuario) > 0 ) {
	    			if ( $this->linea === $depositoUsuario[0]['linea'] ) {
	    				//return true;
	    			} else {
	    				$this->addError('deposito', Yii::t('backend', 'El deposito ya existe'));
	    			}
	    		}
	    	}
	    }

	}
?>