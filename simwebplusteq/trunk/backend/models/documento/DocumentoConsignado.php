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
 *  @file DocumentoConsignado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-06-2016
 *
 *  @class DocumentoConsignado
 *  @brief Clase
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
	use yii\db\ActiveRecord;
	use backend\models\utilidad\documento\DocumentoRequisito;


	/**
	 * Clase maestra de los documentos y requisitos consignados por el contribuyente al momento
	 * de aprobar la solicitd.
	 */
	class DocumentoConsignado extends ActiveRecord
	{

		/**
		 *	Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
		 * 	Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
		 * 	@return Nombre de la base de datos
		 */
		public static function getDb()
		{
			return Yii::$app->db;
		}


		/**
		 * 	Metodo que retorna el nombre de la tabla que utiliza el modelo.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'documentos_consignados';
		}


		/**
		 * Relacion con la entidad "config_documentos_requisitos".
		 * @return Active Record.
		 */
		public function getDocumentoRequisito()
		{
			return $this->hasMany(DocumentoRequisito::className(), ['id_documento' => 'id_documento']);
		}

	}

?>