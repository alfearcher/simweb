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
 *  @file LiquidarInmuebleSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class LiquidarInmuebleSearch
 *  @brief Clase modelo para la liquiadcion de los inmuebles.
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

	namespace backend\models\inmueble\liquidar;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use backend\models\inmueble\InmueblesConsulta;
	use yii\helpers\ArrayHelper;
	use yii\data\ActiveDataProvider;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\inmueble\liquidar\Liquidar;
	use backend\models\inmueble\avaluo\HistoricoAvaluoSearch;




	/**
	* Clase que gestiona la liquidacion de Inmuebles, donde los nuevos
	* periodos a liquidar se guardaran en una planilla. Se determina cual es el
	* ultimo lapso (año-periodo) liquidado y se determina la condicion del mismo,
	* si el ultimo lapso esta pagado se debe generar un nuevo numero de planilla
	* para guardar los nuevos lapsos, sino es asi, los nuevos lapsos se guardaran
	* en la utlima planilla que existe pendiente.
	*/
	class LiquidarInmuebleSearch extends Liquidar
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
		 * Metodo que busca los inmuebles activos asociados al contribuyente.
		 * @param  array  $chkIdImpuesto arreglo de los identificadores de los inmuebles. Este
		 * arreglo puede estar vacio, si es asi, se buscaran todos los inmuebles activos.
		 * @return array retorna un arreglo con los datos de los vehiculos.
		 */
		public function getListaInmueble($chkIdImpuesto = [])
		{
			$findModel = self::findInmuebleModel();
			if ( count($chkIdImpuesto) > 0 ) {
				$result = $findModel->andWhere(['IN', 'id_impuesto', $chkIdImpuesto])
				                    ->asArray()->all();
			} else {
				$result = $findModel->asArray()->all();
			}

			return $result;
		}




		/**
		 * Metodo que genera el modelo general de consulta de los inmuebles que le pertenecen
		 * a un contribuyente.
		 * @return InmueblesConsulta
		 */
		private function findInmuebleModel()
		{
			return InmueblesConsulta::find()->alias('V')
										    ->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->id_contribuyente])
										    ->andWhere('inactivo =:inactivo',
													[':inactivo' => 0]);

		}



		/**
		 * Metodo que genera un proveedro de datos del tipo ArrayDataProvider, con
		 * la informacion basica del Inmueble. Ademas el proveedor contiene un atributo
		 * uqe contine la informacion del ultimo lapso liquidado de tenerlo, este
		 * informacion estara en formato de string: Año - periodo - descripcion lapso.
		 * @return ArrayDataProvider|null
		 */
		public function getDataProviderInmueble($chkIdImpuesto = [])
		{
			$models = self::getListaInmueble($chkIdImpuesto);

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

					$bloquear = 0;
					$observacion = '';
					if ( !self::existeAvaluoActual($model['id_impuesto']) ) {
						$bloquear = 1;
						$observacion = 'NO SE ENCONTRO EL AVALUO ' . date('Y');
					}

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
								'direccion' => $model['direccion'],
								'catastro' => $model['catastro'],
								'planilla' => $planilla,
								'idPago' => $idPago,
								'añoImpositivo' => $añoImpositivo,
								'periodo' => $periodo,
								'unidad' => $unidad,
								'condicion' => $condicion,
								'bloquear' => $bloquear,
								'observacion' => $observacion,
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
		 * @param  integer $idImpuesto identificador del inmueble.
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
		 * @param  integer $idImpuesto identificador del inmueble.
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




		/**
		 * [getDataProviderDetalleLiquidacion description]
		 * @param  [type] $detalles [description]
		 * @return [type]           [description]
		 */
		public function getDataProviderDetalleLiquidacion($detalles)
		{
			return $provider = New ArrayDataProvider([
									'allModels' => $detalles,
									'pagination' => false,
							]);
		}




		/**
		 * Metodo ue permite determinar si existe un avaluo para el año actual.
		 * @param  integer $idImpuesto identificador del inmueble.
		 * @return boolean retorna true si existe, false en caso contrario.
		 */
		public function existeAvaluoActual($idImpuesto)
		{
			$existe = false;
			$añoActual = (int)date('Y');
			$avaluo = self::getAvaluoSegunAnoImpositivo($añoActual, $idImpuesto);
			if ( count($avaluo) > 0 ) {
				if ( (int)$avaluo['ano_impositivo'] == $añoActual ) {
					$existe = true;
				}
			}

			return $existe;
		}





		/**
		 * Metodo que permite obtener el avaluo de un inmueble segun el año impositivo.
		 * La estructura retornada es un arraeglo con la informacion de la entidad "historico-avaluos"
		 * @param  integer $añoImpositivo año impositivo especifico.
		 * @param  integer $idImpuesto identificador del inmueble.
		 * @return array retorna una arreglo con los datos del avaluo.
		 */
		public function getAvaluoSegunAnoImpositivo($añoImpositivo, $idImpuesto)
		{
			$searchAvaluo = New HistoricoAvaluoSearch($this->id_contribuyente, $idImpuesto);
			return $avaluo = $searchAvaluo->getUltimoAvaluoSegunAnoImpositivo($añoImpositivo);

		}




	}


?>