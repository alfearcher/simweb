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
 *  @file SolicitudDocumentoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-05-2016
 *
 *  @class SolicitudDocumentoSearch
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

	namespace backend\models\configuracion\documentosolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumento;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use backend\models\utilidad\documento\DocumentoRequisito;

	/**
	* 	Clase que permite
	*/
	class SolicitudDocumentoSearch extends SolicitudDocumento
	{

		private $id_config_solicitud;
		private $nro_solicitud;



		/**
		 * Metodo constructor de la clase.
		 * @param Long $nroSolicitud identificador de la solicitud creadapor el contribuyente o funcionario.
		 */
		public function __construct($nroSolicitud)
		{
			$this->nro_solicitud = $nroSolicitud;
			self::setIdConfigSolicitud();
		}



		/**
		 * [setIdConfigSolicitud description]
		 */
		private function setIdConfigSolicitud()
		{
			$result = null;
			$this->id_config_solicitud = 0;
			if ( $this->nro_solicitud > 0 ) {
				$solicitud = New SolicitudesContribuyenteForm();
				$result = $solicitud->getParametroSolicitudContribuyente($this->nro_solicitud, ['id_config_solicitud']);
				if ( count($result) > 0 ) {
					$this->id_config_solicitud = $result['id_config_solicitud'];
				}
			}
		}



		/**
		 * [findDocumentoSolicitud description]
		 * @return [type] [description]
		 */
		private function findDocumentoSolicitud()
		{
			$modelFind = DocumentoRequisito::find()->where(DocumentoRequisito::tableName().'.inactivo =:inactivo', [':inactivo' => 0])
												   ->andWhere('id_config_solicitud =:id_config_solicitud', [
												   										':id_config_solicitud' => $this->id_config_solicitud
												   										])
												   ->andWhere(SolicitudDocumento::tableName().'.inactivo =:inactivo', [':inactivo' => 0])
												   ->joinWith('solicitudDocumento', false)
												   ->orderBy([
												   		'id_documento' => SORT_ASC,
												   	]);

			return isset($modelFind) ? $modelFind : null;
		}



		/***/
		public function getListaDocumentoSegunSolicitud()
		{
			$model = self::findDocumentoSolicitud();
			return isset($model) ? $model->all() : null;
		}



		/***/
		public function getDataProvider()
		{
			$query = self::findDocumentoSolicitud();

			$dataProvider = new ActiveDataProvider([
	            'query' => $query,
	        ]);

	        return $dataProvider;
		}


	}
?>