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
 *  @file DepositoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-11-2016
 *
 *  @class DepositoForm
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

	namespace backend\models\recibo\deposito;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;


	/**
	* 	Clase base del formulario
	*/
	class DepositoForm extends Deposito
	{
		public $recibo;			// Autoincremental
		public $proceso;
		public $fecha;
		public $monto;
		public $estatus;
		public $ultima_impresion;
		public $observacion;
		public $usuario;
		public $id_contribuyente;
		public $nro_control;
		public $usuario_creador;
		public $fecha_hora_creacion;
		public $fecha_hora_proceso;
		public $fecha_vcto;

		public $totalSeleccionado;
		public $fechaCreacion;

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
	        	// [['id_contribuyente', 'nro_control',
	        	//   'estatus', 'fecha', 'monto'],
	        	//   'require']
	        	[['recibo', 'id_contribuyente',
	        	  'nro_control', 'estatus'],
	        	  'integer'],
	        	[['observacion', 'proceso', 'usuario',
	        	  'usuario_creador'],
	        	  'string'],
	        	[['fecha'], 'date', 'format' => 'php:Y-m-d'],
	        	[['fecha_hora_creacion', 'ultima_impresion',
	        	  'fecha_hora_proceso'],
	        	  'date', 'format' => 'php:Y-m-d H:i:s'],
	        	[['fecha'], 'default', 'value' => date('Y-m-d')],
	        	[['estatus'], 'default', 'value' => 0],
	        	[['ultima_impresion', 'fecha_hora_proceso'],
	        	  'default', 'value' => '0000-00-00 00:00:00'],
	        	[['fecha_hora_creacion', 'proceso', 'fecha_vcto'],
	        	  'default', 'value' => date('Y-m-d H:i:s')],
	        	[['monto'], 'double'],

	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'recibo' => Yii::t('frontend', 'Nro. Recibo'),
	        	'fecha' => Yii::t('frontend', 'Fecha'),
	        	'monto' => Yii::t('frontend', 'Monto'),
	        	'estatus' => Yii::t('frontend', 'Condicion'),
	        	'id_contribuyente' => Yii::t('frontend', 'Id. Contribuyente'),
	        	'usuario' => Yii::t('frontend', 'Usuario'),

	        ];
	    }


	}
?>