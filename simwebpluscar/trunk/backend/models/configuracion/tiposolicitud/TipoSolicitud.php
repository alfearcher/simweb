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
 *  @file TipoSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class TipoSolicitud
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

	namespace backend\models\configuracion\tiposolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\solicitud\ConfigurarSolicitud;
	use backend\models\impuesto\Impuesto;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;

	/**
	* 	Clase
	*/
	class TipoSolicitud extends ActiveRecord
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
			return 'config_tipos_solicitudes';
		}



		/**
		 * Relacion con la entidad "config-solicitudes".
		 * @return Active Record
		 */
		public function getConfigurarSolicitud()
		{
			return $this->hasMany(ConfigurarSolicitud::className(), ['tipo_solicitud' => 'id_tipo_solicitud']);
		}



		/**
		 * Relacion con la entidad "impuestos".
		 * @return Active Record
		 */
		public function getImpuestos()
		{
			return $this->hasOne(Impuesto::className(), ['impuesto' => 'impuesto']);
		}



		/**
		 * Relacion con la entidad "funcionarios-solicitudes".
		 * @return Active Record.
		 */
		public function getFuncionarioSolicitud()
		{
			return $this->hasMany(FuncionarioSolicitud::className(), ['tipo_solicitud' => 'id_tipo_solicitud']);
		}



		/**
		 * Relacion con la entidad "solicitudes-contribuyente".
		 * @return Active Record.
		 */
		public function getSolicitudContribuyente()
		{
			return $this->hasMany(SolicitudesContribuyente::className(), ['tipo_solicitud' => 'id_tipo_solicitud']);
		}

	}

?>