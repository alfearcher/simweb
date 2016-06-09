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
 *  @file TransaccionInmobiliaria.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-06-2016
 *
 *  @class TransaccionInmobiliaria
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

	namespace common\models\calculo\transaccioninmobiliaria;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\db\Exception;
	use backend\models\utilidad\tarifa\inmueble\TarifaTransaccionInmobiliariaSearch;
	use backend\models\utilidad\ut\UnidadTributariaForm;


	/**
	* 	Clase que gestiona el calculo referente a las transacciones inmobiliarias.
	*
	*/
	class TransaccionInmobiliaria extends TarifaTransaccionInmobiliariaSearch
	{

		
		/**
		 * Metodo que realiza la conversion de un monto ($precioInmueble) a unidades
		 * tributarias, las unidad tributaria se tomara del año ($añoImpositivo)
		 * @param  Double $precioInmueble monto del precio del inmueble.
		 * @param  Integer $añoImpositivo Año impositivo del que se quiera obtener
		 * la unidad tributaria.
		 * @return Double Retorna un monto equivalente en unidades tributarias del
		 * precio del inmueble
		 */
		public function getConvertirPrecioUT($precioInmueble, $añoImpositivo)
		{
			// Precio convertido en unidades tributarias.
			$precioConvertido = 0;

			settype($añoImpositivo, 'integer');

			// Unidades tributarias del año.
			$montoUt = 0;
			$montoUt = self::getUnidadTributaria($añoImpositivo);

			if ( $montoUt > 0 ) {
				$precioConvertido = $precioInmueble / $montoUt;
			}

			return $precioConvertido;

		}



		/***/
		public function getUnidadTributaria($añoImpositivo)
		{
			$montoUt = 0;
			$ut = New UnidadTributariaForm();
			$montoUt = $ut->getUnidadTributaria($añoImpositivo);

			if ( $montoUt == null ) { $montoUt = 0; }
			return $montoUt;
		}



		/***/
		public function iniciarCalculoTransaccion($precioInmueble, $añoImpositivo)
		{
			return self::calcularTransaccionCaroni();
		}



		/***/
		public function calcularTransaccionCaroni($precioInmueble, $añoImpositivo)
		{
			// Obligar seteando el contenido de la variable a integer.
			settype($añoImpositivo, 'integer');

			// Se realiza la conversion del precio a unidades tributarias.
			$montoConversion = self::getConvertirPrecioUT($precioInmueble, $añoImpositivo);

			if ( $montoConversion > 0 ) {
				// Se busca el modelo del catalogo de tarifas para las transacciones inmobiliarias.
				// Metodo de la clase padre.
				$model = $this->findTarifaTransaccion($añoImpositivo);

				if ( $model !== null ) {
					// Se pasa a determinar en que rango se encuentra ubicado el monto convertido
					// en unidades tributarias.
					$tarifas = $model->asArray()->all();
					$ut = New UnidadTributariaForm();
					foreach ( $tarifas as $tarifa ) {
						if ( $tarifa['monto_hasta'] > 0 ) {
							$parametros['tipo_rango'] = $tarifa['tipo_rango'];
							$parametros['monto_aplicar'] = $tarifa['monto_aplicar'];
							$parametros['ano_impositivo'] = $tarifa['ano_impositivo'];

							if ( $tarifa['monto_desde'] <= $montoConversion && $tarifa['monto_hasta'] >= $montoConversion ) {
								// Determina el monto que debe aplicar, si es en unidades tributarias, se realiza
								// la conversion a moneda nacional.
								$montoAplicar = $ut->getMontoAplicar($parametros);
							}
						} elseif ( $tarifa['monto_hasta'] == 0 ) {
							$montoAplicar = $ut->getMontoAplicar($parametros);
						}
					}
				}
			}
		}

	}

?>