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
 *  @file CalculoTarifaAvaluoDirecto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-04-2016
 *
 *  @class CalculoTarifaAvaluoDirecto
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

	namespace common\models\calculo\liquidacion\inmueble;

  
 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\tarifa\inmueble\TarifaParametroInmueble;
	use backend\models\inmueble\avaluo\HistoricoAvaluoForm;
	use backend\models\inmueble\InmueblesConsulta;



	/**
	* 	Clase que determina el impuesto a calcular de inmuebles urbanos basandose en la
	* 	metodologia de la localizacion (manzana_limite) y el año impositivo. Es decir
	* 	se busca en un catalogo los parametros de calculos, utilizando como valores de
	* 	busquedad la localizacion del inmueble y el año impositivo. Una vez obetnido estos
	* 	parametros se pasara a calcular el impuesto.
	*/
	class CalculoTarifaAvaluoDirecto
	{

		private $_añoImpositivo;
		private $_datosInmueble;
		private $_montoCalculado;



		/**
		 * Metodo constructor de la clase
		 * @param Array $datosInmueble arreglo uni-dimensional que contiene los datos del inmueble.
		 * Este arreglo se refiere a la entidad "inmuebles".
		 * @param Integer $añoImpositivo Año impositivo al cual se le calculara el impuesto.
		 */
		public function __construct($datosInmueble, $añoImpositivo)
		{
			$this->_montoCalculado = 0;
			$this->_añoImpositivo = $añoImpositivo;
			$this->_datosInmueble = $datosInmueble;
		}  




		/**
		 * Metodo que inicia el proceso de calculo del impuesto segun la metodologia
		 * @return Double Retorna un monto calcula del impuesto segun la metodologia.
		 * Si el monto es cero significa que falto algun parametro para relaizar los calculos.
		 */
		public function iniciarCalculo()
		{
			$this->_montoCalculado = 0;
			if ( count($this->_datosInmueble) > 0 && $this->_añoImpositivo > 0 ) {
				// Se debe buscar el avaluo que corresponde segun el año impositivo.
				$historico = New HistoricoAvaluoForm();
				// lo siguiente retorna un arreglo uni-dimensional de los datos del avaluo, para el año impositivo
				// especifico. El metodo realiza un ajuste para determinar que avaluo se debe utilizar en el calculo
				// según el año impositivo.
				$avaluo = $historico->getUltimoAvaluoSegunAnoImpositivo($this->_datosInmueble['id_impuesto'], $this->_añoImpositivo);
				if ( count($avaluo) > 0 ) {
					$this->_montoCalculado = self::calcularImpuesto($avaluo);
				}
			}
			return $this->getMontoCalculado();
		}





		/***/
		public function getMontoCalculado()
		{
			return $this->_montoCalculado;
		}



		/**
		 * Metodo que reune todos los parametros necesarios para realizar el calculo del impuesto.
		 * @param  Array $avaluo arreglo multi-dimensional de la entidad "historico-avaluos", este
		 * avaluo corresponde al del inmueble.
		 * @return Double Retorna el monto calculado del impuesto, si el monto es cero significa que
		 * falto algun parametro de calculo.
		 */
		private function calcularImpuesto($avaluo)
		{
			$monto = 0;
			$montoAvaluo = 0;
			// Se obtienen los parametros o tarifas que se aplicaran en los calculo
			// conjuntamente con los valores del avaluo.
			$tarifa = self::getParametroTarifa();
			if ( count($tarifa) > 0 ) {
				$tasaLocal = $tarifa[0]['tasa_construccion'] + $tarifa[0]['tasa_terreno'];
				$minimo = $tarifa[0]['minimo'];
				$montoAvaluo = self::getAvaluo($avaluo);
				if ( $tarifa[0]['tasa_construccion'] > 0 ) {
					$monto = $montoAvaluo * $tarifa[0]['tasa_construccion'];
					if ( $monto < $minimo ) {
						$monto = $minimo;
					}
				}
			}
			return $monto;
		}




		/**
		 * Metodo que permite obetener los parametros de calculo de la entidad de tarifas de avaluos.
		 * Dicha tarifa permite obtener los parametros segun la ubucacion del inmueble (manzana-limite)
		 * y el año impositivo. Lo recibido sera un arreglo de los campos de la entidad.
		 * @return Array Retorna un  arreglo de los campos de al entidad de "tarifas-avaluos"
		 */
		private function getParametroTarifa()
		{
			return TarifaParametroInmueble::getTarifaAvaluoSegunLocalizacion($this->_datosInmueble['manzana_limite'], $this->_añoImpositivo);
		}



		/**
		 * Metodo que determina el monto del avaluo general, entre el avaluo de terreno
		 * y el avaluo de construccion.
		 * @param  Array $avaluo arreglo multi-dimensional con los campo de la entidad
		 * "historico-avaluos".
		 * @return Double Retorna un monto de la suma de los avaluo.
		 */
		private function getAvaluo($avaluo)
		{
			$montoAvaluo = 0;
			if ( count($avaluo) > 0 ) {
				// Avaluo de la construccion.
				$montoAvaluoConstruccion = $avaluo[0]['mts'] * $avaluo[0]['valor_por_mts2'];

				// Avaluo del terreno.
				$montoAvaluoTerreno = $avaluo[0]['mts2_terreno'] * $avaluo[0]['valor_por_mts2_terreno'];

				$montoAvaluo = $montoAvaluoTerreno + $montoAvaluoConstruccion;
			}

			return $montoAvaluo;
		}




	}

?>