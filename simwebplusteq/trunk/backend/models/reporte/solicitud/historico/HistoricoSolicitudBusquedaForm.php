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
 *  @file HistoricoSolicitudBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class HistoricoSolicitudBusquedaForm
 *  @brief Clase Modelo del formulario para buscar las solicitudes.
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

	namespace backend\models\reporte\solicitud\historico;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\fecha\RangoFechaValido;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de las solicitudes.
	*/
	class HistoricoSolicitudBusquedaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $impuesto;
		public $tipo_solicitud;
		public $estatus;
		public $id_contribuyente;
		public $nro_solicitud;

		private $rangoValido;

		const SCENARIO_IMPUESTO = 'impuesto';
		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_SOLICITUD = 'solicitud';
		const SCENARIO_DEFAULT = 'default';

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_IMPUESTO => [
        			'fecha_desde',
        			'fecha_hasta',
        			'impuesto',
        			'tipo_solicitud',
        			'estatus',
        		],
        		self::SCENARIO_CONTRIBUYENTE => [
        			'id_contribuyente',
 					'estatus',
        		],
        		self::SCENARIO_SOLICITUD => [
        			'nro_solicitud',
        			'estatus',
        		],
        		self::SCENARIO_DEFAULT => [
        			//Model::scenarios()
        			'fecha_desde',
        			'fecha_hasta',
        			'impuesto',
        			'tipo_solicitud',
        			'estatus',
        		]

        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['fecha_desde', 'fecha_hasta',
	        	  'impuesto',],
	        	  'required', 'on' => 'impuesto',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_contribuyente'],
	        	  'required', 'on' => 'contribuyente',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['nro_solicitud'],
	        	  'required', 'on' => 'solicitud',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['tipo_solicitud', 'estatus',
	        	  'usuario', 'nro_solicitud'],
	        	  'safe'],
	        	[['id_contribuyente', 'estatus',
	        	  'nro_solicitud', 'tipo_solicitud'],
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
	        	'tipo_solicitud' => Yii::t('backend', 'Tipo de Solicitud'),
	        	'impuesto' => Yii::t('backend', 'Impuesto'),
	        	'estatus' => Yii::t('backend', 'Condicion'),
	        	'id_contribuyente' => Yii::t('backend', 'Id Contribuyente'),
	        	'nro_solicitud' => Yii::t('backend', 'Nro. Solicitud'),
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