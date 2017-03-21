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
 *  @file VaucheDetalleUsuarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-11-2016
 *
 *  @class VaucheDetalleUsuarioForm
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
	use backend\models\recibo\depositodetalle\VaucheDetalleUsuario;
	use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;
	use backend\models\utilidad\banco\BancoSearch;


	/**
	* 	Clase base del formulario
	*/
	class VaucheDetalleUsuarioForm extends VaucheDetalleUsuario
	{
		public $id_vauche;	// Autoincremental
		public $recibo;
		public $linea;
		public $cuenta;
		public $cheque;
		public $tipo;
		public $fecha;
		public $monto;
		public $estatus;
		public $usuario;
		public $codigo_cuenta;
		public $id_forma;



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
	        	[['recibo', 'id_forma',
	        	  'monto', 'cheque',
	        	  'cuenta', 'tipo',
	        	  'codigo_cuenta', 'usuario',],
	        	  'required', 'when' => function($model) {
	        	  							if ( $model->tipo == 2 ) {
	        	  								return true;
	        	  							}
	        	  						},
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'id_forma',
	        	  'monto', 'tipo',
	        	  'usuario',],
	        	  'required', 'when' => function($model) {
	        	  							if ( $model->tipo == 1 ) {
	        	  								return true;
	        	  							}
	        	  						},
	        	  'message' => Yii::t('backend','{attribute} is required')],
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
	        	  'message' => Yii::t('backend', 'Debe contener 21 digitos')],
	        	[['cuenta', 'cheque'],
	        	  'validateCheque',
	        	  'message' => Yii::t('backend', 'El numero de cheque ya existe')],
	        	['codigo_cuenta',
	        	 'validateCodigoCuenta',
	        	 'message' => Yii::t('backend', 'El banco no es valido')],
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




	    /***/
	    public function validateCheque()
	    {
	    	self::validateChequeVauche();
	    	self::validateChequeFormaPago();
	    }





	    /**
	     * Metodo que controal la existencia del numero de cheque.
	     * @return
	     */
	    public function validateChequeVauche()
	    {
	    	if ( $this->id_forma == 2 ) {
	    		$depositoUsuario = VaucheDetalleUsuario::find()->alias('U')
	    		                                                 ->where('cuenta =:cuenta',
	    																['cuenta' => $this->cuenta])
	    														 ->andWhere('cheque =:cheque',
	    														 		['cheque' => $this->cheque])
	    														 ->all();

	    		if ( count($depositoUsuario) > 0 ) {
    				$this->addError('cuenta', Yii::t('backend', 'El cheque ya existe'));
    				$this->addError('cheque', Yii::t('backend', 'El cheque ya existe'));
	    		}
	    	}
	    }



	    /***/
	    public function validateChequeFormaPago()
	    {
	    	if ( $this->id_forma == 2 ) {
	    		$depositoUsuario = DepositoDetalleUsuario::find()->alias('U')
	    														 ->where('id_forma =:id_forma',
	    														 		['id_forma' => $this->id_forma])
	    		                                                 ->andWhere('cuenta =:cuenta',
	    																['cuenta' => $this->cuenta])
	    														 ->andWhere('cheque =:cheque',
	    														 		['cheque' => $this->cheque])
	    														 ->all();

	    		if ( count($depositoUsuario) > 0 ) {
    				$this->addError('cuenta', Yii::t('backend', 'El cheque ya existe'));
    				$this->addError('cheque', Yii::t('backend', 'El cheque ya existe'));
	    		}
	    	}
	    }

	}
?>