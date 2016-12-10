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
 *  @file Pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-03-2016
 *
 *  @class Pago
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

	namespace common\models\planilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\planilla\PagoDetalle;

	/**
	* 	Clase
	*/
	class Pago extends ActiveRecord
	{

		public $id_pago;
		public $ente;
		public $id_contribuyente;
		public $planilla;
		public $status_pago;
		public $notificado;
		public $ult_act;
		public $recibo;
		public $id_moneda;
		public $exigibilidad_deuda;



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
			return 'pagos';
		}




		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['ente', 'id_contribuyente',
	        	  'planilla', 'status_pago',
	        	  'notificado', 'ult_act',
	        	  'id_moneda', 'exigibilidad_deuda'],
	        	  'required', 'message' => Yii::t('backend','{attribute} is required')],
	        	['recibo', 'status_pago', 'id_moneda', 'default', 'value' => 0],
	        	['ente', 'default', 'value' => Yii::$app->ente->getEnte()],
	        	[['id_contribuyente', 'planilla',
	        	  'recibo', 'status_pago',
	        	  'notificado', 'ente'],
	        	  'integer', 'message' => Yii::t('backend','{attribute}')],
	        	['planilla', 'unique', 'message' => Yii::t('backend','{attribute} debe ser unica')],

	        ];
	    }




		/**
		 * Relacion con la entidad "pagos-detalle".
		 * @return [type] [description]
		 */
		public function getPagoDetalle()
		{
			return $this->hasMany(PagoDetalle::className(), ['id_pago' => 'id_pago']);
		}

	}

?>