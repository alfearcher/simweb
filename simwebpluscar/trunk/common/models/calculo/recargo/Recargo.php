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
 *  @file Recargo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-10-2016
 *
 *  @class Recargo
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

	namespace common\models\calculo\recargo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\ordenanza\OrdenanzaAsignacion;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\utilidad\ut\UnidadTributariaForm;



	/**
	* Clase que realiza el calculo de los recargos que se aplicaran a un periodo
	* por concepto de mora.
	* Se recibe un año-periodo (lapso) y segun la ordenanza que le correponda segun el año
	* se determina si dicho lapso esta vencido. Si es asi se aplica el recargo correspondiente
	* al monto del lapso.
	* tipo asignacion:
	* 1.-	Recargo.
	* 2.-	Interes.
	*/
	class Recargo
	{

		private $_año_impositivo;
		private $_periodo;
		private $_monto;
		private $_impuesto;
		private $_id_ordenanza = 0;
		private $_tipoAsignacion = 0;
		private $_exigibilidadLiq = [];
		private $_recargo = 0;

		/**
		 * Configuracion de la ordenanza y los parametros por mora.
		 * @var array
		 */
		private $_configOrdAsignacion = [];

		private $_lapso_vencido = false;


		/**
		 * Metodo constructor de la clase
		 * @param integer $impuesto identificador del impuesto.
		 */
		public function __construct($impuesto)
		{
			$this->_impuesto = $impuesto;
		}



		/**
		 * Metodo que inicia el proceso de calculo del recargo.
		 * @param integer $añoImpositivo año del lapso al cual se le calculara
		 * el recargo.
		 * @param integer $periodo periodo del lapso al cual se le calculara
		 * el recargo.
		 * @param double $monto monto al cual se le calculara el recargo.
		 * @return double retorna el monto calculado por el recargo.
		 */
		public function calcularRecargo($añoImpositivo, $periodo, $monto)
		{
			$this->_año_impositivo = $añoImpositivo;
			$this->_periodo = $periodo;
			$this->_monto = $monto;
			$recargo = 0;
			if ( $this->_año_impositivo > 0 && $this->_periodo > 0  && $this->_impuesto > 0 ) {
				$recargo = self::IniciarCalculoRecargo();
			}

			$this->_recargo = $recargo;
			return $recargo;
		}


		/***/
		public function getRecargo()
		{
			return $this->_recargo;
		}




		/**
		 * Metodo que busca el identificador de la ordenanza y setea la vatiable
		 * que contiene la informacion del mismo.
		 * @return no retorna.
		 */
		private function getOrdenanza()
		{
			$ordenanza = [];
			// Se obtiene el identificador de la ordenanza segun el año y el impuesto.
			$ordenanza = OrdenanzaBase::getIdOrdenanza($this->_año_impositivo, $this->_impuesto);
			if ( count($ordenanza) > 0 && $ordenanza !== false ) {
				$this->_id_ordenanza = $ordenanza['id_ordenanza'];
			}
		}




		/**
		 * Metodo que permite obtener los parametros de ls exigibilidad segun
		 * el año impositivo y el impuesto. Esto permite obtener un array con
		 * los atributos de la entidad "exigibilidades".
		 * @return no returna.
		 */
		private function getExigibilidadLiquidacion()
		{
			$exigibilidad = [];
			$this->_exigibilidadLiq = [];
			$exigibilidad = OrdenanzaBase::getExigibilidadLiquidacion($this->_año_impositivo, $this->_impuesto);
			if ( count($exigibilidad) > 0 && $exigibilidad !== false ) {
				$this->_exigibilidadLiq = $exigibilidad;
			}
		}




		/**
		 * Metodo que retorna el modelo de consulta de configuracion de la aplicacion
		 * de los recargos
		 * @return active record o un arreglo vacio.
		 */
		private function findConfiguracion()
		{
			if ( $this->_id_ordenanza > 0 ) {
				// Se busca la configuracion que se aplicara al proceso del calculo
				// del recargo.
				$findModel = OrdenanzaAsignacion::find()->where('id_ordenanza =:id_ordenanza',
																[':id_ordenanza' => $this->_id_ordenanza])
														->andWhere('tipo_asignacion >:tipo_asignacion',
																[':tipo_asignacion' => 0])
														->andWhere('id_asignacion =:id_asignacion',
																[':id_asignacion' => 1])
														->andWhere('impuesto =:impuesto',
																[':impuesto' => $this->_impuesto])
														->andWhere('periodo =:periodo',
																[':periodo' => $this->_periodo]);
			}

			return ( isset($findModel) > 0 ) ? $findModel : [];
		}




		/**
		 * Metodo que permite buscar los parametros de configuracion que se
		 * hizo, estos valores se tomaran para definir como se debe aplicar la
		 * logica del recargo.
		 * @return no retorna.
		 */
		private function getConfiguracion()
		{
			$this->_configOrdAsignacion = [];
			$findModel = self::findConfiguracion();
			$config = $findModel->all();
			if ( count($config) > 0 ) {
				$this->_configOrdAsignacion = $config;
			}
		}





		/**
		 * Metodo que inicia el proceso para calcular el recargo
		 * @return double retorna el monto calculado.
		 */
		private function IniciarCalculoRecargo()
		{
			self::getOrdenanza();
			self::getConfiguracion();
			self::getExigibilidadLiquidacion();
			$fechaActual = date('Y-m-d');
			$montoRecargo = 0;
			$meses = 0;
			$dias = 0;
			$añoActual = (int)date('Y');

			if ( count($this->_configOrdAsignacion) > 0 ) {
				if ( $this->_id_ordenanza > 0 && count($this->_exigibilidadLiq) > 0 ) {

					$fechaInicioPeriodo = self::getFechaInicioPeriodo();
					$periodoActual = OrdenanzaBase::getPeriodoSegunFecha($this->_exigibilidadLiq['exigibilidad'], $fechaActual);

					if ( $this->_configOrdAsignacion['id_aplicacion'] == 1 ) {
						// Al N mes del primer mes del periodo
						// ------------------------------------------------------
						// Contar los N meses a partir del primer mes del periodo
						// sin tomar este en el conteo.

						if ( $fechaInicioPeriodo !== '' ) {

							// Cantidad de meses que se tienen para pagar el periodo.
							$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

							// Cantidad de meses entre las fechas.
							$interval = date_diff($fechaInicioPeriodo, $fechaActual);
							$cantMeses = $interval->{'m'};

							if ( $cantMeses > $meses ) {
								$montoRecargo = self::determinarMontoRecargo();
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 2 ) {
						// Al vencer el periodo.
						// ---------------------------------------------------------
						// Al mes siguiente del ultimo mes de cada periodo.

						if ( $fechaInicioPeriodo !== '' ) {
							if ( $this->_año_impositivo < $añoActual ) {

								$montoRecargo = self::determinarMontoRecargo();

							} elseif ( $this->_año_impositivo == $añoActual ) {
								if ( $periodoActual > $this->_periodo ) {

									$montoRecargo = self::determinarMontoRecargo();
								}
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 3 ) {
						// A los N dias de cada mes.
						// ------------------------------------------------------------
						// La penalización se aplica transcurrido los N dias de cada mes
						// incluido el 1er mes de cada periodo tambien.

						// Cantidad de dias que se tienen para pagar el periodo.
						$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

						// Cantidad de dias entre las fechas.
						$interval = date_diff($fechaInicioPeriodo, $fechaActual);
						$cantDias = $interval->{'days'};

						if ( $cantDias > $dias ) {
							$montoRecargo = self::determinarMontoRecargo();
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 4 ) {
						// N meses a partir del 1er mes del periodo x N dias de ese mes.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para ese mes(1ero de cada periodo).

						// Cantidad de meses que se tienen para pagar el periodo.
						$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

						// Cantidad de dias que se tienen para pagar el periodo.
						$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

						$interval = date_diff($fechaInicioPeriodo, $fechaActual);
						$cantMeses = $interval->{'m'};
						$cantDias = $interval->{'d'}

						if ( $cantMeses > $meses ) {
							$montoRecargo = self::determinarMontoRecargo();

						} elseif ( $cantMeses == $meses ) {
							if ( $cantDias > $dias ) {
								$montoRecargo = self::determinarMontoRecargo();

							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 5 ) {
						// N meses a partir del 1er mes del periodo x N dias de todos
						// los meses.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para todos los meses que vienen.


						// Cantidad de meses que se tienen para pagar el periodo.
						$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

						// Cantidad de dias que se tienen para pagar el periodo.
						$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

						$interval = date_diff($fechaInicioPeriodo, $fechaActual);
						$cantMeses = $interval->{'m'};
						$cantDias = $interval->{'d'}

						if ( $cantMeses > $meses ) {
							$montoRecargo = self::determinarMontoRecargo();

						} elseif ( $cantMeses == $meses ) {
							if ( $cantDias > $dias ) {
								$montoRecargo = self::determinarMontoRecargo();

							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 9 ) {
						// Despues de transcurrido N dias del periodo.
						// --------------------------------------------------------------
						// Contar a partir del primer dia del 1er mes de cada periodo.

						// Cantidad de dias que se tienen para pagar el periodo.
						$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

						// Cantidad de dias entre las fechas.
						$interval = date_diff($fechaInicioPeriodo, $fechaActual);
						$cantDias = $interval->{'days'};

						if ( $cantDias > $dias ) {
							$montoRecargo = self::determinarMontoRecargo();
						}
					}

				}
			}

			return $montoRecargo;
		}




		/**
		 * Metodo que permite rutear a la logica que se aplicara para determinar
		 * el monto por recargo.
		 * @return double retorna monto del recargo segun la logica aplicada.
		 * Sino determina la logica retornara cero (0).
		 */
		private function determinarMontoRecargo()
		{
			$montoRecargo = 0;
			if ( $this->_configOrdAsignacion['tipo_asignacion'] == 1 ) {
				$montoRecargo = self::aplicarMontoFijo();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 2 ) {
				$montoRecargo = self::aplicarUnidadTributaria();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 3 ) {
				$montoRecargo = self::aplicarMontoFijo();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 9 ) {
				$montoRecargo = self::aplicarPorcentajeBCV();

			}

			return $montoRecargo;
		}



		/**
		 * Metodo que aplica el calculo de recargo, segun la politica de porcentaje
		 * fijo.
		 * @return double retorna monto del recargo a aplicar por porcentaje fijo.
		 */
		private function aplicarPorcentajeFijo()
		{
			return $monto = $this->_monto * ( $this->_configOrdAsignacion['monto'] / 100 );
		}



		/**
		 * Metodo que aplica el calculo de recargo, segun la politica de buscar la unidad
		 * tributaria del año especifico.
		 * @return double retorna monto del recargo.
		 */
		private function aplicarUnidadTributaria()
		{
			$montoUT = 0;
			$ut = New UnidadTributariaForm();
			$montoUT = $ut->getUnidadTributaria($this->_año_impositivo);

			return $monto = $montoUT * ( $this->_configOrdAsignacion['monto'] );
		}



		/**
		 * Metodo que aplica el calculo de recargo, segun la politica de aplicar un
		 * monto fijo.
		 * @return double retorna monto del recargo.
		 */
		private function aplicarMontoFijo()
		{
			return $monto = ( $this->_configOrdAsignacion['monto'] );
		}



		/**
		 * Metodo que aplica el calculo de recargo, segun la politica de aplicar un
		 * porcentaje definido por el Banco Central. Se tomara el mes anterior al mes
		 * de vencimiento del plazo para pagar el periodo.
		 * @return double retorna monto del recargo.
		 */
		private function aplicarPorcentajeBCV()
		{
			return 0;
		}



		/**
		 * Metodo que determina la fecha de inicio de un periodo, segun la exigibilidad
		 * de liquidacion.
		 * @return string retorna fecha.
		 */
		private function getFechaInicioPeriodo()
		{
			return $fecha = OrdenanzaBase::getFechaInicioSegunPeriodo($this->_año_impositivo, $this->_periodo,
					        										  $this->_exigibilidadLiq['exigibilidad']);

		}



	}

?>