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
 *  @file CorreccionRazonSocialForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-11-2015
 *
 *  @class CorreccionRazonSocialForm
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

 	namespace backend\models\aaee\correccionrazonsocial;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocial;
	use common\models\aaee\Sucursal;


	class CorreccionRazonSocialForm extends CorreccionRazonSocial
	{
		public $id_correcion;
		public $nro_solicitud;
		public $id_contribuyente;
		public $razon_social_v;
		public $razon_social_new;
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
        					'razon_social_v',
        					'razon_social_new',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'razon_social_v',
        					'razon_social_new',
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
    			[['razon_social_new', 'id_contribuyente'],
    			  'required', 'on' => 'frontend',
    			  'message' => Yii::t('backend', '{attribute} es requerida')],
    			['razon_social_new', 'filter', 'filter' => 'strtoupper'],
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
	            'nro_solicitud' => Yii::t('backend', 'Request'),
	            'razon_social_v' => Yii::t('backend', 'Antique Company Name'),
	            'razon_social_new' => Yii::t('backend', 'New Company Name'),
	            'dni' => Yii::t('backend', 'DNI'),
	            'id_sim' => Yii::t('backend', 'License'),
	            'domicilio_fiscal' => Yii::t('backend', 'Addrres Office'),
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
	    public function getDataProviderCorreccionesRazonSocial($arrayIdCorreccion = [])
	    {
	    	if ( is_array($arrayIdCorreccion) ) {
		    	if ( count($arrayIdCorreccion) > 0 ) {
		    		$query = CorreccionRazonSocial::find();
		    		$dataProvider = new ActiveDataProvider([
		            	'query' => $query,
		        	]);
		        	$query->where(['in', 'id_correccion', $arrayIdCorreccion]);

		        	return $dataProvider;
		    	}
		    }
		    return false;
	    }

	}
?>