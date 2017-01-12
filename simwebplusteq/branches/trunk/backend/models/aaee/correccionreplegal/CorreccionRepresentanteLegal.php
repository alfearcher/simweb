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
 *  @file CorreccionRepresentanteLegal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-12-2015
 *
 *  @class CorreccionRepresentanteLegal
 *  @brief Clase Modelo principal
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

 	namespace backend\models\aaee\correccionreplegal;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use common\models\aaee\Sucursal;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;


	/**
	 * Clase que gestiona el funcionamiento de la solicitud para la actualizacion
	 * del representante legal de la empresa.
	 */
	class CorreccionRepresentanteLegal extends ActiveRecord
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
			return 'sl_correcciones_representante_legal';
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
		 * Relacion con la entidad "contribuyentes", Sucursal.
		 * @return Active Record.
		 */
		public function getSucursal()
		{
			return $this->hasOne(Sucursal::className(), ['id_contribuyente' => 'id_contribuyente']);
		}



		/**
		 * Metodo que permite obtener la descripcion del tipo de solicitud
		 * @param  long $nroSolicitud identificacion de la solicitud. Autoincremental
		 * que se genera al crear la solicitud.
		 * @return string retorna la descripcion de la solicitud.
		 */
		 public function getDescripcionTipoSolicitud($nroSolicitud)
		 {
			return $tipo = SolicitudesContribuyente::getDescripcionTipoSolicitud($nroSolicitud);
		 }

	}
 ?>