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
 *  @file InteresBcvSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-09-2015
 *
 *  @class InteresBcvSearch
 *  @brief Clase Modelo que permite realizar algunas consultas relacionas a los intereses.
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

	namespace backend\models\utilidad\bcv;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\bcv\InteresBcv;


	/**
	* Clase que permite determinar la cantidad de intereses de morosidad que
	* existe entre dos fechas.
	*/
	class InteresBcvSearch
	{

		private $_rango;
		private $_porcentajePorMes;










		/**
		 * Metodo que devuelve el monto por interes a plicar para el lapso de fechas.
		 * Metodo que incia el proceso.
		 * @param date  $fechaDesde fecha inicial del rango.
		 * @param date  $fechaHasta fecha final del rango.
		 * @param double $porcentaje porcentaje fijo que se debe aplicar. Ejemplo:
		 * - 0.1 (10%)
		 * - 0.01 (100%)
		 * @return double.
		 */
		public function armarRangoPorcentualInteres($fechaDesde, $fechaHasta, $porcentaje = 0)
		{
			self::calcularPorMes($fechaDesde, $fechaHasta, $porcentaje);
			return self::getRangoPorcentajes();
		}




		/***/
		public function getRangoPorcentajes()
		{
			return $this->_porcentajePorMes;
		}




		/**
		 * Metodo que arma un rango desde la fechaDesde hasta la fechaHasta
		 * @param date $fechaDesde fecha inicial del rango.
		 * @param date $fechaHasta fecha final del rango.
		 * @return array.
		 */
		public function crearCicloEntreFecha($fechaDesde, $fechaHasta)
		{
			$rango = [];
			$añoDesde = (int)date('Y', strtotime($fechaDesde));
			$añoHasta = (int)date('Y', strtotime($fechaHasta));
			$mesDesde = (int)date('m', strtotime($fechaDesde));
			$mesHasta = (int)date('m', strtotime($fechaHasta));

			for ( $i = $añoDesde; $i <= $añoHasta; $i++ ) {
				if ( $añoDesde == $añoHasta ) {
					$mesInicio = $mesDesde;
					$mesFinal = $mesHasta;

				} else {
					if ( $i == $añoDesde ) {
						$mesInicio = $mesDesde;
						$mesFinal = 12;

					} elseif ( $i == $añoHasta ) {
						$mesInicio = 1;
						$mesFinal = $mesHasta;

					} else {
						$mesInicio = 1;
						$mesFinal = 12;

					}
				}

				for ( $j = $mesInicio; $j <= $mesFinal; $j++ ) {
					$rango[] = [
						'a' => $i,
						'm' => $j,
					];
				}
			}

			return $rango;

		}



		/**
		 * Metodo que busca el interes a aplicar para el año-mes.
		 * @param integer $año año de consulta.
		 * @param  integer $mes mes de consulta.
		 * @return InteresBcv.
		 */
		private function findInteres($año, $mes)
		{
			$findModel = InteresBcv::find()->where('ano =:ano',[':ano' => $año])
										   ->andWhere('mes =:mes',[':mes' => $mes])
										   ->one();
			return $findModel;
		}




		/**
		 * Metodo que determina y arma un arreglo donde a cada año-mes, le asocia
		 * un porcentaje que se debe aplicar como porcentaje de interes.
		 * El arreglo tiene la siguiente estructura:
		 * array => {
		 * 	[] => [
		 *  	'a' => año,
		 *   	'm' => mes,
		 *    	'p' => porcentaje que se debe aplicar,
		 * 	]
		 * }
		 * @param date  $fechaDesde fecha inicial del rango.
		 * @param adte  $fechaHasta fecha final del rango.
		 * @param double $porcentaje porcentaje fijo que se deberia aplicar.
		 * @return array retorna un arreglo con los parametros, año-mes-porcentaje.
		 */
		private function calcularPorMes($fechaDesde, $fechaHasta, $porcentaje = 0)
		{
			$porc = 0;
			$rango = self::crearCicloEntreFecha($fechaDesde, $fechaHasta);

			if ( count($rango) > 0 ) {
				foreach ( $rango as $key => $r ) {
					if ( $porcentaje == 0 ) {
						$porc = self::getDeterminarPorcentaje((int)$r['a'], (int)$r['m']);
					} else {
						$porc = $porcentaje;
					}

					$this->_porcentajePorMes[] = [
							'a' => $r['a'],
							'm' => $r['m'],
							'p' => $porc,
					];
				}
			}

		}




		/**
		 * Metodo que permite determinar y calcular el porcentaje a aplicar
		 * para el año-mes.
		 * @param integer $año [description]
		 * @param integer $mes [description]
		 * @return double retorna el porcentaje que se aplicara en el año-mes.
		 */
		public function getDeterminarPorcentaje($año, $mes)
		{
			$añoLocal = 0;
			$mesLocal = 0;
			$porcentaje = 0;
			// Se debe realizar un ajuste en la consulta, ya que para un año-mes especifico
			// el porcentaje de interes a buscar sera el del laspo (año-mes anterior),
			// debido a que para el mes en curos no se puede obtener el porcentaje por ser un
			// mes no finalizado.

			if ( $mes == 1 ) {
				// Lapso anterior
				$añoLocal = $año - 1;
				$mesLocal = 12;
			} else {
				$añoLocal = $año;
				$mesLocal = $mes - 1;
			}

			$model = self::findInteres($añoLocal, $mesLocal);

			if ( $model !== null ) {
				if ( $model->factor_divisor > 0 ) {
					$porcentaje = number_format(( $model->interes_promedio / $model->factor_divisor ), 2);
				} else {
					$porcentaje = $model->interes_promedio;
				}
			}

			return $porcentaje;

		}


	}

?>