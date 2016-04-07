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
 *  @file PagoDetalle.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-03-2016
 *
 *  @class PagoDetalle
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
	use common\models\planilla\Pago;


	/**
	* 	Clase
	*/
	class PagoDetalle extends ActiveRecord
	{

		public $id_detalle;
		public $id_pago;
		public $id_impuesto;
		public $impuesto;
		public $ano_impositivo;
		public $trimestre;
		public $monto;
		public $descuento;
		public $recargo;
		public $interes;
		public $fecha_emision;
		public $fecha_vcto;
		public $pago;
		public $fecha_pago;
		public $referencia;
		public $descripcion;
		public $monto_reconocimiento;
		public $exigibilidad_pago;
		public $fecha_desde;
		public $fecha_hasta;



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
			return 'pagos_detalle';
		}



		/**
		 * Relacion con la entidad "pagos".
		 * @return [type] [description]
		 */
		public function getPagos()
		{
			return $this->hasOne(Pago::className(), ['id_pago' => 'id_pago']);
		}

	}

?>