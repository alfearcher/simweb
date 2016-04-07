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
 *  @file PlanillaActividadEconomica.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-04-2016
 *
 *  @class PlanillaActividadEconomica
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
 	use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;
	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;
 	//use common\models\planilla\Pago;
 	use common\models\planilla\Planilla;

	/**
	* 	Clase
	*/
	class PlanillaActividadEconomica extends Planilla
	{
		private $_idContribuyente;
		private $_tipoDeclaracion;
		private $_añoDesde;
		private $_añoHasta;
		private $_periodoDesde;
		private $_periodoHasta;
		private $_ultimaLiquidacion;
		private $_fechaInicio;
		private $_montoCalculado;
		private $_montoTotal;
		private $_periodosLiquidados = [];



		/***/
		public function __construct($id)
		{
			$this->_idContribuyente = $id;
			$this->_periodosLiquidados = null;
		}



		/***/
		public function liquidarEstimada()
		{
			$this->setTipoDeclaracion("ESTIMADA");
			$this->configurarLapsoLiquidacionActividadEconomica();
			$result = $this->configurarCicloLiquidacion();
			$this->iniciarCicloLiquidacion($result);
//die(var_dump($result));
		}



		/***/
		private function iniciarCicloLiquidacion($cicloLiquidacion)
		{
			$montoCalculo = 0;
			if ( count($cicloLiquidacion) > 0 ) {
				foreach ( $cicloLiquidacion as $key => $value ) {
					$montoCalculado = 0;
					if ( strlen($key) == 4 && is_integer($key) ) {
						if ( is_array($value) ) {
							$año = $key;
							$periodos = $cicloLiquidacion[$año];

							$exigibilidadDeclaracion = self::getExigibilidadDeclaracion($año);
							$exigibilidadLiq = self::getExigibilidadLiquidacion($año);

							if ( $exigibilidadDeclaracion['exigibilidad'] == 1 ) {
								$montoCalculado = $this->liquidarDeclaracion($año, 1);
								if ( $montoCalculado > 0 ) {
									$this->generarPeriodosLiquidados($montoCalculado, $año, $periodos, $exigibilidadLiq, $exigibilidadDeclaracion);
die(var_dump($this->_periodosLiquidados));

								} else {
									// Abortar el proceso. Por no determinar monto. Renderizar vista
								}

							} elseif ( $exigibilidadDeclaracion['exigibilidad'] > 1 ) {
								foreach ( $periodos as $key => $value ) {
									$montoCalculado = $this->liquidarDeclaracion($año, $value);
									if ( $montoCalculado > 0 ) {
										$this->generarPeriodosLiquidados($montoCalculado, $año, $value, $exigibilidadLiq, $exigibilidadDeclaracion);

									} else {
										// Abortar el proceso. Por no determinar monto. Renderizar vista
									}
								}
							}
						} else {
							// Abortar la operacion. Renderizar a una vista.
						}
					} else {
						// Abortar la operacion. Renderizar a una vista.
					}
				}
			}
		}




		/***/
		public function liquidarDefinitiva($añoImpositivo, $periodo)
		{
			$this->setTipoDeclaracion("DEFINITIVA");
		}




		/**
		 * Metodo que setea la variable tipo declaracion, esta variable se utilizara en la clase
		 * "CalculoRubro", dicho valor representara al campo que se quiere utilizar en el calculo
		 * del impuesto, por ejemplo estimado, reales, rectificatoria, etc. Dicha variable representa
		 * a alguno de estos campos.
		 * @param String $tipo, represente a alguno de los siguientes campos a utilizar en los calculos
		 * estimado, reales, rectificatorio, auditoria, etc. Dicho campos se encuentran en la entidad
		 * "act-econ-ingresos".
		 */
		private function setTipoDeclaracion($tipo)
		{
			if ( $tipo == 'ESTIMADA' ) {
				$this->_tipoDeclaracion = 'estimado';
			} elseif ( $tipo == "DEFINITIVA" ) {
				$this->_tipoDeclaracion = 'reales';
			} elseif ( $tipo == 'RECTIFICATORIA' ) {
				$this->_tipoDeclaracion = 'rectificatoria';
			} elseif ( $tipo == 'SUSTITUTIVA' ) {
				$this->_tipoDeclaracion = 'sustitutiva';
			} elseif ( $tipo == 'AUDITORIA' ) {
				$this->_tipoDeclaracion = 'auditoria';
			}
		}



		/***/
		public function setAnoImpositivoDesde($año)
		{
			$this->_añoDesde = $año;
		}



		/***/
		public function setPeriodoDesde($periodo)
		{
			$this->_periodoDesde = $periodo;
		}



		/***/
		public function setAnoImpositivoHsta($año)
		{
			$this->_añoHasta = $año;
		}



		/***/
		public function setPeriodoHasta($periodo)
		{
			$this->_periodoHasta = $periodo;
		}



		/***/
		public function setLapsoPeriodo($añoDesde, $periodoDesde, $añoHasta, $periodoHasta)
		{
			$this->setAnoImpositivoDesde($añoDesde);
			$this->setPeriodoDesde($periodoDesde);
			$this->setAnoImpositivoHsta($añoHasta);
			$this->setPeriodoHasta($periodoHasta);
		}




		/***/
		public function getMontoCalculado()
		{
			return $this->_montoCalculado;
		}


		/***/
		private function setMontoCalculado($montoCalculado)
		{
			$this->_montoCalculado = $montoCalculado;
		}


		/***/
		public function getMontoTotal()
		{
			return $this->_montoTotal;
		}


		/***/
		private function setMontoTotal($montoTotal)
		{
			$this->_montoTotal = $montoTotal;
		}




		/**
		 * Metodo que se encarga se setear las variables que determinan el rango de liquidacion.
		 * las variables son 4 y permiten determiinar el "desde" y el"hasta" donde de debe liquidar.
		 * @return [type] [description]
		 */
		private function configurarLapsoLiquidacionActividadEconomica()
		{
			$ultimo = $this->getUltimaLiquidacion();
			if ( $ultimo == null ) {
				// Se determinara la primera liquidacion del contribuyente. Se requiere la fecha
				// de inicio de sus actividades.
				$this->_fechaInicio = ContribuyenteBase::getFechaInicio($this->_idContribuyente);
				if ( date($this->_fechaInicio) ) {
					$año = date('Y', strtotime($this->_fechaInicio));
					$año = (int) $año;

					$this->_añoDesde = self::getAnoDesde($año);

					if ( strlen(trim($this->_añoDesde)) == 4 ) {
						// El periodo dependera de la configuracion de la ordenanza segun el impuesto
						// y el año. Para este caso el impuesto es Actividad Economica y el año estara
						// definido por la fecha inicio ($this->_fechaInicio). Lo que se debe determinar
						// es la exigibilidad de liquidacion para el impuesto y año impositivo.
						$exigibilidadLiq = self::getExigibilidadLiquidacion($this->_añoDesde);
						$this->_periodoDesde = OrdenanzaBase::getPeriodoSegunFecha($exigibilidadLiq['exigibilidad'], $this->_fechaInicio);

						if ( $this->_periodoDesde == null ) {
							$this->anulacionRangoLiquidacion();

						} else {
							// Solo se permitira la liquidacion de un periodo. Condicion que puede cambiar
							// para futuros proyectos, es aqui donde se debe realizar el ajuste del año-periodo
							// final.
							$this->_añoHasta = $this->_añoDesde;
							$this->_periodoHasta = $this->_periodoDesde;
						}
					} else {
						$this->anulacionRangoLiquidacion();
					}

				} else {
					// No esta definida la fecha inicio del contribuyente. Aqui termina el proceso.
					$this->anulacionRangoLiquidacion();
				}
			} else {
				// No es la primera liquidacion, se debe determinar cual es el utlimo año-periodo
				// liquidado para continuar a partir desde el siguiente a este.

				$añoActual = date('Y');
				if ( $ultimo['ano_impositivo'] < $añoActual ) {

					$exigibilidadLiq = self::getExigibilidadLiquidacion($ultimo['ano_impositivo']);
					if ( $ultimo['trimestre'] == $exigibilidadLiq['exigibilidad'] ) {
						// Es indicativo que el ultimo periodo liquidado corresponde al ultimo periodo
						// del año.
						$this->_añoDesde = $ultimo['ano_impositivo'] + 1;
						$this->_añoHasta = $this->_añoDesde;
						$this->_periodoDesde = 1;
						$this->_periodoHasta = $this->_periodoDesde;

					} elseif ( $ultimo['trimestre'] < $exigibilidadLiq['exigibilidad'] ) {
						$this->_añoDesde = $ultimo['ano_impositivo'];
						$this->_añoHasta = $this->_añoDesde;
						$this->_periodoDesde = $ultimo['trimestre'] + 1;
						$this->_periodoHasta = $this->_periodoDesde;

					} else {
						$this->anulacionRangoLiquidacion();
					}

				} elseif ( $ultimo['ano_impositivo'] == $añoActual ) {

					$año = $ultimo['ano_impositivo'] + 1;
					$exigibilidadLiq = self::getExigibilidadLiquidacion($año);

					if ( $ultimo['trimestre'] == $exigibilidadLiq['exigibilidad'] ) {
						// No existen periodos por liquidar. Aqui debe finalizar el proceso.
						$this->anulacionRangoLiquidacion();

					} elseif ( $ultimo['trimestre'] < $exigibilidadLiq['exigibilidad'] ) {
						$this->_añoDesde = $ultimo['ano_impositivo'];
						$this->_añoHasta = $this->_añoDesde;
						$this->_periodoDesde = $ultimo['trimestre'] + 1;
						$this->_periodoHasta = $this->_periodoDesde;

					} else {
						$this->anulacionRangoLiquidacion();
					}
				} else {
					$this->anulacionRangoLiquidacion();
				}
			}
		}



		/***/
		private function getAnoDesde($añoImpositivo)
		{
			return OrdenanzaBase::determinarAnoDesde($añoImpositivo, 1);
		}




		/**
		 * Metodo que setea las variables que determinan el rango de liquidacion
		 * a un valor igual a "null". Cuando esto sucede es que no existen las condiciones
		 * para continuar con la liquidacion.
		 * @return Bollean true.
		 */
		public function anulacionRangoLiquidacion()
		{
			$this->_añoDesde = null;
			$this->_añoHasta = $this->_añoDesde;
			$this->_periodoDesde = null;
			$this->_periodoHasta = $this->_periodoDesde;
		}




		/**
		 * Metodo que controla y valida que los rango de inicio y finalizacion de la liquidacion
		 * este correcto.
		 * @return Boolean, Retorna true si es correcto y false si el rango esta errado.
		 */
		private function validarRangoLiquidacion()
		{
			if ( $this->_añoDesde == null || $this->_añoHasta == null || $this->_periodoDesde == null || $this->_añoDesde == null ) {
				return false;
			} else {
				if ( $this->_añoDesde > $this->_añoHasta ) {
					return false;
				} elseif ( $this->_añoDesde == $this->_añoHasta ) {
					if ( $this->_periodoDesde > $this->_periodoDesde ) {
						return false;
					}
				}
			}
			return true;
		}




		/**
		 * Metodo que permite obtener el registro de la ultima liquidacion que presenta (de haberla)
		 * el contribuyente, se obtiene entre otros datos la planilla y los detalles de la misma.
		 * @return Array, Retorna un array con los datos principales de la entidad "pagos" y la entidad
		 * "pagos-detalle".
		 */
		public function getUltimoLapsoActEcon()
		{
			$this->_ultimaLiquidacion = null;
			if ( $this->_idContribuyente > 0 ) {
				$model = $this->getUltimoLapsoActividadEconomica($this->_idContribuyente);
				// die(var_dump($model));
				if ( $model != null ) {
					return $this->_ultimaLiquidacion = isset($model) ? $model : null;
				}
			}
			return null;
		}




		/**
		 * Metodo que determina el ultimo registro liquidado, de haberlo.
		 * Si el metodo retorna null quiere decir que no existe periodos liquidados
		 * lo que indica que la liquidacion actual sera la primera.
		 * @return Array, Retorna un arreglo con los campos de la entidad "pagos-detalle"
		 * este registro es el ultimo encontrado, o sea el ultimo registro existente
		 * en la entidad segun los parametros de consulta.
		 */
		public function getUltimaLiquidacion()
		{
			$this->getUltimoLapsoActEcon();
			$detalle = null;
			if ( count($this->_ultimaLiquidacion) > 0 ) {
				$detalle = isset($this->_ultimaLiquidacion) ? $this->_ultimaLiquidacion : null;
			}
			return $detalle;
		}




		/**
		 * Metodo que arma un array con los año y periodos a liquidar,
		 * creando uin ciclo desde el inicio de la liquidacion hasta
		 * el final de la misma. El array es multidimensional por ejemplo:
		 * array(13) {
		 *	  [0]=>
		 *	  array(2) {
		 *	    [0]=>
		 *	    int(2009)
		 *	    [1]=>
		 *	    string(1) "6"
		 *	  }
		 *	  [1]=>
		 *	  array(2) {
		 *	    [0]=>
		 *	    int(2009)
		 *	    [1]=>
		 *	    int(7)
		 *	  }
		 * @return Array, Retorna un array multidimensional con el año y periodo. Si
		 * retorna null es porque no consiguio crear el ciclo de liquidacion.
		 */
		private function configurarCicloLiquidacion()
		{
			$ciclo = null;
			$periodos = null;
			if ( self::validarRangoLiquidacion() ) {
				for ( $i = $this->_añoDesde; $i <= $this->_añoHasta; $i++ ) {
					if ( $i == $this->_añoDesde ) {
						$periodoInicial = $this->_periodoDesde;
						if ( $this->_añoDesde == $this->_añoHasta ) {
							$periodoFinal = $periodoInicial;
						} else {
							$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];
						}

					} elseif ( ( $i > $this->_añoDesde ) && ( $i < $this->_añoHasta ) ) {
						$periodoInicial = 1;
						$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];

					} elseif ( ( $i > $this->_añoDesde ) && ( $i == $this->_añoHasta ) ) {
						$periodoInicial = 1;
						$periodoFinal = $this->_periodoHasta;

					}
					$ciclo[$i] = null;
					$periodos = null;
					for ( $j = $periodoInicial; $j <= $periodoFinal; $j++ ) {
						// Con lo siguiente se forma el rango de liquidacion
						//echo $i . ' - ' . $j;
						//echo '<br>';
						$periodos[] = $j;
					}
					$ciclo[$i] = $periodos;

					// Se manda el año con sus correspondientes periodos.
					//self::liquidarDeclaracion($i, $periodos);
				}
			}
			return $ciclo;
		}





		/***/
		public function LiquidarDeclaracion($año, $periodo)
		{
			$monto = 0;		// Calculo anual de la liquidacion.

			$liquidacion = New LiquidacionActividadEconomica($this->_idContribuyente);
			$liquidacion->iniciarCalcularLiquidacion($año, $periodo, $this->_tipoDeclaracion);
			$monto = $liquidacion->getCalculoAnual();
			$monto = number_format($monto, 2, '.', '');

			return $monto;
		}




		/***/
		private function generarPeriodosLiquidados($montoLiquidado, $año, $periodos, $exigibilidadLiq, $exigibilidadDeclaracion)
		{
			if ( $montoLiquidado == 0 || $montoLiquidado < 0 ) {
					// Se debe abortar el proceso por declaracion faltante o parametros en los calculos incompletos.
					// Renderizar a un vista.

			} elseif ( $montoLiquidado > 0 ) {
				$divisor = 0;
				// Lo siguiente retorna un arreglo con los datos de la planilla y el detalle de la misma
				// del primer periodo liquidado para el año ($año).
				$primerPeriodo = $this->getPrimerPeriodoLiquidadoActividadEconomica($this->_idContribuyente, $año);
				if ( count($primerPeriodo) > 0 ) {
					// Si el primer periodo encontrado es igual a 1 (trimestre = 1), el monto calculado
					// se divide entre la exigibilidad del año ($exigibilidadLiq['exigibilidad']), si el
					// primer periodo liquidado es mayor a uno se debe realizar un ajuste en la division
					// del monto liquidado del año. El ajuste sera la exigibilidad del año menos el primer
					// periodo encontrado más uno.
					$p = $primerPeriodo['trimestre'];
					if ( $p == 1 ) {
						$divisor = (int) $exigibilidadLiq['exigibilidad'];

					} elseif ( $p > 1 ) {
						$divisor = (int) ($exigibilidadLiq['exigibilidad'] - $p) + 1;
					}

				} else {
					// No posee periodos liquidados para el Año ($año) en consideracion. Es decir que sera la primera
					// liquidacion del año ($año).
					$divisor = (int) $exigibilidadLiq['exigibilidad'];

				}

				if ( $divisor > 0 ) {
					$montoPeriodo = number_format(($montoLiquidado/$divisor), 2, '.', '');

					$fechaActual = date('Y-m-d');
					if ( is_array($periodos) ) {
						foreach ( $periodos as $key => $value ) {
							$this->_periodosLiquidados[] = [$año, $value, $montoPeriodo, $fechaActual];
						}
					} elseif ( is_integer($periodos) )  {
						$this->_periodosLiquidados[] = [$año, $periodos, $montoPeriodo, $fechaActual];
					}

				} else {
					// Ha ocurrido un error al intentar obtener la exigibilidad de la liquidacion.
					// Renderizar a un vista.

				}
			}
		}






		/**
		 * Metodo que obtiene un array con la informacion de la entidad "exigibilidades".
		 * Para acceder a la informacion de las exigibilidad, segun la liquidacion o
		 * declaracion.
		 * @param  Integer $año, Año impositivo de 4 digito.
		 * @return Array, retorna un array de los campos con la entidad "exigibilidades".
		 */
		private function getExigibilidadDeclaracion($año)
		{
			return OrdenanzaBase::getExigibilidadDeclaracion($año, 1);
		}



		/**
		 * Metodo que obtiene un array con la informacion de la entidad "exigibilidades".
		 * Para acceder a la informacion de las exigibilidad, segun la liquidacion o
		 * declaracion.
		 * @param  Integer $año, Año impositivo de 4 digito.
		 * @return Array, retorna un array de los campos con la entidad "exigibilidades".
		 */
		private function getExigibilidadLiquidacion($año)
		{
			return OrdenanzaBase::getExigibilidadLiquidacion($año, 1);
		}


	}

?>