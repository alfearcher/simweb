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
 *  @file HistoricoSolicitudSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class HistoricoSolicitudSearch
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

	namespace backend\models\reporte\solicitud\historico;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use backend\models\reporte\solicitud\historico\HistoricoSolicitudBusquedaForm;
	use yii\helpers\ArrayHelper;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use common\models\solicitudescontribuyente\DetalleSolicitudCreada;
	use common\utilidades\CrearComboTipoSolicitud;



	/**
	* Clase que permite gestinar el modelo de consultas basicas sobre la busqueda
	* del historico de solicitudes, asi como el modelo de la cansulta para obtener
	* el listado del combo de impuesto y del tipo de solicitud.
	*/
	class HistoricoSolicitudSearch extends HistoricoSolicitudBusquedaForm
	{


		/**
		 * Metodo que permite obtener la descripcion del tipo de naturaleza del contribuyente
		 * segun el identificador del mismo.
		 * @param integer $idContribuyente identificador de la entidad.
		 * @return string
		 */
		public function getTipoNatutaleza($idContribuyente)
		{
			$contribuyente = New ContribuyenteBase();
			return $contribuyente->getTipoNaturalezaDescripcionSegunID($idContribuyente);
		}



		/**
		 * Metodo que retorna el modelo basico de consulta de la entidad respectiva.
		 * @return SolicitudesContribuyente.
		 */
		public function findSolicitudContribuyenteModel()
		{
			return SolicitudesContribuyente::find()->alias('S');
		}



		/**
		 * Metodo que realiza la consulta de una solicitud a traves del identificador
		 * de la entidad (nro-solicitud).
		 * @param integer $nroSolicitud identificador de la entidad, valor unico.
		 * @return array. Modelo SolicitudesContribuyente.
		 */
		public function findSolicitudById($nroSolicitud)
		{
			return self::findSolicitudContribuyenteModel()->where('nro_solicitud =:nro_solicitud',
				 														[':nro_solicitud' => $nroSolicitud])
			   											  ->joinWith('tipoSolicitud T', true, 'INNER JOIN')
			   											  ->joinWith('impuestos I', true, 'INNER JOIN')
			   											  ->joinWith('estatusSolicitud E', true, 'INNER JOIN')
			   											  ->joinWith('nivelAprobacion N', true, 'INNER JOIN')
			   											  ->all();
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
	     * Metodo que permite obtener un arreglo con los atributos de la entidad
	     * "impuestos". Se obtienen todos los registros.
	     * @return array.
	     */
	    private function listaImpuesto()
	    {
	    	$impuesto = New ImpuestoForm();
	    	return $model = $impuesto->findImpuesto();
	    }



	    /**
	     * Metodo que permite obtener la lista de impuestos que seran utilizados
	     * para la consulta de los solicitudes de los contribuyentes de tipo
	     * naturaleza "NATURAL" (No juridicos).
	     * @return array
	     */
	    public function getListaImpuestoContribuyenteNatural()
	    {
	    	$lista = [];
	    	$model = self::listaImpuesto();
	    	foreach ( $model as $imp ) {
	    		// Se filtra el impuesto de actividad economica para aquellos contribuyentes
	    		// de naturaleza igual a "NATURAL".
	    		if ( (int)$imp['impuesto'] !== 1 ) {
	    			$lista[] = $imp;
	    		}
	    	}
	    	return ArrayHelper::map($lista, 'impuesto', 'descripcion');
	    }


	    /**
	     * Metodo para obtener una lista de los impuestos que aplica para los contribuyentes
	     * de tipo naturaleza igual a juridico.
	     * @return array
	     */
	    public function getListaImpuestoContribuyenteJuridico()
	    {
	    	$model = self::listaImpuesto();
	    	return ArrayHelper::map($model, 'impuesto', 'descripcion');
	    }



	    /**
	     * Metodo que arma un combo-lista de tipos de solicitudes.
	     * @param integer $impuesto identificador del impuesto.
	     * @return array
	     */
	    public function armarComboTipoSolicitud($impuesto)
	    {
	    	return CrearComboTipoSolicitud::getComboTipoSolicitud($impuesto);
	    }



	    /**
	     * Metodo que permite obtener una lista de los estatus de las solicitudes.
	     * @return array
	     */
	    public function getListaEstatusSolicitud()
	    {
	    	$model = EstatusSolicitud::find()->all();
	    	return ArrayHelper::map($model, 'estatus_solicitud', 'descripcion');
	    }




	    /**
	     * Metodo que arma el modelo de consulta para el historico de licencias
	     * @return HistoricoLicencia
	     */
	    public function armarConsultaHistoricoSolicitudModel()
	    {

	    	$findModel = self::findSolicitudContribuyenteModel();
	    	$this->validarRango($this->fecha_desde, $this->fecha_hasta);
	    	if ( $this->getRangoValido() && trim($this->impuesto) !== '' ) {
	    		$findModel = $findModel->where(['BETWEEN',
	    											'date(S.fecha_hora_creacion)',
	    											date('Y-m-d', strtotime($this->fecha_desde)), date('Y-m-d', strtotime($this->fecha_hasta))])
	    							   ->andWhere(['=', 'impuesto', $this->impuesto]);

	    	} elseif ( trim($this->id_contribuyente) !== '' ) {
	    		$findModel = $findModel->where(['=', 'S.id_contribuyente', $this->id_contribuyente]);

	    	} elseif ( trim($this->nro_solicitud) !== '' ) {
	    		$findModel = $findModel->where(['=', 'S.nro_solicitud', $this->nro_solicitud]);
	    	}

	    	if ( trim($this->tipo_solicitud) !== '' ) {
	    		if ( $this->tipo_solicitud > 0 ) {
	    			$findModel = $findModel->andWhere(['=', 'tipo_solicitud', $this->tipo_solicitud]);
	    		}
	    	}

	    	if ( trim($this->estatus) !== '' ) {
	    		$findModel = $findModel->andWhere(['=', 'S.estatus', $this->estatus]);
	    	}

	    	return $findModel;
	    }



	    /**
	     * Metodo que genera el data provider del historico de las solicitudes
	     * @param boolean $export indica si el resultado se utilizara para
	     * mostrarlo en un reporte tipo excel o pdf.
	     * @return ActiveDataProvider
	     */
	    public function getDataProvider($export = false)
	    {
	    	$query = self::armarConsultaHistoricoSolicitudModel();
	    	if ( $export ) {
	    		$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => false,
		    	]);
	    	} else {
		    	$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => [
		    			'pageSize' => '50',
		    		],
		    	]);
		    }
	    	$query->all();
	    	return $dataProvider;
	    }




	    /**
	     * Metodo que genera el data provider del historico de las solicitudes. Este metodo
	     * se utilizara para la consulta del contribuyente (frontend).
	     * @param integer $idContribuyente identificador del contribuyente.
	     * @param boolean $export indica si el resultado se utilizara para
	     * mostrarlo en un reporte tipo excel o pdf
	     * @return ActiveDataProvider
	     */
	    public function getDataProviderFrontend($idContribuyente, $export = false)
	    {

    		$query = self::armarConsultaHistoricoSolicitudModel();
    		$query = $query->andWhere(['=', 'S.id_contribuyente', $idContribuyente]);

    		if ( $export ) {
	    		$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => false,
		    	]);
	    	} else {
		    	$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => [
		    			'pageSize' => '50',
		    		],
		    	]);
		    }
	    	$query->all();

	    	return $dataProvider;
	    }




	    /**
	     * Metodo que retorna la vista del detalle de la solicitud.
	     * @param integer $nroSolicitud identificador de la solicitud.
	     * @return view.
	     */
	    public function getViewDetalleSolicitud($nroSolicitud)
	    {
	    	$detalle = New DetalleSolicitudCreada($nroSolicitud);
			return $viewDetalle = $detalle->getDatosSolicitudCreada();
	    }



	    /**
	     * Metodo que permite obtener el registro maestro de la solicitud, a traves del
	     * numero de la solicitud (identificador de la solicitus).
	     * @param integer $nroSolicitud identificador de la solicitud.
	     * @return array|null. Retorna un arreglo del tipo campo => valor si encuentra
	     * la solicitud, de no encontrar nada retorna NULL.
	     */
	    public function findDataSolicitudMaestro($nroSolicitud)
	    {
	    	$findModel = self::findSolicitudContribuyenteModel();
	    	return $findModel->where('nro_solicitud =:nro_solicitud',
	    									[':nro_solicitud' => $nroSolicitud])
	    	     			 ->one();
	    }



	    /**
	     * Metodo que retorna la vista con la informacion maestro de la solicitud-
	     * @param integer $nroSolicitud identificador de la solicitud.
	     * @return view.
	     */
	    public function getViewMaestroSolicitud($nroSolicitud)
	    {
	    	$model = self::findDataSolicitudMaestro($nroSolicitud);
	    	if ( $model !== null ) {
	    		return $this->render('@backend/views/solicitud/busqueda/view-maestro-solicitud', [
	    															'model' => $model,
	    			]);
	    	}
			return null;
	    }


	}
?>