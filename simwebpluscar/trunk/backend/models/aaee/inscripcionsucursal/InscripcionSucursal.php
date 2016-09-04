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
 *  @file InscripcionSucursal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-10-2015
 *
 *  @class InscripcionSucursal
 *  @brief Clase modelo de la inscripcion de sucursales
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

	namespace backend\models\aaee\inscripcionsucursal;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use backend\models\solicitud\estatus\EstatusSolicitud;

	/**
	* 	Clase
	*/
	class InscripcionSucursal extends ActiveRecord
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
			return 'sl_inscripciones_sucursales';
		}



		 /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_inscripcion_sucursal' => Yii::t('backend', 'Id. Sucursal'),
	        	'id_sede_principal' => Yii::t('backend', 'Id. sede'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Request No.'),
	            'naturaleza' => Yii::t('backend', 'RIF'),
	            'cedula' => Yii::t('backend', 'DNI'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'domicilio_fiscal' => Yii::t('backend', 'Home'),
	            'tlf_ofic' => Yii::t('backend', 'Office No Phone'),
	            'tlf_ofic_otro' => Yii::t('backend', 'Office No Phone'),
	            'tlf_celular' => Yii::t('backend', 'Office Celular No'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'email' => Yii::t('backend', 'email'),
	            'razon_social' => Yii::t('backend', 'Razon Social'),
	            'id_sim' => Yii::t('backend', 'Licence Number'),
	            'num_celular' => Yii::t('backend', 'Celular Number'),
	            'num_tlf_ofic' => Yii::t('backend', 'Office Number 1'),
	            'num_tlf_ofic_otro' => Yii::t('backend', 'Office Number 2'),
	            'dni_representante' => Yii::t('backend', 'DNI legal represent'),
	            'representante' => Yii::t('backend', 'Legal represent'),

	        ];
	    }



	    /**
	     * Relacion con la entidad "estatus-solicitudes", EstatusSolicitud
	     * @return Active Record
	     */
	    public function getEstatusInscripcion()
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

	}


?>