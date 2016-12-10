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
 *  @class LiquidacionVehiculo
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

		const IMPUESTO = 3;			// Impuesto asociado a vehiculo.



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


		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}



		/**
		 * Metodo donde comienza el proceso.s
		 * @return Array retorna un arreglo con los siguientes valores:
		 * id-impuesto, impuesto, año impositivo, placa y monto calculado.
		 */
		public function iniciarCalcularLiquidacionVehiculo()
		{
			$this->_calculoAnual = 0;
			$tarifasParametros = null;
			$monto = 0;
			$model = self::getDatosVehiculo();
			if ( $model != null ) {
				$this->_datosVehiculo = $model->toArray();
				if ( count($this->_datosVehiculo) > 0 ) {
					// Se buscan las tarifas que coincidan con la clase del vehiculo y la oredenanza respectiva.
					// Aqui se recibe un arreglo de las tarifas ligadas a la clase de vehiculo. Luego se enviada el
					// conjunto de tarifas que coinciden con el criterio de busqueda para que se determinen los detalles
					// de cada tarifa. $tarifasParametros, arreglo multi-dimensional que contiene los campos de la
					// entidades "tarifas-vehiculos", "clases-vehiculos" y "tipos-rangos".
					$tarifasParametros = self::getTarifaPorOrdenanzaClaseVehiculo($this->_datosVehiculo['clase_vehiculo']);

					if ( count($tarifasParametros) > 0 ) {

						// Se envialas las tarifas que coincidan con la clase del vehiculo para crear un ciclo
						// que vaya realizando la consulta de los detalles de las mismas por identificador
						// de tarifa.
						$monto = self::iniciarCicloTarifa($tarifasParametros);
					}
				}
			}

			$this->_calculoAnual = $monto;
			return $this->getCalculoAnual();
		}




		/**
		 * Metodo que recibe un arreglo de tarifas que coincidan con la clase del vehiculo y la
		 * ordenanza respetiva. Se obtiene los campos de la entidad "tarifas-vehiculos" ($tarifa)
		 * para ir evaluando cada tarifa ($tarifa), se envia el identificador de la tarifa para
		 * obtener sus detalles.
		 * @param  Array $tarifasParametros, arreglo que contiene los campos de la entidades
		 * "tarifas-vehiculos", "clases-vehiculos" y "tipos-rangos"
		 * @return Double Retorna monto ($monto).
		 */
		private function iniciarCicloTarifa($tarifasParametros)
		{
			$monto = 0;
			$result = false;
			if ( count($tarifasParametros) > 0 ) {
				foreach ( $tarifasParametros as $key => $tarifa ) {
					$result = self::iniciarCicloTarifaDetalle($tarifa['id_tarifa_vehiculo']);
					if ( $result ) {
						$monto = self::getMontoAplicar($tarifa);
						break;
					}
				}
			}
			return $monto;
		}




		/**
		 * Metodo que busca los detalles de la tarifa por identificador, cada ciclo debe
		 * buscar los detalles de cada identificador.
		 * @param  Long $idTarifaVehiculo, identificador de la entidad "tarifas-vehiculos".
		 * @return [type]                   [description]
		 */
		private function iniciarCicloTarifaDetalle($idTarifaVehiculo)
		{
			$tarifaDetalle = null;
			$result = false;
			if ( $idTarifaVehiculo > 0 ) {
				// Se obtienen todos los detalles de la tarifa.
				// Esto en realidad son caracteristicas expresadas en rangos que estan asociadas a la tarifa
				// si el vehiculo satisface todas las caracteristicas de la tarifa, se le aplicara el monto
				// de dicha tarifa.
				// $tarifaDetalles representa el conjunto de detalles (caracteristicas) que debe satisfacer
				// el vehiculo para aplicarle la tarifa.
				// $detalle representa una caracteristica en particular que sera evaluada contra la del vehiculo.
				// Por ejemplo:
				// la caracteristica puede ser "numero de ejes", y su rango de valores estara entre 1 y 3, estos
				// valores se comparan contra la caracteristica del vehiculo "numero de eje" para determinar si
				// se encuentra entre dicho rango.
				$tarifaDetalles = self::getDetallePorTarifa($idTarifaVehiculo);
				if ( count($tarifaDetalles) > 0 ) {

					foreach ( $tarifaDetalles as $key => $detalle ) {
						// Si el $rsult es true, significa que el vehiculo satisface la caracteristica. False
						// caso contrario.
						$result = self::satisfaceCaracteristica($detalle);
						if ( !$result ) { break; }
					}

				}
			}
			return $result;
		}




		/**
		 * Metodo que compara si un parametro de las caracteristicas de un vehiculo
		 * esta dentro del rango que define la tarifa. Se evalua la caracteristica especifica
		 * del vehiculo contra la del rango de la tarifa.
		 * @param Array $detalle arreglo que posee los campos de la entidad tarifas-vehiculos-detalles"
		 * el cual representa la caracteristica que debe cumplir el vehiculo.
		 * @return Boolean Retorna true si la caracteristica a evaluar del vehiculo esta dentro del
		 * rango que define la tarifa.
		 */
		private function satisfaceCaracteristica($detalle)
		{
			$result = false;
			$caracteristica = $detalle['tipoRango']['tipo_rango'];
			$rangoDesde = $detalle['rango_desde'];
			$rangoHasta = $detalle['rango_hasta'];
			$parametro = 0;
			switch ( $caracteristica ) {
				case 0:					//	BOLIVARES
					# code...
					break;
				case 1:					//	UNIDAD TRIBUTARIA
					# code...
					break;
				case 2:					//	PORCENTAJE(%)
					# code...
					break;
				case 3:					//	PESO(EN KG)
					$parametro = $this->_datosVehiculo['peso'];
					break;
				case 4:					//	CAPACIDAD(DE CARGA EN KG)
					$capacidad = 0;
					if ( strtolower($this->_datosVehiculo['medida_cap']) == 'ton' ) {
						$capacidad = $this->_datosVehiculo['capacidad'] * 1000;
					} else {
						$capacidad = $this->_datosVehiculo['capacidad'];
					}
					$parametro = $capacidad;
					break;
				case 5:					//	ANTIGUEDAD(EN AÑOS)
					$añoVehiculo = strlen($this->_datosVehiculo['ano_vehiculo']) == 4 ? $this->_datosVehiculo['ano_vehiculo'] : 0;
					if ( $añoVehiculo > 0 ) {
						if ( (int) date('Y') >= (int) $añoVehiculo ) { $parametro = date('Y') - $añoVehiculo; }
					}
					break;
				case 6:					//	NRO. PUESTOS
					$parametro = $this->_datosVehiculo['nro_puestos'];
					break;
				case 7:					//	NRO. PUERTAS
					# code...
					break;
				case 8:
					# code...
					break;
				case 9:					//	NO APLICA
					# code...
					break;
				case 10:				//	METROS CUADRADOS
					# code...
					break;
				case 11:				//	NRO. EJES
					$parametro = $this->_datosVehiculo['no_ejes'];
					break;
				case 12:				//	SUELDOS MINIMOS
					# code...
					break;
				default:
					# code...
					break;
			}

			settype($rangoDesde, 'float');
			settype($rangoHasta, 'float');

			if ( $rangoDesde >= 0 && $rangoHasta > 0 ) {
				if ( $rangoDesde <= $parametro && $rangoHasta >= $parametro ) {
					$result = true;
				}
			} elseif ( $rangoDesde > 0 && $rangoHasta == 0 ) {
				if ( $rangoDesde <= $parametro ) {
					$result = true;
				}
			}

			return $result;
		}




		/**
		 * Metodo que permite armar un arreglo de datos para luego
		 * enviarlo a un metodo que determina el monto a aplicar, segun
		 * el tipo de monto. Si el tipo de monto corresponde a unidad tributaria
		 * se debe enviar el año.
		 * @param  Array $tarifa arreglo de la entidad "tarifas-vehiculos"
		 * @return Double Retorna monto expresado en moneda nacional.
		 */
		private function getMontoAplicar($tarifa)
		{
			$parametros = null;
			$montoAplicar = 0;

			if ( count($tarifa) > 0 ) {
				$parametros['tipo_monto'] = $tarifa['tipo_monto'];
				$parametros['monto'] = $tarifa['monto_aplicar'];
				$parametros['ano_impositivo'] = $this->getAnoImpositivo();

				$montoAplicar = UnidadTributariaForm::getMontoAplicar($parametros);
			}

			return $montoAplicar;

		}






		/***/
		private function getDetallePorTarifa($idTarifaVehiculo)
		{
			if ( $idTarifaVehiculo > 0 ) {
				return TarifaParametroVehiculo::getDetalleTarifaVehiculo($idTarifaVehiculo);
			}
			return null;
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
		protected function getTarifaPorOrdenanzaClaseVehiculo($claseVehiculo)
		{
			$modelTarifa = null;
			$idOrdenanza = null;
			$idOrdenanza = self::getIdOrdenanza();

			if ( count($idOrdenanza) > 0 && $claseVehiculo > 0 ) {

				$modelTarifa = TarifaParametroVehiculo::getTarifasVehiculoSegunClase($idOrdenanza[0]['id_ordenanza'], $claseVehiculo);
			}
			return $modelTarifa;
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
		 * año e impuesto (en este caso 3).
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




	}

?>