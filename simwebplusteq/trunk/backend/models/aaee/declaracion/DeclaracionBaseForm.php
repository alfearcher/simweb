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
		public $fecha_inicio;
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
		public $islr;
		public $pp_industria;
		public $pagos_retencion;
		public $iva_enero;
		public $iva_febrero;
		public $iva_marzo;
		public $iva_abril;
		public $iva_mayo;
		public $iva_junio;
		public $iva_julio;
		public $iva_agosto;
		public $iva_septiembre;
		public $iva_octubre;
		public $iva_noviembre;
		public $iva_diciembre;

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
	        					'fecha_inicio',
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
	        					'iva_enero',
	        					'iva_febrero',
	        					'iva_marzo',
	        					'iva_abril',
	        					'iva_mayo',
	        					'iva_junio',
	        					'iva_julio',
	        					'iva_agosto',
	        					'iva_septiembre',
	        					'iva_octubre',
	        					'iva_noviembre',
	        					'iva_diciembre',
	        					'islr',
	        					'pp_industria',
	        					'pagos_retencion',

	        		],
        		self::SCENARIO_DEFINITIVA => [
        						'id_contribuyente',
	        					'ano_impositivo',
	        					'exigibilidad_periodo',
	        					'fecha_inicio',
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
	        					'iva_enero',
	        					'iva_febrero',
	        					'iva_marzo',
	        					'iva_abril',
	        					'iva_mayo',
	        					'iva_junio',
	        					'iva_julio',
	        					'iva_agosto',
	        					'iva_septiembre',
	        					'iva_octubre',
	        					'iva_noviembre',
	        					'iva_diciembre',
	        					'islr',
	        					'pp_industria',
	        					'pagos_retencion',

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
	        	  'monto_new', 'id_rubro', 'tipo_declaracion',
	        	  'rubro', 'descripcion'],
	        	  'required', 'on' => 'estimada',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['id_contribuyente',
	        	  'monto_new', 'id_rubro', 'tipo_declaracion',
	        	  'rubro', 'descripcion'],
	        	  'required', 'on' => 'definitiva',
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
	          	[['islr', 'pp_industria', 'pagos_retencion',
	          	  'iva_enero', 'iva_febrero', 'iva_marzo',
	          	  'iva_abril', 'iva_mayo', 'iva_abril',
	          	  'iva_junio', 'iva_julio', 'iva_agosto',
	          	  'iva_septiembre', 'iva_octubre', 'iva_noviembre', 'iva_diciembre'],
	          	  'default', 'value' => 0],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	['monto_new',
    			 'compare',
    			 'compareAttribute' => 'monto_minimo',
    			 'operator' => '>=', 'on' => 'estimada',
    			 'message' => Yii::t('backend', '{attribute} must be no less that ' . self::attributeLabels()['monto_minimo'])],
    			 ['monto_new',
    			 'compare',
    			 'compareAttribute' => 'monto_minimo',
    			 'operator' => '>=', 'on' => 'definitiva',
    			 'message' => Yii::t('backend', '{attribute} must be no less that ' . self::attributeLabels()['monto_minimo'])],
    			[['monto_new', 'monto_v',],
    			  'double', 'message' => Yii::t('backend', '{attribute} must be decimal.')],
    			['monto_new', 'compare', 'compareValue' => 0, 'operator' => '>='],

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
	            'monto_minimo' => Yii::t('frontend', 'Monto minimo'),
	            'monto_new' => Yii::t('frontend', 'Monto a declarar'),
	            'rubro' => Yii::t('frontend', 'Category'),
	            'id_rubro' => Yii::t('frontend', 'Id.'),
	            'islr' => Yii::t('frontend', 'islr'),
	            'pp_industria' => Yii::t('frontend', 'Pago por Industria'),
	            'pagos_retencion' => Yii::t('frontend', 'Pago por Retencion'),
	            'iva_enero' => Yii::t('frontend', 'enero'),
	            'iva_febrero' => Yii::t('frontend', 'febrero'),
	            'iva_marzo' => Yii::t('frontend', 'marzo'),
	            'iva_abril' => Yii::t('frontend', 'abril'),
	            'iva_mayo' => Yii::t('frontend', 'mayo'),
	            'iva_junio' => Yii::t('frontend', 'junio'),
	            'iva_julio' => Yii::t('frontend', 'julio'),
	            'iva_agosto' => Yii::t('frontend', 'agosto'),
	            'iva_septiembre' => Yii::t('frontend', 'septiembre'),
	            'iva_octubre' => Yii::t('frontend', 'octubre'),
	            'iva_noviembre' => Yii::t('frontend', 'noviembre'),
	            'iva_diciembre' => Yii::t('frontend', 'diciembre'),
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


	}
?>