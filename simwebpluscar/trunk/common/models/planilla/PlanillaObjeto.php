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
 *  @file PlanillaObjeto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-04-2016
 *
 *  @class PlanillaObjeto
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
 	use common\models\planilla\Planilla;
 	use backend\models\aaee\declaracion\Declaracion;



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
		public $conexion;			// Instancia de tipo ConexionController,
		 							// permite ejecutar los metodo para guardar.
		public $conn;				// Instancia de conexion de la db.



		/***/
		public function __construct($id, $conexionLocal, $connLocal)
		{
			$this->_idContribuyente = $id;
			$this->_periodosLiquidados = null;
			$this->conexion = $conexionLocal;
			$this->conn = $connLocal;
		}



		/**
		 * Metodo que inicia el proceso de la liquidacion estimada.
		 * @return Array Retorna un arreglo multi-dimensional de los periodos liquidados.
		 * De lo contrario retorna false.
		 */
		public function liquidarEstimada()
		{
			if ( isset($this->conexion) && isset($this->conn) ) {
				$this->setTipoDeclaracion("ESTIMADA");
				$this->configurarLapsoLiquidacionActividadEconomica();
				$ciclo = self::configurarCicloLiquidacion();
				if ( $ciclo != null ) {
					// Lo siguente retorna un array multi-dimensional, donde el indice de este array, son los años
					// impositivo que tienen periodos por liquidar. Los elementos del array corresponde
					// a otro array, donde los indices son enteros empezando por el cero (0) y los elementos
					// del mismo son los campos (modelo de la clase PagoDetalle) de la entidad que tiene los
					// detalleas de la planilla.
					$result = self::iniciarCicloLiquidacion($ciclo);
					if ( $result != null ) {
						return $this->iniciarGuadarPlanilla($this->conexion, $this->conn, $this->_idContribuyente, $result);
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}



		/**
		 * [iniciarCicloLiquidacion description]
		 * @param  [type] $cicloLiquidacion [description]
		 * @return [type]                   [description]
		 */
		private function iniciarCicloLiquidacion($cicloLiquidacion)
		{
			// Array de periodos liquidados, donde el indice del array es el año impositivo
			// y los elemento del array corresponde al modelo de la entidad "pagos-detalle".
			$periodosLiq;

			// Monto anual de la liquidacion.
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
									$periodosLiq[$año] = self::generarPeriodosLiquidados($montoCalculado, $año,
									                                                     $periodos, $exigibilidadLiq,
									                                                     $exigibilidadDeclaracion);
									if ( !isset($periodosLiq[$año]) ) {
										return null;
									}

								} else {
									// Abortar el proceso. Por no determinar monto. Renderizar vista
									return null;
								}

							} elseif ( $exigibilidadDeclaracion['exigibilidad'] > 1 ) {
								foreach ( $periodos as $key => $value ) {
									$montoCalculado = $this->liquidarDeclaracion($año, $value);
									if ( $montoCalculado > 0 ) {
										$periodosLiq[$año] = self::generarPeriodosLiquidados($montoCalculado, $año, $value, $exigibilidadLiq, $exigibilidadDeclaracion);
										if ( !isset($periodosLiq[$año]) ) {
											return null;
										}

									} else {
										// Abortar el proceso. Por no determinar monto. Renderizar vista
										return null;
									}
								}
							}
						} else {
							// Abortar la operacion. No se pudo determinar los periodos. Renderizar a una vista.
							return null;
						}
					} else {
						// Abortar la operacion. No se pudo determinar el año. Renderizar a una vista.
						return null;
					}
				}
			}
			return $periodosLiq;
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
			$añoActual = date('Y');
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
							// Se define el rango final de la liquidacion del contribuyente.
							if ( $this->_añoDesde == $añoActual ) {
								$this->_añoHasta = $añoActual;
								$this->_periodoHasta = $exigibilidadLiq['exigibilidad'];

							} elseif ( $this->_añoDesde < $añoActual ) {
								$this->_añoHasta = $añoActual;
								$this->_periodoHasta = $exigibilidadLiq['exigibilidad'];

							} else {
								$this->anulacionRangoLiquidacion();
							}
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

				if ( $ultimo['ano_impositivo'] < $añoActual ) {

					$exigibilidadLiq = self::getExigibilidadLiquidacion($ultimo['ano_impositivo']);
					if ( $ultimo['trimestre'] == $exigibilidadLiq['exigibilidad'] ) {
						// Es indicativo que el ultimo periodo liquidado corresponde al ultimo periodo
						// del año.
						$this->_añoDesde = $ultimo['ano_impositivo'] + 1;
						$this->_añoHasta = $añoActual;
						$this->_periodoDesde = 1;
						$this->_periodoHasta = $exigibilidadLiq['exigibilidad'];

					} elseif ( $ultimo['trimestre'] < $exigibilidadLiq['exigibilidad'] ) {
						$this->_añoDesde = $ultimo['ano_impositivo'];
						$this->_añoHasta = $añoActual;
						$this->_periodoDesde = $ultimo['trimestre'] + 1;
						$this->_periodoHasta = $exigibilidadLiq['exigibilidad'];

					} else {
						$this->anulacionRangoLiquidacion();
					}

				} elseif ( $ultimo['ano_impositivo'] == $añoActual ) {

					$exigibilidadLiq = self::getExigibilidadLiquidacion($añoActual);

					if ( $ultimo['trimestre'] == $exigibilidadLiq['exigibilidad'] ) {
						// No existen periodos por liquidar. Aqui debe finalizar el proceso.
						$this->anulacionRangoLiquidacion();

					} elseif ( $ultimo['trimestre'] < $exigibilidadLiq['exigibilidad'] ) {
						$this->_añoDesde = $ultimo['ano_impositivo'];
						$this->_añoHasta = $añoActual;
						$this->_periodoDesde = $ultimo['trimestre'] + 1;
						$this->_periodoHasta = $exigibilidadLiq['exigibilidad'];

					} else {
						$this->anulacionRangoLiquidacion();
					}
				} else {
					$this->anulacionRangoLiquidacion();
				}
			}
		}



		/**
		 * Metodo que permite buscar o determinar el año de inicio de la liquidacion estimada
		 * segun la configuracion de la ordenanza, tomando como parametro el año impositivo
		 * especifico.
		 * @param  Integer $añoImpositivo, expresion de 4 digitos.
		 * @return Integer Retorna año de 4 digitos.
		 */
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
			if ( $this->_añoDesde == null || $this->_añoHasta == null || $this->_periodoDesde == null || $this->_añoHasta == null ) {
				return false;
			} else {
				if ( $this->_añoDesde > $this->_añoHasta ) {
					return false;
				} elseif ( $this->_añoDesde == $this->_añoHasta ) {
					if ( $this->_periodoDesde > $this->_periodoHasta ) {
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
				// Metodo de la clase Planilla().
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
							//$periodoFinal = $periodoInicial;
							$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];
						} else {
							$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];
						}

					} elseif ( ( $i > $this->_añoDesde ) && ( $i < $this->_añoHasta ) ) {
						$periodoInicial = 1;
						$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];

					} elseif ( ( $i > $this->_añoDesde ) && ( $i == $this->_añoHasta ) ) {
						$periodoInicial = 1;
						//$periodoFinal = $this->_periodoHasta;
						$periodoFinal = self::getExigibilidadLiquidacion($i)['exigibilidad'];

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
				}
			}
			return $ciclo;
		}





		/**
		 * Metodo que instancia la clase que permite realizar los calculos para liquidar
		 * la declaracion segun el año y el periodo. Para complementar los parametros
		 * se envia el tipo de declaracion a la cual se le realizara la liquidacion.
		 * El monto ($monto), represntara lo liquidado para un año especifico si la
		 * exigibilidad de la declaracion es uno (1), si la exigibilidad de la declaracion
		 * es mayor a uno, el monto calculado representara a la declaracion de dicho periodo
		 * exclusivamente. Ejemplo 2010-1 o 2010-2...
		 * @param Integer $año, expresion de 4 digitos, que representa el año impositivo.
		 * @param Integer $periodo, expresion de minimo un digito y maximo 2 digitos.
		 * @return Double $monto, Retorna el monto liquidado de la declaracion.
		 */
		public function LiquidarDeclaracion($año, $periodo)
		{
			$monto = 0;		// Calculo anual de la liquidacion.

			$liquidacion = New LiquidacionActividadEconomica($this->_idContribuyente);
			$liquidacion->iniciarCalcularLiquidacion($año, $periodo, $this->_tipoDeclaracion);
			$monto = $liquidacion->getCalculoAnual();
			$monto = number_format($monto, 2, '.', '');

			return $monto;
		}




		/**
		 * Metodo que distribuye el monto calculado de la liquidacion entre los periodos
		 * respectivo del año. Si el primer periodo de la liquidacion es mayor a cero
		 * se debe realizar un ajuste en la distribucion del monto entre los periodos.
		 * @param  Double $montoLiquidado, Calculo de la liquidacion.
		 * @param  Integer $año, expresion de 4 digitos que representa el año impositivo.
		 * @param  Array|Interger $periodos, periodos o periodo de la liquidacion.
		 * @param  Array $exigibilidadLiq, determina la cantidad de periodos que deben
		 * ser liquidados en un año. Es un arreglo de la entidad "exigibilidades".
		 * @param  Array $exigibilidadDeclaracion, determina la cantidad de declaraciones
		 * que se deben realizar en un año. Es un arreglo de la entidad "exigibilidades"
		 * @return Array Retorna un arreglo de la entidad "pagos-detalle".
		 */
		private function generarPeriodosLiquidados($montoLiquidado, $año, $periodos, $exigibilidadLiq, $exigibilidadDeclaracion)
		{
			if ( $montoLiquidado == 0 || $montoLiquidado < 0 ) {
					// Se debe abortar el proceso por declaracion faltante o parametros en los calculos incompletos.
					// Renderizar a un vista.

			} elseif ( $montoLiquidado > 0 ) {
				$fechaActual = date('Y-m-d');
				$fechaVcto = $this->getUltimoDiaMes($fechaActual);

				$modelDetalle = New PagoDetalle();

				$arregloDetalle = array_values($modelDetalle->attributes());

				$idImpuesto = self::getIdImpuestoSegunAnoImpositivo($año);

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

					if ( is_array($periodos) ) {
						// Aqui $value es el periodo (trimstre).
						foreach ( $periodos as $key => $value ) {
							$arregloDatos[] = $this->inicializarDatos($arregloDetalle);

							$arregloDatos[$key]['trimestre'] = $value;
							$arregloDatos[$key]['monto'] = $montoPeriodo;
							$arregloDatos[$key]['id_impuesto'] = $idImpuesto;
							$arregloDatos[$key]['impuesto'] = 1;
							$arregloDatos[$key]['ano_impositivo'] = $año;
							$arregloDatos[$key]['fecha_emision'] = $fechaActual;
							$arregloDatos[$key]['fecha_pago'] = null;
							$arregloDatos[$key]['fecha_vcto'] = $fechaVcto;
							$arregloDatos[$key]['descripcion'] = 'LIQUIDACION';
							$arregloDatos[$key]['fecha_desde'] = null;
							$arregloDatos[$key]['fecha_hasta'] = null;
							$arregloDatos[$key]['exigibilidad_pago'] = $exigibilidadLiq['exigibilidad'];
						}
					} elseif ( is_integer($periodos) )  {
							$arregloDatos[] = $this->inicializarDatos($arregloDetalle);

							$arregloDatos[$periodos]['trimestre'] = $value;
							$arregloDatos[$periodos]['monto'] = $montoPeriodo;
							$arregloDatos[$periodos]['id_impuesto'] = $idImpuesto;
							$arregloDatos[$periodos]['impuesto'] = 1;
							$arregloDatos[$periodos]['ano_impositivo'] = $año;
							$arregloDatos[$periodos]['fecha_emision'] = $fechaActual;
							$arregloDatos[$periodos]['fecha_pago'] = null;
							$arregloDatos[$periodos]['fecha_vcto'] = $fechaVcto;
							$arregloDatos[$periodos]['descripcion'] = 'LIQUIDACION';
							$arregloDatos[$periodos]['fecha_desde'] = null;
							$arregloDatos[$periodos]['fecha_hasta'] = null;
							$arregloDatos[$periodos]['exigibilidad_pago'] = $exigibilidadLiq['exigibilidad'];

					}

				} else {
					// Ha ocurrido un error al intentar obtener la exigibilidad de la liquidacion.
					// Renderizar a un vista.

				}
				return $arregloDatos;
			}
			return null;
		}




		/***/
		private function inicializarDatos($arregloDatos)
		{
			$arreglo = null;
			foreach ( $arregloDatos as $key => $value ) {
				$arreglo[$value] = 0;
			}
			return $arreglo;
		}




		/**
		 * Metodo que instancia otra clase para determinar el identificador de la
		 * declaracion del contribuyente, segun el año impositivo especifico.
		 * @param  Integer $añoImpositivo, expresion de 4 digitos.
		 * @return Long Retorna un identificador de la declaracion.
		 */
		private function getIdImpuestoSegunAnoImpositivo($añoImpositivo)
		{
			$idImpuesto = 0;
			if ( $añoImpositivo > 0 ) {
				$declaracion = New Declaracion($this->_idContribuyente);
				$idImpuesto = $declaracion->getIdImpuestoSegunAnoImpositivo($añoImpositivo);
			}
			return $idImpuesto['id_impuesto'];
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