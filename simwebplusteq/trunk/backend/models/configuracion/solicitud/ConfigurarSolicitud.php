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
 *  @file ConfigurarSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class ConfigurarSolicitud
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

	namespace backend\models\configuracion\solicitud;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\Impuesto;
	use backend\models\configuracion\detallesolicitud\SolicitudDetalle;
	use backend\models\configuracion\procesosolicitud\SolicitudProceso;
	use backend\models\configuracion\nivelaprobacion\NivelAprobacion;

	/**
	* 	Clase
	*/
	class ConfigurarSolicitud extends ActiveRecord
	{
		public $tipoSolicitud;

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
			return 'config_solicitudes';
		}


		/**
		* Relacion de las entidades "config-solicitudes" y "config-tipos-solicitudes".
		*/
		public function getTipoSolicitud()
		{
			return $this->hasOne(TipoSolicitud::className(), ['id_tipo_solicitud' => 'tipo_solicitud']);
		}



		/**
		* Relacion entre las entidades "config-solicitudes" e "impuesto".
		* El primer "impuesto se refiere a la entidad impuestos".
		*/
		public function getImpuestoSolicitud()
		{
			return $this->hasOne(Impuesto::className(), ['impuesto' => 'impuesto']);
		}


		/**
		* Relacion entre las entidades "config-solicitudes" e "config-solic-detalles".
		* * El primer "id_config_solicitud se refiere a la entidad config-solic-detalles".
		*/
		public function getDetalleSolicitud()
		{
			return $this->hasMany(SolicitudDetalle::className(), ['id_config_solicitud' => 'id_config_solicitud']);
		}



		/**
		 * Relacion entre las entidades "config-solic-detalles" y "config-solicitud-procesos"
		 * @return [type] [description]
		 */
		public function getDetalleProcesoSolicitud()
		{
			$config = SolicitudDetalle::find()->where(['id_config_solicitud' => $this->getIdConfigSolicitud()])
											  ->with('procesoSolicitud');
			return $config;
		}


		/***/
		public function getDetalleProceso()
		{
			return SolicitudDetalle::getProcesoSolicitud();
		}



		/**
		 * Relacion entre las entidades "config-solicitudes" y "niveles_aprobacion"
		 * @return [type] [description]
		 */
		public function getNivelAprobacion()
		{
			return $this->hasOne(NivelAprobacion::className(), ['nivel_aprobacion' => 'nivel_aprobacion']);
		}

	}

?>