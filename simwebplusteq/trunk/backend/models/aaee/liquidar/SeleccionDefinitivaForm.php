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
 *  @file SeleccionDefinitivaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class SeleccionDefinitivaForm
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

	namespace backend\models\aaee\liquidar;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBase;



	/**
	* 	Clase base del formulario
	*/
	class SustitutivaBaseForm extends SustitutivaBase
	{
		public $id_sustitutiva;
		public $nro_solicitud;
		public $id_contribuyente;
		public $id_impuesto;
		public $id_rubro;
		public $exigibilidad_periodo;
		public $fecha_inicio;
		public $estimado;
		public $reales;
		public $sustitutiva;
		public $rectificatoria;
		public $auditoria;
		public $tipo_declaracion;
		public $periodo_fiscal_desde;
		public $periodo_fiscal_hasta;
		public $origen;
		public $usuario;
		public $fecha_hora;
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;
		public $condicion;

		public $ano_impositivo;
		public $rubro;
		public $descripcion;
		public $chkHabilitar;

		const SCENARIO_ESTIMADA = 'estimada';
		const SCENARIO_DEFINITIVA = 'definitiva';
		const SCENARIO_SEARCH = 'search';
		const SCENARIO_SEARCH_TIPO = 'search_tipo';


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
	        					'id_impuesto',
	        					'id_rubro',
	        					'exigibilidad_periodo',
	        					'fecha_inicio',
	        					'estimado',
	        					'reales',
	        					'sustitutiva',
	        					'rectificatoria',
	        					'auditoria',
	        					'tipo_declaracion',
	        					'periodo_fiscal_desde',
	        					'periodo_fiscal_hasta',
	        					'rubro',
	        					'descripcion',
	        					'origen',
	        					'fecha_hora',
	        					'usuario',
	        					'estatus',
	        					'ano_impositivo',
	        					'condicion',
	        					'chkHabilitar',


	        		],
        		self::SCENARIO_DEFINITIVA => [
        						'id_contribuyente',
	        					'id_impuesto',
	        					'id_rubro',
	        					'exigibilidad_periodo',
	        					'fecha_inicio',
	        					'estimado',
	        					'reales',
	        					'sustitutiva',
	        					'rectificatoria',
	        					'auditoria',
	        					'tipo_declaracion',
	        					'periodo_fiscal_desde',
	        					'periodo_fiscal_hasta',
	        					'rubro',
	        					'descripcion',
	        					'origen',
	        					'fecha_hora',
	        					'usuario',
	        					'estatus',
	        					'ano_impositivo',
	        					'condicion',
	        					'chkHabilitar',

        			],
        		self::SCENARIO_SEARCH => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'tipo_declaracion',
        					'exigibilidad_periodo',

        			],

        		self::SCENARIO_SEARCH_TIPO => [
        					'id_contribuyente',
        					'tipo_declaracion',

        			],
	        ];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'estimado',
	        	  'id_impuesto', 'id_rubro',
	        	  'exigibilidad_periodo', 'tipo_declaracion',
	        	  'rubro', 'descripcion', 'chkHabilitar'],
	        	  'required', 'when' => function($model) {
	        	  							if ( $model->chkHabilitar == 1 ) {
	        	  								return true;
	        	  							}
	        	  },
	        	  'on' => 'estimada',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_contribuyente', 'reales',
	        	  'id_impuesto', 'id_rubro',
	        	  'exigibilidad_periodo', 'tipo_declaracion',
	        	  'rubro', 'descripcion', 'chkHabilitar'],
	        	  'required', 'when' => function($model) {
	        	  							if ( $model->chkHabilitar == 1 ) {
	        	  								return true;
	        	  							}
	        	  },
	        	  'on' => 'definitiva',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'exigibilidad_periodo', 'tipo_declaracion'],
	        	  'required', 'on' => 'search',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_contribuyente', 'tipo_declaracion'],
	        	  'required', 'on' => 'search_tipo',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_rubro', 'estatus', 'id_contribuyente',
	        	  'exigibilidad_periodo', 'id_impuesto',
	        	  'tipo_declaracion', 'chkHabilitar'],
	        	  'integer',
	        	  'message' => Yii::t('frontend', '{attribute} no valid')],
	          	[['estatus', 'tipo_declaracion', 'condicion'],
	          	  'default', 'value' => 0],
	          	[['estimado','reales', 'sustitutiva',
	          	  'rectificatoria', 'auditoria'],
	          	  'default', 'value' => 0],
	          	[['sustitutiva'], 'default', 'value' => function($model) {
	          												return self::getMontoDefault($model);
	          											},
	          	],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	// ['monto_new',
    			 // 'compare',
    			 // 'compareAttribute' => 'monto_minimo',
    			 // 'operator' => '>=', 'on' => 'estimada',
    			 // 'message' => Yii::t('backend', '{attribute} must be no less that ' . self::attributeLabels()['monto_minimo'])],
    			// [['estimado', 'reales', 'sustitutiva',],
    			//   'double', 'message' => Yii::t('backend', '{attribute} must be decimal.')],
    			['estimado',
    			 'compare',
    			 'compareValue' => 0, 'operator' => '>=',
    			 'on' => 'estimado'],
    			['reales',
    			 'compare',
    			 'compareValue' => 0, 'operator' => '>=',
    			 'on' => 'definitiva'],

	        ];
	    }



	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales
	    * son las que aparecen en las vistas.
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas
	    * como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'descripcion' => Yii::t('backend', 'Description'),
	            'estatus' => Yii::t('backend', 'Condition'),
	            'estimado' => Yii::t('backend', 'Monto estimado'),
	            'reales' => Yii::t('backend', 'Monto definitiva'),
	            'sustitutiva' => Yii::t('backend', 'Monto sustitutiva'),
	            'rubro' => Yii::t('backend', 'Rubro'),
	            'id_rubro' => Yii::t('backend', 'Id. Rubro.'),
	            'id_contribuyente' => Yii::t('backend', 'ID.'),
	            'ano_impositivo' => Yii::t('backend', 'Año'),
	            'exigibilidad_periodo' => Yii::t('backend', 'Periodo'),
	        ];
	    }



	    /**
	     * Metodo que retorna un arreglo de atributos que seran actualizados
	     * al momento de procesar la solicitud (aprobar o negar). Estos atributos
	     * afectaran a la entidad respectiva de la clase.
	     * @param String $evento, define la accion a realizar sobre la solicitud.
	     * - Aprobar.
	     * - Negar.
	     * @return Array Retorna un arreglo de atributos segun el evento.
	     */
	    public function atributosUpDateProcesarSolicitud($evento)
	    {
	    	$atributos = [
	    		Yii::$app->solicitud->aprobar() => [
	    						'estatus' => 1,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    		Yii::$app->solicitud->negar() => [
	    						'estatus' => 9,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    	];

	    	return $atributos[$evento];
	    }



	    /***/
	    public function getMontoDefault($model)
	    {
	    	if ( $model->chkHabilitar == 0 ) {
	    		if ( $model->tipo_declaracion == 1 ) {
	    			return $model->estimado;
	    		} elseif ( $model->tipo_declaracion == 2 ) {
	    			return $model->reales;
	    		}
	    	}

	    	return 0;
	    }

	}
?>