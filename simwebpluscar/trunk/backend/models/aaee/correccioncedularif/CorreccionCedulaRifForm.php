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
 *  @date 29-10-2015
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


	/**
	* 	Clase base del formulario autorizar-ramo-form.
	*/
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
	        	[['naturaleza_new','cedula_new','tipo_new'],'required','when' => function($model) {
	        																if ( $model->cedula_new != null || $model->tipo_new != null || $model->naturaleza_new != null ) {
	        																	return $model->tipo_naturaleza_new == 1; }
	        																}],
	        	[['naturaleza_new','cedula_new'],'required','when' => function($model) {
	        																if ( $model->cedula_new != null || $model->naturaleza_new != null  ) {
	        																	return $model->tipo_naturaleza_new == 0; }
	        																}],
	        	['cedula_new', 'string', 'max' => 8, 'when' => function($model) { return $model->tipo_naturaleza_new == 0; }],
	          	['cedula_new', 'string', 'max' => 9, 'when' => function($model) { return $model->tipo_naturaleza_new == 1; }],
	          	[['cedula_new', 'tipo_new', 'tipo_naturaleza_new', 'id_contribuyente'], 'integer'],
	          	[['naturaleza_new', 'naturaleza_v'], 'string'],
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




	    public function validateForm($model, $requestPost)
	    {
	    	$result = false;
	    	$tipoNaturalezaLocal = '';
	    	$nombreForm = $model->formName();
	    	//die(var_dump($requestPost));
	    	if ( isset($requestPost) ) {
	    		if ( isset($requestPost[$nombreForm]) ) {
	    			$request = $requestPost[$nombreForm];
	    			$tipoNaturalezaLocal = $request['tipo_naturaleza_new'];
	    			if ( $tipoNaturalezaLocal == 0 ) {
	    				// Contribuyente Natural
	    				if ( strlen($request['naturaleza_new']) == 1  && is_numeric($request['cedula_new']) && !isset($request['tipo_new']) ) {
	    					$result = true;
	    				} else {
	    					$model->addError('naturaleza_new', Yii::t('backend', 'DNI is not valid.'));
	    				}
	    			} elseif ( $tipoNaturalezaLocal == 1 ) {
	    				// Contribuyente Juridico
	    				if ( strlen($request['naturaleza_new']) == 1  && is_numeric($request['cedula_new']) && is_numeric($request['tipo_new']) ) {
	    					$result = true;
	    				} else {
	    					$model->addError('naturaleza_new', Yii::t('backend', 'DNI is not valid.'));
	    				}
	    			}
	    		}
	    	}
	    	return $result;
	    }


	}
?>