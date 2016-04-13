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
 *  @file LiquidacionVehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class LiquidacionVahiculo
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

	namespace common\models\calculo\liquidacion\vehiculo;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\db\Exception;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\utilidad\ut\UnidadTributariaForm;
	use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
	//use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculo;
	//use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculoDetalle;
	use backend\models\utilidad\tarifa\vehiculo\TarifaParametroVehiculo;

	/**
	* 	Clase que gestiona el calculo anual del impuesto de vehiculo,
	*
	*/
	class LiquidacionVehiculo
	{

		private $_calculoAnual;
		private $_datosVehiculo;
		private $_añoImpositivo;
		private $_idImpuesto;		// Identificador del Vehiculo.
		private $_parametro;



		/**
		 * Metodo constructor
		 * @param Long $idImpuesto identificador del vehiculo.
		 */
		public function __construct($idImpuesto)
		{
			$this->_calculoAnual = 0;
			$this->_añoImpositivo = 0;
			$this->_parametro = null;
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




		/**
		 * Metodo donde comienza el proceso.s
		 * @return Array retorna un arreglo con los siguientes valores:
		 * id-impuesto, impuesto, año impositivo, placa y monto calculado.
		 */
		public function iniciarCalcularLiquidacionVehiculo()
		{
			$this->_calculoAnual = 0;
			$this->_parametro = null;

			$model = self::getDatosVehiculo();
			if ( $model != null ) {
				$this->_datosVehiculo = $model->toArray();
				if ( count($this->_datosVehiculo) > 0 ) {
					// Se buscan los parametros de las tarifas segun clase y ordennaza
					$this->_parametro = self::getTarifaPorOrdenanzaClaseVehiculo($this->_datosVehiculo['clase_vehiculo']);
die(var_dump($this->_parametro));
				}
			} else {
				return false;
			}
		}






		/**
		 * Metodo que retorna un modelo que instancia una clase tipo ActiveRecord con los datos
		 * del vehiculo, utilizando como parametro de busqueda el identificador del vehiculo.
		 */
		private function getDatosVehiculo()
		{
			if ( $this->_idImpuesto > 0 ) {
				return BusquedaVehiculos::findOne($this->_idImpuesto);
			}
			return null;
		}




		/***/
		private function calculoImpuestoVehiculo()
		{
			$model = $this->getDatosVehiculo();
			if ( isset($model) ) {

			} else {

			}
		}




		/***/
		private function getTarifaPorOrdenanzaClaseVehiculo($claseVehiculo)
		{
			$modelTarifa = null;
			$idOrdenanza = null;
			$idOrdenanza = self::getIdOrdenanza();

			if ( $idOrdenanza != null && $claseVehiculo > 0 ) {
				$modelTarifa = TarifaParametroVehiculo::getTarifasVehiculoSegunClase($idOrdenanza[0]['id_ordenanza'], $claseVehiculo);
			}
			return $modelTarifa;
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
				$año = OrdenanzaBase::getAnoOrdenanzaSegunAnoImpositivoImpuesto($this->_añoImpositivo, 3);
			}
			return $año;
		}




		/**
		 * Metodo que determina el identificador de la ordenanza, segun los parametros
		 * año e impuesto (en este caso 3).
		 * @return Array, Retorna un arreglo donde contiene el identificador de la ordenanza,
		 * año de creacion de la misma y el impuesto respectivo. Sino consigue nada retorna null.
		 */
		private function getIdOrdenanza()
		{
			$idOrdenanza = null;
			$año = self::getAnoOrdenanza();
			if ( $año > 0 ) {
				$idOrdenanza = OrdenanzaBase::getIdOrdenanza($año, 3);
				if ( !isset($idOrdenanza) || $idOrdenanza == false ) {
					$idOrdenanza = null;
				}
			}
			return $idOrdenanza;
		}




	}

?>