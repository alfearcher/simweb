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
 *  @file PlanillaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-06-2016
 *
 *  @class PlanillaSearch
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
	use yii\db\Exception;
	use yii\db\ActiveRecord;
 	use common\models\planilla\Pago;
 	use common\models\planilla\PagoDetalle;
 	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;
 	use yii\db\Query;
 	use yii\data\ActiveDataProvider;

	/**
	* 	Clase que permite consultar informacion diversa sobre una planilla.
	*/
	class PlanillaSearch
	{

		private $_planilla;





		/**
		 * Metodo constructor de la clase.
		 * @param Long $planilla numero de planilla que se desea consultar.
		 */
		public function __construct($planilla)
		{
			$this->_planilla = $planilla;
		}



		/**
		 * Metodo que permite obtener una informacion resumida por monto de la planilla.
		 * Dicha informacion esta representada por totales por los diferentes conceptos,
		 * descripcion de los periodos, observacion presente en la planilla, descripcion del impuesto,
		 * estatus de pago, numero de planilla, id del contribuyente.
		 * @return Array Retorna un arreglo multidimensional con los datos mencionados.
		 */
		public function getResumenGeneral()
		{
			$query = New Query();

			$query->select(['P.planilla',
							'P.id_contribuyente',
							'SUM(D.monto) as sum_monto',
							'SUM(D.recargo) as sum_recargo',
							'SUM(D.interes) as sum_interes',
							'SUM(D.descuento) as sum_descuento',
							'SUM(D.monto_reconocimiento) as sum_monto_reconocimiento',
							'D.pago',
							'I.descripcion as descripcion_impuesto',
							'D.descripcion',
							'E.unidad'
							])
				  ->from('pagos as P')
				  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
				  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto = I.impuesto')
				  ->join('INNER JOIN', 'exigibilidades as E', 'D.exigibilidad_pago = E.exigibilidad')
				  ->where('planilla =:planilla', [':planilla' => $this->_planilla])
				  ->groupBy('P.planilla');

			return $query->one();
		}




		/***/
		public function findPlanillaDetalle()
		{
			$model = PagoDetalle::find()->where('planilla =:planilla', [':planilla' => $this->_planilla])
										->joinWith('pagos')
										->orderBy([
											'ano_impositivo' => SORT_ASC,
											'trimestre' => SORT_ASC
											]);

			return isset($model) ? $model : null;
		}





		/***/
		public function getDataProviderPlanilla()
		{
			$query = self::findPlanillaDetalle();

			$query = $query->all();
			$dataProvider = New ActiveDataProvider([
					'query' => $query,
				]);

			return isset($dataProvider) ? $dataProvider : null;
		}




	}

?>