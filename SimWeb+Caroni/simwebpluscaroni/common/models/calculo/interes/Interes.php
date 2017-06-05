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
 *  @file Interes.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-10-2016
 *
 *  @class Interes
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

	namespace common\models\calculo\interes;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\ordenanza\OrdenanzaAsignacion;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\utilidad\ut\UnidadTributariaForm;
	use backend\models\utilidad\bcv\InteresBcvSearch;



	/**
	* Clase que realiza el calculo de los intereses que se aplicaran a un periodo
	* por concepto de mora.
	*/
	class Interes
	{

		private $_año_impositivo;
		private $_periodo;
		private $_monto;
		private $_impuesto;
		private $_id_ordenanza = 0;
		private $_tipoAsignacion = 0;
		private $_exigibilidadLiq = [];
		private $_interes = 0;

		/**
		 * Variable que contiene los datos del año, mes y porcentaje a aplicar
		 * @var array
		 */
		private $_rangoPorcentual = [];


		/**
		 * Arreglo donde se guardara la descripcion de la configuracion.
		 * Que se aplica en la interes. Tendra la siguiente estructura
		 * ['mes' => 'aplicacion']
		 * [
		 * 		'febrero' => '2%']
		 * @var array
		 */
		private $_tipoConfig = [];

		/**
		 * Configuracion de la ordenanza y los parametros por mora.
		 * @var array
		 */
		private $_configOrdAsignacion = [];



		/**
		 * Metodo constructor de la clase
		 * @param integer $impuesto identificador del impuesto.
		 */
		public function __construct($impuesto)
		{
			$this->_impuesto = $impuesto;
		}



		/**
		 * Metodo que inicia el proceso de calculo de los intereses.
		 * @param integer $añoImpositivo año del lapso al cual se le calculara
		 * el interes.
		 * @param integer $periodo periodo del lapso al cual se le calculara
		 * el interes.
		 * @param double $monto monto al cual se le calculara el interes.
		 * @return double retorna el monto calculado por el interes.
		 */
		public function calcularInteres($añoImpositivo, $periodo, $monto)
		{
			$this->_año_impositivo = $añoImpositivo;
			$this->_periodo = $periodo;
			$this->_monto = $monto;

			if ( $this->_año_impositivo > 0 && $this->_periodo > 0  && $this->_impuesto > 0 ) {
				$aplicoSancion = self::IniciarCalculoInteres();
				if ( $aplicoSancion ) {
					$fechaDesde = self::getFechaVencePeriodo();
					$fechaHasta = date('Y-m-d');

					if ( self::getRangoFechaValido($fechaDesde, $fechaHasta) ) {
						self::determinarRangoPorcentual($fechaDesde, $fechaHasta);
						$this->_interes = self::calcularMontoInteres();
					} else {
						$this->_interes = 0;
					}
				}
			}

			return $this->_interes;
		}



		/***/
		private function getRangoFechaValido($fechaDesde, $fechaHasta)
		{
			if ( $fechaDesde !== null || $fechaHasta !== null ) {
				if ( $fechaDesde <= $fechaHasta ) {
					return true;
				}
			}

			return false;
		}



		/***/
		public function getFechaVencePeriodo()
		{
			$fecha = null;
			if ( count($this->_configOrdAsignacion) > 0 ) {
				$meses = 0;
				$dias = 0;
				$meses = $this->_configOrdAsignacion['mes_aplicacion'];
				$dias = $this->_configOrdAsignacion['dias_aplicacion'];

				$fecha = date_create(self::getFechaInicioPeriodo());

				if ( $meses > 0 ) {
					$m = $meses . ' month';
					date_add($fecha, date_interval_create_from_date_string($m));
				}

				if ( $dias > 0 ) {
					$d = $dias . ' days';
					date_add($fecha, date_interval_create_from_date_string($d));
				}

				return date_format($fecha, 'Y-m-d');
			}

			return null;
		}





		/***/
		private function calcularMontoInteres()
		{
			$sumaInteres = 0;
			foreach ( $this->_rangoPorcentual as $rango ) {
				$sumaInteres = $rango['p'] + $sumaInteres;
			}
			$interes = $sumaInteres/100;

			return $this->_monto * $interes;
		}





		/***/
		public function getInteres()
		{
			return $this->_interes;
		}




		/***/
		public function getConfigPenalidad()
		{
			return $this->_tipoConfig;
		}



		/**
		 * Metodo que busca el identificador de la ordenanza y setea la vatiable
		 * que contiene la informacion del mismo.
		 * @return no retorna.
		 */
		private function getOrdenanza()
		{
			$ordenanza = [];
			$this->_id_ordenanza = 0;
			// Se obtiene el identificador de la ordenanza segun el año y el impuesto.
			$ordenanza = OrdenanzaBase::getIdOrdenanzaSegunAnoImpositivo($this->_año_impositivo, $this->_impuesto);
			if ( count($ordenanza) > 0 && $ordenanza !== false ) {
				$this->_id_ordenanza = $ordenanza[0]['id_ordenanza'];
			}
			return;
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
			return;
		}




		/**
		 * Metodo que retorna el modelo de consulta de configuracion de la aplicacion
		 * de los recargos
		 * @return active record o un arreglo vacio.
		 */
		private function findConfiguracion()
		{
			if ( $this->_id_ordenanza > 0 ) {
				$tabla = OrdenanzaAsignacion::tableName();
				// Se busca la configuracion que se aplicara al proceso del calculo
				// de los intereses.
				$findModel = OrdenanzaAsignacion::find()->alias('A')
														->where('id_ordenanza =:id_ordenanza',
																[':id_ordenanza' => $this->_id_ordenanza])
														->andWhere('A.tipo_asignacion >:tipo_asignacion',
																[':tipo_asignacion' => 0])
														->andWhere('A.id_asignacion =:id_asignacion',
																[':id_asignacion' => 2])
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

			if ( count($findModel) > 0 ) {
				$config = $findModel->joinWith('tipoAsignacion', true, 'INNER JOIN')
								    ->joinWith('asignacion', true, 'INNER JOIN')
				                    ->asArray()->one();
				if ( count($config) > 0 ) {
					$this->_configOrdAsignacion = $config;
				}
			}
			return;
		}





		/**
		 * Metodo que inicia el proceso para calcular de los intereses.
		 * @return double retorna el monto calculado.
		 */
		private function IniciarCalculoInteres()
		{
			self::getOrdenanza();
			self::getConfiguracion();
			self::getExigibilidadLiquidacion();
			$fechaActual = date('Y-m-d');
			$montoInteres = 0;
			$meses = 0;
			$dias = 0;
			$añoActual = (int)date('Y');
			$aplicoSancion = false;
			$cantMeses = 0;
			$cantA = 0;

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
							if ( date_create($fechaInicioPeriodo) <= date_create($fechaActual) ) {
								$interval = date_diff(date_create($fechaInicioPeriodo), date_create($fechaActual));
								$cantMeses = $interval->{'m'};
								$cantA = $interval->{'y'};
								if ( $cantA > 0 ) {
									$cantMeses = $cantA * 12;
								}

								if ( $cantMeses >= $meses ) {
									$aplicoSancion = true;
								}
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 2 ) {
						// Al vencer el periodo.
						// ---------------------------------------------------------
						// Al mes siguiente del ultimo mes de cada periodo.

						if ( $fechaInicioPeriodo !== '' ) {

							if ( $this->_año_impositivo < $añoActual ) {

								$montoInteres = self::determinarMontoInteres();

							} elseif ( $this->_año_impositivo == $añoActual ) {
								if ( $periodoActual > $this->_periodo ) {
									$aplicoSancion = true;
								}
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 3 ) {
						// A los N dias de cada mes.
						// ------------------------------------------------------------
						// La penalización se aplica transcurrido los N dias de cada mes
						// incluido el 1er mes de cada periodo tambien.

						if ( $fechaInicioPeriodo !== '' ) {

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							// Cantidad de dias entre las fechas.
							$interval = date_diff(date_create($fechaInicioPeriodo), date_create($fechaActual));
							$cantDias = $interval->{'days'};

							if ( $cantDias >= $dias ) {
								$aplicoSancion = true;
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 4 ) {
						// N meses a partir del 1er mes del periodo x N dias de ese mes.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para ese mes(1ero de cada periodo).

						if ( $fechaInicioPeriodo !== '' ) {

							// Cantidad de meses que se tienen para pagar el periodo.
							$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							$interval = date_diff(date_create($fechaInicioPeriodo), date_create($fechaActual));
							$cantMeses = $interval->{'m'};
							$cantDias = $interval->{'d'};

							if ( $cantMeses >= $meses ) {
								$aplicoSancion = true;

							} elseif ( $cantMeses == $meses ) {
								if ( $cantDias >= $dias ) {
									$aplicoSancion = true;

								}
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 5 ) {
						// N meses a partir del 1er mes del periodo x N dias de todos
						// los meses.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para todos los meses que vienen.


						if ( $fechaInicioPeriodo !== '' ) {
							// Cantidad de meses que se tienen para pagar el periodo.
							$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							$interval = date_diff(date_create($fechaInicioPeriodo), date_create($fechaActual));
							$cantMeses = $interval->{'m'};
							$cantDias = $interval->{'d'};

							if ( $cantMeses >= $meses ) {
								$aplicoSancion = true;

							} elseif ( $cantMeses == $meses ) {
								if ( $cantDias >= $dias ) {
									$aplicoSancion = true;

								}
							}
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 9 ) {
						// Despues de transcurrido N dias del periodo.
						// --------------------------------------------------------------
						// Contar a partir del primer dia del 1er mes de cada periodo.

						if ( $fechaInicioPeriodo !== '' ) {
							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							// Cantidad de dias entre las fechas.
							$interval = date_diff(date_create($fechaInicioPeriodo), date_create($fechaActual));
							$cantDias = $interval->{'days'};

							if ( $cantDias >= $dias ) {
								$aplicoSancion = true;
							}
						}
					}

				}
			}

			return $aplicoSancion;
		}



		/**
		 * Metodo que inicia el proceso para armar el rango porcentual entres las fechas.
		 * @param date  $fechaDesde fecha inicial de consulta.
		 * @param date  $fechaHasta fecha final de consulta.
		 * @return double retorna el monto total.
		 */
		public function determinarRangoPorcentual($fechaDesde, $fechaHasta)
		{

			$this->_rangoPorcentual = [];
			$montoCalculado = 0;
			if ( count($this->_configOrdAsignacion) > 0 ) {

				if( $this->_configOrdAsignacion['tipo_asignacion'] > 0 ) {

					$interes = New InteresBcvSearch();
					if ( $this->_configOrdAsignacion['tipo_asignacion'] == 1 ) {		// Porcentaje fijo

						$porcentaje = $this->_configOrdAsignacion['monto'];	// 1.50, 1.00, etc
						$this->_rangoPorcentual = $interes->armarRangoPorcentualInteres($fechaDesde, $fechaHasta, $porcentaje);

					} elseif ( $this->_configOrdAsignacion['tipo_asignacion'] == 9 ) {	// Porcentaje BCV

						$this->_rangoPorcentual = $interes->armarRangoPorcentualInteres($fechaDesde, $fechaHasta);
					}
				}

			}

		}





		/**
		 * Metodo que permite rutear a la logica que se aplicara para determinar
		 * el monto por interes.
		 * @return double retorna monto del interes segun la logica aplicada.
		 * Sino determina la logica retornara cero (0).
		 */
		private function determinarMontoInteres()
		{
			$montoInteres = 0;
			if ( $this->_configOrdAsignacion['tipo_asignacion'] == 1 ) {
				$montoInteres = self::aplicarPorcentajeFijo();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 2 ) {
				//$montoInteres = self::aplicarUnidadTributaria();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 3 ) {
				//$montoInteres = self::aplicarMontoFijo();

			} elseif (  $this->_configOrdAsignacion['tipo_asignacion'] == 9 ) {
				$montoInteres = self::aplicarPorcentajeBCV();

			}

			return $montoInteres;
		}



		/**
		 * Metodo que aplica el calculo de interes, segun la politica de porcentaje
		 * fijo.
		 * @return double retorna monto del interes a aplicar por porcentaje fijo.
		 */
		private function aplicarPorcentajeFijo()
		{
			return $monto = $this->_monto * ( $this->_configOrdAsignacion['monto'] / 100 );
		}



		/**
		 * Metodo que aplica el calculo de interes, segun la politica de buscar la unidad
		 * tributaria del año especifico.
		 * @return double retorna monto del interes.
		 */
		private function aplicarUnidadTributaria()
		{
			$montoUT = 0;
			$ut = New UnidadTributariaForm();
			$montoUT = $ut->getUnidadTributaria($this->_año_impositivo);

			return $monto = $montoUT * ( $this->_configOrdAsignacion['monto'] );
		}



		/**
		 * Metodo que aplica el calculo de interes, segun la politica de aplicar un
		 * monto fijo.
		 * @return double retorna monto del interes.
		 */
		private function aplicarMontoFijo()
		{
			return $monto = ( $this->_configOrdAsignacion['monto'] );
		}



		/**
		 * Metodo que aplica el calculo de interes, segun la politica de aplicar un
		 * porcentaje definido por el Banco Central. Se tomara el mes anterior al mes
		 * de vencimiento del plazo para pagar el periodo.
		 * @return double retorna monto del interes.
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
			$fecha = OrdenanzaBase::getFechaInicioSegunPeriodo($this->_año_impositivo, $this->_periodo,
					        								   $this->_exigibilidadLiq['exigibilidad']);

			return date_format(date_create($fecha), 'Y-m-d');

		}



		/***/
		public function setAnoImpositivo($añoImpositivo)
		{
			$this->_año_impositivo = $añoImpositivo;
		}




		/***/
		public function setPeriodo($periodo)
		{
			$this->_periodo = $periodo;
		}



		/***/
		public function generarEtiquetaInteres()
		{
			self::getOrdenanza();
			self::getConfiguracion();
			self::getExigibilidadLiquidacion();
			$fechaActual = date('Y-m-d');
			$meses = 0;
			$dias = 0;
			$aplico = '';

			$this->_tipoConfig = [];

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

							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));
							// indico a partir de que fecha aplica el recargo.
							if ( $meses == 0 ) {

								$this->_tipoConfig[$this->_periodo] = [
															'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
															'interesEn' => self::getDescripcionPenalidad(),
								];

							} else {
								$aplico = $meses . ' month';
								$f = date_create($fechaInicioPeriodo);
								date_add($f, date_interval_create_from_date_string($aplico));
								$fecha = date_format($f, 'Y-m-d');
								$m = date('m', strtotime($fecha));

								$this->_tipoConfig[$this->_periodo] = [
															'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
															'interesEn' => Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
								];
							}

						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 2 ) {
						// Al vencer el periodo.
						// ---------------------------------------------------------
						// Al mes siguiente del ultimo mes de cada periodo.

						// Cantidad de meses que se tienen para pagar el periodo.
						$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

						if ( $fechaInicioPeriodo !== '' ) {
							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));

							$aplico = $meses . ' month';
							$f = date_create($fechaInicioPeriodo);
							date_add($f, date_interval_create_from_date_string($aplico));
							$fecha = date_format($f, 'Y-m-d');
							$m = date('m', strtotime($fecha));

							$this->_tipoConfig[$this->_periodo] = [
														'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
														'interesEn' => Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
							];
						}



					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 3 ) {
						// A los N dias de cada mes.
						// ------------------------------------------------------------
						// La penalización se aplica transcurrido los N dias de cada mes
						// incluido el 1er mes de cada periodo tambien.

						if ( $fechaInicioPeriodo !== '' ) {

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));

							$aplico = $dias . ' days';
							$f = date_create($fechaInicioPeriodo);
							date_add($f, date_interval_create_from_date_string($aplico));
							$fecha = date_format($f, 'Y-m-d');
							$m = date('m', strtotime($fecha));
							$d = date('d', strtotime($fecha));

							$this->_tipoConfig[$this->_periodo] = [
														'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
														'interesEn' => $d . '/' . Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
							];

						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 4 ) {
						// N meses a partir del 1er mes del periodo x N dias de ese mes.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para ese mes(1ero de cada periodo).

						if ( $fechaInicioPeriodo !== '' ) {

							// Cantidad de meses que se tienen para pagar el periodo.
							$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];


							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));

							$aplico = $meses . ' month';
							$f = date_create($fechaInicioPeriodo);
							date_add($f, date_interval_create_from_date_string($aplico));

							$aplico = $dias . ' days';
							date_add($f, date_interval_create_from_date_string($aplico));

							$fecha = date_format($f, 'Y-m-d');
							$m = date('m', strtotime($fecha));
							$d = date('d', strtotime($fecha));

							$this->_tipoConfig[$this->_periodo] = [
														'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
														'interesEn' => $d . '/' . Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
							];



						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 5 ) {
						// N meses a partir del 1er mes del periodo x N dias de todos
						// los meses.
						// --------------------------------------------------------------
						// Aplica transcurrido N meses del 1er mes del periodo y contando
						// N dias para todos los meses que vienen.


						if ( $fechaInicioPeriodo !== '' ) {
							// Cantidad de meses que se tienen para pagar el periodo.
							$meses = (int)$this->_configOrdAsignacion['mes_aplicacion'];

							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));

							$aplico = $meses . ' month';
							$f = date_create($fechaInicioPeriodo);
							date_add($f, date_interval_create_from_date_string($aplico));

							$aplico = $dias . ' days';
							date_add($f, date_interval_create_from_date_string($aplico));

							$fecha = date_format($f, 'Y-m-d');
							$m = date('m', strtotime($fecha));
							$d = date('d', strtotime($fecha));

							$this->_tipoConfig[$this->_periodo] = [
														'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
														'interesEn' => $d . '/' . Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
							];
						}


					} elseif ( $this->_configOrdAsignacion['id_aplicacion'] == 9 ) {
						// Despues de transcurrido N dias del periodo.
						// --------------------------------------------------------------
						// Contar a partir del primer dia del 1er mes de cada periodo.

						if ( $fechaInicioPeriodo !== '' ) {
							// Cantidad de dias que se tienen para pagar el periodo.
							$dias = (int)$this->_configOrdAsignacion['dias_aplicacion'];

							$pagarEn = (int)date('m', strtotime($fechaInicioPeriodo));

							$aplico = $dias . ' days';
							$f = date_create($fechaInicioPeriodo);
							date_add($f, date_interval_create_from_date_string($aplico));

							$fecha = date_format($f, 'Y-m-d');
							$m = date('m', strtotime($fecha));
							$d = date('d', strtotime($fecha));

							$this->_tipoConfig[$this->_periodo] = [
														'ipagarEn' => Yii::$app->mesdias->getMes($pagarEn),
														'interesEn' => $d . '/' . Yii::$app->mesdias->getMes((int)$m) . '(' . self::getDescripcionPenalidad() . ')',
							];
						}
					}

				}
			}

			return;
		}



		/***/
		public function getDescripcionPenalidad()
		{
			$result = '';
			if ( count($this->_configOrdAsignacion) > 0 ) {
				if ( $this->_configOrdAsignacion['tipo_asignacion'] == 0 ) {
					$result = $this->_configOrdAsignacion['tipoAsignacion']['descripcion'];

				} elseif ( $this->_configOrdAsignacion['tipo_asignacion'] == 1 ) {
					$result = $this->_configOrdAsignacion['monto'] . '%';

				} elseif ( $this->_configOrdAsignacion['tipo_asignacion'] == 2 ) {
					$result = $this->_configOrdAsignacion['monto'] . 'UT';

				} elseif ( $this->_configOrdAsignacion['tipo_asignacion'] == 3 ) {
					$result = $this->_configOrdAsignacion['monto'];

				} elseif ( $this->_configOrdAsignacion['tipo_asignacion'] == 9 ) {
					$result = '%BCV';
				}
			}

			return $result;
		}


	}

?>