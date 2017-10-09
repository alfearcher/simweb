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
 *  @file BusquedaReferenciaBancariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-10-2017
 *
 *  @class BusquedaReferenciaBancariaForm
 *  @brief Clase Modelo del formulario que muestra las opciones de busqueda de las
 *  pre-referencias bancarias.
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

	namespace backend\models\ajuste\pago\prereferencia;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositodetalle\DepositoDetalle;
	use backend\models\pago\consulta\ConsultaGeneralPagoForm;
	use yii\helpers\ArrayHelper;
	use  backend\models\recibo\prereferencia\PreReferenciaPlanilla;
	use backend\models\utilidad\tipo\ajusteprereferencia\TipoAjustePreReferenciaBancaria;


	/**
	* Clase
	*/
	class BusquedaPreReferenciaBancariaForm extends ConsultaGeneralPagoForm
	{


		/**
		 * Metodo setter de la fecha desde.
		 * @param string $fechaDesde fecha inicial del rango de consulta.
		 */
		public function setFechaDesde($fechaDesde)
		{
			$this->fecha_desde = date('Y-m-d', strtotime($fechaDesde));
		}


		/**
		 * Metodo setter de la fecha hasta.
		 * @param string $fechaHasta fecha final del rango de consulta.
		 */
		public function setFechaHasta($fechaHasta)
		{
			$this->fecha_hasta = date('Y-m-d', strtotime($fechaHasta));
		}


		/**
		 * Metodo setter del identificador del banco.
		 * @param integer $idBanco identificador drl banco.
		 */
		public function setCodigoBanco($idBanco)
		{
			$this->codigo_banco = $idBanco;
		}


		/**
		 * Metodo setter de la cuenta recaudadora donde se realizó el ingreso.
		 * @param string $cuentaRecaudadora numero de cuenta recaudadora.
		 */
		public function setCuentaDeposito($cuentaRecaudadora)
		{
			$this->cuenta_deposito = $cuentaRecaudadora;
		}



		/**
		 * Metodo que retorna el modelo general de consulta de las pre-referncias.
		 * @return PreReferenciaPlanilla
		 */
		public function findPreReferenciaModel()
		{
			return PreReferenciaPlanilla::find()->alias('R');
		}




		/**
		 * Metodo que arma el modelo de la consulta sobre las pre-referencias
		 * @return PreReferenciaPlanilla
		 */
		public function armarConsultaModel()
		{
			$findModel = self::findPreReferenciaModel()->joinWith('depositoDetalle D', true, 'LEFT JOIN');
			if ( $this->recibo > 0 ) {
				$findModel->where('recibo =:recibo', [':recibo' => $this->recibo]);

			} elseif ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' ) {
				$this->fecha_desde = $this->formatFecha($this->fecha_desde);
				$this->fecha_hasta = $this->formatFecha($this->fecha_hasta);

				$findModel->where(['BETWEEN', 'R.fecha', $this->fecha_desde, $this->fecha_hasta]);

				if ( strlen($this->codigo_banco) > 0 ) {
					$findModel->andWhere('codigo_banco =:codigo_banco',
											[':codigo_banco' => $this->codigo_banco]);
				}

				if ( strlen($this->cuenta_deposito) > 0 ) {
					$findModel->andWhere('cuenta_deposito =:cuenta_deposito',
											[':cuenta_deposito' => $this->cuenta_deposito]);
				}
			}
			return $findModel->andWhere(['IN', 'R.estatus', [0,1]]);
		}



		/**
		 * Metodo que genera el dat provider para el reporte
		 * @return ActiveDataProvider
		 */
		public function getDataProvider()
		{
			$query = self::armarConsultaModel();
			//$query = self::findPreReferenciaModel();
			$dataProvider = New ActiveDataProvider([
				'key' => 'id_referencia',
				'query' => $query,
				'pagination' => false,
				// 'pagination' => [
				// 	'pageSize' => 100,
				// ],
			]);

			$query->all();

			$dataProvider->setSort([
				'attributes' => [
					'recibo' => [
						'asc' => ['recibo' => SORT_ASC],
						'desc' => ['recibo' => SORT_DESC],
					],
					'monto_recibo' => [
						'asc' => ['monto_recibo' => SORT_ASC],
						'desc' => ['monto_recibo' => SORT_DESC],
					],
					'serial_edocuenta' => [
						'asc' => ['serial_edocuenta' => SORT_ASC],
						'desc' => ['serial_edocuenta' => SORT_DESC],
					],
				],
			]);

			return $dataProvider;
		}

	}
?>