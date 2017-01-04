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
 *  @file InscripcionSucursalForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-10-2015
 *
 *  @class InscripcionSucursalForm
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

	namespace backend\models\aaee\inscripcionsucursal;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;
	use common\models\contribuyente\ContribuyenteBase;

	/**
	* 	Clase base del formulario inscripcion-sucursal-form.
	*/
	class InscripcionSucursalForm extends InscripcionSucursal
	{
		public $id_inscripcion_sucursal;
		public $nro_solicitud;
		public $id_sede_principal;
		public $id_contribuyente;
		public $naturaleza;
		public $cedula;
		public $tipo;
		public $razon_social;
		public $domicilio_fiscal;
		public $id_sim;
		public $tlf_ofic;
		public $tlf_ofic_otro;
		public $email;
		public $tlf_celular;
		public $fecha_inicio;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;
		public $num_tlf_ofic;
		public $num_tlf_ofic_otro;
		public $num_celular;

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
        					'id_sede_principal',
        					'id_contribuyente',
        					'naturaleza',
        					'cedula',
        					'tipo',
        					'razon_social',
        					'domicilio_fiscal',
        					'id_sim',
        					'email',
        					'tlf_ofic',
        					'tlf_celular',
        					'fecha_inicio',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        					//'num_tlf_ofic',
        					//'num_tlf_ofic_otro',
        					//'num_celular',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_sede_principal',
        					'id_contribuyente',
        					'naturaleza',
        					'cedula',
        					'tipo',
        					'razon_social',
        					'domicilio_fiscal',
        					'id_sim',
        					'email',
        					'tlf_ofic',
        					'tlf_celular',
        					'fecha_inicio',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        					// 'num_tlf_ofic',
        					// 'num_tlf_ofic_otro',
        					// 'num_celular',
        		]
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_sede_principal', 'naturaleza',
	        	  'cedula', 'tipo', 'domicilio_fiscal',
	        	  'email',
	        	  'fecha_inicio', 'razon_social',
	        	  'tlf_ofic', 'tlf_celular',],
	        	  'required', 'on' => 'frontend', 'message' => Yii::t('frontend','{attribute} is required')],
	        	[['fecha_inicio'], 'date', 'format' => 'dd-MM-yyyy','message' => Yii::t('backend','formatted date no valid')],
	        	// [['fecha_inicio'], 'required', 'when' => function($model) {
	        	// 										return self::fechaInicioValida();
	        	// 									}
	        	// ],
	        	[['email'], 'email', 'message' => Yii::t('backend','{attribute} is email')],
	        	['email', 'filter','filter'=>'strtolower'],
	        	['razon_social', 'filter', 'filter' => 'strtoupper'],
	        	[['domicilio_fiscal', 'naturaleza',
	        	  'tlf_celular', 'tlf_ofic',
	        	  'tlf_ofic_otro', 'id_sim',
	        	  'num_tlf_ofic', 'num_tlf_ofic_otro',
	        	  'num_celular'],
	        	  'string', 'on' => 'frontend',],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	//['id_sim', 'unique'],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login],
	     		['origen', 'default', 'value' => 'WEB'],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		//['id_contribuyente', 'default', 'value' => 0],
	     		['razon_social', 'string', 'max' => 75],
	     		//[['tlf_ofic', 'tlf_ofic_otro', 'tlf_celular'], 'match', 'pattern' => '/^[0-9]{4}-[0-9]{7}$/', 'message' => Yii::t('backend', '{attribute} not valid')],
	     		[['tlf_ofic', 'tlf_ofic_otro', 'tlf_celular'], 'string', 'max' => 12],
	     		[['tlf_ofic', 'tlf_celular'], 'string', 'min' => 12],
	     		['id_sede_principal', 'default', 'value' => $_SESSION['idContribuyente']],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_inscripcion_sucursal' => Yii::t('backend', 'Id. Sucursal'),
	        	'id_sede_principal' => Yii::t('backend', 'Id. sede'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Application Number'),
	            'naturaleza' => Yii::t('backend', 'RIF'),
	            'cedula' => Yii::t('backend', 'DNI'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'domicilio_fiscal' => Yii::t('backend', 'Home'),
	            'tlf_ofic' => Yii::t('backend', 'Phone Office 1'),
	            'tlf_ofic_otro' => Yii::t('backend', 'Phone Office 2'),
	            'tlf_celular' => Yii::t('backend', 'Phone Celular of Office'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'email' => Yii::t('backend', 'email'),
	            'razon_social' => Yii::t('backend', 'Razon Social'),
	            'id_sim' => Yii::t('backend', 'Licence Number'),
	            'num_celular' => Yii::t('backend', 'Celular Number'),
	            'num_tlf_ofic' => Yii::t('backend', 'Office Number 1'),
	            'num_tlf_ofic_otro' => Yii::t('backend', 'Office Number 2'),
	            'dni_representante' => Yii::t('backend', 'DNI legal represent'),
	            'representante' => Yii::t('backend', 'Legal represent'),
	            'num_reg' => Yii::t('backend', 'Number'),
	            'reg_mercantil' => Yii::t('backend', 'Commercial register'),
	            'fecha' => Yii::t('backend', 'Date'),
	            'tomo' => Yii::t('backend', 'Volumen number'),
	            'folio' => Yii::t('backend', 'Folio'),
	            'capital' => Yii::t('backend', 'Capital'),
	            'dni_principal' => Yii::t('backend', 'DNI headquarter main')

	        ];
	    }




	    /**
	     * Metodo que indica los atributos que seran insertados
	     * @return array retorna arreglo de atributos que seran incluidos en
	     * la operacion de insercion.
	     */
	    public function getAttributeInsert()
	    {
	    	return [
	        	'id_inscripcion_sucursal',
	        	'nro_solicitud',
	        	'id_sede_principal',
	            'id_contribuyente',
	            'naturaleza',
	            'cedula',
	            'tipo',
	            'razon_social',
	            'domicilio_fiscal',
	            'id_sim',
	            'tlf_ofic',
	            'tlf_ofic_otro',
	            'email',
	            'tlf_celular',
	            'fecha_inicio',
	            'origen',
	            'fecha_hora',
	            'usuario',
	            'estatus',
	            'fecha_hora_proceso',
	            'user_funcionario',
	        ];
	    }



	    /**
	     * Metodo que retorna los atributos que seran utilizados para crear la sucursal, estos
	     * atributos diferencian a cada sucursal entre si mismas y entre las sucursales y la sede
	     * principal, lo que se busca es solo afectar aquellos atributos particulares de cada
	     * sucursal. Estos atributos fueron cargados en la solicitud para crear la sucursal, asi
	     * que cada atributo debe tener un valor especifico en la solicitud relaizada. De no ser
	     * asi se debe considerar que la solicitud no corresponde con el modelo de negocio de la
	     * solicitud.
	     * @return array retorna una arrego de atributos.
	     */
	    public function getAtributoSucursal()
	    {
	    	return [
	    		'naturaleza',
	            'cedula',
	            'tipo',
	            'razon_social',
	            'domicilio_fiscal',
	            'id_sim',
	            'tlf_ofic',
	            'tlf_ofic_otro',
	            'email',
	            'tlf_celular',
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




	    /**
	     * Metodo que permite verificar si un contribuyente posee todos los datos necesarios
	     * del registro mercantil, esto permitira crear la sucursal y asumir los mismos valores
	     * que aparece en el registro mercantil de la sede principal.
	     * @param  $datos, array que posee los datos de la sede que se pretende tomar como principal.
	     * @return return boolean, true si todos los datos estan bien, false no se puede crear la sucursal.
	     */
	    public static function datosRegistroMercantilValido($datos)
	    {
	    	if ( is_array($datos) ) {
	    		if ( !isset($datos['fecha']) ) { return false; }
	    		if ( !isset($datos['reg_mercantil']) ) { return false; }
	    		if ( !isset($datos['num_reg']) ) { return false; }
	    	} else {
	    		return false;
	    	}
	    	return true;
	    }



	    /**
	     * Metodo para determinar si un contribuyente es una sede principal.
	     * @param  $datos, array que posee los datos de la sede que se pretende tomar como principal.
	     * @return return boolean, true si es una sede principal, y false no lo es.
	     */
	    public static function sedePrincipal($datos)
	    {
	    	if ( is_array($datos) ) {
	    		if ( $datos[0]['id_rif'] == 0 ) {
	    			return true;
	    		}
	    	}
	    	return false;
	    }


	    /***/
	    public function fechaInicioValida()
	    {
	    	$result = true;
			// Se determina la fecha de inicio de la principal, sino tiene la
			// validacion no debe realizarse.
			$findModel = ContribuyenteBase::find($this->id_sede_principal);
			if ( count($findModel) > 0 ) {
				if ( $findModel->fecha_inicio !== null && $findModel->fecha_inicio !== '0000-00-00' ) {
					if ( $this->fecha_inicio >= $findModel->fecha_inicio ) {
						$result	= false;
					}
				}
			}

			return $result;
	    }




	}
?>