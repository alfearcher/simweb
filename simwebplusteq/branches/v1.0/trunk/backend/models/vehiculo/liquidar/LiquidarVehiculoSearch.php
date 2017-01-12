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
 *  @file LiquidarVehiculoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class LiquidarVehiculoSearch
 *  @brief Clase modelo para la liquiadcion de los vehiculos.
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

	namespace backend\models\vehiculo\liquidar;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use backend\models\vehiculo\VehiculosForm;
	use yii\helpers\ArrayHelper;
	use yii\data\ActiveDataProvider;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\vehiculo\liquidar\Liquidar;




	/**
	* Clase que gestiona la liquidacion de Vehiculos, donde los nuevos
	* periodos a liquidar se guardaran en una planilla. Se determina cual es el
	* ultimo lapso (año-periodo) liquidado y se determina la condicion del mismo,
	* si el ultimo lapso esta pagado se debe generar un nuevo numero de planilla
	* para guardar los nuevos lapsos, sino es asi, los nuevos lapsos se guardaran
	* en la utlima planilla que existe pendiente.
	*/
	class LiquidarVehiculoSearch extends Liquidar
	{

		protected $id_contribuyente;

		const IMPUESTO = 3;



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
		 * Metodo que busca los vehiculos activos asociados al contribuyente.
		 * @param  array  $chkIdImpuesto arreglo de los identificadores de los vehiculos. Este
		 * arreglo puede estar vacio, si es asi, se buscaran todos los vehiculos activos.
		 * @return array retorna un arreglo con los datos de los vehiculos.
		 */
		public function getListaVehiculo($chkIdImpuesto = [])
		{
			$findModel = self::findVehiculoModel();
			if ( count($chkIdImpuesto) > 0 ) {
				$result = $findModel->andWhere(['IN', 'id_vehiculo', $chkIdImpuesto])
				                    ->asArray()->all();
			} else {
				$result = $findModel->asArray()->all();
			}

			return $result;
		}




		/**
		 * Metodo que genera el modelo general de consulta de los vehiculos que le pertenecen
		 * a un contribuyente.
		 * @return VehiculosForm
		 */
		private function findVehiculoModel()
		{
			return VehiculosForm::find()->alias('V')
										->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->id_contribuyente])
										->andWhere('status_vehiculo =:status_vehiculo',
													[':status_vehiculo' => 0]);

		}



		/**
		 * Metodo que genera un proveedro de datos del tipo ArrayDataProvider, con
		 * la informacion basica del vehiculo. Ademas el proveedor contiene un atributo
		 * uqe contine la informacion del ultimo lapso liquidado de tenerlo, este
		 * informacion estara en formato de string: Año - periodo - descripcion lapso.
		 * @return ArrayDataProvider|null
		 */
		public function getDataProviderVehiculo($chkIdImpuesto = [])
		{
			$models = self::getListaVehiculo($chkIdImpuesto);

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

					$ultimo = self::getUltimoLapsoLiquidado($model['id_vehiculo']);
					if ( count($ultimo) > 0 ) {
						$planilla = $ultimo['pagos']['planilla'];
						$idPago = $ultimo['id_pago'];
						$añoImpositivo = $ultimo['ano_impositivo'];
						$periodo = $ultimo['trimestre'];
						$unidad = $ultimo['exigibilidad']['unidad'];
						$condicion = $ultimo['estatus']['descripcion'];
					}

					$data[$model['id_vehiculo']] = [
								'id_impuesto' => $model['id_vehiculo'],
								'placa' => $model['placa'],
								'marca' => $model['marca'],
								'modelo' => $model['modelo'],
								'color' => $model['color'],
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
		 * @param  integer $idImpuesto identificador del vehiculo.
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
						$periodoInicio = (int)$rangoInicial['periodo'];

					} elseif ( $rangoInicial['ano_impositivo'] < $rangoFinal['ano_impositivo'] ) {

						if ( (int)$i == (int)$rangoInicial['ano_impositivo'] ) {
							$periodoInicio = (int)$rangoInicial['periodo'];

						} elseif ( (int)$i > (int)$rangoInicial['ano_impositivo'] ) {
							$periodoInicio = 1;
						}
					}

					for ( $j = $periodoInicio; $j <= (int)$exigibilidad['exigibilidad']; $j++ ) {
						// año - periodo - descripcion.
						$lapsos[$i . '-' . $j] =
										$i . ' - ' . $j . ' - ' . $exigibilidad['unidad'];

					}
				}
			}

			return $lapsos;

		}



		/**
		 * Metodo que genera una descripcion de la informacion resumida del ultimo lapso.
		 * @param  integer $idImpuesto identificador del vehiculo.
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