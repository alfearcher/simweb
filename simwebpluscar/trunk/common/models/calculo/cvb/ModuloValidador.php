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
 *  @file ModuloValidador.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-09-2016
 *
 *  @class ModuloValidador
 *  @brief Clase
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

	namespace common\models\calculo\cvb;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;


	/**
	 * Clase que gestiona los diferentes metodos de generacion de codigos validadores.
	 */
	class ModuloValidador
	{

		/**
		 * Metodo que permite obtener un digito validador para una expresion numerica
		 * (entero o decimal). El valor a convertir se representa en una cadena de
		 * digitos, donde el primer digito mas significativo a la derecha del guarismo
		 * tendra como indice en un arreglo el numero cero (0), el siguiente el numero
		 * 1 (uno) y asi sucesivamente. A cada digito del guarismo se le asignara un
		 * valor de ponderacion (peso), y se muliplicara por este, el resultado sera
		 * acumulado. A este valor acumulado se le calculara el modulo 11.
		 * Las cifras con montos decimales deben contener 2 digitos para la representacion
		 * decimal.
		 * @param string $valorBase cadena de digitos al cual se le
		 * calculara el digito modulo 11. El valor solo debe contener numeros.
		 * @return integer retorna un entero que representa el digito validador, sino
		 * retornara null.
		 */
		private function getCodigoModuloOnce($valorBase)
		{
			$modulo11 = null;
			if ( ctype_digit($valorBase) ) {
				$arregloDigitos = [];

				// Crea un array con los digitos resultantes.
				$arregloDigitos = str_split($valorBase);

				// Se invierte el arreglo.
				$arregloDigitos = array_reverse($arregloDigitos);

				$suma = 0;
				$operacion = 0;
				$ponderado = 0;

				foreach ( $arregloDigitos as $key => $value ) {
					$ponderado = self::getPesoAgregar($key);
					$operacion = $value * $ponderado;
					$suma = $operacion + $suma;

					// Permite ver los parametros resultantes
					$v[] = [$value, $ponderado, $operacion];
				}

				// Se obtiene el resto de la division $suma entre 11. Modulo 11.
				$resto = fmod($suma, 11);
				$modulo11 = (int)(11 - $resto);
				if ( $modulo11 == 10 ) {
					$modulo11 = 1;
				} elseif ( $modulo11 == 11 ) {
					$modulo11 = 0;
				}
			}

			return $modulo11;
		}



		/***/
		public function getDigitoControl($valor)
		{
			$digitoControl = null;

			$digitoControl = self::getCodigoModuloOnce($valor);
			return $digitoControl;
		}



		/**
		 * Metodo que recibe un valor float y lo convierte en una cadena continua de
		 * numeros, sin las comas o puntos.
		 * @param  float $valorFloat valor decimal o float que se transformara.
		 * @return long retorna una cadena de puros numeros sin signos.
		 */
		public function filtrarCaracteresFloat($valorFloat)
		{
			$result = null;
			$cadenaDigito = str_replace(".", "", $valorFloat);
			//$cadenaDigito = str_replace(",", "", $valorFloat);
			$cadenaDigito = str_replace(",", "", $cadenaDigito);

			return $cadenaDigito;
		}





		/***/
		private function getPesoAgregar($posicion, $indiceModulo = 11)
		{
			$indicePeso = Yii::$app->ente->getEnte();	// Identificacion de la Alcaldia.

			// El digito mas significativo a la dercha.
			$primerIndice = (int)substr($indicePeso, -1);

			if ( $indiceModulo == 11 ) {			// indica que se utiliza el modulo 11
				if ( $primerIndice == 1 || $primerIndice == 4 || $primerIndice == 7 ) {
					$peso = [2,3,4,5,6,7];
				} elseif ( $primerIndice == 2 || $primerIndice == 5 || $primerIndice == 8 ) {
					$peso = [4,5,2,7,8,9];
				} elseif ( $primerIndice == 3 || $primerIndice == 6 || $primerIndice == 9 ) {
					$peso = [8,7,9,5,4,3];
				} else {
					$peso = [2,4,6,1,3,5];
				}
			}

			$itemCount = count($peso);
			if ( $posicion >= $itemCount ) {
				$r = 100;
				while ( $r > $itemCount ) {
					$p = $posicion - $itemCount;
					$posicion = $p;
					$r = $p;
				}
			}

			return (int)$peso[$posicion];
		}






	}

?>