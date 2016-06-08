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
 *  @file TarifaTransaccionInmobiliariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-06-2016
 *
 *  @class TarifaTransaccionInmobiliariaForm
 *  @brief Clase Modelo del formulario para
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

	namespace backend\models\utilidad\tarifa\transaccioninmobiliaria;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\tarifa\inmueble\TarifaTransaccionInmobiliaria;
	use backend\models\utilidad\ut\UnidadTributariaForm;

	/**
	* 	Clase
	*/
	class TarifaTransaccionInmobiliariaSearch extends TarifaTransaccionInmobiliaria
	{





		/***/
		public function findTarifaTransaccion($añoImpositivo)
		{
			$modelFind = TransaccionInmobiliaria()->find()
												  ->where('ano_impositivo =:ano_impositivo',
												  						[':ano_impositivo' => $añoImpositivo])
												  ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
												  ->orderBy([
												  		'monto_desde' => SORT_ASC,
												  	])
			return isset($modelFind) ? $modelFind : null;
		}



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
			settype($añoImpositivo, 'integer');
			$ut = New UnidadTributariaForm();

			$montoConversion = self::getConvertirPrecioUT($precioInmueble, $añoImpositivo);
			if ( $montoConversion > 0 ) {
				$model = self::findTarifaTransaccion($añoImpositivo);
				if ( $model !== null ) {
					// Se pasa a determinar en que rango se encuentra ubicado el monto convertido
					// en unidades tributarias.
					$tarifas = $model->asArray()->all();
					foreach ( $tarifas as $tarifa ) {
						if ( $tarifa['monto_hasta'] > 0 ) {
							if ( $tarifa['monto_desde'] <= $montoConversion && $tarifa['monto_hasta'] >= $montoConversion ) {
								$montoAplicar = $ut->getMontoAplicar($tarifa['tipo_rango']);
							}
						} elseif ( $tarifa['monto_hasta'] == 0 ) {

						}
					}
				}
			}
		}




	}
?>