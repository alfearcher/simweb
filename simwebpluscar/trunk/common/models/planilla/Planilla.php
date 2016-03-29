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
 *  @file Planilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-03-2016
 *
 *  @class Planilla
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
	//use yii\base\Model;
	//use yii\db\ActiveRecord;
 	use common\models\calculo\liquidacion\LiquidacionActividadEconomica;
 	use common\models\planilla\Pago;
 	use common\models\planilla\PagoDetalle;
 	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;


	/**
	* 	Clase
	*/
	class Planilla
	{

		public $numeroPlanilla;
		private $_ente;
		private $_idContribuyente;
		private $_idImpuesto;
		private $_impuesto;
		private $_ultimaLiquidacion;
		private $_fechaInicio;
		public $añoDesde;
		public $añoHasta;
		public $periodoDesde;
		public $periodoHasta;
		public $monto = [];
		public $recargo = [];
		public $interes = [];
		public $descuento = [];
		public $montoReconocimiento = [];
		public $esUnaPlanillaVarios;
		public $esUnaDefinitiva = false;
		public $primeraLiquidacion;



		/**
		 * [__construct description]
		 * @param Long $idC, Identificador del Contribuyente.
		 * @param Long $idImpuesto, Identificador del Objeto imponible. Si $esVarios es true
		 * entonces ese valor corresponde al identificador de la entidad de "varios", si es
		 * false se refiere al identificador del objeto. Para determinar de que objeto se debe
		 * verificar el valor de $impuesto.
		 * @param Integer  $impuesto, Identificador del tipo de impuesto.
		 * @param boolean $esTasa, Si es true el valor de $idImpuesto corresponde al identificador
		 * de la entidad "varios", lo cual indica que la planilla es una tasa.
		 */
		public function __construct($idContribuyente, $esTasa = false, $esUnaDefinitiva = false)
		{
			$this->_ultimaLiquidacion = null;
			$this->_idContribuyente = $idContribuyente;
			$this->esUnaDefinitiva = $esUnaDefinitiva;
			if ( $esTasa ) {
				$this->esUnaDefinitiva = false;
			}
			$this->esUnaPlanillaVarios = $esTasa;
			$this->_fechaInicio = ContribuyenteBase::getFechaInicio($idContribuyente);
		}



		/***/
		public function setIdImpuesto($idImpuesto)
		{
			if ( !$this->esUnaDefinitiva ) {
				$this->_idImpuesto = $idImpuesto;
			}
		}



		/***/
		public function setAnoImpositivoDesde($año)
		{
			$this->añoDesde = $año;
		}



		/***/
		public function setPeriodoDesde($periodo)
		{
			$this->periodoDesde = $periodo;
		}



		/***/
		public function setAnoImpositivoHsta($año)
		{
			$this->añoHasta = $año;
		}



		/***/
		public function setPeriodoHasta($periodo)
		{
			$this->periodoHasta = $periodo;
		}



		/**
		 * Metodo que setea el valor de la variable impuesto.
		 * @param integer $imp impuesto de la planilla a ser liquidada.
		 */
		public function setImpuesto($imp)
		{
			$this->impuesto = $imp;
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
		public function iniciarCicloLiquidacion()
		{
			if ( $this->validarRangoLiquidacion() ) {
				$ultima = isset($this->getUltimaLiquidacionExistente()) ? $this->getUltimaLiquidacionExistente() : null;
				$detalle = isset($ultima['pagoDetalle']) ? $ultima['pagoDetalle'] : null;
				if ( $detalle == null ) {
					// Es indicativo que no tiene registros liquidados, lo que implica que esta sera
					// la primera liquidacion del contribuyente u objeto.


				}
			}
			return false;
		}




		/***/
		private function configurarLapsoLiquidacionActividadEconomica()
		{
			$ultimo = $this->getUltimaLiquidacion();
			if ( $ultimo == null ) {
				// Se determinara la primera liquidacion del contribuyente. Se requiere la fecha
				// de inicio de sus actividades.
				$this->_fechaInicio = ContribuyenteBase::getFechaInicio($this->_idContribuyente);

				if ( date($this->_fechaInicio) ) {
					$this->añoDesde = date('Y', $this->_fechaInicio);

					// El periodo dependera de la configuracion de la ordenanza segun el impuesto
					// y el año. Para este caso el impuesto es Actividad Economica y el año estara
					// definido por la fecha inicio ($this->_fechaInicio). Lo que se debe determinar
					// es la exigibilidad de liquidacion para el impuesto y año impositivo.
					$exigibilidadLiq = OrdenanzaBase::getExigibilidadLiquidacion($this->añoDesde, $this->_impuesto);
					$this->periodoDesde = OrdenanzaBase::getPeriodoSegunFecha($exigibilidadLiq['exigibilidad_liquidacion'], $this->_fechaInicio);

					if ( $this->periodoDesde == false ) { return null;}

					// Solo se permitira la liquidacion de un periodo. Condicion que puede cambiar
					// para futuros proyectos, es aqui donde se debe realizar el ajuste del año-periodo
					// final.
					$this->añoHasta = $this->añoDesde;
					$this->periodoHasta = $this->periodoDesde;

				} else {
					// No esta definida la fecha inicio del contribuyente. Aqui termina el proceso.
					return null;
				}
			} else {
				// No es la primera liquidacion, se debe determinar cual es el utlimo año-periodo
				// liquidado para continuar a partir de este.
				$exigibilidadLiq = OrdenanzaBase::getExigibilidadLiquidacion($this->añoDesde, $this->_impuesto);
				$añoActual = date('Y');
				if ( $ultimo['trimestre'] == $exigibilidadLiq['exigibilidad_liquidacion'] ) {
					// Es indicativo que el ultimo periodo liquidado corresponde al ultimo periodo
					// del año.
					$this->añoDesde = $ultimo['ano_impositivo'] + 1;
					$this->añoHasta = $this->añoDesde;
					$this->periodoDesde = 1;
					$this->periodoHasta = $this->periodoDesde;

				} elseif ( $ultimo['ano_impositivo'] < $añoActual ) {

				}


			}
		}



		/***/
		private function validarRangoLiquidacion()
		{
			if ( $this->añoDesde > $this->añoHasta ) {
				return false;
			} elseif ( $this->añoDesde == $this->añoHasta ) {
				if ( $this->periodoDesde > $this->periodoDesde ) {
					return false;
				}
			}
			return true;
		}




		/***/
		private function getUltimoLapsoLiquidadoObjeto($impuesto, $idImpuesto)
		{
			$this->setIdImpuesto($idImpuesto);
			$this->setImpuesto($impuesto);
			if ( $this->_idContribuyente > 0 && $this->_idImpuesto > 0 && $this->_impuesto > 0 ) {
				$this->_ultimaLiquidacion = Pago::find()->where('id_impuesto =:id_impuesto',[':id_impuesto' => $this->_idImpuesto])
										 				->andWhere('impuesto =:impuesto', [':impuesto' => $this->_impuesto])
										 				->andWhere('trimestre >:trimestre', [':trimestre' => 0])
										 				->andWhere('pago !=:pago', [':pago' => 9])
										 				->andWhere('referencia =:referencia',[':referencia' => 0])
										 				->joinWith('pagoDetalle')
										 				->orderBy([
										 					'ano_impositivo' => SORT_DESC,
										 					'trimestre' => SORT_DESC,
										 				])
										 				->asArray()
										 				->one();
			}
			if ( count($this->_ultimaLiquidacion) > 0 ) {
				return $this->_ultimaLiquidacion;
			}
			return null;
		}




		/***/
		private function getUltimoLapsoActividadEconomica()
		{
			if ( $this->_idContribuyente > 0 ) {
				$this->setImpuesto(1);
				$this->_ultimaLiquidacion = Pago::find()->where('id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
														->andWhere('impuesto =:impuesto', [':impuesto' => 1])
														->andWhere('trimestre >:trimestre', [':trimestre' => 0])
														->andWhere('pago !=:pago', [':pago' => 9])
														->andWhere('referencia =:referencia',[':referencia' => 0])
														->joinWith('pagoDetalle')
														->orderBy([
										 					'ano_impositivo' => SORT_DESC,
										 					'trimestre' => SORT_DESC,
										 				])
										 				->asArray()
										 				->one();
			}
			if ( count($this->_ultimaLiquidacion) > 0 ) {
				return $this->_ultimaLiquidacion;
			}
			return null;
		}



		/***/
		public function getUltimaLiquidacionExistente()
		{
			return $this->_ultimaLiquidacion;
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
			$detalle = null;
			if ( count($this->_ultimaLiquidacion) > 0 ) {
				$detalle = isset($this->_ultimaLiquidacion['pagoDetalle'][0]) ? $this->_ultimaLiquidacion['pagoDetalle'][0] : null;
			}
			return $detalle;
		}

	}

?>