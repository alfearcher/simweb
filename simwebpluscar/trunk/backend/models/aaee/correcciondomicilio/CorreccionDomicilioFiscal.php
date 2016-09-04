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
 *  @file CorreccionDomicilioFiscal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2015
 *
 *  @class CorreccionDomicilioFiscal
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

 	namespace backend\models\aaee\correcciondomicilio;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;

	class CorreccionDomicilioFiscal extends ActiveRecord
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
			return 'sl_correcciones_domicilio';
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
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_correccion' => Yii::t('frontend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('frontend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('frontend', 'Request'),
	            'domicilio_fiscal_v' => Yii::t('frontend', 'Current Tax Address'),
	            'domicilio_fiscal_new' => Yii::t('frontend', 'New Tax Address'),
	            'estatus' => Yii::t('frontend', 'Condition'),
	            'fecha_hora' => Yii::t('frontend', 'Date/Hour'),
	            'razon_social' => Yii::t('frontend', 'Companies'),
	            'dni' => Yii::t('frontend', 'DNI'),

	        ];
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