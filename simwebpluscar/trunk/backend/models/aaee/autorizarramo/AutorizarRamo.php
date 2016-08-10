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
 *  @file AutorizarRamo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-10-2015
 *
 *  @class AutorizarRamo
 *  @brief Clase modelo de aprobacion de rubro
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

	namespace backend\models\aaee\autorizarramo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use common\models\aaee\Sucursal;
	use backend\models\aaee\rubro\Rubro;

	/**
	* 	Clase
	*/
	class AutorizarRamo extends ActiveRecord
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
			return 'sl_ramos_autorizados';
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
		 * Relacion con la entidad "contribuyentes", Sucursal.
		 * @return Active Record.
		 */
		public function getSucursal()
		{
			return $this->hasOne(Sucursal::className(), ['id_contribuyente' => 'id_contribuyente']);
		}




		// /**
		//  * Metodo que permite determinar si un contribuyente tiene registros en
		//  * la entidad de las declaraciones.
		//  * @param  $idContribuyente, Long que identifica al contribuyente.
		//  * @return returna una instancia con los datos de la entidad act_econ.
		//  * Si retorna false no ss ejecuto la consulta o no encontro nada.
		//  */
		// public static function tieneRecordActEcon($idContribuyente = 0)
	 //    {
	 //    	if ( $idContribuyente > 0 ) {
	 //    		$sql = 'SELECT * FROM act_econ WHERE id_contribuyente=:id_contribuyente';
	 //    		$actEcon = self::findBySql($sql, [':id_contribuyente' => $idContribuyente])->one();
	 //    		if ( isset($actEcon) ) {
	 //    			return $actEcon;
	 //    		}
	 //    	}
	 //    	return false;
	 //    }
	}


?>