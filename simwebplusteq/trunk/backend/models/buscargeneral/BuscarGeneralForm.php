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
 *  @file BuscarGeneralForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-08-2015
 *
 *  @class BuscarGeneralForm
 *  @brief Clase Modelo del formulario para la busqueda general del contribuyente
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

	namespace backend\models\buscargeneral;

 	use Yii;
	use yii\base\Model;
	use common\conexion\ConexionController;
	use yii\data\ActiveDataProvider;
	use backend\models\buscargeneral\BuscarGeneral;
	use common\models\contribuyente\ContribuyenteBase;

	/**
	* 	Clase base del formulario para la busqueda general del contribuyente.
	*/
	class BuscarGeneralForm extends BuscarGeneral
	{
		public $ente;						// Identificador de la Entidad de Recaudacion Local.
		public $id_contribuyente;			// Identificador del contribuyente en base de datos.
		public $naturaleza;					//	E, V, J, G, etc.
		public $cedula;
		public $tipo;						// Ultimo numero del rif del contribuyente juridico.
		public $apellidos;
		public $nombres;
		public $razon_social;
		public $tipo_naturaleza;			// 0 => Natural  1 => Juridico
		public $inactivo;					// Indicador de la condicion del registros, 0 => Activo 1 => Inactivo
		public $fecha_inicio;				// Fecha de inicio de actividades de los contribuyentes que declaran (Juridico).
		public $agente_retencion;			// Indicador que especifica si el contribuyente retiene impuestos a otros contribuyentes.
		public $domicilio_fiscal;
		public $id_sim;						// Numero de licencia otorgada por la entidad local al contribuyente, la misma corresponde a un 									//	permiso para explotar ciertas actividades comerciales.


		public $dataProviderLocal;

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario buscar-general-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['naturaleza','cedula','tipo'],'required','when' => function($model) {
	        																if ( strlen($model->cedula) > 0 || strlen($model->tipo) > 0 ) {
	        																	return $model->tipo_naturaleza == 1; }
	        																}],
	        	[['naturaleza','cedula'],'required','when' => function($model) {
	        																if ( $model->cedula !== null || $model->naturaleza !== null  ) {
	        																	return $model->tipo_naturaleza == 0; }
	        																}],
	            [['cedula', 'tipo', 'id_contribuyente', 'tipo_naturaleza'], 'integer'],
	            [['apellidos', 'nombres', 'razon_social', 'naturaleza', 'id_sim'], 'string'],
	          	['ente', 'default', 'value' => Yii::$app->ente->getEnte()],
	          	['naturaleza','verificarNaturalezaContribuyente'],
	          	['cedula', 'string', 'max' => 8, 'when' => function($model) { return $model->tipo_naturaleza == 0; }],
	          	['cedula', 'string', 'max' => 9, 'when' => function($model) { return $model->tipo_naturaleza == 1; }],
	        ];
	    }





	    /**
	    *	Metodo que verifica que un contribuyente con tipo naturaleza: natural, solo contenga como primera letra
	    * 	en su cedula de identidad, las letras "V" o "E".
	    */
	    public function verificarNaturalezaContribuyente($attribute, $params)
	    {
	    	$listaNaturaleza = ['J', 'G'];
	    	if ( $this->tipo_naturaleza == 0 ) {	// Aqui se refiere a Contribuyente Natural
	    		if ( in_array($this->naturaleza, $listaNaturaleza) ) {
	    			$this->addError($attribute, Yii::t('backend','Select no valid'));
	    		}
	    	} elseif ( $this->tipo_naturaleza == 1 ) {	// Aqui se refiere a Contribuyente Juridico
	    		if ( $this->tipo == null and  $this->cedula == null ) {
	    			$this->addError($attribute, Yii::t('backend','Select no valid'));
	    		}
	    	}
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_contribuyente' => Yii::t('backend', 'Id Taxpayer'),
	            'ente' => Yii::t('backend', 'Entity'),
	            'cedula' => Yii::t('backend', 'Identity Number'),
	            'tipo' => Yii::t('backend', 'Type'),
	            'apellidos' => Yii::t('backend', 'Last Name'),
	            'nombres' => Yii::t('backend', 'Name'),
	            'tipo_naturaleza' => Yii::t('backend', 'Natural Type'),
	            'fecha_inicio' => Yii::t('backend', 'Date of Admission'),
	            'razon_social' => Yii::t('backend', 'Business Name'),
	            'domicilio_fiscal' => Yii::t('backend', 'Offices'),
	            'id_sim' => Yii::t('backend', 'License Number'),
	            'inactivo' => Yii::t('backend', 'Condition'),

	        ];
	    }




	    /**
	    *	Metodo que determina si la busqueda realizada por el usuario es por el ID del Contribuyente
	    * 	@param arrayParametros, variable de tipo arreglo. Contiene el post enviado por el formulario
	    * 	de busqueda general.
	    * 	@return true o false
	    */
	    public function BuscarContribuyente($arrayParametros = '')
	    {

			// 	Determino si el parametro enviado es un arreglo.
	    	// 	El array es multidimensional, donde el primer parametro debe ser el nombre del formulario.
	    	if ( is_array($arrayParametros) ) {
	    		if ( !isset($arrayParametros['page']) ) {

		    		//$query = BuscarGeneral::find()->where(['id_contribuyente' => 40]);
		    		$query = BuscarGeneral::find();
		    		$dataProvider = new ActiveDataProvider([
	            		'query' => $query,
	        		]);

		    		if ( $this->id_contribuyente > 0 ) {

		    			// Buscando por Id Contribuyente.
						$query->andFilterWhere([
	            			'id_contribuyente' => $this->id_contribuyente,
	        			]);

		    		} elseif ( $this->tipo_naturaleza == 0 ) {

			    		/**
			    		 *	Buscando por contribuyente Natural
			    		 * 	Aqui solo se puede buscar por:
			    		 *  naturaleza-cedula.
			    		 *  apellidos.
			    		 *  nombres.
			    		 *  apellidos-nombres.
			    		 */

			    		if ( trim($this->naturaleza) != '' and is_numeric($this->cedula) ) {

			    			$query->where('naturaleza = :naturaleza and tipo = 0 and tipo_naturaleza = 0', [':naturaleza' => $this->naturaleza])
			    				  ->andWhere('cedula = :cedula', [':cedula' => $this->cedula]);

			    		} else {
			    			$query->andFilterWhere(['like', 'apellidos', $this->apellidos])
	            			  	  ->andFilterWhere(['like', 'nombres', $this->nombres]);
			    		}

		    		} elseif ( $this->tipo_naturaleza == 1 ) {

			    		/**
			    		 *	Buscando por contribuyente Juridico
			    		 * 	Aqui solo se puede buscar por:
			    		 *  naturaleza-cedula-tipo (rif).
			    		 *  razon social.
			    		 *  numero de licencia (id_sim).
			    		 */
			    		if ( trim($this->naturaleza) != '' and is_numeric($this->cedula) and is_numeric($this->tipo) ) {
			    			$query->where('naturaleza = :naturaleza and tipo_naturaleza = 1', [':naturaleza' => $this->naturaleza])
			    				  ->andWhere('cedula = :cedula', [':cedula' => $this->cedula])
			    				  ->andWhere('tipo = :tipo', [':tipo' => $this->tipo]);
			    		} else {
			    			$query->andFilterWhere(['like', 'razon_social', $this->razon_social])
	            			  	  ->andFilterWhere(['like', 'id_sim', $this->id_sim]);
			    		}


			    	}
		    		return $dataProvider;
		    	} else {

		    		return $dataProvider;
		    	}
	    	} else {
	    		return false;
	    	}
	    }



	    /**
	     * [getCedulaRif description]
	     * @return [type] [description]
	     */
	    public function getCedulaRif($tipoNaturaleza, $naturaleza, $cedula, $tipo)
		{
			return ContribuyenteBase::getCedulaRifDescripcion($tipoNaturaleza, $naturaleza, $cedula, $tipo);
		}



		/**
		 * [getActivoInactivo description]
		 * @param  [type] $condicion [description]
		 * @return [type]            [description]
		 */
		public function getActivoInactivo($condicion)
		{
			return ContribuyenteBase::getActivoInctivoDescripcion($condicion);
		}



		/**
		 * [getTipoNaturaleza description]
		 * @param  [type]  $tipoNaturaleza  [description]
		 * @param  integer $idContribuyente [description]
		 * @return [type]                   [description]
		 */
		public function getTipoNaturaleza($tipoNaturaleza, $idContribuyente = 0)
		{
			if ( $idContribuyente > 0 ) {
				return ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($idContribuyente);
			} else {
				return ContribuyenteBase::getTipoNaturalezaDescripcion($tipoNaturaleza);
			}
		}



		/**
		 * [getDescripcionContribuyente description]
		 * @param  [type]  $tipoNaturaleza      [description]
		 * @param  string  $razonSocial         [description]
		 * @param  string  $apellidos           [description]
		 * @param  string  $nombres             [description]
		 * @param  integer $ordenApellidoNombre [description]
		 * @return [type]                       [description]
		 */
		public function getDescripcionContribuyente($tipoNaturaleza, $razonSocial = '', $apellidos = '', $nombres = '', $ordenApellidoNombre = 0)
		{
			return ContribuyenteBase::getContribuyenteDescripcion($tipoNaturaleza, $razonSocial, $apellidos, $nombres, $ordenApellidoNombre);
		}




		/**
		 * [getDescripcionContribuyenteSegunID description]
		 * @param  integer $idContribuyente [description]
		 * @return [type]                   [description]
		 */
		public function getDescripcionContribuyenteSegunID($idContribuyente = 0)
		{
			if ( $idContribuyente > 0 ) {
				return ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
			}
			return "";
		}

	}

?>