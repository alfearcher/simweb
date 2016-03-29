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
				if ( $detalle != null ) {

				}
			}
			return false;
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