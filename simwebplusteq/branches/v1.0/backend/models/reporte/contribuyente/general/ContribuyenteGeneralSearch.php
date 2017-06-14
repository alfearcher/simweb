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
 *  @file ContribuyenteGeneralSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-06-2017
 *
 *  @class ContribuyenteGeneralSearch
 *  @brief Clase Modelo
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

	namespace backend\models\reporte\contribuyente\general;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\helpers\ArrayHelper;



	/**
	* Clase que permite realizar la consulta a traves de los parametros indicado
	* en el formulario de consulta de los contribuyentes existentes. La busqueda
	* se realiza.
	*/
	class ContribuyenteGeneralSearch extends ContribuyenteGeneralBusquedaForm
	{



		/**
		 * Metodo que genera el modelo basico de consulta sobre la entidad "contribuyentes".
		 * @return ContribuyenteBase
		 */
		public function findContribuyenteModel()
		{
			return ContribuyenteBase::find()->alias('C');
		}




		/**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return array
	     */
	    private function getListaFuncionarioAutorizado()
	    {
	    	return [
	    		'adminteq',
	    		'kperez',
	    		'pfranco',
	    	];
	    }



	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario)
	    {
	    	$listaUsuarioAutorizado = self::getListaFuncionarioAutorizado();
	    	if ( count($listaUsuarioAutorizado) > 0 ) {
	    		foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    			if ( $value == $usuario ) {
	    				return true;
	    			}
	    		}
	    	}
	    	return false;
	    }



	    /**
	     * Metodo que arma el modelo de consulta general
	     * @return HistoricoLicencia
	     */
	    public function armarConsultaContribuyenteModel()
	    {

	    	$findModel = self::findContribuyenteModel();

	    	if ( trim($this->tipo_naturaleza) !== '' ) {
	    		if ( $this->tipo_naturaleza == 9 ) {
	    			// Todos
	    			$findModel = $findModel->where(['IN', 'C.tipo_naturaleza', [0,1]]);
	    		} else {
	    			$findModel = $findModel->where(['=', 'C.tipo_naturaleza', $this->tipo_naturaleza]);
	    		}

	    	}

	    	if ( trim($this->condicion) !== '' ) {
	    		if ( $this->condicion == 9 ) {
	    			// Todos
	    			$findModel = $findModel->andWhere(['IN', 'C.inactivo', [0,1]]);
	    		} else {
	    			$findModel = $findModel->andWhere(['=', 'C.inactivo', $this->condicion]);
	    		}
	    	}

	    	// Parametros adicionales
	    	if ( trim($this->sin_licencia) !== '' ) {
	    		if ( $this->sin_licencia > 0 ) {
	    			$findModel = $findModel->andWhere(['<=', 'LENGTH(TRIM(C.id_sim))', 1]);
	    		}
	    	}

			if ( trim($this->sin_email) !== '' ) {
	    		if ( $this->sin_email > 0 ) {
	    			$findModel = $findModel->andWhere(['<=', 'LENGTH(TRIM(C.email))', 1]);
	    		}
	    	}

	    	return $findModel;
	    }



	    /**
	     * Metodo que ejecuta la consulta sobre el modelo de consulta creado.
	     * @return array
	     */
	    public function findDataContribuyente()
	    {
	    	$findModel = self::armarConsultaContribuyenteModel()->orderBy(['C.id_contribuyente' => SORT_ASC]);
	    	return $registers = $findModel->asArray()->all();
	    }



	    /**
	     * Metodo que crea el data provider de los contribuyentes
	     * @return ActiveDataProvider
	     */
	    public function getDataProvider()
	    {
	    	$query = self::armarConsultaContribuyenteModel();
	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    		'pagination' => [
	    			'pageSize' => 50,
	    		],
	    	]);
	    	$query->all();
	    	return $dataProvider;
 	    }



 	    /**
 	     * Metodo que retorna una lista de los tipos de naturaleza.
 	     * @return array
 	     */
 	    public function findTipoNaturaleza()
 	    {
 	    	return [
 	    		0 => 'NATURAL',
 	    		1 => 'JURIDICO',
 	    		9 => 'TODOS'
 	    	];
 	    }



 	    /**
 	     * Metodo que retorna una lista de las condiciones del contribuyente.
 	     * Esto se usara en el formulario de consulta de contribuyente. la condicion
 	     * numero 9 es para considerar a todos los registros.
 	     * @return array
 	     */
 	    public function findCondicionContribuyente()
 	    {
 	    	return [
 	    		0 => 'ACTIVO',
 	    		1 => 'INACTIVO',
 	    		9 => 'TODOS'
 	    	];
 	    }



 	    /**
 	     * Metodo getter del tipo de naturaleza
 	     * @return array
 	     */
 	    public function getListaTipoNaturaleza()
 	    {
 	    	return self::findTipoNaturaleza();
 	    }



 	    /**
 	     * Metodo getter de las condiciones de los contribuyentes.
 	     * @return array
 	     */
 	    public function getListaCondicionContribuyente()
 	    {
 	    	return self::findCondicionContribuyente();
 	    }

	}

?>