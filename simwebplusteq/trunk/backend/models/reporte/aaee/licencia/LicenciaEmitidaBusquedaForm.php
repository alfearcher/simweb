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
 *  @file LicenciaEmitidaBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class LicenciaEmitidaBusquedaForm
 *  @brief Clase Modelo del formulario para buscar las licencias emitidas.
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

	namespace backend\models\reporte\aaee\licencia;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\fecha\RangoFechaValido;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de las solicitudes de licencias emitidas.
	*/
	class LicenciaEmitidaBusquedaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $tipo_licencia;
		public $estatus;
		public $usuario;
		public $id_contribuyente;
		public $licencia;

		private $rangoValido;

		const SCENARIO_FECHA = 'fecha';
		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_LICENCIA = 'licencia';
		const SCENARIO_DEFAULT = 'default';

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_FECHA => [
        			'fecha_desde',
        			'fecha_hasta',
        			'usuario',
        			'tipo_licencia',
        		],
        		self::SCENARIO_CONTRIBUYENTE => [
        			'id_contribuyente',
        			'usuario',
        			'tipo_licencia',
        		],
        		self::SCENARIO_LICENCIA => [
        			'licencia',
        			'usuario',
        			'tipo_licencia',
        		],
        		self::SCENARIO_DEFAULT => [
        			Model::scenarios()
        		]

        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'fecha',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_contribuyente'],
	        	  'required', 'on' => 'contribuyente',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['licencia'],
	        	  'required', 'on' => 'licencia',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['tipo_licencia', 'estatus',
	        	  'usuario', 'licencia'],
	        	  'safe'],
	        	[['id_contribuyente', 'estatus'],
	        	  'integer',
	        	  'message' => Yii::t('backend', '{attribute} not is valid')],
	        	['fecha_desde',
	        	 'compare',
	        	 'compareAttribute' => 'fecha_hasta',
	        	 'operator' => '<=',
	        	 'enableClientValidation' => false],
	        	['fecha_desde' , 'validarRango'],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'default',
	        	  'value' => '0000-00-00'],
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
	        	'tipo_licencia' => Yii::t('backend', 'Tipo de Licencia'),
	        	'estatus' => Yii::t('backend', 'Condicion'),
	        	'id_contribuyente' => Yii::t('backend', 'Id Contribuyente'),
	        	'usuario' => Yii::t('backend', 'Usuario'),
	        	'licencia' => Yii::t('backend', 'Nro. Licencia'),
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



	    /***/
	    // public function afterValidate()
	    // {
	    // 	if ( $this->fecha_desde !== '' || $this->fecha_desde !== '' ) {
	    // 		$validarRango = New RangoFechaValido($this->fecha_desde, $this->fecha_desde);
	    // 		return $validarRango->rangoValido();
	    // 	}
	    // 	return false;
	    // }

	}
?>