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
 *  @file AnexoRamoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-08-2016
 *
 *  @class AnexoRamoForm
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

	namespace backend\models\aaee\anexoramo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\anexoramo\AnexoRamo;
	use backend\models\aaee\anexoramo\AnexoRamoSearch;
	use backend\models\aaee\rubro\RubroForm;



	/**
	* 	Clase base del formulario anexar-ramo-form.
	*/
	class AnexoRamoForm extends AnexoRamo
	{
		public $id_anexo_ramo;
		public $nro_solicitud;
		public $id_contribuyente;
		public $ano_impositivo;
		public $periodo;
		public $id_rubro;
		public $fecha_desde;
		public $fecha_hasta;
		public $fecha_hora;
		public $usuario;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;

		public $a;		// año
		public $p;		// periodo

		public $inputSearch;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';
		const SCENARIO_SEARCH = 'search';
		const SCENARIO_DEFAULT = 'default';

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
        					'ano_impositivo',
        					'periodo',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'periodo',
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
        					'periodo',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        		],
        		self::SCENARIO_DEFAULT => [
        					'id_contribuyente',
        					'ano_impositivo',
        					'periodo',
        					// 'fecha_desde',
        					// 'fecha_hasta',
        					// 'origen',
        					// 'fecha_hora',
        					// 'usuario',
        					// 'estatus',
        		],
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'periodo', 'fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'frontend',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'periodo', 'fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'backend',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	        	 [['id_contribuyente', 'ano_impositivo',
	        	  'periodo',],
	        	  'required', 'on' => 'search',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	        	[['nro_solicitud', 'id_contribuyente',
	        	  'ano_impositivo',
	        	  'periodo', 'estatus'],
	        	  'integer', 'message' => Yii::t('frontend','{attribute}')],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'date', 'format' => 'yyyy-MM-dd',
	        	  'message' => Yii::t('frontend','formatted date no valid')],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	     		['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
	     		['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['fecha_hora_proceso', 'default', 'value' => '0000-00-00 00:00:00'],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
	     		// ['fecha_desde', function() {
	     		// 					return self::rangoFiscal();
	     		// }],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->username, 'on' => 'backend'],
	        ];
	    }





	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_anexo_ramo' => Yii::t('frontend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('frontend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('frontend', 'Request'),
	            'fecha_desde' => Yii::t('frontend', 'Fiscal Start Date'),
	            'fecha_hasta' => Yii::t('frontend', 'Fiscal End Date'),
	            'periodo' => Yii::t('frontend', 'Period'),
	            'ano_impositivo' => Yii::t('frontend', 'Fiscal Year'),
	            'dni' => Yii::t('frontend', 'DNI'),
	            'razon_social' => Yii::t('frontend', 'Company Name'),
	            'domicilio_fiscal' => Yii::t('frontend', 'Addrres Office'),
	            'id_sim' => Yii::t('frontend', 'License'),
	            'rubro' => Yii::t('frontend', 'Category'),
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