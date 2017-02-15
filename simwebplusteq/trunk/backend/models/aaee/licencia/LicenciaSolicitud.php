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
 *  @file LicenciaSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-11-2016
 *
 *  @class LicenciaSolicitud
 *  @brief Clase modelo
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

	namespace backend\models\aaee\licencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use backend\models\aaee\actecon\ActEcon;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use backend\models\aaee\rubro\Rubro;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;
	use common\models\contribuyente\ContribuyenteBase;


	/**
	* Clase que permite gestionar las solicitudes de emision de licencias de Actividades
	* Economicas.
	*/
	class LicenciaSolicitud extends ActiveRecord
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
			return 'sl_licencias';
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
		 * Relacion con la entidad "rubros"
		 * @return active record
		 */
		public function getRubro()
		{
			return $this->hasOne(Rubro::className(), ['id_rubro' => 'id_rubro']);
		}



		/**
		 * Relacion con la entidad "solicitides-planillas"
		 * @return [type] [description]
		 */
		public function getPlanillas()
		{
			return $this->hasMany(SolicitudPlanilla::className(),['nro_solicitud' => 'nro_solicitud']);
		}



		/**
		 * Descripcion del contribuyente, razon social para los de tipo naturaleza "juridico",
		 * apellidos y nombres pra los de tipo naturaleza "natural"
		 * @param  integer $idContribuyente identificador del contribuyente
		 * @return string retorna la descripcion del contribuyente.
		 */
		public function getContribuyente($idContribuyente)
		{
			return ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
		}



		/**
		 * Relacion con la entidad "solicitudes-contribuyente"
		 * @return [type] [description]
		 */
		public function getSolicitud()
		{
			return $this->hasOne(SolicitudesContribuyente::className(),['nro_solicitud' => 'nro_solicitud']);
		}



		/**
		 * Relacion con la entidad "contribuyentes"
		 * @return ContribuyenteBase.
		 */
		public function getDatosContribuyente()
		{
			return $this->hasOne(ContribuyenteBase::className(),['id_contribuyente' => 'id_contribuyente']);
		}

	}


?>