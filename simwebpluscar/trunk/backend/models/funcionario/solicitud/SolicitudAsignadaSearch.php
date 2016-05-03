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
 *  @file SolicitudAsignadaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-04-2016
 *
 *  @class SolicitudAsignadaSearch
 *  @brief Clase Modelo del formulario de creacion de funcionarios, mantiene las reglas de validacion
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *
 *
 *  @inherits
 *
 */


	namespace backend\models\funcionario\solicitud;

	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\funcionario\Funcionario;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use backend\models\configuracion\tiposolicitud\TipoSolicitudSearch;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\ImpuestoForm;
	use yii\data\ActiveDataProvider;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;


	/**
	 *	Clase principal del formulario.
	 */
	class SolicitudAsignadaSearch extends SolicitudAsignadaForm
	{


	    /**
	     * Metodo que permite obetene los identificadores de los tipos de solictudes
	     * asignadas a un funcionario. El paramatro de busqueda utilizado es el login
	     * (nombre de usuario) del funcionario.
	     * @param  String $userLocal usuario del funcionoario, establecido en la entidad
	     * principal "funcionarios".
	     * @return Array Retorna un arreglo de identificadores del tipo de solicitud
	     * asignada al funcionario. Esta relacion se determina en la entidad "funcionarios-solicitudes".
	     */
	    public function findTipoSolicitudAsignadaFuncionario($userLocal)
	    {
	    	$modelFind = null;
	    	$modelFind = FuncionarioSolicitud::find()->where('inactivo =:inactivo', [':inactivo' => 0])
	                                                 ->andWhere('login =:login', [':login' => $userLocal])
	                                                 ->joinWith('funcionario', false)
			                                         ->orderBy([
			                                     		'tipo_solicitud' => SORT_ASC,
			                                    	]);

	    	return isset($modelFind) ? $modelFind : null;
	    }




	    /**
	     * Metodo que permite obtener un listado de los identificadores de los tipos
	     * de solicitudes que tiene asignado un funcionario.
	     * @param  String $userLocal nombre del usuario del funcionario logueado.
	     * @return Array Retorna un arreglo con los valores de los identificadores de las solicitudes
	     * asignadas al funcionario existentes en la entidad "funcionarios-solicitudes".
	     */
	    public function getTipoSolicitudAsignada($userLocal)
	    {
	    	$lista = null;
	    	$model = $this->findTipoSolicitudAsignadaFuncionario($userLocal);
	    	$listaSolicitudeAsignadas = $model->asArray()->all();
	    	foreach ( $listaSolicitudeAsignadas as $solicitud ) {
	    		$lista[] = $solicitud['tipo_solicitud'];
	    	}

	    	return $lista;
	    }




	    /**
	     * Metodo que permite obtener un listado de las solicitudes generadas por los contribuyentes
	     * y que coincidan con elo los identificadores de los tipos de solicitudes asignadas a un
	     * funcionario. Esto con el fin de que el funcionario tenga acceso a solo las solicitudes
	     * asignadas previamente, para que pueda procesarlas. Evitando que funcionarios procesen
	     * con sus cuentas, solicitudes no autorizadas.
	     * @param  Array $tipoSolicitud identificador del tipo de solicitud asignada.
	     * @return [type]                [description]
	     */
	    public function findSolicitudContribuyenteEmitida($tipoSolicitud)
	    {
	    	$modelFind = SolicitudesContribuyente::find()->where('estatus =:estatus', [':estatus' => 0])
	    	                                             ->andWhere(SolicitudesContribuyente::tableName().'.inactivo =:inactivo', [':inactivo' => 0])
	    	                                             ->andWhere(TipoSolicitud::tableName().'.inactivo =:inactivo', [':inactivo' => 0])
	    	                                            // ->andWhere(['tipo_solicitud' => $tipoSolicitud])
	    	                                             ->joinWith('tipoSolicitud', 'impuestos')
	    	                                             ->orderBy([
	    	                                             		'nro_solicitud' => SORT_ASC,
	    	                                             	]);
	    	                                             //->limit(10);

	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /**
	     * [getDataProviderSolicitudContribuyente description]
	     * @param  Array $tipoSolicitud arreglo de identificadores del tipo de solicitud.
	     * @return Data Provider.
	     */
	    public function getDataProviderSolicitudContribuyente($tipoSolicitud)
	    {
	    	$query = $this->findSolicitudContribuyenteEmitida($tipoSolicitud);

	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,

	    	]);

	    	if ( count($tipoSolicitud) == 0 ) {
	    		$query->where('0=1');
	    		return $dataProvider;
	    	}
	    	$query->andFilterWhere(['IN', 'tipo_solicitud', $tipoSolicitud]);
	    	if ( $this->tipo_solicitud > 0 ) {
		   		$query->andFilterWhere(['=', 'tipo_solicitud', $this->tipo_solicitud]);
		   	} else {
		   		//$query->andFilterWhere(['IN', 'tipo_solicitud', $tipoSolicitud]);
		   	}
	    	if ( $this->fecha_desde != null && $this->fecha_hasta != null ) {
		    	$query->andFilterWhere(['BETWEEN','fecha_hora_creacion',
		    	      					 date('Y-m-d',strtotime($this->fecha_desde)),
		    	      					 date('Y-m-d',strtotime($this->fecha_hasta))]);
		   	}
		   	if ( $this->impuesto > 0 ) {
		   		$query->andFilterWhere(['=', SolicitudesContribuyente::tableName().'.impuesto', $this->impuesto]);
		   	}
	    	return $dataProvider;
	    }

	}
?>