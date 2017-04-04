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
 *  @file RegistroTxtSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-04-2017
 *
 *  @class RegistroTxtSearch
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
	use yii\base\Model;
	use common\models\planilla\Pago;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;


	/**
	* Clase
	*/
	class RegistroTxtSearch extends RegistroTxt
	{

		private $_fecha_pago;
		private $_planilla;



		/**
		 * Metodo que setea le valor del atributo fecha de pago.
		 * @param string $fechaPago fecha de pago.
		 */
		public function setFechaPago($fechaPago)
		{
			$this->_fecha_pago = self::formatearFechaPago($fechaPago);
		}


		/**
		 * Metodo getter de la fecha de pago.
		 * @return string retorna fecha de pago.
		 */
		public function getFechaPago()
		{
			return $this->_fecha_pago;
		}



		/**
		 * Metodo que formatea el atributo fecha de pago a la forma Y-m-d.
		 * @param string $fechaPago fecha de pago.
		 * @return string retorna la fecha con el formato Y-m-d.
		 */
		public function formatearFechaPago($fechaPago)
		{
			return date('Y-m-d', strtotime($fechaPago));
		}


		/**
		 * Metodo que setea el valor del atributo planilla.
		 * @param integer $planilla numero de planilla.
		 */
		public function setPlanilla($planilla)
		{
			$this->_planilla = $planilla;
		}



		/**
		 * Metodo getter de la planilla.
		 * @return integer retorna numero de planilla.
		 */
		public function getPlanilla()
		{
			return $tis->_planilla;
		}



		/**
		 * Metodo que realiza la consulta de los registros que esta relacionados a una
		 * fecha especifica de pago.
		 * @return RegistroTxt.
		 */
		public function findRegistroTxtByFecha()
		{
			return $this->find()->where('fecha_pago =:fecha_pago',
												[':fecha_pago' => $this->_fecha_pago])
								->orderBy([
									'planilla' => SORT_ASC,
								])
							    ->all();
		}



		/**
		 * Metodo que realiza una consulta sobre las pre-referencias segun una fecha de pago
		 * especifica.
		 * @param array $estatus arreglo de entero que especifica los valores que asumira
		 * el atributo en la consulta.
		 * @return PreReferenciaPlanilla
		 */
		public function findPreReferenciaPlanillaByFecha($estatus = [])
		{
			if ( count($estatus) > 0 ) {
				return $registers = PreReferenciaPlanilla::find()->where('fecha =:fecha',
																			[':fecha' => $this->_fecha_pago])
																 ->andWhere(['IN', 'estatus', $estatus])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
			} else {
				return $registers = PreReferenciaPlanilla::find()->where('fecha =:fecha',
																			[':fecha' => $this->_fecha_pago])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
			}
		}



		/**
		 * Metodo que realiza la consulta de las referencias por el numero de planilla
		 * @return PreReferenciaPlanilla.
		 */
		public function findPreReferenciaPlanillaByPlanilla()
		{
			return $registers = PreReferenciaPlanilla::find()->where('planilla =:planilla',
																			[':planilla' => $this->_planilla])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
		}




		/**
		 * Metodo que genera un listado de planillas existentes el las referencias.
		 * @param PreRefernciaPlanilla $model instancia de la clase
		 * @param string $nombreKey nombre del elemento clave del arreglo.
		 * @param string $nombreValor nombre del elemento valor del arreglo.
		 * @return array
		 */
		public function getListaPlanillaReferencia($model, $nombreKey, $nombreValor)
		{
			return ArrayHelper::map($model, $nombreKey, $nombreValor);
		}





		/***/
		private function getDataPlanillaSinReferencia()
		{
			// registros de las referencias existentes para una fecha especifica.
			// Se buscan los registros que no esten anulados, para que sirvan de
			// filtro para la entidad "registros-txt".
			$referencias = self::findPreReferenciaPlanillaByFecha([0,1]);

			// Lista de planilla registradas con referencia.
			$listaReferencia = self::getListaPlanillaReferencia($referencias, 'planilla', 'planilla');

			// arreglo donde el valor delo mismo es el numero de planilla.
			$listaPlamilla = array_values($listaReferencia);

			// Ahora se debe buscar en la entidad "registros-txt", las planillas que aun
			// no estan en las referencias. Se debe realizar una consulta excluyente de
			// conjunto.
			$registers = $this->find()->where('fecha_pago =:fecha_pago',
													[':fecha_pago' => $this->_fecha_pago])
									  ->andWhere(['NOT IN', 'planilla', $listaPlamilla])
									  ->all();

die(var_dump($registers));
		}



		/***/
		public function getDataProviderByFecha()
		{
			$data = self::getDataPlanillaSinReferencia();

			$provider = New ArrayDataProvider([
				'allModels' => $data,
				'pagination' => false,
			]);

			return $provider;
		}




	}

?>