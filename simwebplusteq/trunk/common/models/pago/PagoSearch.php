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
 *  @file PagoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-11-2016
 *
 *  @class PagoSearch
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

 	namespace common\models\pago;

 	use Yii;
 	use yii\db\Exception;
 	use common\conexion\ConexionController;
	use yii\db\Query;
	use yii\db\Command;
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;



	/**
	 * Clase que permite obtener informacion de los pagos de un objeto o de un contribuyente
	 */
	class PagoSearch
	{

		private $_id_contribuyente;
		private $_id_impuesto;
		private $_impuesto;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct()
		{
		}


		/**
		 * Metodo que setea el valor del identificador del contribuyente
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function setIdContribuyente($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}



		/**
		 * Metodo que setea el valor del identificador del objeto.
		 * @param integer $idImpuesto identificador del objteo (inmueble, vehiculo)
		 */
		public function setIdImpuesto($idImpuesto)
		{
			$this->_id_impuesto = $idImpuesto;
		}


		/**
		 * Metodo que setea el valor del identificador del impuesto.
		 * @param integer $impuesto identificador del impuesto.
		 */
		public function setImpuesto($impuesto)
		{
			$this->_impuesto = $impuesto;
		}


		/**
		 * Metodo que crear el modelo general de consulta, para los pagos.
		 * @return model retorna active record
		 */
		private function getModelGeneral()
		{
			$findModel = PagoDetalle::find()->alias('D')
											->joinWith('pagos P', true, 'INNER JOIN')
											->where('D.pago =:pago',[':pago' => 1]);

			return ( count($findModel) > 0 ) ? $findModel : [];
		}



		/**
		 * Metodo que genera el modelo general de consulta de los impuestos de Actividad Economica.
		 * @return model retorna modelo de consulta para actividad economica.
		 */
		private function getModelGeneralActividadEconomica()
		{
			$findModelAct = null;
			$findModel = self::getModelGeneral();
			if ( count($findModel) > 0 ) {
				$findModelAct = $findModel->andWhere('D.impuesto =:impuesto',[':impuesto' => 1])
									      ->andWhere('P.id_contribuyente =:id_contribuyente',
									      			[':id_contribuyente' => $this->_id_contribuyente]);
			}
			return $findModelAct;
		}



		/**
		 * Metodo que genera el modelo general de consulta de los impuestos de Inmuebles.
		 * @return model retorna modelo de consulta para inmuebles urbanos.
		 */
		private function getModelGeneralInmueble()
		{
			$findModelInmueble = null;
			$findModel = self::getModelGeneral();
			if ( count($findModel) > 0 ) {
				$findModelInmueble = $findModel->andWhere('D.impuesto =:impuesto',[':impuesto' => 2]);
			}
			return $findModelInmueble;
		}




		/**
		 * Metodo que genera el modelo general de consulta de los impuestos de Vehiculs.
		 * @return model retorna modelo de consulta para Vehiculos.
		 */
		private function getModelGeneralVehiculo()
		{
			$findModelVehiculo = null;
			$findModel = self::getModelGeneral();
			if ( count($findModel) > 0 ) {
				$findModelVehiculo = $findModel->andWhere('D.impuesto =:impuesto',[':impuesto' => 3]);
			}
			return $findModelVehiculo;
		}



		/**
		 * Metodo que arma el modelo de consulta general de los pagos por estimada.
		 * Esto son los pagos que realiza el contribuyente de Actividad Economica
		 * durante el año. Solo se comtemplan los pagos de periodos mayores a cero (0).
		 * Se complemneta la consulta con la variable $todo, que indica si se tomara el
		 * modelo que abarca los pagos de todo el año, o solo los pagos del año y del
		 * periodo especificado.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @param  boolean $todo indica si se tomaran todos los pagos de los periodos
		 * mayores a cero o si solo se tomaran los pagos de un periodo especifico.
		 * @return model retorna active record modelo de consulta de los pagos.
		 */
		private function pagoPorEstimadaSegunLapso($añoImpositivo, $periodo = 0, $todo = true)
		{
			$pago = null;
			$findModelAct = self::getModelGeneralActividadEconomica();
			if ( count($findModelAct) > 0 ) {
				if ( $todo ) {
					$pago = $findModelAct->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo])
										 ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
										 ->andWhere('referencia =:referencia',[':referencia' => 0])
										 ->orderBy([
										 		'D.ano_impositivo' => SORT_ASC,
										 		'trimestre' => SORT_ASC,
										   ]);
										 //->all();
				} else {
					$pago = $findModelAct->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo])
										 ->andWhere('trimestre >:trimestre',[':trimestre' => $periodo])
										 ->andWhere('referencia =:referencia',[':referencia' => 0])
										 ->orderBy([
										 		'D.ano_impositivo' => SORT_ASC,
										 		'trimestre' => SORT_ASC,
										   ]);
										 //->all();
				}
			}

			return $pago;
		}




		/**
		 * Metodo que realiza la consulta de las definitivas pagadas por un contribuyente
		 * en un lapso especifico.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return array retorna un arreglo con los registros de pago.
		 */
		private function pagoPorDefinitivaSegunLapso($añoImpositivo, $periodo)
		{
			$pago = null;
			$findModelAct = self::getModelGeneralActividadEconomica();
			if ( count($findModelAct) > 0 ) {
				$pago = $findModelAct->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo])
									 ->andWhere('trimestre =:trimestre',[':trimestre' => $periodo])
									 ->andWhere('referencia =:referencia',[':referencia' => 1])
									 ->orderBy([
									 		'ano_impositivo' => SORT_ASC,
									 		'trimestre' => SORT_ASC,
									   ]);
									 //->all();
			}

			return $pago;

		}



		/**
		 * Metodo que realiza la consulta de los pagos por concepto de complementos para
		 * los caso del calculo de la definitiva. Se realiza un foltro donde se excluyen
		 * aquellos id-impuestos que no deberia ser tomados en cuenta para el calculo de
		 * la definitiva por pertenecer a otros conceptos.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  array  $idImpuestoExcluir identificadores de la entidad "varios" que
		 * no seran tomados en cuenta para el calculo.
		 * @return model retorna un modelo con los registros resultantes de la consulta.
		 */
		private function pagoAbonoActEconomica($añoImpositivo, $idImpuestoExcluir = [])
		{
			$pago = null;
			$findModelAct = self::getModelGeneralActividadEconomica();
			if ( count($findModelAct) > 0 ) {
				if ( count($idImpuestoExcluir) > 0 ) {
					$pagos = $findModelAct->andWhere('D.ano_impositivo =:ano_impositivo',
														[':ano_impositivo' => $añoImpositivo])
										  ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
										  ->andWhere(['NOT IN', 'D.id_impuesto', $idImpuestoExcluir])
										  ->orderBy([
										 		'D.ano_impositivo' => SORT_ASC,
										 		'trimestre' => SORT_ASC,
										    ]);
										 //->all();
				} else {
					$pagos = $findModelAct->andWhere('D.ano_impositivo =:ano_impositivo',
														[':ano_impositivo' => $añoImpositivo])
										  ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
										  ->orderBy([
										 		'D.ano_impositivo' => SORT_ASC,
										 		'trimestre' => SORT_ASC,
										    ]);
										  //->all();
				}
			}

			return $pagos;
		}



		/**
		 * Metodo que realizar la consulta de los id-impuestos que seran excluidos de los pagos
		 * por pertencer a retenciones o multas de actividad economica. Los pagos por retenciones
		 * se excluyen del pago del contribuyente porque dicho paga se origina como consecuencia
		 * de una retencion hecha por el contribuyente a otro contribuyente, lo que implica que
		 * dicho pago es en realidad del segundo contribuyente.
		 * Los pagos por multas se excluyen debido a que no forman parte de los pagos del lapso
		 * por actividad economica, sino debido a una sancion. Estos pagos que contienen estos
		 * id-impuestos no deben ser tomados en cuenta para el calculo de la definitiva.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @return array retorna un arreglo de id-impuestos.
		 */
		public function getIdImpuestoExcluido($añoImpositivo)
		{
			$idImpuestoExcluir = [];
			$idImpuestoRetencion = [];
			$idImpuestoMulta = [];

			// Se buscan los id-impuesto que seran excluidos de los pagos. En este caso
			// se buscan los id-impuestos por concepto de retenciones.
			$findModelAct = self::getModelGeneralActividadEconomica();
			$pagos = $findModelAct->andWhere('trimestre =:trimestre',[':trimestre' => 0])
								  ->andWhere('D.ano_impositivo =:ano_impositivo',
								 				[':ano_impositivo' => $añoImpositivo])
								  ->andWhere(['LIKE', 'T.descripcion', 'retencion'])
								  ->joinWith('tasa T', false, 'INNER JOIN')
								  ->orderBy([
								 		'D.ano_impositivo' => SORT_ASC,
								 		'trimestre' => SORT_ASC,
								    ])
								  ->asArray()
								  ->all();

			if ( count($pagos) > 0 ) {
				foreach ( $pagos as $pago ) {
					if ( !in_array($pago['id_impuesto'], $idImpuestoRetencion) ) {
						$idImpuestoRetencion[] = $pago['id_impuesto'];
					}
				}
			}

			$pagos = null;
			// Ahora se buscan los id-impuesto de multas.
			$findModel = self::getModelGeneral();
			$findModelMulta = $findModel->andWhere('trimestre =:trimestre',[':trimestre' => 0])
										->andWhere('D.impuesto =:impuesto',[':impuesto' => 10])
										->andWhere('D.ano_impositivo =:ano_impositivo',
								 				[':ano_impositivo' => $añoImpositivo])
										->andWhere(['LIKE', 'T.descripcion', 'Multas Actividad Economicas'])
										->joinWith('tasa T', false, 'INNER JOIN')
										->orderBy([
										 	'D.ano_impositivo' => SORT_ASC,
										 	'trimestre' => SORT_ASC,
										  ])
										->asArray()
								  		->all();

			if ( count($pagos) > 0 ) {
				foreach ( $pagos as $pago ) {
					$idImpuestoMulta[] = $pago['id_impuesto'];
				}
			}

			$idImpuestoExcluir = array_merge($idImpuestoRetencion, $idImpuestoMulta);

			return $idImpuestoExcluir;
		}




		/**
		 * Metodo que retorna los pagos del año.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @return array retorna un arreglo con el modelo de consulta.
		 */
		public function getPagoEstimadaSegunAnoImpositivo($añoImpositivo)
		{
			return self::pagoPorEstimadaSegunLapso($añoImpositivo);
		}



		/**
		 * Metodo que retorna los pagos del año por definitiva.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return array retorna un arreglo con el modelo de consulta.
		 */
		public function getPagoPorDefinitivaSegunLapso($añoImpositivo, $periodo)
		{
			return self::pagoPorDefinitivaSegunLapso($añoImpositivo, $periodo);
		}




		/**
		 * Metodo que retorna los pagos del año por Abono y/o similares.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @return array retorna un arreglo con el modelo de consulta.
		 */
		public function getPagoAbonoActEconomica($añoImpositivo)
		{
			$idImpuestoExcluir = self::getIdImpuestoExcluido($añoImpositivo);
			return self::pagoAbonoActEconomica($añoImpositivo, $idImpuestoExcluir);
		}




		/**
		 * Metodo que envia a contabilizar los pagos por estimada realizadas en año.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return double retorna un monto del total contabilizado.
		 */
		public function getContabilizarPagoPorEstimada($añoImpositivo, $periodo)
		{
			$total = 0;

			// if ( $periodo > 0 ) {
			$pagos = self::getPagoEstimadaSegunAnoImpositivo($añoImpositivo)->asArray()->all();
			if ( count($pagos) > 0 ) {
				$total = self::getContabilizar($pagos);
			}
			// } else {
				// Se coloca una consulta que busque los pagos por el año-periodo.
			// }

			return (float)$total;
		}



		/**
		 * Metodo que envia a contabilizar los pagos por definitiva.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return double retorna un monto del total contabilizado.
		 */
		public function getContabilizarPagoPorDefinitiva($añoImpositivo, $periodo)
		{
			$total = 0;
			$pagos = self::getPagoPorDefinitivaSegunLapso($añoImpositivo, $periodo)->asArray()->all();
			if ( count($pagos) > 0 ) {
				$total = self::getContabilizar($pagos);
			}

			return (float)$total;
		}



		/**
		 * Metodo que envia a contabilizar los pagos por Abono.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return double retorna un monto del total contabilizado.
		 */
		public function getContabilizarPagoAbonoActEconomica($añoImpositivo)
		{
			$total = 0;
			$pagos = self::getPagoAbonoActEconomica($añoImpositivo)->asArray()->all();
			if ( count($pagos) > 0 ) {
				$total = self::getContabilizar($pagos);
			}

			return (float)$total;
		}



		/**
		 * Metodo que contabiliza los diferentes monto para calcular el pago total realizado
		 * segun el parametro $pagos.
		 * @param  array $pagos arreglo de la consulta pagos y pagos-detalle.
		 * @return double retorna un monto del total contabilizado.
		 */
		public function getContabilizar($pagos)
		{
			$total = 0;
			$subTotal1 = 0;
			$subTotal2 = 0;
			if ( count($pagos) > 0 ) {
				foreach ( $pagos as $pago ) {
					$subTotal1 = $pago['monto']; // + $pago['recargo'] + $pago['interes'];
					//$subTotal2 = $pago['descuento'] + $pago['monto_reconocimiento'];

					$total = $total + ($subTotal1 - $subTotal2);
				}

				$total = $total;
			}

			return $total;
		}



		/**
		 * Metodo que genera un resumen de los pagos de un congtribuyente de actividad economica
		 * por conceptos especificos, los cuales seran utilizados en el calculo de la definitiva.
		 * @param  integer  $añoImpositivo año impositivo del lapso.
		 * @param  integer  $periodo periodo del lapso.
		 * @return array retorna un arreglo con un resumen de los pagos por conceptos.
		 */
		public function getResumenPagoDefinitiva($añoImpositivo, $periodo)
		{

			$resumen = [
				'pagoEstimada' => self::getContabilizarPagoPorEstimada($añoImpositivo, $periodo),
				'pagoNoEstimada' => self::getDeudaActividadEconomicaPendiente($añoImpositivo),
				'pagoDefinitiva' => self::getContabilizarPagoPorDefinitiva($añoImpositivo, $periodo),
				'pagoAbono' => self::getContabilizarPagoAbonoActEconomica($añoImpositivo),
				'pagoRetencion' => 0,
				'pagoIndustria' => 0,

			];

			return $resumen;
		}



		/***/
		public function getListaPagoActEcon()
		{
			return $listaPagos = [
						'pagoEstimada' => 'TOTAL PAGOS POR ESTIMADA',
						'pagoNoEstimada' => 'TOTAL PERIODOS ESTIMADA NO PAGADOS',
						'pagoDefinitiva' => 'TOTAL PAGOS COMPLEMENTARIOS',
						'pagoAbono' => 'TOTAL PAGOS POR ABONOS Y/O SIMILARES',
						'pagoRetencion' => 'TOTAL PAGOS POR RETENCIONES Y/O RECONOCIMIENTOS',
						'pagoIndustria' => 'TOTAL PAGOS POR SEDE DE INDUSTRIA'
					];
		}





		/**
		 * Metodo que consulta los pagos de un inmueble especifico, solo considerando los
		 * periodos mayores a cero.
		 * @param  integer $idImpuesto idenificador del inmueble.
		 * @return active record retorna los registros de pagos del inmueble. null si no
		 * encuentra nada.
		 */
		public function getPagoInmuebleEspecifico($idImpuesto)
		{
			self::setIdImpuesto($idImpuesto);
			self::setImpuesto(2);
			$model = self::getModelPagoObjetoImpositivo('>');	// Periodos mayores a cero.
			$pagos = $model->joinWith('exigibilidad E')
			               ->joinWith('impuestos I')
			               ->asArray()
			               ->all();
			if ( count($pagos) > 0 ) {
				return $pagos;
			}
			return null;
		}




		/***/
		public function getPagoInmuebleEspecificoSegunAñoImpositivo($idImpuesto, $añoImpositivo)
		{
			self::setIdImpuesto($idImpuesto);
			self::setImpuesto(2);
			$model = self::getModelPagoObjetoImpositivo('>');	// Periodos mayores a cero.
			$pagos = $model->andWhere('D.ano_impositivo =:ano_impositivo',
											[':ano_impositivo' => $añoImpositivo])
			               ->asArray()
			               ->all();
			if ( count($pagos) > 0 ) {
				return $pagos;
			}
			return null;
		}





		/**
		 * Metodo que consulta los pagos de un Aseo especifico, solo considerando los
		 * periodos mayores a cero.
		 * @param  integer $idImpuesto idenificador del Aseo.
		 * @return active record retorna los registros de pagos del Aseo. null si no
		 * encuentra nada.
		 */
		public function getPagoAseoEspecifico($idImpuesto)
		{
			self::setIdImpuesto($idImpuesto);
			self::setImpuesto(12);
			$model = self::getModelPagoObjetoImpositivo('>');	// Periodos mayores a cero.
			$pagos = $model->joinWith('exigibilidad E')
			               ->joinWith('impuestos I')
			               ->asArray()
			               ->all();
			if ( count($pagos) > 0 ) {
				return $pagos;
			}
			return null;
		}




		/**
		 * Metodo que consulta los pagos de un vehiculo especifico, solo considerando los
		 * periodos mayores a cero.
		 * @param  integer $idImpuesto idenificador del vehiculo.
		 * @return active record retorna los registros de pagos del vehiculo. null si no
		 * encuentra nada.
		 */
		public function getPagoVehiculoEspecifico($idImpuesto)
		{
			self::setIdImpuesto($idImpuesto);
			self::setImpuesto(3);
			$model = self::getModelPagoObjetoImpositivo('>');	// Periodos mayores a cero.
			$pagos = $model->joinWith('exigibilidad E')
			               ->joinWith('impuestos I')
			               ->asArray()
			               ->all();

			if ( count($pagos) > 0 ) {
				return $pagos;
			}
			return null;
		}





		/**
		 * [getPagoVehiculoEspecificoSegunAñoImpositivo description]
		 * @param  [type] $idImpuesto    [description]
		 * @param  [type] $añoImpositivo [description]
		 * @return [type]                [description]
		 */
		public function getPagoVehiculoEspecificoSegunAñoImpositivo($idImpuesto, $añoImpositivo)
		{
			self::setIdImpuesto($idImpuesto);
			self::setImpuesto(3);
			$model = self::getModelPagoObjetoImpositivo('>');	// Periodos mayores a cero.
			$pagos = $model->andWhere('D.ano_impositivo =:ano_impositivo',
											[':ano_impositivo' => $añoImpositivo])
			               ->asArray()
			               ->all();

			if ( count($pagos) > 0 ) {
				return $pagos;
			}
			return null;
		}







		/**
		 * Metodo que realiza la consulta de los pagos sobre Actividades Economicas
		 * @return active record modelo con la consulta realziadas.
		 */
		public function getPagoPeriodoActividadEconomica()
		{
			$findModel = self::getModelGeneralActividadEconomica();
			$model = $findModel->andWhere('trimestre >:trimestre',
			 									[':trimestre' => 0])
							   ->joinWith('exigibilidad E')
							   ->joinWith('impuestos I')
							   ->asArray()
							   ->all();

			return $model;

		}




		/**
		 * Metodo que retorna el ultimo lapso liquidado pagado.
		 * @return array retorna el arrego con los datos del ultimo periodo pagado.
		 */
		public function getUltimoLapsoPagoActividadEconomica()
		{
			$ultimo = null;
			$pagos = self::getPagoPeriodoActividadEconomica();
die(var_dump($pagos));
			if ( count($pagos) > 0 ) {
				$ultimo = end($pagos);
			}
			return $ultimo;
		}





		/**
		 * Metodo que retorna el ultimo lapso liquidado pagado.
		 * @return array retorna el arrego con los datos del ultimo periodo pagado.
		 */
		public function getUltimoLapsoPagoObjeto($impuesto, $idImpuesto)
		{
			$ultimo = null;
			$pagos = null;

			if ( $impuesto == 2 ) {

				$pagos = self::getPagoInmuebleEspecifico($idImpuesto);

			} elseif ( $impuesto == 3 ) {

				$pagos = self::getPagoVehiculoEspecifico($idImpuesto);

			} elseif ( $impuesto == 12 ) {

				$pagos = self::getPagoAseoEspecifico($idImpuesto);

			}

			if ( count($pagos) > 0 ) {
				$ultimo = end($pagos);
			}
			return $ultimo;
		}






		/**
		 * Metodo que retorna el modelo de consulta para los pagos existentes de un objeto
		 * imponible. Objeto imponible se refiere a Inmuebles, Vehiculos, Propagandas, etc.
		 * Los registros se ordenan por año impositivo, trimestres ascendente.
		 * @param  string $tipoPeriodo tipo de periodo que se requiere en la consulta. Aqui
		 * periodo se refiere al atributo "trimestre" de la entidad "pagos-detalle".
		 * - los tipos de periodos seran:
		 * 1. =, iguales a cero.
		 * 2. >, mayores a cero.
		 * 3. >=, mayores e iguales a cero.
		 * @return active record retorna un modelo para la consulta.
		 */
		private function getModelPagoObjetoImpositivo($tipoPeriodo)
		{
			$model = null;

			// Se controla el tipo de periodo.
			if ( in_array($tipoPeriodo, ['=', '>', '>=']) ) {

				if ( $this->_impuesto == 2 || $this->_impuesto == 12 ) {

					// Pagos de inmuebles.
					$findModel = self::getModelGeneralInmueble();
					$model = $findModel->andWhere('id_impuesto =:id_impuesto',
														[':id_impuesto' => $this->_id_impuesto])
									   ->andWhere('trimestre '. $tipoPeriodo .':trimestre',
									   					[':trimestre' => 0])
									   ->orderBy([
									   		'ano_impositivo' => SORT_ASC,
									   		'trimestre' => SORT_ASC
									   	]);

				} elseif ( $this->_impuesto == 3 ) {

					// Pagos de vehiculos.
					$findModel = self::getModelGeneralVehiculo();
					$model = $findModel->andWhere('id_impuesto =:id_impuesto',
														[':id_impuesto' => $this->_id_impuesto])
									   ->andWhere('trimestre '. $tipoPeriodo .':trimestre',
									   					[':trimestre' => 0])
									   ->orderBy([
									   		'ano_impositivo' => SORT_ASC,
									   		'trimestre' => SORT_ASC
									   	]);

				}
			}

			return $model;
		}



		public function getDeudaActividadEconomicaPendiente($añoImpositivo)
		{
			$idImpuestoExcluido = self::getIdImpuestoExcluido($añoImpositivo);

			if ( count($idImpuestoExcluido) > 0 ) {
				$deuda1 = PagoDetalle::find()->alias('D')
				                            ->joinWith('pagos P', true, 'INNER JOIN')
											->where('id_contribuyente =:id_contribuyente',
														[':id_contribuyente' => $this->_id_contribuyente])
											->andWhere('D.ano_impositivo =:ano_impositivo',
														[':ano_impositivo' => $añoImpositivo])
											->andWhere('D.pago =:pago',
														[':pago' => 0])
											->andWhere('D.impuesto =:impuesto',
														[':impuesto' => 1])
											->andWhere('D.trimestre =:trimestre',
														[':trimestre' => 0])
											->andWhere(['NOT IN', 'id_impuesto', $idImpuestoExcluido])
											->asArray()
											->all();
			} else {
				$deuda1 = PagoDetalle::find()->alias('D')
				                            ->joinWith('pagos P', true, 'INNER JOIN')
											->where('id_contribuyente =:id_contribuyente',
														[':id_contribuyente' => $this->_id_contribuyente])
											->andWhere('D.ano_impositivo =:ano_impositivo',
														[':ano_impositivo' => $añoImpositivo])
											->andWhere('D.pago =:pago',
														[':pago' => 0])
											->andWhere('D.impuesto =:impuesto',
														[':impuesto' => 1])
											->andWhere('D.trimestre =:trimestre',
														[':trimestre' => 0])
											->asArray()
											->all();

			}

			$suma1 = 0;
			foreach ( $deuda1 as $d ) {
				$suma1 =  $suma1 + $d['monto'];
			}


			$deuda2 = PagoDetalle::find()->alias('D')
		                            ->joinWith('pagos P', true, 'INNER JOIN')
									->where('id_contribuyente =:id_contribuyente',
												[':id_contribuyente' => $this->_id_contribuyente])
									->andWhere('D.ano_impositivo =:ano_impositivo',
												[':ano_impositivo' => $añoImpositivo])
									->andWhere('D.pago =:pago',
												[':pago' => 0])
									->andWhere('D.impuesto =:impuesto',
												[':impuesto' => 1])
									->andWhere('D.trimestre >:trimestre',
												[':trimestre' => 0])
									->andWhere('D.referencia =:referencia',
												[':referencia' => 0])
									->asArray()
									->all();

			$suma2 = 0;
			foreach ( $deuda2 as $d ) {
				$suma2 =  $suma2 + $d['monto'];
			}


			$total = 0;
			$total = $suma1 + $suma2;

			return $total;
		}


	}
