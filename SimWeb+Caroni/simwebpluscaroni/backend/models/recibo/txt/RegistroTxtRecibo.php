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
 *  @file RegistroTxtRecibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-04-2017
 *
 *  @class RegistroTxtRecibo
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

	namespace backend\models\recibo\txt;

 	use Yii;
	use yii\db\ActiveRecord;
	use backend\models\recibo\estatus\EstatusDeposito;



	/**
	*	Clase
	*/
	class RegistroTxtRecibo extends ActiveRecord
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
			return 'registros_txt_recibos';
		}


		/**
		 * Relacion con la entidad "estatus-solicitudes", EstatusSolicitud
		 * @return Active Record.
		 */
		public function getEstatusDeposito()
		{
			return $this->hasOne(EstatusDeposito::className(), ['estatus' => 'estatus']);
		}


	}

?>