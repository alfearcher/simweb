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
 *  @file LicenciaEmitidaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class LicenciaEmitidaSearch
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

	namespace backend\models\reporte\aaee\licencia;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use backend\models\reporte\aaee\licencia\LicenciaEmitidaBusquedaForm;
	use backend\models\aaee\historico\licencia\HistoricoLicencia;
	use yii\helpers\ArrayHelper;




	/**
	* Clase que permite realizar la consulta a traves de los parametros indicado
	* en el formulario de consulta de las licencias emitidas. La busqueda se realiza
	* contra el historico de licencia, pues es alli donde se guardan las licencias
	* aprobadas o sea emitidas.
	*/
	class LicenciaEmitidaSearch extends LicenciaEmitidaBusquedaForm
	{

		/**
		 * Metodo que genera el modelo de consulta basico.
		 * @return HistoricoLicencia
		 */
		public function findHistoricoLicencia()
		{
			return HistoricoLicencia::find()->alias('H');
		}



		/**
		 * Metodo que realiza la consulta sobre la entidad respectiva a tarves del identificador
		 * de la entidad. En este caso el id-historico.
		 * @param integer $idHistorico identificador del registro o entidad.
		 * @return array, retorna los datos del historico d ela licencia.
		 */
		public function findHistoricoLicenciaById($idHistorico)
		{
			return $registers = self::findHistoricoLicencia()->where('id_historico =:id_historico',
			 															[':id_historico' => $idHistorico])
												      		 ->one();
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
	     * Metodo que genera un arreglo con los usuario existentes en el historico
	     * de licencias generadas.
	     * @return array
	     */
	    public function getListaUsuarioRelacionadoHistorico()
	    {
	    	$findModel = self::findHistoricoLicencia();
	    	$registers = $findModel->select('usuario')
	    					       ->distinct('usuario')
	    					       ->orderBy([
	    						   		'usuario' => SORT_ASC,
	    							])
	    					  	   ->asArray()
	    					       ->all();
	    	return $registers;
	    }




	    /**
	     * Metodo que retorna un arreglo con los usurios determinados en la consulta
	     * y que estan relacionado al historico de la generacion de las licencias.
	     * @return array
	     */
	    public function getListaUsuarioLicencia()
	    {
	    	$registers = self::getListaUsuarioRelacionadoHistorico();
	    	return ArrayHelper::map($registers, 'usuario', 'usuario');
	    }




	    /**
	     * Metodo que arma el modelo de consulta para el historico de licencias
	     * @return HistoricoLicencia
	     */
	    public function armarConsultaHistoricoLicenciaModel()
	    {

	    	$findModel = self::findHistoricoLicencia();
	    	$this->validarRango($this->fecha_desde, $this->fecha_hasta);
	    	if ( $this->getRangoValido() ) {
	    		$findModel = self::findHistoricoLicencia()->where(['BETWEEN',
	    												       'date(H.fecha_hora)',
	    												        date('Y-m-d', strtotime($this->fecha_desde)), date('Y-m-d', strtotime($this->fecha_hasta))]);

	    	} elseif ( trim($this->id_contribuyente) !== '' ) {
	    		$findModel = $findModel->where(['=', 'H.id_contribuyente', $this->id_contribuyente]);

	    	} elseif ( trim($this->licencia) !== '' ) {
	    		$findModel = $findModel->where(['=', 'H.licencia', $this->licencia]);
	    	}

	    	if ( trim($this->tipo_licencia) !== '' ) {
	    		$findModel = $findModel->andWhere(['LIKE', 'H.tipo', $this->tipo_licencia]);
	    	}
	    	if ( trim($this->usuario) !== '' ) {
	    		$findModel = $findModel->andWhere(['=', 'H.usuario', $this->usuario]);
	    	}
	    	return $findModel;
	    }



	    /**
	     * Metodo que ejecuta la consulta sobre el modelo de consulta creado.
	     * @return array
	     */
	    public function findDataHistoricoLicencia()
	    {
	    	$findModel = self::armarConsultaHistoricoLicenciaModel();
	    	return $registers = $findModel->asArray()->all();
	    }



	    /**
	     * Metodo que crea el data provider del historico de licencia
	     * @return ActiveDataProvider
	     */
	    public function getDataProvider()
	    {
	    	$query = self::armarConsultaHistoricoLicenciaModel();
	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    		'pagination' => [
	    			'pageSize' => 50,
	    		],
	    	]);
	    	$query->all();
	    	return $dataProvider;
 	    }

	}

?>