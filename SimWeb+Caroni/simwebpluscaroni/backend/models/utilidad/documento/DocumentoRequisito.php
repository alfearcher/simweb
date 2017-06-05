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
 *  @file DocumentoRequisito.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-09-2015
 *
 *  @class DocumentoRequisito
 *  @brief Clase Modelo que maneja la politica de validaciones del formulario que se
 * 	@brief utiliza la
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

	namespace backend\models\utilidad\documento;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\conexion\ConexionController;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumento;
	use backend\models\documento\DocumentoConsignado;
	/**
	* 	Clase
	*/
	class DocumentoRequisito extends ActiveRecord
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
			//return 'documentos_requisitos';
			return 'config_documentos_requisitos';
		}




		/**
		 * [actionDocumentoRequisitoSegunImpuesto description]
		 * @param $impusto, integer que identifica al impuesto.
		 * @return returna lista de item describiendo los documentos y requisitos.
		 */
		public function documentoRequisitoSegunImpuesto($impuesto)
		{
			if ( $impuesto > 0 ) {
				try {
					$conexion = new ConexionController();
					$conn = $conexion->InitConectar('db');
					$conn->open();

					$tabla = self::tableName();
					$sql = "select * from {$tabla} where inactivo = 0 and impuesto = {$impuesto}";

					$dataReport = $conexion->buscarRegistro($conn, $sql);
					$conn->close();

					return $dataReport;

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}



		/**
		 * Relacion entre las entidades "config-documentos-requisitos" y "config-solic-documentos"
		 */
		public function getSolicitudDocumento()
		{
			return $this->hasMany(SolicitudDocumento::className(), ['id_documento' => 'id_documento']);
		}


		/**
		 * Relacion con la entidada "documentos-consignados".
		 * @return Active Record.
		 */
		public function getDocumentoConsignado()
		{
			return $this->hasMany(DocumentoConsignado::className(), ['id_documento' => 'id_documento']);
		}



	}

?>