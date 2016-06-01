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



		/***/
		protected function findPlanilla()
		{
			$modelFind = PagoDetalle::find()->where('planilla =:planilla', [':planilla' => $this->_planilla])
											->joinWith('pagos')
											->orderBy([
													'ano_impositivo' => SORT_ASC,
													'trimestre' => SORT_ASC,
												]);
			return isset($modelFind) ? $modelFind : null;
		}




		public function getResumenGeneral()
		{
			return $m = self::findPlanilla();
		}



	}

?>