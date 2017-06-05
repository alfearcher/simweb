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
 *  @file LiquidacionInmueble.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class LiquidacionInmueble
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

	namespace common\models\calculo\liquidacion\inmueble;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\db\Exception;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\utilidad\ut\UnidadTributariaForm;
	use backend\models\inmueble\InmueblesConsulta;
	use backend\models\inmueble\avaluo\HistoricoAvaluoForm;
	use backend\models\utilidad\tarifa\inmueble\TarifaParametroInmueble;
	use common\models\calculo\liquidacion\inmueble\CalculoTarifaAvaluoDirecto;
	use common\models\calculo\liquidacion\inmueble\CalculoPorUsoInmuebleAvaluo;
	use common\models\calculo\liquidacion\inmueble\CalculoPorAlicuotaAplicadaAvaluo;


	/**
	* 	Clase que gestiona el calculo anual del impuesto de inmueble,
	*
	*/
	class LiquidacionInmueble
	{

		private $_calculoAnual;
		private $_datosInmueble;
		private $_añoImpositivo;
		private $_idImpuesto;		// Identificador del Inmueble.
		private $_parametro;

		const IMPUESTO = 2;



		/**
		 * Metodo constructor
		 * @param Long $idImpuesto identificador del inmeuble.
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
		public function iniciarCalcularLiquidacionInmueble()
		{
			$this->_calculoAnual = 0;
			$tarifasParametros = null;
			$monto = 0;
			$idMetodo = 0;
			$model = self::getDatosInmueble();

			if ( $model !== null ) {
				$this->_datosInmueble = $model->toArray();

				if ( count($this->_datosInmueble) > 0 ) {
					$idMetodo = self::getDeterminarMetodologiaCalculo();
					if ( $idMetodo > 0 ) {
						$monto = self::iniciarCalculoLiquidacionInmuebleSegunMetodo($idMetodo);
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
		protected function getAnoOrdenanza()
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
		 * Metodo que busca la metodologia de calculo configurada en la ordenanza.
		 * @return Long Retorna el identificador de la metodologia, de la entidad
		 * "calculos-metodos".
		 */
		private function getDeterminarMetodologiaCalculo()
		{
			$idMetodo = 0;
			$idOrdenanza = 0;
			$detalle = null;
			if ( $this->_añoImpositivo > 0 ) {
				$idOrdenanza = $this->getIdOrdenanza();
				if ( count($idOrdenanza) > 0 ) {
					$id = $idOrdenanza[0]['id_ordenanza'];
					$detalle = OrdenanzaBase::getDetalleOrdenanza($id, self::IMPUESTO);
					if ( count($detalle) > 0 ) {
						$idMetodo = $detalle['id_metodo'];
					}
				}
			}
			return $idMetodo;
		}




		/**
		 * Metodo que bifurca hacia la metodologia de calculo.
		 * @param  Long $idMetodo identificador de la metodologia de calculo que se
		 * configuro en la ordenanza.
		 * @return Double Retorna el monto calculado por la metodologia especifica.
		 */
		private function iniciarCalculoLiquidacionInmuebleSegunMetodo($idMetodo)
		{
			$monto = 0;
			if ( $idMetodo == 1 ) {

			} elseif ( $idMetodo == 2 ) {

			} elseif ( $idMetodo == 3 ) {

			} elseif ( $idMetodo == 4 ) {

			} elseif ( $idMetodo == 5 ) {

			} elseif ( $idMetodo == 6 ) {

			} elseif ( $idMetodo == 7 ) {

			} elseif ( $idMetodo == 8 ) {

			} elseif ( $idMetodo == 9 ) {
				$calculoDirecto = New CalculoTarifaAvaluoDirecto($this->_datosInmueble, $this->_añoImpositivo);
				$monto = $calculoDirecto->iniciarCalculo();

			} elseif ( $idMetodo == 10 ) {
				$calculo = New CalculoPorUsoInmuebleAvaluo($this->_datosInmueble, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			} elseif ( $idMetodo == 11 ) {
				$calculo = New CalculoPorAlicuotaAplicadaAvaluo($this->_datosInmueble, $this->_añoImpositivo);
				$monto = $calculo->iniciarCalculo();

			}
			return $monto;
		}





		/**
		 * Metodo que retorna un modelo que instancia una clase tipo ActiveRecord con los datos
		 * del inmueble, utilizando como parametro de busqueda el identificador del inmueble.
		 */
		private function getDatosInmueble()
		{
			if ( $this->_idImpuesto > 0 ) {
				return InmueblesConsulta::findOne($this->_idImpuesto);
			}
			return null;
		}



	}

?>