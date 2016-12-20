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
 *  @file DocumentoConsignadoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-07-2016
 *
 *  @class DocumentoConsinadoSearch
 *  @brief Clase Modelo que se utiliza para las consultas sobre la entidad.
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

	namespace backend\models\documento;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\documento\DocumentoConsignado;
	use backend\models\utilidad\documento\DocumentoRequisito;

	/**
	* 	Clase base del formulario
	*/
	class DocumentoConsignadoSearch
	{
		public $id_contribuyente;
		public $id_impuesto;
		public $impuesto;
		private $_nro_solicitud;



		/**
		 * Metodo constructor de la clase.
		 * @param long $nroSolicitud identificador del registro, numero de la solicitud
		 * creada.
		 */
		public function __construct($nroSolicitud)
		{
			$this->_nro_solicitud = $nroSolicitud;
		}



		/***/
		public function findDocumentoConsignado()
		{
			$findModel = DocumentoRequisito::find()->where('nro_solicitud =:nro_solicitud',
																		 [':nro_solicitud' => $this->_nro_solicitud])
													->andWhere('estatus =:estatus', [':estatus' => 0])
													->joinWith('documentoConsignado', false)
													->orderBy([
															'descripcion' => SORT_ASC,
														]);
			return isset($findModel) ? $findModel : null;
		}



		/***/
		public function getDataProviderDocumentoConsignado()
		{
			$query = self::findDocumentoConsignado();

			$query->all();

			$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    	]);

	    	return $dataProvider;
		}

	}
?>