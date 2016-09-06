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
		public $origen;
		public $usuario;
		public $fecha_hora;
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;

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
	        					'fecha_desde',
	        					'fecha_hasta',
	        					'origen',
	        					'fecha_hora',
	        					'usuario',
	        					'estatus',

	        		],
        		self::SCENARIO_DEFINITIVA => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'exigibilidad_periodo',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_SEARCH => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'exigibilidad_periodo',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        		],
	        ];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'monto_v', 'monto_new'],
	        	  'required', 'on' => 'estimada',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['id_contribuyente', 'ano_impositivo', 'exigibilidad_periodo'],
	        	  'required', 'on' => 'search',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['tipo_declaracion', 'inactivo'],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute}')],
	          	['inactivo', 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'tipo_declaracion' => Yii::t('backend', 'Id. Register'),
	            'descripcion' => Yii::t('backend', 'description'),
	            'inactivo' => Yii::t('backend', 'Condition'),

	        ];
	    }

	}
?>