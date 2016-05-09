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
	use common\models\contribuyente\ContribuyenteBase;
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
		public $origen;
		public $estatus;
		public $fecha_hora;
		public $usuario;
		public $user_funcionario;				// Funcionario que aprueba o rechaza la solicitud.
		public $fecha_hora_proceso;				// Fecha y hora cuando de aprueba o rechaza la solcitud.

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['reg_mercantil', 'tomo', 'folio', 'fecha',
	        	  'num_reg', 'capital', 'num_empleados',
	        	  'naturaleza_rep', 'cedula_rep', 'representante'],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_contribuyente', 'num_reg',
	        	  'num_empleados', 'cedula_rep'],
	        	  'integer'],
	        	[['capital'], 'double'],
	        	[['reg_mercantil', 'tomo', 'folio', 'naturaleza_rep',
	        	  'representante'],
	        	  'string'],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	['num_reg', 'unique'],
	          	['capital', 'default', 'value' => 0],
	     		['estatus', 'default', 'value' => 0],
	     		['fecha_hora_proceso', 'default', 'value' => date('Y-m-d H:i:s', strtotime('0000-00-00 00:00:00'))],
	     		['user_funcionario', 'default', 'value' => null],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	     		['origen', 'default', 'value' => 'LAN'],
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
	            'num_reg' => Yii::t('backend', 'Number Registration'),
	            'fecha' => Yii::t('backend', 'Date'),
	            'tomo' => Yii::t('backend', 'Volume Number'),
	            'folio' => Yii::t('backend', 'Folio'),
	            'capital' => Yii::t('backend', 'Capital'),
	            'num_empleados' => Yii::t('backend', 'Number of Employees'),
	            'naturaleza_rep' => Yii::t('backend', 'Natural'),
	            'cedula_rep' => Yii::t('backend', 'DNI'),
	            'representante'  => Yii::t('backend', 'Legal Representative'),

	        ];
	    }




	    /**
	    *	Metodo que retarna el arreglo de atributos que seran actualizados.
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
	    		'representante'
	    	];
	    }




	    /**
	     * Metodo que retorna la descripcion del tipo de contribuyente, segun el identificador del mismo.
	     * "NATURAL".
	     * "JURIDICO".
	     * @param  Long $idContribuyente identificador dle contribuyente.
	     * @return String Retorna la descripcion del tipo de contribuyente.
	     */
	    public function getTipoNaturalezaDescripcionSegunID($idContribuyente)
	    {
	    	$descripcion = null;
	    	return $descripcion = ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($idContribuyente);
	    }


	}
?>