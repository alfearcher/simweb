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
				$result = $solicitud->getParametroSolicitudContribuynete();
				if ( count($result) > 0 ) {
					$this->id_config_solicitud = $result['id_config_solicitud'];
				}
			}
		}




		public function getListaDocumentoSegunSolicitud()
		{

		}


	}
?>