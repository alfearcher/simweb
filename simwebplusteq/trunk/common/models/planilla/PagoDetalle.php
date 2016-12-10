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
	use backend\models\impuesto\Impuesto;
	use backend\models\inmueble\InmueblesUrbanosForm;
	use backend\models\vehiculo\VehiculosForm;
	use backend\models\propaganda\Propaganda;
	use backend\models\utilidad\exigibilidad\Exigibilidad;
	use backend\models\tasa\Tasa;
	use common\models\presupuesto\codigopresupuesto\CodigosContables;
	use backend\models\recibo\estatus\EstatusDeposito;


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


		/**
		 * Relacion con la entidad "impuestos"
		 * @return active record.
		 */
		public function getImpuestos()
		{
			return $this->hasOne(Impuesto::className(), ['impuesto' => 'impuesto']);
		}



		/**
		 * Relacion con la entidad "inmuebles"
		 * @return active record.
		 */
		public function getInmueble()
		{
			return $this->hasOne(InmueblesUrbanosForm::className(), ['id_impuesto' => 'id_impuesto']);
		}



		/**
		 * Relacion con la entidad "vehiculos"
		 * @return active record.
		 */
		public function getVehiculo()
		{
			return $this->hasOne(VehiculosForm::className(), ['id_vehiculo' => 'id_impuesto']);
		}




		/**
		 * Relacion con la entidad "propagandas"
		 * @return active record.
		 */
		public function getPropaganda()
		{
			return $this->hasOne(Propaganda::className(), ['id_impuesto' => 'id_impuesto']);
		}



		/**
		 * Relacion con la entidad "exigibilidades"
		 * @return active record
		 */
		public function getExigibilidad()
		{
			return $this->hasOne(Exigibilidad::className(), ['exigibilidad' => 'exigibilidad_pago']);
		}



		/**
		 * Relacion con la entidad "tasas"
		 * @return active record
		 */
		public function getTasa()
		{
			return $this->hasOne(Tasa::className(),
										[
											'id_impuesto' => 'id_impuesto',
										 	'impuesto' => 'impuesto',
										 	'ano_impositivo' => 'ano_impositivo',
										]);
		}




		/**
		 * Relacion con la entidad "estatus-depositos"
		 * @return active record
		 */
		public function getEstatus()
		{
			return $this->hasOne(EstatusDeposito::className(),['estatus' => 'pago']);
		}




		/***/
		public function getCodigoContable($idImpuesto)
		{

			$tasa = New Tasa;
			$model = $tasa->find()->alias('T')
			                      ->joinWith('codigoContable C', true, 'INNER JOIN')
			                      ->where('id_impuesto =:id_impuesto',
			                      				[':id_impuesto' => $idImpuesto])
			                      ->one();
			return [
				'codigo' => $model->codigoContable->codigo,
				'descripcion' => $model->codigoContable->descripcion,
			];

		}

	}

?>