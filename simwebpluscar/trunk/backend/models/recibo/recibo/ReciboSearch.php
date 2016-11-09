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
		 * Metodo constructor de clase
		 * @param integer $idContribuyente identificador del contribuyente
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_deuda = New DeudaSearch($this->_id_contribuyente);
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
			if ( count($deudas) > 0 ) {
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
						'deuda' => $t,
						'id_contribuyente' => $deuda['pagos']['id_contribuyente'],
						'id_impuesto' => $deuda['id_impuesto'],
						'impuesto' => $deuda['impuesto'],
						'id_detalle' => $deuda['id_detalle'],
					];
				}

				$provider = New ArrayDataProvider([
								'allModels' => $data,
								'pagination' => false,
								// 'pagination' => [
								// 	'pageSize' => 20,
								// ],
				]);
			}
			return $provider;
		}




		/***/
		public function getDataProviderDeudaDetalle($impuesto, $idImpuesto = 0)
		{
			$provider = null;
			$data = [];
			$deudas = self::getDeudaDetalle($impuesto, $idImpuesto);
			if ( count($deudas) > 0 ) {
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
						'deuda' => $t,
						'id_contribuyente' => $deuda['pagos']['id_contribuyente'],
						'id_impuesto' => $deuda['id_impuesto'],
						'impuesto' => $deuda['impuesto'],
						'id_detalle' => $deuda['id_detalle'],

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

	}

?>