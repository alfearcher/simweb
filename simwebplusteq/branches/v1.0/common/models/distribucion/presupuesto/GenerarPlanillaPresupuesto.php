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
 *  @file GererarPlanillaPresupuesto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @class GererarPlanillaPresupuesto
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

	namespace common\models\distribucion\presupuesto;

 	use Yii;
 	use backend\models\recibo\deposito\Deposito;
 	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
 	use backend\models\tasa\Tasa;
 	use common\models\planilla\PlanillaSearch;
 	use common\models\contribuyente\ContribuyenteBase;




	/***/
	class GenerarPlanillaPresupuesto
	{

		private $_recibo;
		private $_fecha_pago;

		/**
		 * Variable que contiene los datos del contribuyente.
		 * @var array
		 */
		private $_contribuyente = [];

		/**
		 * Variable que contiene el registro del recibo.
		 * @var Deposito.
		 */
		private $_deposito;

		/**
		 * Variable que contiene el registro donde estan las planillas relacionadas
		 * al recibo.
		 * @var DepositoPlanilla.
		 */
		private $_depositoPlanilla;

		/**
		 * VAriable arreglo que contiene las distribuciones presupuestaria, recibo-planilla-codigo.
		 * @var array
		 */
		private $_planillaPresupuesto = [];

		public $errores;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 * @param string $fechaPago fecha de pago de la transaccion, este valor se debe
		 * asignar cuando se esta efectuando el pago, para los casos donde se este contabilizando
		 * nuevamente no es necesario asignarlo.
		 */
		public function __construct($recibo, $fechaPago = null)
		{
			$this->_recibo = $recibo;
			$this->_fecha_pago = ( $fechaPago !== null ) ? date('Y-m-d', strtotime($fechaPago)) : null;
		}


		/***/
		public function iniciarPlanillaPresupuesto()
		{
			self::getDatoRecibo();
			self::getPlanillaRecibo();

			self::cicloPlanilla();
			return self::getPlanillaPresupuesto();

		}



		/**
		 * Metodo que permite obtener los datos de la planilla.
		 * @param integer $planilla numero de planilla.
		 * @return array.
		 */
		public function getDatoPlanilla($planilla)
		{
			$planillaSearch = New PlanillaSearch($planilla);
			return $planillaSearch->findPlanillaDetalle();

		}




		/**
		 * Metodo que retorna la descripcion del contribuyente, segun el identificador
		 * del mismo. Si el contribuyente es Juridico retorna la razon social, si es
		 * Natural retornara apellidos y nombre.
		 * @param integer $idContribuyente identificador de la entidad y del contribuyente.
		 * @return string
		 */
		private function getDescripcionContribuyente($idContribuyente)
		{
			$contribuyente = New ContribuyenteBase();
			return $contribuyente->getContribuyenteDescripcionSegunID($idContribuyente, 1);
		}




		/**
		 * Metodo que setea un error ocurrido.
		 * @param srting $mensajeError descripcion del error ocurrido.
		 */
		public function setError($mensajeError)
		{
			$this->errores[] = $mensajeError;
		}



		/**
		 * Metodo getter de los errores existentes
		 * @return array
		 */
		public function getError()
		{
			return $this->errores;
		}



		/**
		 * Metodo getter de las rafaga.
		 * @return array
		 */
		public function getPlanillaPresupuesto()
		{
			return $this->_planillaPresupuesto;
		}



		/**
		 * Metodo que realiza la consulta para obtener el registro del recibo.
		 * @return
		 */
		private function getDatoRecibo()
		{
			$this->_deposito = Deposito::find()->where('recibo =:recibo',
															['recibo' => $this->_recibo])
											   ->asArray()
											   ->one();
		}



		/**
		 * Metodo que realiza la consulta para obetener las planillas relacionadas
		 * al recibo.
		 * @return
		 */
		private function getPlanillaRecibo()
		{
			$this->_depositoPlanilla = DepositoPlanilla::find()->where('recibo =:recibo',
																			[':recibo' => $this->_recibo])
															   ->orderBy([
															   		'planilla' => SORT_ASC,
															   	])
															   ->asArray()
															   ->all();
		}



		/**
		 * Metodo que retorna los codigos presupuestarios segun el impuesto.
		 * @return array
		 */
		private static function getRelacionImpuestoPresupuesto()
		{
			return $codigos = require(dirname(__DIR__)) . '/presupuesto/relacion-impuesto-codigo-presupuesto.php';
		}




		/**
		 * Metodo que inicia el ciclo de planillas a los cuales se les hara la
		 * distribucion de codigos presupuestario.
		 * @return
		 */
		private function cicloPlanilla()
		{
			if ( count($this->_depositoPlanilla) > 0 ) {

				foreach ( $this->_depositoPlanilla as $planilla ) {

					// $planilla, cada planilla en depositos-planillas.

					// Detalles por cada planilla.
					$detallesPlanilla = self::getDatoPlanilla($planilla['planilla']);

					if ( count($detallesPlanilla) > 0 ) {
						$idContribuyente = $detallesPlanilla[0]['id_contribuyente'];

						$datoCotribuyente = self::getDescripcionContribuyente($idContribuyente);

						foreach ( $detallesPlanilla as $detalle ) {

							self::distribuirCodigoPlanilla($detalle, $datoCotribuyente);

						}
					}
				}
			}

		}





		/**
		 * Metodo que inicia la distribucion presupuestaria de los montos de la planilla.
		 * @param array $detallePlanilla detalle de un registro perteneciente a la
		 * planilla.
		 * @param string $datoCotribuyente descripcion del contribuyente, razon sociaL o
		 * apellidos y nombres.
		 * @return
		 */
		private function distribuirCodigoPlanilla($detallePlanilla, $datoContribuyente)
		{
			$recibo = $this->_recibo;
			$planilla = $detallePlanilla['planilla'];
			$monto = $detallePlanilla['monto'];
			$recargo = $detallePlanilla['recargo'];
			$interes = $detallePlanilla['interes'];
			$descuento = $detallePlanilla['descuento'];
			$montoReconocimiento = $detallePlanilla['monto_reconocimiento'];
			$impuestoDescripcion = $detallePlanilla['descripcion_impuesto'];

			$fechaPago = ( $this->_fecha_pago !== null ) ? $this->_fecha_pago : date('Y-m-d', strtotime($detallePlanilla['fecha_pago']));
			$primeraVez = true;

			// Lista de codigos presupuestarios
			$listaCodigo = self::getRelacionImpuestoPresupuesto();

			$codigo = isset($listaCodigo[$detallePlanilla['impuesto']]) ? $listaCodigo[$detallePlanilla['impuesto']] : '';
//die(var_dump( $fechaPago >= date('Y-m-d', strtotime('2011-01-01'))));
			// Se comienza con la distribucion.
			$listaIdimpuesto1 = [171, 517, 652, 654, 1120];
			if ( (int)$detallePlanilla['impuesto'] <= 7 ) {
				if ( (int)$detallePlanilla['ano_impositivo'] < (int)date('Y', strtotime($fechaPago)) ) {
					if ( $fechaPago >= date('Y-m-d', strtotime('2011-01-01'))  ) {

						// Deuda morosa
						$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
						$codigo = self::getCodigoPresupuestarioDeudaMorosa();
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);

						if ( (float)$detallePlanilla['recargo'] > 0 ) {
							self::relacionarConceptoRecargo($detallePlanilla, $datoContribuyente);
						}

						if ( (float)$detallePlanilla['interes'] > 0 ) {
							self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
						}

					} else {
						$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
						$codigo = self::getCodigoPresupuestarioDeudaMorosa();
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);

					}
				} else {

					$montoAplicar = $monto - ( $descuento + $montoReconocimiento );

					if ( $primeraVez ) {
						$primeraVez = false;
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					} else {
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					}

					if ( (float)$detallePlanilla['recargo'] > 0 ) {
						self::relacionarConceptoRecargo($detallePlanilla, $datoContribuyente);
					}

					if ( (float)$detallePlanilla['interes'] > 0 ) {
						self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
					}
				}

			} elseif ( (int)$detallePlanilla['impuesto'] == 10 && $fechaPago > date('Y-m-d', strtotime('2013-01-03')) && in_array($detallePlanilla['id_impuesto'], $listaIdimpuesto1) ) {
				// Activado el 08-04-2013

				// Reparo fiscales
				$codigo = self::getCodigoPresupuestarioReparoFiscales((int)$detallePlanilla['ano_positivo']);
				$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
				self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);

				if ( (float)$detallePlanilla['interes'] > 0 ) {
					self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
				}

			} elseif ( $detallePlanilla['impuesto'] == 12 ) {
				if ( (int)$detallePlanilla['ano_impositivo'] < (int)date('Y', strtotime($fechaPago)) ) {

					$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
					if ( $fechaPago >= date('Y-m-d', strtotime('2011-01-01')) ) {

						$codigo = self::getCodigoPresupuestarioDeudaMorosa();
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					}

					if ( (float)$detallePlanilla['recargo'] > 0 ) {
						self::relacionarConceptoRecargo($detallePlanilla, $datoContribuyente);
					}

					if ( (float)$detallePlanilla['interes'] > 0 ) {
						self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
					}

				} else {

					$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
					if ( $primeraVez ) {
						$primeraVez = false;
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					} else {
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					}

					if ( (float)$detallePlanilla['recargo'] > 0 ) {
						self::relacionarConceptoRecargo($detallePlanilla, $datoContribuyente);
					}

					if ( (float)$detallePlanilla['interes'] > 0 ) {
						self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
					}
				}

			} else {

				if ( (int)$detallePlanilla['ano_impositivo'] < (int)date('Y', strtotime($fechaPago)) ) {

					// Concepto de Cuentas por Cobrar.
					$listaIdimpuesto2 = [190, 433];
					if ( in_array($detallePlanilla['id_impuesto'], $listaIdimpuesto2) ) {

						$montoAplicar = $monto - ( $descuento + $montoReconocimiento );

						// Informacion del codigo presupuestario de la entidad "codigos-contables".
						$codigo = self::getCodigoPresupuestarioCuentaPorCobrar();
						if ( $codigo !== null ) {
							self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
						}

					} else {
						if ( $detallePlanilla['impuesto'] == 9 ) {
							if ( $fechaPago >= date('Y-m-d', strtotime('2014-03-01')) ) {

								// Deuda morosa por tasa.
								$codigo = self::getCodigoPresupuestarioDeudaMorosaPorTasa();

							} else {
								// Deuda morosa.
								$codigo = self::getCodigoPresupuestarioDeudaMorosa();
							}
						} else {
							// Deuda morosa.
							$codigo = self::getCodigoPresupuestarioDeudaMorosa();
						}

						$montoAplicar = $monto - ( $descuento + $montoReconocimiento );
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo, $montoAplicar);
					}

					if ( (float)$detallePlanilla['interes'] > 0 ) {
						self::relacionarConceptoInteres($detallePlanilla, $datoContribuyente);
					}

				} else {

					$montoAplicar = $monto - ( $descuento + $montoReconocimiento );

					// Informacion del codigo presupuestario de la entidad "codigos-contables".
					$codigo = self::getDatoCodigoPresupuestarioByIdImpuesto($detallePlanilla);
					if ( $codigo !== null ) {
						self::relacionar($detallePlanilla, $datoContribuyente, $codigo['codigo'], $montoAplicar);
					}

					if ( (float)$detallePlanilla['interes'] > 0 ) {
						self::relacionarConceptoInteres($detallePlanilla, $datonCotribuyente);
					}
				}
			}
		}




		/**
		 * Metodo que retorna el codigo presupuestario por concepto de interes de mora.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioInteres($añoImpositivo = 0)
		{
			if ( $añoImpositivo == 0 ) {
				return '301110100';
			}
			return '301110100';
		}



		/**
		 * Metodo que retorna el codigo presupuestario por concepto de recargo por mora.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioRecargo($añoImpositivo = 0)
		{
			if ( $añoImpositivo == 0 ) {
				return '301110800';
			}
			return '301110800';
		}



		/**
		 * Metodo que retorna el codigo presupuestario por concepto de reparo fiscales.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioReparoFiscales($añoImpositivo = 0)
		{
			if ( $añoImpositivo >= 2014 ) {
				return '301111200';
			} elseif ( $añoImpositivo < 2014 ) {
				return '301110200';
			}
		}




		/**
		 * Metodo que retorna el codigo presupuestario por concepto de cuentas por cobrar.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioCuentaPorCobrar($añoImpositivo = 0)
		{
			if ( $añoImpositivo == 0 ) {
				return '101020200';
			}
			return '101020200';
		}




		/**
		 * Metodo que retorna el codigo presupuestario por concepto de Deuda Morosa Por Tasas.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioDeudaMorosaPorTasa($añoImpositivo = 0)
		{
			if ( $añoImpositivo == 0 ) {
				return '301035900';
			}
			return '301035900';
		}




		/**
		 * Metodo que retorna el codigo presupuestario por concepto de Deuda Morosa.
		 * @param integer $añoImpositivo año impositivo de la planilla.
		 * @return string. Codigo presupuestario.
		 */
		private static function getCodigoPresupuestarioDeudaMorosa($añoImpositivo = 0)
		{
			if ( $añoImpositivo == 0 ) {
				return '301021200';
			}
			return '301021200';
		}




		/**
		 * Metodo que retorna la informacion del codigo presupuestario segun la tasa
		 * especifica, aqui la tasa se identifica por el valor del id-impuesto que
		 * contiene la planilla.
		 * @param array $detallePlanilla detalle de un registro perteneciente a la
		 * planilla.
		 * @return array | null si no encuentra nada.
		 */
		private static function getDatoCodigoPresupuestarioByIdImpuesto($detallePlanilla)
		{
			$registers = Tasa::find()->alias('T')
			          	             ->joinWith('codigoContable C', true, 'INNER JOIN')
			                         ->where('T.id_impuesto =:id_impuesto',
			                    					[':id_impuesto' => (int)$detallePlanilla['id_impuesto']])
			                         ->asArray()
			                         ->one();

			if ( count($registers) > 0 ) {
				return $registers['codigoContable'];
			}
			return $registers;
		}




		/**
		 * Metodo que prepara la relacion presupuestaria de los montos por intereses moratorios.
		 * @param array $detallePlanilla detalle de un registro perteneciente a la
		 * planilla.
		 * @param string $datoCotribuyente descripcion del contribuyente, razon sociaL o
		 * apellidos y nombres.
		 * @return
		 */
		public function relacionarConceptoInteres($detallePlanilla, $datoCotribuyente)
		{
			if ( (float)$detallePlanilla['interes'] > 0 ) {
				$montoAplicar = (float)$detallePlanilla['interes'];
				$codigo = self::getCodigoPresupuestarioInteres();
				self::relacionar($detallePlanilla, $datoCotribuyente, $codigo, $montoAplicar);
			}
			return;
		}




		/**
		 * Metodo que prepara la relacion presupuestaria de los montos por recargo por mora.
		 * @param array $detallePlanilla detalle de un registro perteneciente a la
		 * planilla.
		 * @param string $datoCotribuyente descripcion del contribuyente, razon sociaL o
		 * apellidos y nombres.
		 * @return
		 */
		public function relacionarConceptoRecargo($detallePlanilla, $datoCotribuyente)
		{
			if ( (float)$detallePlanilla['recargo'] > 0 ) {
				$montoAplicar = (float)$detallePlanilla['recargo'];
				$codigo = self::getCodigoPresupuestarioRecargo();
				self::relacionar($detallePlanilla, $datoCotribuyente, $codigo, $montoAplicar);
			}
			return;
		}



		/**
		 * Metodo que agrega una relacion de codigo presupuestario vs planilla, en el arreglo.
		 * @param array $detallePlanilla detalle de un registro perteneciente a la
		 * planilla.
		 * @param string $datoCotribuyente descripcion del contribuyente, razon sociaL o
		 * apellidos y nombres.
		 * @param string $codigoPresupuestario codigo presupuestario.
		 * @param double $montoAplicar monto respectivo.
		 * @return
		 */
		private function relacionar($detallePlanilla, $datoCotribuyente, $codigoPresupuestario, $montoAplicar)
		{
			$this->_planillaPresupuesto[] = [
				'recibo' => $this->_recibo,
				'planilla' => $detallePlanilla['planilla'],
				'monto' => $montoAplicar,
				'impuesto' => $detallePlanilla['descripcion_impuesto'],
				'descripcion' => $datoCotribuyente,
				'codigo' => $codigoPresupuestario,
				'estatus' => 0,
				'codigo_contable' => $codigoPresupuestario,		// quitar lo ultimo dos digitos.
			];
		}

	}
?>