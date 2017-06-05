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

 	namespace backend\models\aaee\correccioncapital;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\correccioncapital\CorreccionCapital;
	use common\models\aaee\Sucursal;


	/**
	 * Clase principal
	 */
	class CorreccionCapitalForm extends CorreccionCapital
	{
		public $id_correcion;
		public $nro_solicitud;
		public $id_contribuyente;
		public $capital_v;
		public $capital_new;
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
        					'capital_v',
        					'capital_new',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'capital_v',
        					'capital_new',
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
    			[['capital_new', 'id_contribuyente',
    			  'capital_v'],
    			  'required', 'on' => 'frontend',
    			  'message' => Yii::t('backend', '{attribute} is required')],
    			 [['capital_new', 'id_contribuyente',
    			  'capital_v'],
    			  'required', 'on' => 'backend',
    			  'message' => Yii::t('backend', '{attribute} is required')],
    			//[['capital_new'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
    			[['capital_new', 'capital_v'],
    			  'double', 'message' => Yii::t('backend', '{attribute} must be decimal.')],
    			['capital_new',
    			 'compare',
    			 'compareAttribute' => 'capital_v',
    			 'operator' => '>=',
    			 'message' => Yii::t('backend', '{attribute} must be no less that ' . self::attributeLabels()['capital_v'])],
    			//['capital_new', 'format', Yii::$app->formatted->asDecimal($model->)]
    			['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
	     		['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['estatus', 'default', 'value' => 0],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
    		];
    	}




    	/**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_correccion' => Yii::t('backend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Application Number'),
	            'capital_v' => Yii::t('backend', 'Current Capital'),
	            'capital_new' => Yii::t('backend', 'New Capital'),
	            'razon_social' => Yii::t('frontend', 'Companies'),
	            'id_sim' => Yii::t('frontend', 'License'),
	            'dni' => Yii::t('frontend', 'DNI'),
	        ];
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
	    public function getDataProviderCorreccionesCapital($arrayIdCorreccion = [])
	    {
	    	if ( is_array($arrayIdCorreccion) ) {
		    	if ( count($arrayIdCorreccion) > 0 ) {
		    		$query = CorreccionCapital::find();
		    		$dataProvider = new ActiveDataProvider([
		            	'query' => $query,
		        	]);
		        	$query->where(['in', 'id_correccion', $arrayIdCorreccion]);

		        	return $dataProvider;
		    	}
		    }
		    return false;
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