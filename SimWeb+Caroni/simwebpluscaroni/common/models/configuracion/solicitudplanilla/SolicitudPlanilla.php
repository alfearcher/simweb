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
 *  @file SolicitudPlanilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-05-2016
 *
 *  @class SolicitudPlanilla
 *  @brief Clase modelo que permite mantener la relacion entre la solicitud
 *  generada, planilla y objeto.
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

	namespace common\models\configuracion\solicitudplanilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;

	/**
	* 	Clase
	*/
	class SolicitudPlanilla extends ActiveRecord
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
		 *  Entidad donde se guardan las planillas generadas por la solicitud,
		 *  ya sea en los eventos:
		 *  - CREAR.
		 *  - APROBAR.
		 *  - NEGAR.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'solicitudes_planillas';
		}


		/**
		 * Relacion con la entidad "solicitudes-contribuyente".
		 * @return Active Record.
		 */
		public function getSolicitudContribuyente()
		{
			return $this->hasOne(SolicitudesContribuyente::className(), ['nro_solicitud' => 'nro_solicitud']);
		}


		// /***/
  //       public function getPlanilla()
  //       {
  //           return $this->hasMany(SolicitudPlanilla::className(), ['nro_solicitud' => 'nro_solicitud'])
  //                       ->via('pago');
  //       }

  //       /***/
  //       public function getPago()
  //       {
  //           return $this->hasOne(Pago::className(), ['planilla' => 'planilla']);
  //       }

	}


?>