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
 *  @file CorreccionCedulaRifForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-12-2015
 *
 *  @class CorreccionCedulaRifForm
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

 	namespace backend\models\aaee\correccioncedularif;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRif;
	use common\models\aaee\Sucursal;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRifSearch;


	class CorreccionCedulaRifForm extends CorreccionCedulaRif
	{

		public $id_correccion;
		public $nro_solicitud;
		public $id_contribuyente;
		public $naturaleza_v;				// Naturaleza vieja.
		public $cedula_v;					// Cedula vieja.
		public $tipo_v;						// Tipo vieja, solo para los contribuyente Juridicos.
		public $tipo_naturaleza_v;
		public $naturaleza_new;				// Naturaleza nueva.
		public $cedula_new; 				// Cedula nnueva.
		public $tipo_new;					// Tipo nuevo, solo para los contribuyentes Juridicos.
		public $tipo_naturaleza_new;
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $origen;
		public $fecha_hora_proceso;
		public $user_funcionario;

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
        					'naturaleza_v',
        					'cedula_v',
        					'tipo_v',
        					'tipo_naturaleza_v',
        					'naturaleza_new',
        					'cedula_new',
        					'tipo_new',
        					'tipo_naturaleza_new',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'naturaleza_v',
        					'cedula_v',
        					'tipo_v',
        					'tipo_naturaleza_v',
        					'naturaleza_new',
        					'cedula_new',
        					'tipo_new',
        					'tipo_naturaleza_new',
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
    			[['naturaleza_v', 'cedula_v',
    			  'tipo_v', 'tipo_naturaleza_v',
    			   'naturaleza_new', 'cedula_new',
    			   'tipo_new', 'tipo_naturaleza_new',
    			   'id_contribuyente'],
    			   'required', 'on' => 'frontend',
    			   'message' => Yii::t('backend', '{attribute} is required')],
    			[['naturaleza_v', 'cedula_v',
    			  'tipo_v', 'tipo_naturaleza_v',
    			   'naturaleza_new', 'cedula_new',
    			   'tipo_new', 'tipo_naturaleza_new',
    			   'id_contribuyente'],
    			   'required', 'on' => 'backend',
    			   'message' => Yii::t('backend', '{attribute} is required')],
    			[['naturaleza_v', 'naturaleza_new'], 'string', 'max' => 1],
    			[['tipo_v', 'tipo_new', 'cedula_v', 'cedula_new',
    			  'tipo_naturaleza_v', 'tipo_naturaleza_new'],
    			  'integer'],
    			['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
	     		['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
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
	        	'id_correccion' => Yii::t('frontend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('frontend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('frontend', 'Request Number'),
	            'dni_v' => Yii::t('frontend', 'Current DNI'),
	            'dni_new' => Yii::t('frontend', 'New DNI'),
	            'razon_social' => Yii::t('frontend', 'Companies'),
	            'id_sim' => Yii::t('frontend', 'License'),
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
	    						'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,

	    		],
	    		Yii::$app->solicitud->negar() => [
	    						'estatus' => 9,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,

	    		],
	    	];

	    	return $atributos[$evento];
	    }





	    /**
	     * Metodo que retorna un dataProvider de los contribuyentes asociados a un rif (sucursales)
	     * @param  [type] $naturalezaLocal [description]
	     * @param  [type] $cedulaLocal     [description]
	     * @param  [type] $tipoLocal       [description]
	     * @return [type]                  [description]
	     */
	    public function getDataProviderSucursalesSegunRif($naturalezaLocal, $cedulaLocal, $tipoLocal, $tipoNaturalezaLocal)
	    {
	    	if ( trim($naturalezaLocal) != '' && $cedulaLocal > 0 ) {
	    		if ( strlen($naturalezaLocal) == 1 ) {
	    			$query = Sucursal::find();
	    			$dataProvider = new ActiveDataProvider([
	            		'query' => $query,
	        		]);
	    			$query->where('naturaleza =:naturaleza and cedula =:cedula and tipo =:tipo and tipo_naturaleza =:tipo_naturaleza and inactivo =:inactivo',[':naturaleza' => $naturalezaLocal,
	    															':cedula' => $cedulaLocal,
	    															':tipo' => $tipoLocal,
	    															':tipo_naturaleza' => $tipoNaturalezaLocal,
	    															':inactivo'=> '0'])->all();


	    			return $dataProvider;
	    		}
	    	}
	    	return false;
	    }







	    /**
	     * Metodo que retorna un dataProvider, recibiendo como parametro un arreglo de id contribuyentes.
	     * @param $arrayIdContribuyente, array de id contribuyentes,
	     * @return retorna un dataProvider.
	     */
	    public function getDataProviderSucursalesSegunId($arrayIdContribuyente = [])
	    {
	    	if ( is_array($arrayIdContribuyente) ) {
		    	if ( count($arrayIdContribuyente) > 0 ) {
		    		$query = Sucursal::find();
		    		$dataProvider = new ActiveDataProvider([
		            	'query' => $query,
		        	]);
		        	$query->where(['in', 'id_contribuyente', $arrayIdContribuyente]);

		        	return $dataProvider;
		    	}
		    }
		    return false;
	    }






	    /**
	     * Metodo que genera el dataprovider de las correcciones realizadas.
	     * @param $arrayIdCorreccion, array de id correccion guardados en el proceso.
	     * @return returna un dataProvider o false sino logra crear la instancia.
	     */
	    public function getDataProviderCorreccionesCedulaRif($arrayIdCorreccion = [])
	    {
	    	if ( is_array($arrayIdCorreccion) ) {
		    	if ( count($arrayIdCorreccion) > 0 ) {
		    		$query = CorreccionCedulaRif::find();
		    		$dataProvider = new ActiveDataProvider([
		            	'query' => $query,
		        	]);
		        	$query->where(['in', 'id_correccion', $arrayIdCorreccion]);

		        	return $dataProvider;
		    	}
		    }
		    return false;
	    }



	    /***/
	    public function validateRif()
	    {
	    	$result = false;
	    	$correcion = New CorreccionCedulaRifSearch($this->id_contribuyente);

	    	$result = $correcion->validarRif($this->naturaleza_new, $this->cedula_new, $this->tipo_new);

	    	return $result;
	    }

	}
?>