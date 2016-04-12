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
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculo;
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculoDetalle;

	/**
	* 	Clase que gestiona el calculo anual del impuesto por actividad economica,
	*
	*/
	class LiquidacionVehiculo
	{

		private $_calculoAnual;
		private $_idImpuesto;		// Identificador del Vehiculo.
		private $_parametro;		// Array que retornara el id-impuesto, impuesto,
									// año impositivo, placa y monto calculado.


		/**
		 * Metodo constructor
		 * @param Long $idImpuesto identificador del vehiculo.
		 */
		public function __construct($idImpuesto)
		{
			$this->_calculoAnual = 0;
			$this->_parametro = null;
			$this->_idImpuesto = $idImpuesto;
		}



		/**
		 * Metodo donde comienza el proceso.
		 * @return Array retorna un arreglo con los siguientes valores:
		 * id-impuesto, impuesto, año impositivo, placa y monto calculado.
		 */
		public function iniciarCalcularLiquidacionVehiculo()
		{
			$this->_calculoAnual = 0;
			$this->_parametro = null;
			if ( isset($this->_idImpuesto) ) {
				$this->calculoTasa();
				$monto = getCalculoAnual();
				$this->_parametro['monto'] = $monto;
			}
			return $this->_parametro;
		}



		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}




		/**
		 * Metodo que obtiene los parametros (campos del registro) de la entidad.
		 * @return Array Retorna un arreglo con los campos de la entidad segun el identificador.
		 */
		private function getParametrosTasa()
		{
			$tasa = TasaForm::getValoresTasa($this->_idImpuesto);
			return $tasa;
		}



		/**
		 * Metodo que retorna un modelo que instancia una clase tipo ActiveRecord con los datos
		 * del vehiculo, utilizando como parametro de busqueda el identificador del vehiculo.
		 */
		private function getDatosVehiculo()
		{
			return isset($this_idImpuesto) ? BusquedaVehiculos::findOne($this->_idImpuesto) : null;
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
		private function getCalculoPorClaseVehiculo()
		{

		}









	}

?>