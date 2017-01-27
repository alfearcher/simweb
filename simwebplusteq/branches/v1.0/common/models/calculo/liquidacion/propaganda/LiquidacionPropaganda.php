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
 *  @file LiquidacionPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class LiquidacionPropaganda
 *  @brief Clase Modelo que maneja la politica
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

	namespace common\models\calculo\liquidacion\propaganda;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\db\Exception;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\propaganda\Propaganda;
	use backend\models\utilidad\tarifa\propaganda\TarifaPropaganda;
	use backend\models\propaganda\tipo\TipoPropaganda;
	use common\models\calculo\liquidacion\propaganda\CalculoPorUnidad;
	use common\models\calculo\liquidacion\propaganda\CalculoPorMetro;
	use common\models\calculo\liquidacion\propaganda\CalculoPorMetroCuadrado;
	use common\models\calculo\liquidacion\propaganda\CalculoPorTiempoTranscurrido;
	use common\models\calculo\liquidacion\propaganda\CalculoPorUnidadFraccion;
	use common\models\calculo\liquidacion\propaganda\CalculoPorCostoPropaganda;



	/**
	* Clase que gestiona el calculo anual del impuesto de propaganda,
	* Se crea un listado de oopciones que deberan ser elegida segun la base
	* de calculo qoe posea la propaganda. La clase debe entregar como resultado
	* un monto producto del calculo.
	*/
	class LiquidacionPropaganda
	{

		private $_calculoAnual;
		private $_datosPropaganda;
		private $_añoImpositivo;
		private $_idImpuesto;		// Identificador de la Propaganda.
		private $_parametro;

		const IMPUESTO = 4;



		/**
		 * Metodo constructor
		 * @param Long $idImpuesto identificador de la propaganda.
		 */
		public function __construct($idImpuesto)
		{
			$this->_calculoAnual = 0;
			$this->_añoImpositivo = 0;
			$this->_parametro = null;
			$this->_datosInmueble = null;
			$this->_idImpuesto = $idImpuesto;
		}



		/***/
		public function setAnoImpositivo($año)
		{
			$this->_añoImpositivo = $año;
		}



		/***/
		public function getAnoImpositivo()
		{
			return $this->_añoImpositivo;
		}


		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}



		/**
		 * Metodo donde comienza el proceso
		 * @return Array retorna un arreglo con los siguientes valores:
		 * id-impuesto, impuesto, año impositivo, placa y monto calculado.
		 */
		public function iniciarCalcularLiquidacionPropaganda()
		{
			$this->_calculoAnual = 0;
			$tarifasParametros = null;
			$monto = 0;
			$idMetodo = 0;
			$model = self::getDatosPropaganda();

			if ( $model !== null ) {
				$this->_datosPropaganda = $model->toArray();

				if ( count($this->_datosPropaganda) > 0 ) {

					// Se deteremina el parametro "base de calculo", segun el tipo de propaganda.
					$idMetodo = self::getDeterminarMetodologiaCalculo();
					if ( $idMetodo > 0 ) {

						$monto = self::iniciarCalculoLiquidacionPropagandaSegunMetodo($idMetodo);

					}
				}
			}
			$this->_calculoAnual = $monto;
			return $this->getCalculoAnual();
		}



		/**
		 * Metodo que permite obtener el Año de creacion de la ordenanza.
		 * @return Integer, Retorna un entero de 4 digitos si encuentra el año,
		 * en caso contrario retornara 0.
		 */
		public function getAnoOrdenanza()
		{
			$año = 0;
			if ( $this->_añoImpositivo > 0 ) {
				$año = OrdenanzaBase::getAnoOrdenanzaSegunAnoImpositivoImpuesto($this->_añoImpositivo, self::IMPUESTO);
			}
			return $año;
		}




		/**
		 * Metodo que determina el identificador de la ordenanza, segun los parametros
		 * año e impuesto (en este caso 2).
		 * @return Array, Retorna un arreglo donde contiene el identificador de la ordenanza,
		 * año de creacion de la misma y el impuesto respectivo. Sino consigue nada retorna null.
		 */
		private function getIdOrdenanza()
		{
			$idOrdenanza = null;
			$año = self::getAnoOrdenanza();

			if ( $año > 0 ) {
				$idOrdenanza = OrdenanzaBase::getIdOrdenanza($año, self::IMPUESTO);
				if ( !isset($idOrdenanza) || $idOrdenanza == false ) {
					$idOrdenanza = null;
				}
			}
			return $idOrdenanza;
		}




		/**
		 * Metodo que busca la metodologia de calculo que esta definida en la entidad
		 * "tipos-propagandas", esta entidad tiene un atributo de nombre "base-calculo"
		 * que esta relacionada con la entidad "bases-calculos". Con este parametro se
		 * elabora una rutina de calculo que permitira obtener el monto del impuesto.
		 * @return Long Retorna el identificador de la metodologia.
		 */
		public function getDeterminarMetodologiaCalculo()
		{
			// Se refiere al parametro "base-calculo" de la entidad "tipos-propagandas".
			$idMetodo = 0;

			$tipo = self::getDatosPropaganda()->tipoPropaganda;
			if ( isset($tipo->base_calculo) ) {
				$idMetodo = (int)$tipo->base_calculo;
			}
			return $idMetodo;
		}




		/**
		 * Metodo que bifurca hacia la metodologia de calculo.
		 * @param  Long $idMetodo identificador de la metodologia de calculo que se encuentra
		 * en la entidad "tipos-propagandas". base-calculo
		 * @return Double Retorna el monto calculado por la metodologia especifica.
		 */
		private function iniciarCalculoLiquidacionPropagandaSegunMetodo($idMetodo)
		{
			$monto = 0;
			if ( $idMetodo == 1 ) {

				// calculo por unidades.
				$calculo = New CalculoPorUnidad($this->_datosPropaganda, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 2 ) {

				// calculo por metros cuadrados.
				$calculo = New CalculoPorMetroCuadrado($this->_datosPropaganda, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 3 ) {

				// calculo por metros lineales.
				$calculo = New CalculoPorMetro($this->_datosPropaganda, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 4 || $idMetodo == 6 || $idMetodo == 8 || $idMetodo == 9 || $idMetodo == 10 ) {

				// calculo por tiempo transcurrido
				$calculo = New CalculoPorTiempoTranscurrido($this->_datosPropaganda, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 5 ) {

				// calculo por cada 1000 unidaddes y fraccion.
				$calculo = New CalculoPorUnidadFraccion($this->_datosPropaganda, $this->_añoImpositivo, 1000);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 7 ) {

				// calcular por costo de la propaganda.
				$calculo = New CalculoPorCostoPropaganda($this->_datosPropaganda, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 12 ) {

				// calculo por cada 100 unidades y fraccion.
				$calculo = New CalculoPorUnidadFraccion($this->_datosPropaganda, $this->_añoImpositivo, 100);
				$monto = $calculo->iniciarCalculo();

			}
			return $monto;
		}





		/**
		 * Metodo que retorna un modelo que instancia una clase tipo ActiveRecord con los datos
		 * de la propaganda, utilizando como parametro de busqueda el identificador de la propaganda.
		 */
		protected function getDatosPropaganda()
		{
			if ( $this->_idImpuesto > 0 ) {
				return Propaganda::findOne($this->_idImpuesto);
			}
			return null;
		}



	}

?>