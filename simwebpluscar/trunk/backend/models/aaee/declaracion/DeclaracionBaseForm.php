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
 *  @file TipoDeclaracionForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-09-2016
 *
 *  @class TipoDeclaracionForm
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

	namespace backend\models\aaee\declaracion;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\DeclaracionBase;



	/**
	* 	Clase base del formulario
	*/
	class DeclaracionBaseForm extends DeclaracionBase
	{
		public $id_declaracion;
		public $nro_solicitud;
		public $id_contribuyente;
		public $id_impuesto;
		public $ano_impositivo;
		public $id_rubro;
		public $exigibilidad_periodo;
		public $periodo_fiscal_desde;
		public $periodo_fiscal_hasta;
		public $tipo_declaracion;
		public $monto_v;
		public $monto_new;
		public $monto_minimo;
		public $origen;
		public $usuario;
		public $fecha_hora;
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;

		public $rubro;
		public $descripcion;

		const SCENARIO_ESTIMADA = 'estimada';
		const SCENARIO_DEFINITIVA = 'definitiva';
		const SCENARIO_SEARCH = 'search';


		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
	        	self::SCENARIO_ESTIMADA => [
	        					'id_contribuyente',
	        					'ano_impositivo',
	        					'exigibilidad_periodo',
	        					'id_impuesto',
	        					'id_rubro',
	        					'monto_new',
	        					'monto_minimo',
	        					'monto_v',
	        					'tipo_declaracion',
	        					'periodo_fiscal_desde',
	        					'periodo_fiscal_hasta',
	        					'rubro',
	        					'descripcion',
	        					'origen',
	        					'fecha_hora',
	        					'usuario',
	        					'estatus',

	        		],
        		self::SCENARIO_DEFINITIVA => [
        						'id_contribuyente',
	        					'ano_impositivo',
	        					'exigibilidad_periodo',
	        					'id_impuesto',
	        					'id_rubro',
	        					'monto_new',
	        					'monto_minimo',
	        					'monto_v',
	        					'tipo_declaracion',
	        					'periodo_fiscal_desde',
	        					'periodo_fiscal_hasta',
	        					'rubro',
	        					'descripcion',
	        					'origen',
	        					'fecha_hora',
	        					'usuario',
	        					'estatus',

        		],
        		self::SCENARIO_SEARCH => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'exigibilidad_periodo',

        		],
	        ];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'monto_minimo',
	        	  'monto_new', 'id_rubro',
	        	  'rubro', 'descripcion'],
	        	  'required', 'on' => 'estimada',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_contribuyente', 'ano_impositivo', 'exigibilidad_periodo'],
	        	  'required', 'on' => 'search',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['tipo_declaracion', 'inactivo',
	        	  'exigibilidad_periodo', 'ano_impositivo'],
	        	  'integer',
	        	  'message' => Yii::t('frontend', '{attribute} no valid')],
	          	['inactivo', 'default', 'value' => 0],
	          	['monto_minimo', 'default', 'value' => 0],
	          	['monto_v', 'default', 'value' => 0],
	          	['monto_new', 'default', 'value' => 0],
	          	['monto_new',
    			 'compare',
    			 'compareAttribute' => 'monto_minimo',
    			 'operator' => '>=',
    			 'message' => Yii::t('backend', '{attribute} must be no less that ' . self::attributeLabels()['monto_minimo'])],
    			[['monto_new', 'monto_v', 'monto_minimo'],
    			  'double', 'message' => Yii::t('backend', '{attribute} must be decimal.')],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'tipo_declaracion' => Yii::t('frontend', 'Type'),
	            'descripcion' => Yii::t('frontend', 'Description'),
	            'inactivo' => Yii::t('frontend', 'Condition'),
	            'monto_minimo' => Yii::t('frontend', 'Monto Minimo'),
	            'monto_new' => Yii::t('frontend', 'Monto Declaracion'),
	            'rubro' => Yii::t('frontend', 'Category'),
	            'id_rubro' => Yii::t('frontend', 'Id.'),

	        ];
	    }

	}
?>