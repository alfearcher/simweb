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
 *  @file PlanillaTasa.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-04-2016
 *
 *  @class PlanillaTasa
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
 	//use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;
	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;
 	use common\models\planilla\Planilla;



	/**
	* 	Clase
	*/
	class PlanillaTasa extends Planilla
	{
		private $_idContribuyente;
		private $_idImpuesto;
		private $_añoDesde;
		private $_periodoDesde;
		private $_montoCalculado;
		private $_montoTotal;
		private $_periodosLiquidados = [];
		public $conexion;			// Instancia de tipo ConexionController,
		 							// permite ejecutar los metodo para guardar.
		public $conn;				// Instancia de conexion de la db.



		/***/
		public function __construct($id, $idImpuesto,  $conexionLocal, $connLocal)
		{
			$this->_idContribuyente = $id;
			$this->_idImpuesto = $idImpuesto;
			$this->_periodosLiquidados = null;
			$this->conexion = $conexionLocal;
			$this->conn = $connLocal;
		}



		/**
		 * Metodo que inicia el proceso de la liquidacion estimada.
		 * @return Array Retorna un arreglo multi-dimensional de los periodos liquidados.
		 * De lo contrario retorna false.
		 */
		public function liquidarTasa()
		{
			if ( isset($this->conexion) && isset($this->conn) ) {
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
		public function setLapsoPeriodo($añoDesde, $periodoDesde)
		{
			$this->setAnoImpositivoDesde($añoDesde);
			$this->setPeriodoDesde($periodoDesde);
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
		 * Metodo que setea las variables que determinan el rango de liquidacion
		 * a un valor igual a "null". Cuando esto sucede es que no existen las condiciones
		 * para continuar con la liquidacion.
		 * @return Bollean true.
		 */
		public function anulacionRangoLiquidacion()
		{
			$this->_añoDesde = null;
			$this->_periodoDesde = null;
		}



		/**
		 * Metodo que controla y valida que los rango de inicio y finalizacion de la liquidacion
		 * este correcto.
		 * @return Boolean, Retorna true si es correcto y false si el rango esta errado.
		 */
		private function validarRangoLiquidacion()
		{
			if ( $this->_añoDesde == null || $this->_periodoDesde == null ) {
				return false;
			}
			return true;
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



		/***/
		public function LiquidarTasa()
		{
			$result = null;		// Array con los parametros principales y el calculo de la tasa anual.

			$liquidacion = New LiquidacionTasa($this->_Impuesto);
			$result = $liquidacion->iniciarCalcularLiquidacionTasa();
			return $result;
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
		private function generarPeriodoLiquidado($arregloParametro)
		{
			if ( count($arregloParametro) > 0 ) {
				if ( $arregloParametro['monto'] > 0 ) {
					$fechaActual = date('Y-m-d');
					$fechaVcto = $this->getUltimoDiaMes($fechaActual);

					$modelDetalle = New PagoDetalle();

					$arregloDetalle = array_values($modelDetalle->attributes());
				}
			}
			return null;
			if ( $montoLiquidado == 0 || $montoLiquidado < 0 ) {
					// Se debe abortar el proceso por declaracion faltante o parametros en los calculos incompletos.
					// Renderizar a un vista.

			} elseif ( $montoLiquidado > 0 ) {
				$fechaActual = date('Y-m-d');
				$fechaVcto = $this->getUltimoDiaMes($fechaActual);

				$modelDetalle = New PagoDetalle();

				$arregloDetalle = array_values($modelDetalle->attributes());

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
						$arregloDatos[$periodos]['fecha_desde'] = null;
						$arregloDatos[$periodos]['fecha_hasta'] = null;
						$arregloDatos[$periodos]['exigibilidad_pago'] = $exigibilidadLiq['exigibilidad'];

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