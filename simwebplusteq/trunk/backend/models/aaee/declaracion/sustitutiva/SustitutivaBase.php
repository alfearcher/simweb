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
 *  @file SustitutivaBase.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class SustitutivaBase
 *  @brief Clase modelo de la sustitutiva
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

	namespace backend\models\aaee\declaracion\sustitutiva;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use backend\models\aaee\declaracion\tipodeclaracion\TipoDeclaracion;

	/**
	* 	Clase que gestiona la solicitud base de la declaracion.
	*/
	class SustitutivaBase extends ActiveRecord
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
			return 'sl_sustitutivas';
		}



		/**
		 * Relacion con la entidad "rubros"
		 * @return Active Record
		 */
		public function getRubro()
		{
			return $this->hasOne(Rubro::className(), ['id_rubro' => 'id_rubro']);
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
		 * Metodo que permite obtener la descripcion del tipo de solicitud
		 * @param  long $nroSolicitud identificacion de la solicitud. Autoincremental
		 * que se genera al crear la solicitud.
		 * @return string retorna la descripcion de la solicitud.
		 */
		 public function getDescripcionTipoSolicitud($nroSolicitud)
		 {
			return $tipo = SolicitudesContribuyente::getDescripcionTipoSolicitud($nroSolicitud);
		 }



		 /**
		  * Relacion con la entidad "act-econ"
		  * @return active record
		  */
		 public function getActEcon()
		 {
		 	return $this->hasOne(ActEcon::className(), ['id_impuesto' => 'id_impuesto']);
		 }


		 /**
		  * Relacion con la entidad "act-econ-ingresos"
		  * @return active record
		  */
		 public function getActEconIngreso()
		 {
		 	return $this->hasMany(ActEconIngreso::className(), ['id_impuesto' => 'id_impuesto']);
		 }


		 /**
		  * Relacion con la entidad "tipo-declaracion"
		  * @return active record
		  */
		 public function getTipoDeclaracion()
		 {
		 	return $this->hasOne(TipoDeclaracion::className(), ['tipo_declaracion' => 'tipo_declaracion']);
		 }


	}


?>