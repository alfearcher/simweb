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
 *  @file ActEconIngresoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-10-2015
 *
 *  @class ActEconIngresoForm
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

	namespace backend\models\aaee\acteconingreso;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\acteconingreso\ActEconIngreso;

	/**
	* 	Clase
	*/
	class ActEconIngresoForm extends ActEconIngreso
	{
		public $id_impuesto;
		public $id_rubro;
		public $exigibilidad_periodo;
		public $estimado;
		public $reales;
		public $impuestos;
		public $liquidado;
		public $notificado;
		public $periodo_fiscal_desde;
		public $periodo_fiscal_hasta;
		public $sustitutiva;
		public $rectificatoria;
		public $auditoria;
		public $bloqueado;
		public $inactivo;
		public $usuario;
		public $fecha_hora;
		public $condicion;		// 1 => anexado,
								// 2 => autorizado,
								// 3 => agregado por declaracion definitiva.
								// 9 => desincorporado.




		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_impuesto', 'id_rubro', 'exigibilidad_periodo',
	        	  'impuestos', 'liquidado', 'notificado', 'bloqueado',
	        	  'condicion', 'inactivo'],
	        	  'integer'],
	        	[['exigibilidad_periodo'],
	        	  'default', 'value' => 1],
	        	[['impuestos', 'liquidado', 'notificado',
	        	  'bloqueado', 'condicion', 'inactivo'],
	        	  'default' , 'value' => 0],
	        	[['estimado', 'reales', 'sustitutiva',
	        	  'rectificatoria', 'auditoria'],
	        	  'default', 'value' => 0],
	        	[['periodo_fiscal_desde', 'periodo_fiscal_hasta'],
	        	  'date', 'format' => 'yyyy-M-d'],
	        	[['periodo_fiscal_desde', 'periodo_fiscal_hasta'],
	        	  'default', 'value' => date('Y-m-d', strtotime('0000-00-00'))],
	        	[['fecha_hora'], 'default', 'value' => date('Y-m-d H:i:s')],
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