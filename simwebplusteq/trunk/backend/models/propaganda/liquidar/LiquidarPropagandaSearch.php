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
 *  @file LiquidarPropagandaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class LiquidarPropagandaSearch
 *  @brief Clase modelo para la liquiadcion de las propagandas.
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

	namespace backend\models\propaganda\liquidar;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use backend\models\propaganda\Propaganda;
	use yii\helpers\ArrayHelper;
	use yii\data\ActiveDataProvider;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\propaganda\liquidar\Liquidar;




	/**
	* Clase que gestiona la liquidacion de Propagandas, donde los nuevos
	* periodos a liquidar se guardaran en una planilla.
	*/
	class LiquidarPropagandaSearch extends Liquidar
	{

		protected $id_contribuyente;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->id_contribuyente = $idContribuyente;
		}



		/**
		 * Metodo que genera un modelo de consulta general de copnsulta
		 * @param integer $idPago identificador del registro maestro de la planila.
		 * @return PagoDetalle
		 */
		public function infoPlanilla($idPago)
		{
			return $findModel = PagoDetalle::find()->alias('D')
		                                   		   ->where('D.id_pago =:id_pago',[':id_pago'=>$idPago])
		                                           ->joinWith('pagos P', true, 'INNER JOIN');

		}




		/**
		 * Metodo que busca las propagandas activos asociados al contribuyente.
		 * @param  array  $chkIdImpuesto arreglo de los identificadores de las propagandas.
		 * Este arreglo puede estar vacio, si es asi, se buscaran todos las propagandas activos.
		 * @return array retorna un arreglo con los datos de las propagandas.
		 */
		public function getListaPropaganda($chkIdImpuesto = [])
		{
			$findModel = self::findPropagandaModel();
			if ( count($chkIdImpuesto) > 0 ) {
				$result = $findModel->andWhere(['IN', 'id_impuesto', $chkIdImpuesto])
				                    ->asArray()->all();
			} else {
				$result = $findModel->asArray()->all();
			}

			return $result;
		}




		/**
		 * Metodo que genera el modelo general de consulta de las propagandas
		 * que le pertenecen a un contribuyente.
		 * @return Propaganda
		 */
		private function findPropagandaModel()
		{
			return Propaganda::find()->alias('V')
									 ->where('id_contribuyente =:id_contribuyente',
											[':id_contribuyente' => $this->id_contribuyente])
								     ->andWhere('inactivo =:inactivo',
											[':inactivo' => 0]);

		}



		/**
		 * Metodo que genera un proveedro de datos del tipo ArrayDataProvider, con
		 * la informacion basica de la propaganda. Ademas el proveedor contiene un atributo
		 * que contine la informacion del ultimo lapso liquidado de tenerlo, este
		 * informacion estara en formato de string: Año - periodo - descripcion lapso.
		 * @return ArrayDataProvider|null
		 */
		public function getDataProviderPropaganda($chkIdImpuesto = [])
		{
			$models = self::getListaPropaganda($chkIdImpuesto);

			$data = [];
			$provider = null;
			if ( count($models) > 0 ) {

				foreach ( $models as $model ) {
					$planilla = '';
					$idPago = '';
					$añoImpositivo = '';
					$periodo = '' ;
					$unidad = '' ;
					$condicion = '';

					$ultimo = self::getUltimoLapsoLiquidado($model['id_impuesto']);
					if ( count($ultimo) > 0 ) {
						$planilla = $ultimo['pagos']['planilla'];
						$idPago = $ultimo['id_pago'];
						$añoImpositivo = $ultimo['ano_impositivo'];
						$periodo = $ultimo['trimestre'];
						$unidad = $ultimo['exigibilidad']['unidad'];
						$condicion = $ultimo['estatus']['descripcion'];
					}

					$data[$model['id_impuesto']] = [
								'id_impuesto' => $model['id_impuesto'],
								'nombre_propaganda' => $model['nombre_propaganda'],
								'direccion' => $model['direccion'],
								'planilla' => $planilla,
								'idPago' => $idPago,
								'añoImpositivo' => $añoImpositivo,
								'periodo' => $periodo,
								'unidad' => $unidad,
								'condicion' => $condicion,
					];
				}

				if ( count($data) > 0 ) {
					$provider = New ArrayDataProvider([
										'key' => 'id_impuesto',
										'allModels' => $data,
										'pagination' => false,
							]);
				}
			}

			return $provider;
		}




		/**
		 * Metodo que obtine el ultimo lapso liquidado.
		 * @param  integer $idImpuesto identificador de la propaganda.
		 * @return array retorna un arreglo don la informacion del ultimo lapso liquidado.
		 */
		public function getUltimoLapsoLiquidado($idImpuesto)
		{
			$liquidar = New Liquidar($this->id_contribuyente, $idImpuesto);
			return $liquidar->getUltimoLapsoLiquidado();

		}



		/**
		 * Metodo que arma un arreglo con los laspos que quedan por liquidar al objeto.
		 * array => {
		 * 	[] => año - periodo - descripcion(exigibilidad).
		 * }
		 * @param  integer $idImpuesto identificagor del objeto
		 * @return array retorna un arreglo con los lapso por liquidar.
		 */
		public function getListaLapsoPendiente($idImpuesto)
		{
			$liquidar = New Liquidar($this->id_contribuyente, $idImpuesto);

			$rangoInicial = $liquidar->armarRangoLiquidacionInicial();
			$rangoFinal = $liquidar->getUltimoLapso();
			$lapsos = [];

			if ( count($rangoInicial) > 0 && count($rangoFinal) > 0 ) {
				for ( $i = (int)$rangoInicial['ano_impositivo']; $i <= (int)$rangoFinal['ano_impositivo']; $i++ ) {

					$exigibilidad = $liquidar->getExigibilidadLiquidacion($i);
					if ( $rangoInicial['ano_impositivo'] == $rangoFinal['ano_impositivo'] ) {
						//$periodoInicio = (int)$rangoInicial['periodo'];
						$periodoInicio = 0;

					} elseif ( $rangoInicial['ano_impositivo'] < $rangoFinal['ano_impositivo'] ) {

						if ( (int)$i == (int)$rangoInicial['ano_impositivo'] ) {
							//$periodoInicio = (int)$rangoInicial['periodo'];
							$periodoInicio = 0;

						} elseif ( (int)$i > (int)$rangoInicial['ano_impositivo'] ) {
							//$periodoInicio = 1;
							$periodoInicio = 0;
						}
					}

					$periodoFinal = 0;
					// lo que estaba antes (int)$exigibilidad['exigibilidad']
					for ( $j = $periodoInicio; $j <= $periodoFinal; $j++ ) {
						// año - periodo - descripcion.
						$lapsos[$i . '-' . $j] = $i . ' - ' . $j . ' - ' . $exigibilidad['unidad'];

					}
				}
			}

			return $lapsos;

		}



		/**
		 * Metodo que genera una descripcion de la informacion resumida del ultimo lapso.
		 * @param  integer $idImpuesto identificador de la propaganda.
		 * @return string
		 */
		public function getInfoUltimoLapsoLiquidado($idImpuesto)
		{
			$descripcion = '';
			$ultimo = self::getUltimoLapsoLiquidado($idImpuesto);
			if ( count($ultimo) > 0 ) {
				$descripcion = $ultimo['ano_impositivo'] . ' - ' . $ultimo['trimestre'] . ' - ' .
				               $ultimo['exigibilidad']['unidad'] . ' - ' . $ultimo['pagos']['id_pago'] . ' - ' .
				               $ultimo['id_pago'] . ' - ' . $ultimo['estatus']['descripcion'];
			}

			return $descripcion;
		}




		/***/
		public function getDataProviderDetalleLiquidacion($detalles)
		{
			return $provider = New ArrayDataProvider([
									'allModels' => $detalles,
									'pagination' => false,
							]);
		}





	}


?>