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
 *  @file InscripcionActividadEconomicaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-08-2015
 *
 *  @class InscripcionActividadEconomicaForm
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

	namespace backend\models\aaee\inscripcionactecon;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;

	/**
	* 	Clase base del formulario inscripcion-act-econ-form.
	*/
	class InscripcionActividadEconomicaForm extends InscripcionActividadEconomica
	{
		public $nro_solicitud;
		public $id_contribuyente;
		public $reg_mercantil;
		public $num_reg;
		public $tomo;
		public $folio;
		public $fecha;
		public $capital;
		public $num_empleados;
		public $naturaleza_rep;
		public $cedula_rep;
		public $representante;
		public $fecha_inicio;
		public $origen;
		public $estatus;
		public $fecha_hora;
		public $usuario;
		public $user_funcionario;				// Funcionario que aprueba o rechaza la solicitud.
		public $fecha_hora_proceso;				// Fecha y hora cuando de aprueba o rechaza la solcitud.


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
        					'reg_mercantil',
        					'num_reg',
        					'tomo',
        					'folio',
        					'fecha',
        					'capital',
        					'num_empleados',
        					'naturaleza_rep',
        					'cedula_rep',
        					'representante',
        					'fecha_inicio',
        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'reg_mercantil',
        					'num_reg',
        					'tomo',
        					'folio',
        					'fecha',
        					'capital',
        					'num_empleados',
        					'naturaleza_rep',
        					'cedula_rep',
        					'representante',
        					'fecha_inicio',
        		]
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['reg_mercantil', 'tomo', 'folio', 'fecha',
	        	  'num_reg', 'capital', 'num_empleados',
	        	  'naturaleza_rep', 'cedula_rep', 'representante',
	        	  'fecha_inicio',],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_contribuyente', 'num_reg',
	        	  'num_empleados', 'cedula_rep'],
	        	  'integer'],
	        	//['capital', 'double'],
	        	[['reg_mercantil', 'tomo', 'folio', 'naturaleza_rep',
	        	  'representante'],
	        	  'string'],
	        	// [['fecha_inicio'],
	        	//   'date',
	        	//   'message' => Yii::t('frontend','formatted date no valid')],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	//['num_reg', 'unique'],
	          	['capital', 'default', 'value' => 0],
	     		['estatus', 'default', 'value' => 0],
	     		['fecha_hora_proceso', 'default', 'value' => date('Y-m-d H:i:s', strtotime('0000-00-00 00:00:00'))],
	     		['user_funcionario', 'default', 'value' => null],
	     		//['usuario', 'default', 'value' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login, 'on' => 'backend'],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		['origen', 'default', 'value' => 'WEB'],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null],
	     		['cedula_rep', 'string', 'max' => 8],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Number Request'),
	            'reg_mercantil' => Yii::t('backend', 'Commercial Register'),
	            'num_reg' => Yii::t('backend', 'Number Register'),
	            'fecha' => Yii::t('backend', 'Date Register'),
	            'tomo' => Yii::t('backend', 'Volume Number'),
	            'folio' => Yii::t('backend', 'Folio'),
	            'capital' => Yii::t('backend', 'Capital'),
	            'num_empleados' => Yii::t('backend', 'Number of Employees'),
	            'naturaleza_rep' => Yii::t('backend', 'Natural'),
	            'cedula_rep' => Yii::t('backend', 'DNI'),
	            'representante' => Yii::t('backend', 'Legal Representative'),
	            'fecha_inicio' => Yii::t('backend', 'Start Date of Activity'),

	        ];
	    }




	    /**
	    * Metodo que retarna el arreglo de atributos que seran actualizados
	    * en la entidad principal, "contribuyentes".
	    */
	    public function atributosUpDate()
	    {
	    	return [
	    		'reg_mercantil',
	    		'num_reg',
	    		'fecha',
	    		'tomo',
	    		'folio',
	    		'capital',
	    		'num_empleados',
	    		'naturaleza_rep',
	    		'cedula_rep',
	    		'representante',
	    		'fecha_inicio',
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
	    						'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s')
	    		],
	    		Yii::$app->solicitud->negar() => [
	    						'estatus' => 9,
	    						'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s')
	    		],
	    	];

	    	return $atributos[$evento];
	    }


	}
?>