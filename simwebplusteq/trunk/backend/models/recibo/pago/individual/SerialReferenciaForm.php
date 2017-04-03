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
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;

	/**
	* Clase base del formulario
	*/
	class SerialReferenciaForm extends SerialReferenciaUsuario
	{
		public $recibo;
		public $serial;
		public $fecha_edocuenta;
		public $monto_edocuenta;
		public $estatus;
		public $usuario;
		public $observacion;


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
	        	['estatus', 'default', 'value' => 0],
	        	['observacion', 'string'],
	        	['serial', 'unique', 'message' => '{attribute} ya existe'],
	        	[['recibo', 'usuario',
	        	  'estatus', 'observacion'],
	        	  'safe'],
	        	['serial', 'existeSerialFecha'],
	        	['fecha_edocuenta', 'fechaEdoCuentaCorrecta'],
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



	    /**
	     * Metodo que permite determinar si un serial ya esta relacionado a otra planilla
	     * para la misma fecha.
	     * @param string $attribute descripcion del atributo.
	     * @return
	     */
	    public function existeSerialFecha($attribute)
	    {
	    	$result = PreReferenciaPlanilla::find()->where('serial_edocuenta =:serial_edocuenta',
	    															[':serial_edocuenta' => $this->serial])
	    										   ->andWhere('fecha_edocuenta =:fecha_edocuenta',
	    											 				[':fecha_edocuenta' => date('Y-m-d', strtotime($this->fecha_edocuenta))])
	    										   ->andWhere(['IN', 'estatus', [0, 1]])
	    										   ->exists();
	    	if ( $result ) {
	    		$this->addError($attribute, Yii::t('backend', 'Serial ya esta relacionado a otra planilla, para la misma fecha.'));
	    	}
	    }



	    /**
	     * Metodo que permite controlar la unicidad del valor de la fecha para los seriales
	     * que se agreguen a los listados.
	     * @param string $attribute nombre del atributo
	     * @return
	     */
	    public function fechaEdoCuentaCorrecta($attribute)
	    {
	    	$registers = $this->find()->where('usuario =:usuario',
	    											[':usuario' => $this->usuario])
	    						   	  ->andWhere('recibo =:recibo',
	    						   	  				[':recibo' => $this->recibo])
	    						   	  ->asArray()
	    						   	  ->all();
	    	if ( count($registers) > 0 ) {
	    		foreach ( $registers as $register ) {
	    			if ( $this->fecha_edocuenta !== $register['fecha_edocuenta'] ) {
	    				$this->addError($attribute, Yii::t('backend', 'La fecha del serial que intenta agregar no coinciden con los ya existentes.'));
	    			}
	    		}
	    	}

	    }


	}
?>