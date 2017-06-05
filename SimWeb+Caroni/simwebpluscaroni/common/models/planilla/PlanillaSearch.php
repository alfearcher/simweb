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
 	use yii\data\ArrayDataProvider;
 	use backend\models\operacionbase\OperacionBase;
 	use common\models\presupuesto\codigopresupuesto\CodigosContables;


	/**
	* 	Clase que permite consultar informacion diversa sobre una planilla.
	*/
	class PlanillaSearch extends ActiveRecord
	{

		private $_planilla;





		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de planilla que se desea consultar.
		 */
		public function __construct($planilla)
		{
			$this->_planilla = (int)$planilla;
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
							'E.unidad',
							'S.descripcion as estatus',
							])
				  ->from('pagos as P')
				  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
				  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto = I.impuesto')
				  ->join('INNER JOIN', 'exigibilidades as E', 'D.exigibilidad_pago = E.exigibilidad')
				   ->join('INNER JOIN', 'estatus_depositos as S', 'D.pago = S.estatus')
				  ->where('planilla =:planilla', [':planilla' => $this->_planilla])
				  ->groupBy('P.planilla');

			return $query->one();
		}




		/***/
		public function findPlanillaDetalle()
		{
			$query = New Query();

			$query->select(['P.planilla',
							'P.recibo',
							'P.id_contribuyente',
							'D.id_impuesto',
							'D.impuesto',
							'D.ano_impositivo',
							'D.trimestre',
							'D.monto',
							'D.recargo',
							'D.interes',
							'D.descuento',
							'D.monto_reconocimiento',
							'D.referencia',
							'D.pago',
							'D.fecha_pago',
							'D.fecha_vcto',
							'I.descripcion as descripcion_impuesto',
							'D.descripcion',
							'E.unidad',
							'S.descripcion as estatus',
							])
				  ->from('pagos as P')
				  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
				  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto = I.impuesto')
				  ->join('INNER JOIN', 'exigibilidades as E', 'D.exigibilidad_pago = E.exigibilidad')
				  ->join('INNER JOIN', 'estatus_depositos as S', 'D.pago = S.estatus')
				  ->where('planilla =:planilla', [':planilla' => $this->_planilla])
				  ->andWhere(['IN', 'pago', ['0', '1']])
				  ->orderBy([
				  		'ano_impositivo' => SORT_ASC,
				  		'trimestre' => SORT_ASC,
				  	]);

			return $query->all();
		}





		/***/
		public function getArrayDataProviderPlanilla()
		{
			$detalles = self::findPlanillaDetalle();

			if ( count($detalles) > 0 ) {
        		$provider = New ArrayDataProvider([
        				'allModels' => $detalles,
        				'pagination' => false,
        			]);
        	}

			return isset($provider) ? $provider : null;
		}



		/***/
		public function condicionPlanilla()
		{
			$query = New Query();

			$query->select(['P.planilla',
							'P.id_contribuyente',
							'D.pago',
							])
				  ->from('pagos as P')
				  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
				  ->where('planilla =:planilla', [':planilla' => $this->_planilla]);
			return $query->all();
		}




		/***/
		public function anularMiPlanilla($conexionLocal, $connLocal, $observacion = '')
		{
			$result = false;

			$operacion = New OperacionBase();

			$result = $operacion->anularPlanilla($connLocal, [$this->_planilla], $observacion);

			return $result;
		}



		/**
		 * Metodo que retorna el modelo general de consulta.
		 * @return PagoDetalle retorna clase del tipo PagoDetalle.
		 */
		private function findPlanillaGeneralModel()
		{
			return $findMmodel = PagoDetalle::find()->alias('D')
			                                        ->joinWith('pagos P', true, 'INNER JOIN')
			                                        ->where('P.planilla =:planilla',
			                                        			[':planilla' => $this->_planilla]);

		}




		/**
		 * Metodo que retorna los registros de la planilla. Solo aquellos
		 * que estan en la entidad "pagos" y "pagos-detalle".
		 * @return array
		 */
		public function getRegistroDetallePlanilla()
		{
			return self::findPlanillaGeneralModel()->orderBy([
			                                        	'D.ano_impositivo' => SORT_ASC,
			                                        	'D.trimestre' => SORT_ASC,
			                                        ])
			                                       ->asArray()
			                                       ->all();
		}




		/***/
		public function getProviderPlanilla($estatus)
		{
			$findModel = self::findPlanillaGeneralModel();
			$query = $findModel->andWhere('pago =:pago',[':pago' => $estatus])
			                   ->joinWith('exigibilidad E', true, 'INNER JOIN')
			                   ->joinWith('estatus S', true, 'INNER JOIN')
			                   ->joinWith('impuestos I', true, 'INNER JOIN')->asArray()->all();

			$provider = New ArrayDataProvider([
								'allModels' => $query,
								'pagination' => false,
						]);

			// $query->andWhere('pago =:pago',[':pago' => $estatus])->all();

			return $provider;

		}




		/**
		 * Metodo que retorna un modelo de
		 * @return [type] [description]
		 */
		public function getDetallePlanilla($objeto = false)
		{
			$findModel = self::findPlanillaGeneralModel();

			// Se determina el tipo de impuesto y tipo de periodo.
			// Solo se trae un registro para determinar
			$model = $findModel->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true);

			$result = $model->asArray()->all();

			if ( $result[0]['trimestre'] > 0 ) {
				if ( $result[0]['impuesto'] == 1 ) {

					return $model;

				} elseif ( $result[0]['impuesto'] == 2 || $result[0]['impuesto'] == 12 ) {

					return $model = $model->joinWith('inmueble as O', true, 'INNER JOIN');

				} elseif ( $result[0]['impuesto'] == 3 ) {

					return $model = $model->joinWith('vehiculo as O', true, 'INNER JOIN');

				}

			} elseif ( $result[0]['trimestre'] == 0 ) {

				if ( $result[0]['impuesto'] == 9 || $result[0]['impuesto'] == 10 || $result[0]['impuesto'] == 11 ) {

					return $model = $model->joinWith('tasa O', true, 'INNER JOIN');

				} else {

					if ( $objeto ) {
						if ( $result[0]['impuesto'] == 4 ) {
							return $model = $model->joinWith('propaganda O', true, 'INNER JOIN');
						}
					} else {

						return $model = $model->joinWith('tasa O', true, 'INNER JOIN');
					}

				}

			}

			return null;

		}


		/**
		 * Metodo que realiza una consulta para determinar la informacion del codigo presupueatario
		 * segun el identificador de la entidad "codigos-contables".
		 * @param  integer $idCodigo identificador de la entodad "codigos-contables"
		 * @return CodigosContables retorna un modelo con datos del tipo clase CodigosContables.
		 */
		public function getDatosCodigoPresupuesto($idCodigo)
		{
			$codigo = New CodigosContables();
			$model = $codigo->findOne($idCodigo);

			return $model;
		}




		/**
		 * Metodo que arma un arraglo de años y a cada año se le asocia sus periodos
		 * respectivo.
		 * @param  array  $detallePlanilla arreglo de datos de la detalle de la planilla.
		 * Esto es un "select * from pagos as P inner join pagos_detalle as D on
		 * P.id_pago = D.id_pago"
		 * @return array retorna un arreglo.
		 */
		public function getArmarLapso($detallePlanilla = [])
		{
			$lapso = [];
			$año = [];
			$periodo = [];

			foreach ( $detallePlanilla as $detalle ) {
				// Se arma un arreglo de solo años impositivos.
				if ( count($año) > 0 ) {
					$año[$detalle['ano_impositivo']] = $detalle['ano_impositivo'];
				} else {
					if ( !array_key_exists($detalle['ano_impositivo'], $año) ) {
						$año[$detalle['ano_impositivo']] = $detalle['ano_impositivo'];
					}
				}
			}

			// Se asegura que el array quede ordenado por año de manera ascendente.
			ksort($año);

			// Se crear un rango de año
			// $primero = reset($año);
			// $ultimo = end($año);

			foreach ( $año as $i => $value) {
				$periodo = null;

				// Ahora a cada año se le asociara su conjunto de periodos correspondiente
				// Si el año posee ese periodo se le asignara.
				foreach ( $detallePlanilla as $detalle ) {
					if ( (int)$año[$i] == (int)$detalle['ano_impositivo'] ) {
						$periodo[] = $detalle['trimestre'];
					}
				}
				if ( $periodo !== null ) {
					ksort($periodo);
					$año[$i] = $periodo;
				}

			}
			$lapso = $año;

			return $lapso;

		}



		/***/
		public function findRegistrosPlanillaPorAnoImpositivoModel($añoImpositivo)
		{
			$findModel = self::findPlanillaGeneralModel();
			if ( $añoImpositivo > 0 ) {
				$models = $findModel->andWhere('D.ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo]);
			}

			return null;
		}




		/**
		 * Metodo que permite determinar la suma de un campo de la planilla. Se puede especificar un
		 * parametro adicional como el año impositivo.
		 * @param string $campo descripcion del campo.
		 * @param  integer $añoImpositivo año impositivo (opcional).
		 * @return double.
		 */
		public function getMontoPlanillaPorConcepto($campo, $añoImpositivo = 0)
		{
			$suma = 0;

			if ( $añoImpositivo > 0 ) {
				$findModel = self::findRegistrosPlanillaPorAnoImpositivoModel($añoImpositivo);
			} else {
				$findModel = self::findPlanillaGeneralModel();
			}

			$results = $findModel->asArray()->all();

			foreach ( $results as $result ) {
				if ( isset($result[$campo]) ) {
					$suma = $suma + $result[$campo];
				}
			}

			return $suma;

		}




		/**
		 * Metodo que determina el monto de la planilla,
		 * @return double
		 */
		public function getMontoPlanilla()
		{
			$suma = 0;

			$findModel = self::findPlanillaGeneralModel();

			$results = $findModel->asArray()->all();

			foreach ( $results as $result ) {
				$suma = $suma + ( ( $result['monto'] + $result['recargo'] + $result['interes'] ) - ($result['descuento'] + $result['monto_reconocimiento']) );
			}
			return $suma;

		}




	}

?>