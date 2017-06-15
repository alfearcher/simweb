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
 *  @file Solvente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-05-2016
 *
 *  @class Solvente
 *  @brief Clase Modelo principal
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

 	namespace common\models\deuda;

 	use Yii;
 	use yii\db\Exception;
 	use common\models\ordenanza\OrdenanzaBase;
 	use common\models\planilla\Planilla;
 	use common\models\pago\PagoSearch;
 	use backend\models\vehiculo\VehiculosForm;



	/**
	 * Clase que permite determnmar si un contribuyente u objeto se encuentra solvente
	 */
	class Solvente
	{

		private $impuesto;				// Identificador del impuesto.
		private $idImpuesto;			// Identificador del objeto ( execto para impuesto = 1).
		private $_id_contribuyente;		// Identificador del Contribuyente.
		private $añoImpositivo;
		private $periodo;

		/**
		 * Contructor de la clase
		 * @param long $id, Identificador del contribuyente
		 */
		public function __construct()
		{
		}


		/**
		 * Metodo que setea el valor de la variable impuestos
		 * @param Integer $imp identificador del impuesto.
		 */
		public function setImpuesto($imp)
		{
			$this->impuesto = $imp;
		}


		/**
		 * Metodo que setea el valor de la varible idImpuesto. Identificador del objeto.
		 * @param Long $idObjeto identificador del objeto imponible.
		 */
		public function setIdImpuesto($idObjeto)
		{
			$this->idImpuesto = $idObjeto;
		}


		/***/
		public function setIdContribuyente($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}


		/**
		 * Metodo que inicia la consulta para determinar si un objeto esta solvente
		 * segun el impuesto, y la ordenanza respectiva.
		 * @return Boolean Retorna True si esta solvente, en caso cobtrario retorna false.
		 */
		public function determinarSolvencia()
		{
			$result = false;
			if ( isset($this->impuesto) && isset($this->idImpuesto) ) {
				if ( $this->impuesto > 0 && $this->idImpuesto > 0 ) {
					$result = self::verificarCondicionObjeto();
				}
			}
			return $result;
		}



		/**
		 * Metodo que permite obtener el ultimo registro del objeto, en la
		 * entidad "pagos-detalle".
		 * @return Array Retorna un arreglo de datos con la informacion referente
		 * al ultimo periodo existente del objeto segun el impuesto. Sino encuentra nada
		 * retorna NULL.
		 */
		private function getUltimoPeriodoObjeto()
		{
			$ultimoPeriodo = null;
			$ultimo = null;
			$planilla = New Planilla();
			$ultimo = $planilla->getUltimoPeriodoLiquidadoObjeto($this->idImpuesto, $this->impuesto);
			if ( count($ultimo) > 0 ) {
				$ultimoPeriodo['ano_impositivo'] = $ultimo['ano_impositivo'];
				$ultimoPeriodo['trimestre'] = $ultimo['trimestre'];
				$ultimoPeriodo['exigibilidad_pago'] = $ultimo['exigibilidad_pago'];
				$ultimoPeriodo['pago'] = $ultimo['pago'];
			}
			return $ultimoPeriodo;
		}



		/**
		 * Metodo que permite obtener el periodo actual donde estamos segun el Año y el Impuesto.
		 * @return Array Retorna un arreglo de datos, con la informacion referente al periodo actual,
		 * aqui periodo actual se refiere al esquema Año-periodo, donde periodo puede ser mes, bimestre,
		 * trimestre, etc. Sino encuentra nada retorna NULL.
		 */
		public function getPeriodoActualSegunOrdenanza()
		{
			$lapso = null;
			$año = date('Y');
			$fechaActual = date('Y-m-d');
			$ordenanza = New OrdenanzaBase();

			// Lo siguiente se refiere a la exigibilidad de liquidacion.
			$exigibilidad = $ordenanza->getExigibilidadLiquidacion($año, $this->impuesto);
			if ( count($exigibilidad) > 0 ) {
				$lapso['año'] = $año;
				$lapso['periodo'] = $ordenanza->getPeriodoSegunFecha($exigibilidad['exigibilidad'], $fechaActual);
			}

			return $lapso;
		}



		/**
		 * Metodo que verifica y determina con la informacion de la ordenanza segun el impuesto-año
		 * y con la informacion del ultimo regitro liquidado, si el objeto esta Solvente o Insolvente.
		 * @return Boolean Retorna true si esta solvente, false en caso contrario.
		 */
		private function verificarCondicionObjeto()
		{
			$result = false;

			$ultimoPeriodo = self::getUltimoPeriodoObjeto();
			if ( count($ultimoPeriodo) > 0 ) {
				$periodoActual = self::getPeriodoActualSegunOrdenanza();
				if ( count($periodoActual) > 0 ) {
					if ( $ultimoPeriodo['ano_impositivo'] == $periodoActual['año'] ) {
						if ( $ultimoPeriodo['trimestre'] == $periodoActual['periodo'] ) {

							if ( $ultimoPeriodo['pago'] !== 9 && $ultimoPeriodo['pago'] > 0 ) { $result = true; }

						} elseif ( $ultimoPeriodo['trimestre'] > $periodoActual['periodo'] ) {

							if ( $ultimoPeriodo['pago'] !== 9 && $ultimoPeriodo['pago'] > 0 ) { $result = true; }

						} else {
							// Consideracion especial cuando $ultimoPeriodo['trimestre'] = $periodoActual['periodo'] - 1
							// en algunas Alcaldias.
						}
					} elseif ( $ultimoPeriodo['ano_impositivo'] > $periodoActual['año'] ) {

						if ( $ultimoPeriodo['pago'] !== 9 && $ultimoPeriodo['pago'] > 0 ) { $result = true; }

					} elseif ( $ultimoPeriodo['ano_impositivo'] == date('Y') - 1 ) {
						// Consideracion especial cuando $ultimoPeriodo['trimestre'] = $periodoActual['periodo'] - 1.
						// en algunas Alcaldias.
						if ( $ultimoPeriodo['trimestre'] == $ultimoPeriodo['exigibilidad_pago'] ) {
							if ( $ultimoPeriodo['pago'] !== 9 && $ultimoPeriodo['pago'] > 0 ) {

								if ( date('m') == 1 ) { $result = true; }

							}
						}
					}
				}
			}

			return $result;
		}



		/***/
		public function getUltimoPeriodoActividadEconomica()
		{
			$search = New PagoSearch();
			$search->setIdContribuyente($this->_id_contribuyente);
			$search->setImpuesto(1);

			$lapso = null;
			// Lo siguiente retorna un arreglo.
			$pagos = $search->getUltimoLapsoPagoActividadEconomica();

			if ( $pagos !== null ) {
				$lapso['a'] = $pagos['ano_impositivo'];
				$lapso['p'] = $pagos['trimestre'];
				$lapso['exigibilidad'] = $pagos['exigibilidad_pago'];
			}

			return $lapso;

		}



		/***/
		public function getFechaVctoSolvenciaActividadEconomica()
		{
			$fechaVcto = '';
			$ultimoPago = self::getUltimoPeriodoActividadEconomica();
			if ( $ultimoPago !== null ) {
				$fechaVcto = OrdenanzaBase::getFechaVencimientoLapso($ultimoPago['a'], $ultimoPago['p'], 1);
			}
			return $fechaVcto;
		}




		/***/
		public function getUltimoPeriodoPagoObjeto()
		{
			$search = New PagoSearch();
			$search->setIdImpuesto($this->idImpuesto);
			$search->setImpuesto($this->impuesto);

			$lapso = null;
			// Lo siguiente retorna un arreglo.
			$pagos = $search->getUltimoLapsoPagoObjeto($this->impuesto, $this->idImpuesto);

			if ( $pagos !== null ) {
				$lapso['a'] = $pagos['ano_impositivo'];
				$lapso['p'] = $pagos['trimestre'];
				$lapso['exigibilidad'] = $pagos['exigibilidad_pago'];
			}

			return $lapso;

		}




		/***/
		public function getFechaVctoSolvenciaObjeto()
		{
			$fechaVcto = '';
			$ultimoPago = self::getUltimoPeriodoPagoObjeto();
			if ( $ultimoPago !== null ) {
				$fechaVcto = OrdenanzaBase::getFechaVencimientoLapso($ultimoPago['a'], $ultimoPago['p'], 1);
			}
			return $fechaVcto;
		}





		/***/
		public function estaSolventeActividadEconomica()
		{
			$result = false;

			$ultimoPeriodo = self::getUltimoPeriodoActividadEconomica();
			if ( count($ultimoPeriodo) > 0 ) {
				$periodoActual = self::getPeriodoActualSegunOrdenanza();
				if ( count($periodoActual) > 0 ) {
					if ( $ultimoPeriodo['a'] == $periodoActual['año'] ) {

						if ( $ultimoPeriodo['p'] == $periodoActual['periodo'] ) {
							$result = true;

						} elseif ( $ultimoPeriodo['p'] > $periodoActual['periodo'] ) {
							$result = true;

						}

					} elseif ( $ultimoPeriodo['a'] > $periodoActual['año'] ) {

						$result = true;

					} elseif ( $ultimoPeriodo['a'] == date('Y') - 1 ) {
						if ( $ultimoPeriodo['p'] == $ultimoPeriodo['exigibilidad'] ) {
							if ( date('m') == 1 ) { $result = true; }
						}
					}
				}
			}

			return $result;
		}





		/***/
		public function getListaObjetoContribuyente()
		{
			$listaObjeto = [];

			if ( $this->impuesto == 2 ) {			// Lista de inmuebles.

			} elseif ( $this->impuesto == 3 ) {		// Lista de Vehiculos.

				$result = VehiculosForm::find()->where('status_vehiculo =:status_vehiculo',
															['status_vehiculo' => 0])
											   ->andWhere('id_contribuyente =:id_contribuyente',
											  				['id_contribuyente' => $this->_id_contribuyente])
											   ->asArray()
											   ->all();
			}

			return $result;
		}



	}
 ?>