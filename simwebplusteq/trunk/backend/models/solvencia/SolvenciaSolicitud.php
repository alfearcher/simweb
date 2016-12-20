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
 *  @file SolvenciaSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class SolvenciaSolicitud
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

	namespace backend\models\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;


	/**
	* 	Clase
	*/
	class SolvenciaSolicitud extends ActiveRecord
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
			return 'sl_solvencias';
		}


		/**
		 * Relacion con la entidad "estatus-solicitudes", EstatusSolicitud
		 * @return Active Record.
		 */
		public function getEstatusSolicitud()
		{
			return $this->hasOne(EstatusSolicitud::className(), ['estatus_solicitud' => 'estatus']);
		}


		/**
		 * Relacion con la entidad "solicitudes-contribuyente", SolicitudesContribuyente
		 * @return active record.
		 */
		public function getSolicitud()
		{
			return $this->hasOne(SolicitudesContribuyente::className(), ['nro_solicitud' => 'nro_solicitud']);
		}



		/**
		 * Metodo que permite obtener la descripcion de la solicitud.
		 * @param integer $nroSolicitud identificador de la solicitud creada.
		 * @return string retorna La descripcion del tipo de solicitud.
		 */
		public function getDescripcionTipoSolicitud($nroSolicitud)
		{
			$solicitud = New SolicitudesContribuyente();
			return $solicitud->getDescripcionTipoSolicitud($nroSolicitud);
		}

	}

?>