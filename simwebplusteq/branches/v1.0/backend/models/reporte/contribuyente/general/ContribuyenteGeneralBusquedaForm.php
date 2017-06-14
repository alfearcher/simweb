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
 *  @file ContribuyenteGeneralBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-06-2017
 *
 *  @class ContribuyenteGeneralBusquedaForm
 *  @brief Clase Modelo del formulario para buscar a los contribuyentes.
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

	namespace backend\models\reporte\contribuyente\general;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\fecha\RangoFechaValido;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de los contribuyentes existentes.
	*/
	class ContribuyenteGeneralBusquedaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $tipo_naturaleza;	// natural, juridico, todos
		public $condicion;			// inactivo, activo, todos
		public $no_declara;
		public $sin_licencia;
		public $sin_email;

		private $rangoValido;

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
	        	[['tipo_naturaleza', 'condicion'],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['tipo_naturaleza', 'condicion',
	        	  'no_declara', 'sin_licencia', 'sin_email'],
	        	  'integer',
	        	  'message' => Yii::t('backend', '{attribute} not is valid')],
	        	[['sin_licencia'], 'default', 'value' => 0],
	        	// ['fecha_desde',
	        	//  'compare',
	        	//  'compareAttribute' => 'fecha_hasta',
	        	//  'operator' => '<=',
	        	//  'enableClientValidation' => false],
	        	// ['fecha_desde' , 'validarRango'],
	        	// [['fecha_desde', 'fecha_hasta'],
	        	//   'default',
	        	//   'value' => '0000-00-00'],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'fecha_desde' => Yii::t('backend', 'Fecha Desde'),
	        	'fecha_hasta' => Yii::t('backend', 'Fecha Hasta'),
	        	'tipo_naturaleza' => Yii::t('backend', 'Naturaleza'),
	        	'condicion' => Yii::t('backend', 'Condicion'),
	        	'sin_licencia' => Yii::t('backend', 'Sin Licencia'),
	        ];
	    }


	    /***/
	    public function validarRango()
	    {
	    	$this->rangoValido = false;
	    	$validarRango = New RangoFechaValido($this->fecha_desde, $this->fecha_hasta);
	    	if ( !$validarRango->rangoValido() ) {
	    		$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    	} else {
	    		$this->rangoValido = true;
	    	}
	    }


	    /**
	     * Metodo que retorna una variable que indica si el rango de fechas evaluadas es valido.
	     * @return boolean
	     */
	    public function getRangoValido()
	    {
	    	return $this->rangoValido;
	    }





	}
?>