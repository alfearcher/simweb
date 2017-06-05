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
 *  @file CalculoPorUnidadFraccion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-01-2017
 *
 *  @class CalculoPorUnidadFraccion
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

	namespace common\models\calculo\liquidacion\propaganda;


 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\calculo\liquidacion\propaganda\ParametroTarifaPropaganda;



	/**
	 * Clase que basa su calculo en los metros lineales propaganda.
	 */
	class CalculoPorUnidadFraccion extends ParametroTarifaPropaganda
	{

		private $_añoImpositivo;
		private $_datosPropaganda;
		private $_montoCalculado;
		private $_unidadLimite;

		/**
		 * Metodo constructor de la clase
		 * @param Array $datosPropaganda arreglo uni-dimensional que contiene los datos
		 * de la propaganda.
		 * Este arreglo se refiere a la entidad "propagandas".
		 * @param Integer $añoImpositivo Año impositivo al cual se le calculara el impuesto.
		 */
		public function __construct($datosPropaganda, $añoImpositivo, $unidadControl)
		{
			$this->_montoCalculado = 0;
			$this->_añoImpositivo = $añoImpositivo;
			$this->_datosPropaganda = $datosPropaganda;
			$this->_unidadLimite = $unidadControl;
			parent::__construct($datosPropaganda, $añoImpositivo);
		}




		 /**
		 * Metodo que inicia el proceso de calculo del impuesto segun la metodologia
		 * @return Double Retorna un monto calcula del impuesto segun la metodologia.
		 * Si el monto es cero significa que falto algun parametro para relaizar los calculos.
		 */
		public function iniciarCalculo()
		{
			if ( count($this->_datosPropaganda) > 0 && $this->_añoImpositivo > 0 ) {

				$this->_montoCalculado = self::calcularImpuesto();

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
		 * @return Double Retorna el monto calculado del impuesto, si el monto es cero significa que
		 * falto algun parametro de calculo.
		 */
		private function calcularImpuesto()
		{
			$montoAplicar = 0;
			$montoTotal = 0;
			$parteEntera = 0;
			$resto = 0;

			$montoAplicar = $this->determinarMontoAplicar();
			$cantidadPropaganda = $this->_datosPropaganda['cantidad_propagandas'];

			if ( $cantidadPropaganda > $this->_unidadLimite ) {
				$resultado = $cantidadPropaganda / $this->_unidadLimite;
				$parte = explode(".", $resultado);
				$parteEntera = $parte[0];

				$resto = $cantidadPropaganda - ( $parteEntera * $this->_unidadLimite );
				if ( $resto > 0 ) {
					$parteEntera = $parteEntera + 1;
				}
				$montoTotal = $montoAplicar * $parteEntera;

			} else {
				$montoTotal = $montoAplicar;
			}

			$t = '';
			$idTiempo = $this->_datosPropaganda['id_tiempo'];
			if ( $idTiempo == 2 ) {
				$t = 'dia';
			} elseif ( $idTiempo == 3 ) {
				$t = 'semana';
			} elseif ( $idTiempo == 4 ) {
				$t = 'mes';
			} elseif ( $idTiempo == 0 || $idTiempo == 5 ) {
				$t = 'año';
			}

			$cantidadTiempo = $this->getCantidadTiempo($t);

			if ( $cantidadTiempo > 0 ) {
				$montoTotal = $montoTotal * $cantidadTiempo;
			}

			return $montoTotal;
		}


	}

?>