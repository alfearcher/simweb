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
 *  @file CorreccionDomicilioFiscalForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2015
 *
 *  @class CorreccionDomicilioFiscalForm
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

 	namespace backend\models\aaee\correcciondomicilio;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;


	/**
	 * Class principal
	 */
	class CorreccionDomicilioFiscalForm extends CorreccionDomicilioFiscal
	{
		public $id_correcion;
		public $nro_solicitud;
		public $id_contribuyente;
		public $domicilio_fiscal_v;
		public $domicilio_fiscal_new;
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $origen;
		public $fecha_hora_proceso;
		public $user_funcionario;

		public $naturaleza;
		public $cedula;
		public $tipo;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_FRONTEND => [
        					'id_contribuyente',
        					'domicilio_fiscal_v',
        					'domicilio_fiscal_new',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'domicilio_fiscal_v',
        					'domicilio_fiscal_new',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        		]
        	];
    	}




    	/**
    	 * [rules description]
    	 * @return [type] [description]
    	 */
    	public function rules()
    	{
    		return [
    			[['domicilio_fiscal_new', 'id_contribuyente'],
    			  'required', 'on' => 'frontend',
    			  'message' => Yii::t('backend', '{attribute} is required')],
    			[['domicilio_fiscal_new', 'id_contribuyente'],
    			  'required', 'on' => 'backend',
    			  'message' => Yii::t('backend', '{attribute} is required')],
    			['domicilio_fiscal_new', 'filter', 'filter' => 'strtoupper'],
    			[['domicilio_fiscal_new'], 'string', 'max' => 255],
    			['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
    			['estatus', 'default', 'value' => 0],
    			['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->username, 'on' => 'backend'],
	     		['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],

    		];
    	}


    	/**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_correccion' => Yii::t('frontend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('frontend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('frontend', 'Request'),
	            'domicilio_fiscal_v' => Yii::t('frontend', 'Current Tax Address'),
	            'domicilio_fiscal_new' => Yii::t('frontend', 'New Tax Address'),
	            'estatus' => Yii::t('frontend', 'Condition'),
	            'fecha_hora' => Yii::t('frontend', 'Date/Hour'),
	            'razon_social' => Yii::t('frontend', 'Companies'),
	            'dni' => Yii::t('frontend', 'DNI'),

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