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
 *  @file Liquidar.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class Liquidar
 *  @brief Clase modelo que gestiona la liquidacion de Inmuebles Urbanos en el formato
 *  de colocar todos los periodos que se liquidaran en una planilla.
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

	namespace backend\models\inmueble\liquidar;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\calculo\liquidacion\inmueble\LiquidacionInmueble;
	use common\models\calculo\recargo\Recargo;
	use yii\data\ArrayDataProvider;
	use common\models\planilla\Pago;
	use backend\models\inmueble\InmueblesConsulta;





	/**
	* Clase que gestiona la liquidacion de Inmuebles Urbanos, donde los nuevos
	* periodos a liquidar se guardaran en una planilla. Se determina cual es el
	* ultimo lapso (año-periodo) liquidado y se determina su condicion del mismo,
	* si el ultimo lapso esta pagado se debe generar un nuevo numero de planilla
	* para guardar los nuevos lapsos, sino es asi, los nuevos lapsos se guardaran
	* en la utlima planilla que existe pendiente.
	*/
	class Liquidar
	{

		private $_planilla;					// Numero de planilla para los nuevos lapsos.
		private $_id_pago = 0;
		private $_detalleUltimoLapso;		// Ultimo lapso liquidado.

		private $_contribuyente;			// Instancia de la clase ContribuyenteBase().
		private $_detalleLiquidacion = [];	// Detalle de los lapsos liquidados con sus
											// repesctivos montos.

		private $_liquidarInmueble;		// Instabcia de la clase LiquidacionInmueble().
		private $_tipoLiquidacion;
		private $_objeto;				// Instancia de la clase InmueblesConsulta().


		const IMPUESTO = 2;

		private $_controlErrors = [];





		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente, $idImpuesto)
		{
			$this->_contribuyente = ContribuyenteBase::findOne($idContribuyente);
			$this->_objeto = InmueblesConsulta::findOne($idImpuesto);
			$this->_liquidarInmueble = New LiquidacionInmueble($idImpuesto);
			$this->_id_pago = 0;
		}




		/***/
		public function getAnoInicio()
		{
			return $this->_objeto->ano_inicio;
		}



		/***/
		public function getLapsoLiquidado()
		{
			return $this->_detalleLiquidacion;
		}


		/**
		 * Metodo que inicia el proceso de liquidacion y el mismo debe devolver una arreglo
		 * con los laspsos liquidados y sus respectivos montos.
		 * @return array
		 */
		public function iniciarProcesoLiquidacion()
		{
			$this->_detalleLiquidacion = [];

			try {
				$rangoInicio = self::armarRangoLiquidacionInicial();

				if ( count($rangoInicio) > 0 ) {
					$rangoFinal = self::getUltimoLapso();				// Ultimo lapso del año actual.

					$añoInicio = (int)$rangoInicio['ano_impositivo'];
					$periodoInicio = (int)$rangoInicio['periodo'];
					$añoFinal = (int)$rangoFinal['ano_impositivo'];
					$periodoFinal = (int)$rangoFinal['periodo'];

					for ( $i = $añoInicio; $i <= $añoFinal; $i++ ) {
						if ( $i == $añoInicio ) {
							self::liquidarAnoImpositivoInmueble($i, $periodoInicio);

						} elseif ( $i > $añoInicio ) {

							self::liquidarAnoImpositivoInmueble($i, 1);

						}
					}

				}
				return $this->_detalleLiquidacion;

			} catch (Exception $e ) {
				return null;
			}

		}





		/**
		 * Metodo que determina el rango inicial para los calculos del impuesto.
		 * @return array retorna arreglo con los parametros para inicial la liquidacion.
		 */
		private function armarRangoLiquidacionInicial()
		{
			$this->_id_pago = 0;
			$añoComienzo = 0;
			$periodoComienzo = 0;
			$lapsoInicio = [];
			$ultimoLapso = self::getUltimoLapsoLiquidado();
			if ( count($ultimoLapso) > 0 ) {
				$ultimoAño = (int)$ultimoLapso['ano_impositivo'];
				$ultimoPeriodo = (int)$ultimoLapso['trimestre'];
				if ( $ultimoLapso['pago'] == 0 ) {
					$this->_id_pago = $ultimoLapso['id_pago'];
				}

				// Ultimo año es igual al año actual.
				if ( $ultimoAño == (int)date('Y') ) {

					$exigibilidadLiq = self::getExigibilidadLiquidacion($ultimoAño);
					if ( $ultimoPeriodo == (int)$exigibilidadLiq['exigibilidad'] ) {
						// No hay mas periodos que liquidar.
						self::setErrors("No existen mas periodos por liquidar para el año {$ultimoAño}");

					} elseif ( $ultimoPeriodo < $exigibilidadLiq ) {

						$añoComienzo = $ultimoAño;
						$periodoComienzo = $ultimoPeriodo + 1;
					}

				// Ultimo año es anterior al año actual.
				} elseif ( $ultimoAño < (int)date('Y') ) {

					$exigibilidadLiq = self::getExigibilidadLiquidacion($ultimoAño);
					if ( $ultimoPeriodo == (int)$exigibilidadLiq['exigibilidad'] ) {

						$añoComienzo = $ultimoAño + 1;
						$periodoComienzo = 1;

					} elseif ( $ultimoPeriodo < $exigibilidadLiq ) {

						$añoComienzo = $ultimoAño;
						$periodoComienzo = $ultimoPeriodo + 1;

					}
				}

				if ( $añoComienzo > 0 && $periodoComienzo > 0 ) {
					$lapsoInicio = [
						'ano_impositivo' => $añoComienzo,
						'periodo' => $periodoComienzo,
					];
				}

			} else {
				// Significa que no tiene periodos liquidados.
				// Se tomara como fecha de comienzo de la liquidacion, la fecha de inicio de
				// actividades del contribuyente.

				$lapsoInicio = self::definirLapsoInicio();

			}

			return $lapsoInicio;
		}




		/**
		 * Metodo que realizara el calculo de la liquiadacion por año. Se crea un ciclo desde
		 * primer periodo faltante del año hasta el utlimo periodo. Se realiza el calculo luego
		 * se determinan los demas montos como recargos, intereses y descuento.
		 * @param  integer $año año impositivo.
		 * @param  integer $desdePeriodo primer periodo fañltante por liquidar, desde aqui se
		 * comenzara la liquidacion. Luego se determinara en el metodo el periodo final del ciclo
		 * del calculo.
		 * @return array retorna un arreglo con los detalles de la liquidacion.
		 */
		private function liquidarAnoImpositivoInmueble($año, $desdePeriodo)
		{
			$montoCalculado = 0;			// Monto calculado para el año impositivo.
			$modelDetalle = [];
			$exigibilidadLiq = self::getExigibilidadLiquidacion($año);
			$exigibilidadDec = self::getExigibilidadDeclaracion($año);

			if ( count($exigibilidadLiq) > 0 && count($exigibilidadDec) > 0 ) {

				$exigDeclaracion = (int)$exigibilidadDec['exigibilidad'];
				if ( $exigDeclaracion == 1 ) {
					$periodo = $exigDeclaracion;
				} else {
					$periodo = $desdePeriodo;
				}

				// Se realiza el calculo de la liquidacion del año.
				$this->_liquidarInmueble->setAnoImpositivo($año);
				$montoCalculado = $this->_liquidarInmueble->iniciarCalcularLiquidacionInmueble();

				if ( $montoCalculado > 0 ) {
					$exigLiq = (int)$exigibilidadLiq['exigibilidad'];
					$montoPeriodo = self::getMontoPorPeriodo($montoCalculado, $exigLiq);

					// Se crea unu ciclo con los periodos faltantes
					$hastaPeriodo = $exigLiq;

					$fechaVcto = OrdenanzaBase::getFechaVencimientoSegunFecha(date('Y-m-d'));

					$recargo = New Recargo(self::IMPUESTO);

					$j = 0;
					for ( $i = $desdePeriodo; $i <= $hastaPeriodo; $i++) {

						$montoRecargo = 0;
						$recargo->calcularRecargo($año, $i, $montoPeriodo);
						$montoRecargo = $recargo->getRecargo();

						$modelDetalle[$j] = New PagoDetalle();
						$modelDetalle[$j]->id_pago = $this->_id_pago;
						$modelDetalle[$j]->id_impuesto = $this->_objeto->id_impuesto;
						$modelDetalle[$j]->impuesto = self::IMPUESTO;
						$modelDetalle[$j]->ano_impositivo = $año;
						$modelDetalle[$j]->trimestre = $i;
						$modelDetalle[$j]->monto = $montoPeriodo;
						$modelDetalle[$j]->recargo = $montoRecargo;
						$modelDetalle[$j]->interes = 0;
						$modelDetalle[$j]->descuento = 0;
						$modelDetalle[$j]->pago = 0;
						$modelDetalle[$j]->fecha_pago = '0000-00-00';
						$modelDetalle[$j]->referencia = 0;
						$modelDetalle[$j]->descripcion = 'LIQUIDACION DE INMUEBLES URBANOS';
						$modelDetalle[$j]->monto_reconocimiento = 0;
						$modelDetalle[$j]->fecha_emision = date('Y-m-d');
						$modelDetalle[$j]->fecha_vcto = $fechaVcto;
						$modelDetalle[$j]->exigibilidad_pago = $exigibilidadLiq['exigibilidad'];

						$j++;
					}
				} else {
					self::setErrors('El calculo de la liquidacion resulto en cero (0)');
				}

				$model = [];
				if ( count($modelDetalle) > 0 ) {
					foreach ( $modelDetalle as $key => $value ) {

						$model[$key] = $value->attributes;
						$this->_detalleLiquidacion[] = $value->attributes;

					}
				}
				return $model;

			}

			return false;

		}



		/**
		 * Metodo que retorna el modelo general de consulta de los periodos
		 * @return PagoDetalle
		 */
		private function findLapsoModel()
		{
			return PagoDetalle::find()->alias('D')
									  ->where('id_impuesto=:id_impuesto',
													[':id_impuesto' => $this->_objeto->id_impuesto])
									  ->andWhere(['IN', 'pago', [0, 1, 7]])
									  ->andWhere('trimestre >:trimestre',['trimestre' => 0])
									  ->andWhere('impuesto =:impuesto',[':impuesto' => self::IMPUESTO])
									  ->joinWith('pagos P', true, 'INNER JOIN')
									  ->joinWith('exigibilidad E')
									  ->joinWith('estatus S', true, 'INNER JOIN')
									  ->orderBy([
									  		'ano_impositivo' => SORT_DESC,
									  		'trimestre' => SORT_DESC,
									  	]);
		}




		/**
		 * Metodo para obtener el ultimo periodo liquidado. Deber retornar una
		 * estuctura como la siguiente:
		 *
		 * @return array retorna los datos del ultimo lapso. En caso contrario null.
		 */
		public function getUltimoLapsoLiquidado()
		{
			$findModel = self::findLapsoModel();	// Modelo general de consulta.

			return $model = $findModel->asArray()->one();
		}





		/**
		 * Metodo que devuelve el ultimo lapso del año actual.
		 * @return array retorna un arreglo con la estructura:
		 * $lapso = [
					'año' => $año,
					'periodo' => $exigibilidadLiq['exigibilidad'],
				];
		 */
		public function getUltimoLapso()
		{
			$lapso = [];
			$año = (int)date('Y');
			// Lo siguiente retorna un arreglo con la informacion de la entidad "exigibilidades".
			// Todos los atributos de la entidad.
			$exigibilidadLiq = OrdenanzaBase::getExigibilidadLiquidacion($año, self::IMPUESTO);
			if ( count($exigibilidadLiq) > 0 ) {
				$lapso = [
					'ano_impositivo' => $año,
					'periodo' => $exigibilidadLiq['exigibilidad'],
				];
			}

			return $lapso;
		}




		/**
		 * Metodo que determina la exigibilidad de la declaracion para un año especifico.
		 * @param  integer $año año impositivo de la declaracion.
		 * @return array retorna un arreglo con los atributos de la entidad "exigibilidades".
		 */
		private function getExigibilidadDeclaracion($año)
		{
			return $exigibilidadDec = OrdenanzaBase::getExigibilidadDeclaracion($año, self::IMPUESTO);
		}



		/***/
		private function getExigibilidadLiquidacion($año)
		{
			return $exigibilidadLiq = OrdenanzaBase::getExigibilidadLiquidacion($año, self::IMPUESTO);
		}



		/**
		 * Metodo que determina el monto por periodo que se debe asignar a cada periodo segun la
		 * exigiblidada de liquidacion del año.
		 * @param  double $montoCalculado monto calculado del impuesto del año.
		 * @param  integer $exigibilidadLiq numero de veces que se debe liquidar en el año.
		 * @return double retorna el monto que le corresponde a cada periodo.
		 */
		private function getMontoPorPeriodo($montoCalculado, $exigibilidadLiq)
		{
			$monto = 0;
			if ( $montoCalculado > 0 ) {
				$exigibilidadLiq;

				if ( $exigibilidadLiq > 0 ) {
					$monto = $montoCalculado / $exigibilidadLiq;
				}
			}

			return $monto;
		}




		/**
		 * Metodo que determina el lapso para iniciar los calculos del impuesto de Inmueble Urbano.
		 * Esto aplica cuando el inmueble no posee liquidaciones y se procede a liquidadrlo por
		 * primera vez en el impuesto de inmuebles urbanos. Se toma como parametro para la determinacion
		 * de este lapso el año de inicio que posea el inmueble, se compara contra un año
		 * limite para iniciar los calculos de los impuestos. Si el año de inicio es menor al
		 * año limite establecido para los calculos se tomara el año limite como el inicio del lapso para
		 * los calculos.
		 * @return array retorma un arreglo donde dicho arreglo posee dos inidice, ano-impositivo y periodo
		 */
		private function definirLapsoInicio()
		{
			$añoComienzo = 0;
			$periodoComienzo = 0;
			$lapsoInicio = [];
			$añoInicio = (int)$this->_objeto->ano_inicio;

			if ( strlen($añoInicio) == 4 ) {		// Longitud de año valida.

				$añoLimite = (int)Yii::$app->lapso->anoLimiteNotificado();

				if ( $añoInicio < $añoLimite ) {
					$añoComienzo = $añoLimite;
					$periodoComienzo = 1;

					$lapsoInicio = [
						'ano_impositivo' => $añoComienzo,
						'periodo' => $periodoComienzo,

					];

				} elseif ( $añoInicio >= $añoLimite ) {
					$exigibilidadLiq = self::getExigibilidadLiquidacion($añoInicio);

					$añoComienzo = $añoInicio;
					$periodoComienzo = 1;

					$lapsoInicio = [
						'ano_impositivo' => $añoComienzo,
						'periodo' => $periodoComienzo,

					];

				}
			} else {
				self::setErrors('No se pudo obtener la fecha de inicio de actividades');
			}

			return $lapsoInicio;

		}



		/**
		 * Metodo que setea un arreglo de mensajes de errores que pueden ocurrir durenate el
		 * proceso.
		 * @param string $errorMensaje mensaje de error.
		 */
		private function setErrors($errorMensaje)
		{
			$this->_controlErrors[] = $errorMensaje;
		}




		/**
		 * Metodo que permite obtener los mensajes de errores.
		 * @return array retorna un arreglo de mensajes.
		 */
		public function getErrors()
		{
			return $this->_controlErrors;
		}



		/**
		 * Metodo que genera un provider del tipo ArrayDataProvider
		 * @return ArrayDataProvider
		 */
		public function getDataProviderDetalle()
		{
			if ( count($this->_detalleLiquidacion) > 0 ) {

				$provider = New ArrayDataProvider([
									'allModels' => $this->_detalleLiquidacion,
									'pagination' => false,
						]);

				return $provider;
			}

			return null;
		}


	}


?>