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
 *  @file ReciboSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @class ReciboSearch
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

	namespace backend\models\recibo\recibo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use common\models\deuda\DeudaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositoplanilla\DepositoPlanillaSearch;


	/**
	* 	Clase
	*/
	class ReciboSearch extends Deposito
	{

		private $_id_contribuyente;

		/**
		 * variable instancia de la clase DeudaSerach;
		 * @var class DeudaSearch;
		 */
		private $_deuda;

		/**
		 * Planillas ya seleccionadas
		 * @var array
		 */
		private $_planillas = [];


		/**
		 * Metodo constructor de clase
		 * @param integer $idContribuyente identificador del contribuyente
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_deuda = New DeudaSearch($this->_id_contribuyente);
		}


		/**
		 * planillas ya seleccionadas para que no aparezcan en el array data provider
		 * @param array $planillas arreglo de planillas
		 */
		public function setPlanillas($planillas)
		{
			$this->_planillas = $planillas;
		}



		/**
		 * Metodo que devuelve un resumen de la deuda del contribuyente por impuestos.
		 * @return array retorna un arreglo con la informacion solicitada, impuesto y
		 * deuda.
		 */
		public function getDeudaGeneralPorImpuestoSegunContribuyente()
		{
			return $this->_deuda->getDeudaGeneralPorImpuesto();
		}



		/**
		 * Metodo que genera un data provider del tipo array
		 * (ArrayDataProvider)
		 * @return array retorna un data provider del tipo array (ArrayDataProvider).
		 */
		public function getDataProviderDeuda()
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaGeneralPorImpuestoSegunContribuyente();
			if ( count($deudas) > 0 ) {
				foreach ( $deudas as $deuda ) {
					$data[$deuda['impuesto']] = [
						'impuesto' => $deuda['impuesto'],
						'descripcion' => $deuda['descripcion'],
						'deuda' => $deuda['t'],
						'id_contribuyente' => $this->_id_contribuyente,
					];
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => [
									'pageSize' => 20,
								],
				]);
			}
			return $provider;
		}


		/***/
		public function findContribuyente()
		{
			return ContribuyenteBase::findOne($this->_id_contribuyente);
		}



		/**
		 * Metodo que busca las deudas y las separas entre las que tienen periodos mayores
		 * a cero y las que tienen periodo iguales a cero.
		 * @return array.
		 */
		public function getDeudaEnPeriodo($impuesto)
		{
			return $this->_deuda->getDeudaPorImpuestoPeriodo($impuesto);
		}



		/***/
		public function getDataProviderEnPeriodo($impuesto)
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaEnPeriodo($impuesto);
			if ( count($deudas) > 0 ) {
				foreach ( $deudas as $deuda ) {
					$data[$deuda['tipo']] = [
						'impuesto' => $deuda['impuesto'],
						'descripcion' => $deuda['descripcion'],
						'deuda' => $deuda['t'],
						'tipo' => $deuda['tipo'],
						'id_contribuyente' => $this->_id_contribuyente,
					];
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => [
									'pageSize' => 5,
								],
				]);
			}
			return $provider;
		}



		public function initDataPrivider()
		{
			$provider = null;
			$data = [];
			$provider = New ArrayDataProvider([
							'allModels' => $data,
							'pagination' => false,
			]);

			return $provider;
		}




		/***/
		public function getDeudaDetalle($impuesto, $idImpuesto = 0)
		{
			if ( $idImpuesto == 0 ) {
				return $this->_deuda->getDetalleDeudaTasa($impuesto);

			} elseif ( $idImpuesto > 0 ) {
				// Deuda del objeto.
				return $this->_deuda->getDetalleDeudaPorObjeto($impuesto, $idImpuesto);
			}
		}




		/***/
		public function getDeudaDetalleActividadEconomica()
		{

			return $this->_deuda->getDetalleDeudadActividadEconomica();
		}



		/***/
		public function getDataProviderDeudaDetalleActEcon()
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaDetalleActividadEconomica();
			$provider = self::getArmarDataProviderDeudaDetalle($deudas);

			return $provider;
		}




		/***/
		public function getDataProviderDeudaDetalle($impuesto, $idImpuesto = 0)
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaDetalle($impuesto, $idImpuesto);
			$provider = self::getArmarDataProviderDeudaDetalle($deudas);

			return $provider;
		}



		/**
		 * Metodo que retorna la deuda agrupada por objetos.
		 * @param  integer $impuesto identificador de impuesto.
		 * @return array.
		 */
		public function getDeudaPorListaObjeto($impuesto)
		{
			return $this->_deuda->getDeudaPorListaObjeto($impuesto);
		}



		/***/
		public function getDataProviderPorListaObjeto($impuesto)
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaPorListaObjeto($impuesto);

			if ( count($deudas) > 0 ) {
				foreach ( $deudas as $deuda ) {
					if ( $deuda['impuesto'] == 2 || $deuda['impuesto'] == 12 ) {
						$index = $deuda['id_impuesto'];
						$objeto = $deuda['direccion'];
						$caption = 'direccion';

					} elseif ( $deuda['impuesto'] == 3 ) {
						$index = $deuda['id_vehiculo'];
						$objeto = $deuda['placa'];
						$caption = 'placa';
					}
					$data[$index] = [
						'impuesto' => $deuda['impuesto'],
						'descripcion' => $deuda['descripcion'],
						'id_impuesto' => $index,
						'objeto' => $objeto,
						'deuda' => $deuda['t'],
						'tipo' => 'periodo>0',
						'id_contribuyente' => $deuda['id_contribuyente'],
						'caption' => $caption,

					];
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => false,
								// 'pagination' => [
								// 	'pageSize' => 30,
								// ],
				]);
			}

			return $provider;
		}




		/**
		 * Metodo donde se busca las deudas agrupdas por planilla, segun el impuesto
		 * y el identificador del objeto. En caso de buscar las deudas de actividad
		 * economica la variabla $idImpuesto sera cero(0).
		 * @param  ineteger $impuesto identificador del impuesto.
		 * @param  ineteger $idImpuesto identificador del objeto (para impuestos 2, 3, 12).
		 * Cuando se trate de un objeto.
		 * @return array.
		 */
		public function getDeudaPorObjetoPlanilla($impuesto, $idImpuesto = 0, $periodo = '>')
		{
			return $this->_deuda->getDetalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto, $periodo);
		}





		/**
		 * Metodo que arma un provider del tipo array data provider. Con la informacion de
		 * la deuda agrupada por planilla, segun impuesto e id-impuesto.
		 * @param  ineteger $impuesto identificador del impuesto.
		 * @param  ineteger $idImpuesto identificador del objeto (para impuestos 2, 3, 12).
		 * Cuando se trate de un objeto.
		 * @return array.
		 */
		public function getDataProviderDeudaPorObjetoPlanilla($impuesto, $idImpuesto = 0, $periodo = '>')
		{
			$data = [];
			$provider = null;
			$deudas = self::getDeudaPorObjetoPlanilla($impuesto, $idImpuesto, $periodo);
			$provider = self::getArmarDataProviderDeudaPlanilla($deudas);

			return $provider;

		}




		/**
		 * Metodo que arma un provider del tipo array data provider a atrir de una conasulta
		 * ($deudas). Dicha consulta debe agrupar la deuda por planilla para entregar el resultado
		 * en el provider. Se realiza una consulta adicional para determinar si la planilla  esta
		 * disponible para ser utilizada en la creacion de un recibo, sino no esta se setea una
		 * variable (bloquear y causaBlouear). Esto se realiza para indicar en la vista respectiva
		 * si la planilla puede ser utilizada.
		 * @param  array $deudas arreglo que posee la deuda agrupada por planilla.
		 * @return array data provider (ArrayDataProvider).
		 */
		private function getArmarDataProviderDeudaPlanilla($deudas)
		{
			$provider = null;
			if ( count($deudas) > 0 && $deudas !== null ) {
				$acumulado = 0;
				$listaSeleccionada = ( count($this->_planillas) > 0 ) ? $this->_planillas : [];

				foreach ( $deudas as $deuda ) {

					$b = 0;
					$result = self::puedoSeleccionarPlanilla((int)$deuda['planilla']);
					if ( $result['r'] == false ) { $b = 1; }

					$t = ( $deuda['tmonto'] + $deuda['trecargo'] + $deuda['tinteres'] ) - ( $deuda['tdescuento'] + $deuda['tmonto_reconocimiento'] );

					if ( !in_array($deuda['planilla'], $listaSeleccionada) ) {

						// $acumulado = $acumulado + $t;
						if ( $b == 0 ) {
							$acumulado = $acumulado + $t;
						}
						$data[$deuda['planilla']] = [
							'planilla' => $deuda['planilla'],
							'id_pago' => $deuda['id_pago'],
							'id_contribuyente' => $deuda['id_contribuyente'],
							'tmonto' => $deuda['tmonto'],
							'trecargo' => $deuda['trecargo'],
							'tinteres' => $deuda['tinteres'],
							'tdescuento' => $deuda['tdescuento'],
							'tmonto_reconocimiento' => $deuda['tmonto_reconocimiento'],
							't' => $t,
							'impuesto' => $deuda['impuesto'],
							'descripcion_impuesto' => $deuda['descripcion_impuesto'],
							'descripcion' => $deuda['descripcion'],
							'acumulado' => $acumulado,
							'seleccionado' => 0,
							'bloquear' => $b,
							'causaBloquear' => $result['m'],				// Descripcion de bloquear

						];
					}
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => false,
								// 'pagination' => [
								// 	'pageSize' => 30,
								// ],
				]);
			}
			return $provider;
		}



		/***/
		private function getArmarDataProviderDeudaDetalle($deudas)
		{
			$provider = null;
			if ( count($deudas) > 0 && $deudas !== null ) {
				foreach ( $deudas as $deuda ) {
					$t = ($deuda['monto'] + $deuda['recargo'] + $deuda['interes']) - ($deuda['descuento'] + $deuda['monto_reconocimiento']);
					$data[$deuda['id_detalle']] = [
						'planilla' => $deuda['pagos']['planilla'],
						'año' => $deuda['ano_impositivo'],
						'periodo' => $deuda['trimestre'],
						'unidad' => $deuda['exigibilidad']['unidad'],
						'monto' => $deuda['monto'],
						'descuento' => $deuda['descuento'],
						'recargo' => $deuda['recargo'],
						'interes' => $deuda['interes'],
						'monto_reconocimiento' => $deuda['monto_reconocimiento'],
						'descripcion' => $deuda['descripcion'],
						't' => $t,
						'id_contribuyente' => $deuda['pagos']['id_contribuyente'],
						'id_impuesto' => $deuda['id_impuesto'],
						'impuesto' => $deuda['impuesto'],
						'id_detalle' => $deuda['id_detalle'],
						'seleccionado' => 0,
						'bloquear' => 0,
						'causaBloquear' => '',				// Descripcion de bloquear

					];
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => false,
								// 'pagination' => [
								// 	'pageSize' => 30,
								// ],
				]);
			}
			return $provider;
		}




		/**
		 * Metodo que permite determinar la condicion de una planilla, para asi considerarla
		 * el la elaboracion de un recibo.
		 * @param  integer $planilla numero de la planilla.
		 * @return array retorna un arreglo con el resulta de la oeracion y un mesanje de la
		 * resultante.
		 */
		public  function puedoSeleccionarPlanilla($planilla)
		{
			$result = [
				'r' => true,		// Resultado de la consulta.
				'm' => '',			// Mensaje del resultado

			];

			$depositoSearch = New DepositoPlanillaSearch();
			$puedo = $depositoSearch->puedoSeleccionarPlanillaParaRecibo($planilla);
			if ( !$puedo ) {
				$result = [
					'r' => $puedo,
					'm' => '1 - La planilla esta contenida en un recibo',
				];
			}

			return $result;
		}



		/**
		 * Metodo que arma un data provider del tipo ArrayDataProvider. Este metodo se utiliza para
		 * armar el gridview de las planillas seleccionadas.
		 * @param  array $planillas arreglo de numero de planillas.
		 * @return array data provider.
		 */
		public function getDataProviderAgruparDeudaPorPlanilla($planillas)
		{
			$data = [];
			$provider = null;
			$deudas = $this->_deuda->getAgruparDeudaPorPlanillas($planillas);
			$provider = self::getArmarDataProviderDeudaPlanilla($deudas);

			return $provider;
		}




		/***/
		public function getDepositoPlanilla()
		{
			return $findModel = DepositoPlanilla::find()->alias('DP')
			                                    ->joinWith('deposito', true, 'INNER JOIN');
		}



		/***/
		public function getDataProviderDepositoPlanilla($recibo)
		{
			$query = self::getDepositoPlanilla();

			$dataProvider = New ActiveDataProvider([
								'query' => $query,
				]);
			$query->where('DP.recibo =:recibo', [':recibo' => $recibo])
				  ->all();

			return $dataProvider;
		}



		/**
		 * Metodo que generara el detalle de montos de las planillas contenidas
		 * en un recibo de manera siguiente:
		 * impuesto - deudad morosa - deudad actual.
		 * Este detalle servira para formar el pdf del recibo de pago.
		 * @param  integer $recibo identificador del recibo de pago.
		 * @return array
		 */
		public function getDepositoPlanillaPorAnoImpositivoSegunRecibo($recibo)
		{
			$findModel = self::getDepositoPlanilla();

			$planillas = $findModel->where('DP.recibo =:recibo',[':recibo' => $recibo])
								   ->asArray()
								   ->all();
			$listaPlanillas= [];
			foreach ( $planillas as $planilla ) {
				$listaPlanillas[] = $planilla['planilla'];
			}

			$deudas = [];
			if ( count($listaPlanillas) > 0 ) {
				$añoActual = date('Y');
				$deudas['morosa'] = $this->_deuda->getAgruparDeudaPorImpuestoAnoImpositivoPlanilla($añoActual, '<', $listaPlanillas);
				$deudas['actual'] = $this->_deuda->getAgruparDeudaPorImpuestoAnoImpositivoPlanilla($añoActual, '=', $listaPlanillas);
			}

			// Se arma un arreglo de impuestos existentes
			$deudaImpuesto = [];
			foreach ( $deudas as $key => $deuda ) {
				foreach ( $deuda as $j => $d ) {
					if ( !in_array($d['impuesto'], $deudaImpuesto) ) {
						$deudaImpuesto[$d['impuesto']] = [
									'impuesto' => $d['impuesto'],
									'descripcion' => $d['descripcion_impuesto'],
									'morosa' => (float)0,
									'actual' => (float)0,

						];
					}
				}
			}

			foreach ( $deudas as $key => $deuda ) {
				foreach ( $deuda as $j => $d ) {

					$total = self::totalizarDeuda($d);
					$deudaImpuesto[$d['impuesto']][$key] = $total;

				}
			}


			return $deudaImpuesto;

		}





		/**
		 * Metodo que totaliza las deudas, dichas deudas vienes subtotalizadas
		 * por conceptos de montos, es decir, total por monto, total por recargo,
		 * total por interes, total por descuento y total por monto reconocimiento.
		 * Se sumaran estos subtotales para entregar un total general.
		 *
		 * @param  array $deuda array con las consultas.
		 * @return double retorna un monto de la totalizacion.
		 */
		private function totalizarDeuda($deudas)
		{
			$total = 0;
			if ( count($deudas) > 0 ) {
				foreach ( $deudas as $key => $value ) {
					$total = (float)$deudas['tmonto'] + (float)$deudas['trecargo'] + (float)$deudas['tinteres'] - ( (float)$deudas['tdescuento'] + (float)$deudas['tmonto_reconocimiento'] ) ;
				}
			}
			return $total;
		}


	}

?>