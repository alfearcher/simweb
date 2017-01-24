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
 *  @file LiquidarDefinitivaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class LiquidarDefinitivaSearch
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

 	namespace backend\models\aaee\liquidar;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\actecon\ActEconSearch;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoSearch;
	use common\models\ordenanza\OrdenanzaBase;
	use yii\helpers\ArrayHelper;
	use backend\models\aaee\rubro\Rubro;
	use common\models\calculo\liquidacion\aaee\CalculoRubro;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use common\models\planilla\PagoDetalle;
	use common\models\pago\PagoSearch;
	use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;


	/**
	 * Clase que gestiona el proceso de liquidacion de la declaracion definitiva
	 *
	 */
	class LiquidarDefinitivaSearch
	{

		private $_id_contribuyente;
		private $_ano_impositivo;
		private $_periodo;

		/**
		 * Variable que contiene:
		 * - rubro
		 * - descripcion del rubro
		 * - alicuota
		 * - minino
		 * - año
		 * - id-impuesto
		 * - id-rubro
		 * - impuesto calculado
		 * - monto declarado por el contribuyente.
		 * @var array
		 */
		private $_detalleDeclaracion;


		/**
		 * Variable que contiene todos los detalles de la liquidacion, specialmente
		 * lo contenido en la entidad PagoDetalle.
		 * @var array
		 */
		private $detalleLiquidacion;


		/**
		 * Variable instancia de la clase DeclaracionBaseSearch()
		 * @var DeclaracionBaseSearch
		 */
		public $declaracionSearch;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $añoImpositivo año impositivo de la declaracion.
		 * @param integer $periodo periodo correspondiente.
		 */
		public function __construct($idContribuyente, $añoImpositivo, $periodo)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_ano_impositivo = $añoImpositivo;
			$this->_periodo = $periodo;

			$this->declaracionSearch = New DeclaracionBaseSearch($idContribuyente);
		}




		/**
		 * Metodo que genera el modelo general de consulta de los rubros con sus respectivas
		 * montos declrados.
		 * @return ActEconIngreso
		 */
		public function findDetalleDeclaracionModel()
		{
			return $this->declaracionSearch->findRubrosRegistrados($this->_ano_impositivo, $this->_periodo);
		}





		/**
		 * Metodo que determina si existe una definitiva anterior a la que se pretende
		 * liquidar faltante. El metodo debe retornar una arreglo de mensaje con los
		 * lapsos faltantes.
		 * @return array retorna arreglo de mensaje.
		 */
		public function determinarLiquidacionFaltante()
		{
			$añoLimite = Yii::$app->lapso->anoLimiteNotificado();
			$mensaje = [];
			$existe = true;
			$resultados = ActEcon::find()->alias('A')
										 ->distinct('ano_impositivo')
	                                     ->where('id_contribuyente =:id_contribuyente',
	                                   			[':id_contribuyente' => $this->_id_contribuyente])
	                                     ->andWhere(['BETWEEN', 'ano_impositivo', $añoLimite, $this->_ano_impositivo-1])
	                                     ->andWhere('estatus =:estatus',[':estatus' => 0])
	                                     ->andWhere('inactivo =:inactivo',[':inactivo' => 0])
	                                     ->joinWith('actividadDetalle I', true, 'INNER JOIN')
	                                     ->orderBy([
                                     			'ano_impositivo' => SORT_ASC,
                                   	     	])
	                                     ->asArray()
                                         ->all();

		    if ( count($resultados) > 0 ) {

		    	foreach ( $resultados as $rs ) {

		    		if ( self::existeDiferenciaDefinitivaEstimada((int)$rs['ano_impositivo'], 1) ) {

		    			$existe = self::existePlanillaDefinitiva((int)$rs['ano_impositivo'], 1);

			    		if ( !$existe ) {
			    			$mensaje[] = Yii::t('backend', 'Falta por liquidar el laspo ' . $rs['ano_impositivo'] . ' - ' . 1);
			    		}
			    	}
		    	}
		    }

		    return $mensaje;

		}




		/**
		 * Metodo que determina si una planilla de la definituva para un lapso determinado
		 * existe.
		 * @param integer $añoImpositivo año impositivo consultado.
		 * @param  integer $periodo  periodo consultado.
		 * @return boolean
		 */
		public function existePlanillaDefinitiva($añoImpositivo, $periodo)
		{
			return PagoDetalle::find()->alias('D')
			                          ->where('id_contribuyente =:id_contribuyente',
			                          			[':id_contribuyente' => $this->_id_contribuyente])
			                          ->andWhere('ano_impositivo =:ano_impositivo',
			                          			[':ano_impositivo' => $añoImpositivo])
			                          ->andWhere('trimestre =:trimestre',
			                          			[':trimestre' => $periodo])
			                          ->andWhere('impuesto =:impuesto',[':impuesto' => 1])
			                          ->andWhere('referencia =:referencia',[':referencia' => 1])
			                          ->andWhere('pago !=:pago',[':pago' => 9])
			                          ->joinWith('pagos P', true, 'INNER JOIN')
			                          ->exists();
		}




		/**
		 * Metodo que determina si la suma de los montos de la definitica es mayor s cero(0).
		 * @return boolean
		 */
		private function montoDeclarado()
		{
			$result = false;
			$findModel = self::findDetalleDeclaracionModel();
			$resultados = $findModel->asArray()->all();
			$suma = 0;

			if ( count($resultados) > 0 ) {
				foreach ( $resultados as $resultado ) {
					$suma = $resultado['reales'] + $suma;
				}
				if ( $suma > 0 ) { $result = true; }
			}

			return $result;
		}





		/***/
		public function validarEvento()
		{
			$mensajes = [];
			$existe =  false;
			// Valida que exista las liquidaciones anteriores.
			$mensaje = self::determinarLiquidacionFaltante();

			if ( count($mensaje) > 0 ) {
				$mensajes[] = $mensaje;
			}

			// Valida si ya exite la liquidacion.
			$existe = self::existePlanillaDefinitiva($this->_ano_impositivo, $this->_periodo);
			if ( $existe ) {
				$mensajes[] = Yii::t('backend', 'Ya existe la liquidacion del lapso ' . $this->_ano_impositivo . ' - ' . $this->_periodo);
			}

			// Valida que el monto declarado sea mayor a cero (0).
			if ( !self::montoDeclarado() ) {
				$mensajes[] = Yii::t('backend', 'El monto declarado del lapso ' . $this->_ano_impositivo . ' - ' . $this->_periodo . ' es cero (0)');
			}


			// Valida que existe la liquidacion estimada del lapso.
			$resultado = self::exiteLiquidacionEstimadaDelLapso($this->_ano_impositivo);

			if ( !$resultado ) {
				$mensajes[] = Yii::t('backend', 'No se determino las liquidaciones de la estimada para el lapso ' . $this->_ano_impositivo . ' - ' . $this->_periodo);
			}


			return $mensajes;

		}




		/***/
		public function datosDeclaracionImpuesto()
		{
			$findModel = self::findDetalleDeclaracionModel();
			$resultados = $findModel->asArray()->all();
			$data = [];


			foreach ( $resultados as $key => $value ) {

				$calculo = New CalculoRubro($value);

				$data[] = [
					'id_rubro' => $value['id_rubro'],
					'rubro' => $value['rubroDetalle']['rubro'],
					'alicuota' => $value['rubroDetalle']['alicuota'],
					'minimo' => $calculo->getMinimoTributableRubro(),
					'minimo_ut' => $value['rubroDetalle']['minimo_ut'],
					'ano_impositivo' => $value['actividadEconomica']['ano_impositivo'],
					'id_impuesto' => $value['id_impuesto'],
					'descripcion' => $value['rubroDetalle']['descripcion'],
					'declaracion' => $value['reales'],
					'impuesto' => $calculo->getCalcularPorTipoDeclaracion('reales'),
					'id_contribuyente' => $value['actividadEconomica']['id_contribuyente'],
				];
			}

			return $data;
		}






		/***/
		public function getArrayDataProviderDeclaracionImpuesto()
		{
			$data = self::datosDeclaracionImpuesto();
			$provider = New ArrayDataProvider([
						'allModels' => $data,
						'pagination' => false,

					]);

			return $provider;
		}




		/***/
		public function sumaDeclarado($data)
		{
			if ( count($data) == 0 ) {
				$data = self::datosDeclaracionImpuesto();
			}

			$suma = 0;
			if ( count($data) > 0 ) {
				foreach ( $data as $d ) {
					$suma = $suma + $d['declaracion'];
				}
			}

			return $suma;
		}




		/***/
		public function sumaImpuesto($data)
		{
			if ( count($data) == 0 ) {
				$data = self::datosDeclaracionImpuesto();
			}

			$suma = 0;
			if ( count($data) > 0 ) {
				foreach ( $data as $d ) {
					$suma = $suma + $d['impuesto'];
				}
			}

			return $suma;
		}




		/***/
		public function sumaPago($data)
		{
			if ( count($data) == 0 ) {
				$data = self::getResumenPagos();
			}

			$suma = 0;
			if ( count($data) > 0 ) {
				foreach ( $data as $d) {
					$suma = $suma + $d['monto'];
				}
			}

			return $suma;
		}





		/***/
		public function getResumenPagos($añoImpositivo = 0, $periodo = 0)
		{
			$data = [];
			$pagoSearch = New PagoSearch();
			$pagoSearch->setIdContribuyente($this->_id_contribuyente);

			if ( $añoImpositivo == 0 ) {

				$resumen = $pagoSearch->getResumenPagoDefinitiva($this->_ano_impositivo, $this->_periodo);

			} else {
				$resumen = $pagoSearch->getResumenPagoDefinitiva($añoImpositivo, $periodo);
			}

			$listaPagos = $pagoSearch->getListaPagoActEcon();

			foreach ( $resumen as $key => $value ) {

				$data[$key] = [
					'concepto' => $listaPagos[$key],
					'monto' => $value,
				];
			}

			return $data;
		}




		/***/
		public function getArrayDataProviderResumenPago()
		{
			$data = self::getResumenPagos();
			$provider = New ArrayDataProvider([
						'allModels' => $data,
						'pagination' => false,

					]);

			return $provider;
		}




		/***/
		public function getFechaVcto($fecha)
		{
			return OrdenanzaBase::getFechaVencimientoSegunFecha($fecha);
		}





		/***/
		public function exiteLiquidacionEstimadaDelLapso($añoImpositivo)
		{
			return $resultados = PagoDetalle::find()->alias('D')
											 ->where('id_contribuyente =:id_contribuyente',
											 			[':id_contribuyente' => $this->_id_contribuyente])
											 ->andWhere('ano_impositivo =:ano_impositivo',
											 			[':ano_impositivo' => $añoImpositivo])
											 ->andWhere('trimestre >:trimestre',
											 			[':trimestre' => 0])
											 ->andWhere('referencia =:referencia',
											 			[':referencia' => 0])
											 ->andWhere('impuesto =:impuesto',[':impuesto' => 1])
											 ->andwhere('pago !=:pago',
											 			['pago' => 9])
											 ->joinWith('pagos P', true, 'INNER JOIN')
											 ->exists();

		}




		/**
		 * Metodo que permite detreminar si existe diferencias entre la liquidacion definitiva
		 * y la liquidacion estimada de un año-periodo especifico.
		 * @return boolean retorna true o false.
		 */
		public function existeDiferenciaDefinitivaEstimada($añoImpositivo, $periodo)
		{
			$liqEstimada = 0;
			$liqDefinitiva = 0;
			$suma = 0;
			$diferencia = 0;
			$existe = false;

			// Para calcular lo pagdo por estimada
			$diferenciaEstimadaPagoEstimada;

			$liquidacion = New LiquidacionActividadEconomica($this->_id_contribuyente);
			// Monto calculado por definitiva del laspo.
			$liquidacion->iniciarCalcularLiquidacion($añoImpositivo, $periodo, 'reales');
			$liqDefinitiva = round($liquidacion->getCalculoAnual(), 2);

			// Monto calculado por estimada del lapso
			$liquidacion->iniciarCalcularLiquidacion($añoImpositivo, $periodo, 'estimado');
			$liqEstimada = round($liquidacion->getCalculoAnual(), 2);

			// Resumen de los pagos por conceptos. Se recibe un arreglo donde el indice del arreglo
			// es un concepto u el valor del elemento es el monto de lo pagado por ese concepto.
			$pagos = self::getResumenPagos($añoImpositivo, $periodo);

			// Se contabiliza los pagos.
			$suma = self::sumaPago($pagos);

			$diferencia = $liqDefinitiva - $suma;

			if ( $diferencia > 0 ) { $existe = true;}

			return $existe;
		}





	}
 ?>