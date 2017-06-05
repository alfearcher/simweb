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
 *  @file PlanillaContable.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2017
 *
 *  @class PlanillaContable
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

	namespace backend\models\recibo\planillacontable;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\recibo\deposito\Deposito;
	use common\models\planilla\Pago;
	use backend\models\recibo\estatus\EstatusDeposito;



	/**
	* Clase principal de la entidad "planillas-contables"
	*/
	class PlanillaContable extends ActiveRecord
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
			return 'planillas_contables';
		}




		/**
		 * Relacion con la entidad "depositos"
		 * @return [type] [description]
		 */
		public function getDeposito()
		{
			return $this->hasOne(Deposito::className(), ['recibo' => 'recibo']);
		}




		/**
		 * Relacion con la entidad "pagos"
		 * @return active record
		 */
		public function getPago()
		{
			return $this->hasOne(Pago::className(), ['planilla' => 'planilla']);
		}




		/**
		 * Relacion con la entidad "estatus".
		 * @return active record
		 */
		public function getCondicion()
		{
			return $this->hasOne(EstatusDeposito::className(), ['estatus' => 'estatus']);
		}



	}

?>