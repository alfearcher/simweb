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
	//use yii\db\ActiveRecord;
	use yii\db\Exception;
	use backend\models\utilidad\tarifa\transaccioninmobiliaria\TarifaTransaccionInmobiliariaSearch;
	use backend\models\utilidad\ut\UnidadTributariaForm;


	/**
	* Clase que gestiona el calculo referente a las transacciones inmobiliarias.
	* Retorna el monto por el concepto de transaccion inmobiliaria.
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



		/**
		 * Metodo que permite obtener el monto de la unidad tributaria (expresado en moneda nacional).
		 * @param  Integer $añoImpositivo Año actual, define cuando se realizo el calculo delimpuesto.
		 * @return Double Retorna monto expresado en moneda nacional de las unidades tributarias.
		 */
		public function getUnidadTributaria($añoImpositivo)
		{
			$montoUt = 0;
			$ut = New UnidadTributariaForm();
			$montoUt = $ut->getUnidadTributaria($añoImpositivo);

			if ( $montoUt == null ) { $montoUt = 0; }
			return $montoUt;
		}



		/**
		 * Metodo que inicia el proceso de calculo.
		 * @param  Double $precioInmueble monto del precio del inmueble.
		 * @param  Integer $añoImpositivo año impositivo cuando se realizo la transaccion. 
		 * Y año del cual se quiere obtener la unidad tributaria.
		 * @param  Integer $tipoTransaacion identificador del tipo de transaccion, para este
		 * caso particular de Caroni, se manejan dos tipos:
		 * - 	VENTA.
		 * - 	HIPOTECA.
		 * @return Double Retorna un monto calculado que especifica cuanto se debe pagar
		 * por la transaccion comercial realizada sobre el inmueble. Si no se realiza el calculo
		 * retorna cero (0).
		 */
		public function iniciarCalculoTransaccion($precioInmueble, $añoImpositivo, $tipoTransaccion)
		{
			$monto = self::calcularTransaccionCaroni($precioInmueble, $añoImpositivo, $tipoTransaccion);
			return $monto;
		}



		/**
		 * Metodo que realiza el calculo de la transaccion inmobiliaria, segun la metodologia
		 * existente en la Alcaldia de Caroni, Edo. Bolivar. Esta metodologia de calculo consiste
		 * en convertir el precio por el cual se vendio el inmueble, en unidades tributarias.
		 * Las unidades tributarias utilizadas seran las del año cuando se registra dicha transaccion
		 * comercial. Luego dicho monto convertido es comparado contra una lista catalogo que contiene
		 * una serie de rangos (desde-hasta), lo cual permite determinar en cual rango esta el monto
		 * convertido (precio del inmueble / unidad tributaria). Una vez determinado esto, la lista
		 * catalogo de rangos contiene una alicuota que sera la utilizada en los calculos del impuesto
		 * por transacciones inmobiliaria.
		 * Este monto calculado luego sera liquidado a traves de una planilla de liquidacion.
		 * @param  Double $precioInmueble monto del precio del inmueble.
		 * @param  Integer $añoImpositivo Año impositivo del que se quiera obtener
		 * la unidad tributaria.
		 * @param  Integer $tipoTransaccion identificador del tipo de transaccion, para este
		 * caso particular de Caroni, se manejan dos tipos:
		 * - 	VENTA.
		 * - 	HIPOTECA.
		 * @return Double Retorna un monto calculado referente a la transaccion inmobiliaria. Sino
		 * se realiza el calculo retorna cero (0).
		 */
		public function calcularTransaccionCaroni($precioInmueble, $añoImpositivo, $tipoTransaccion)
		{
			// Obligar seteando el contenido de la variable a integer.
			settype($añoImpositivo, 'integer');

			$montoAplicar = 0;
			$montoDescuento = 0;
			$montoCalculado = 0;

			// Se realiza la conversion del precio a unidades tributarias.
			$montoConversion = self::getConvertirPrecioUT($precioInmueble, $añoImpositivo);

			if ( $montoConversion > 0 ) {
				// Se busca el modelo del catalogo de tarifas para las transacciones inmobiliarias.
				// Metodo de la clase padre.
				$model = $this->findTarifaTransaccion($añoImpositivo, $tipoTransaccion, $precioInmueble);

				if ( $model !== null ) {
					// Se pasa a determinar en que rango se encuentra ubicado el monto convertido
					// en unidades tributarias.
					$tarifas = $model->asArray()->all();

					$ut = New UnidadTributariaForm();

					foreach ( $tarifas as $tarifa ) {
						if ( $tarifa['monto_hasta'] > 0 ) {
							$parametros['tipo_rango'] = $tarifa['tipo_rango'];
							$parametros['monto'] = $tarifa['monto_aplicar'];
							$parametros['ano_impositivo'] = $tarifa['ano_impositivo'];

							$descuentos['tipo_rango'] = $tarifa['tipo_monto'];
							$descuentos['monto'] = $tarifa['porc_descuento'];
							$descuentos['ano_impositivo'] = $tarifa['ano_impositivo'];

							if ( $tarifa['monto_desde'] <= $montoConversion && $tarifa['monto_hasta'] >= $montoConversion ) {
								// Determina el monto que debe aplicar, si es en unidades tributarias, se realiza
								// la conversion a moneda nacional.
								$montoAplicar = $ut->getMontoAplicar($parametros);
								break;
							}
						} elseif ( $tarifa['monto_hasta'] == 0 ) {
							$montoAplicar = $ut->getMontoAplicar($parametros);
							break;
						}
					}

					// Se determina si existe un descuento para aplicar.
					if ( $montoAplicar > 0 ) {
						$montoCalculado = $montoAplicar;
						if ( $tarifa['porc_descuento'] > 0 ) {
							$montoCalculado = $montoCalculado * (1 - $tarifa['porc_descuento']);
						}
					}
				}
			}

			//$montoCalculado = $montoAplicar;
			return $montoCalculado;
		}

	}

?>