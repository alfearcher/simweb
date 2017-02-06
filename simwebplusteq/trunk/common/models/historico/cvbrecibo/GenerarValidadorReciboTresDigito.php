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
 *  @file GenerarValidadorRecibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 GenerarValidadorRecibo
 *
 *  @class GenerarValidadorRecibo
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

	namespace common\models\historico\cvbrecibo;

 	use Yii;
	use common\models\calculo\cvb\ModuloValidador;


	/**
	 * ESPECIFICACIONES DEL ALGORITMO QUE GENERARA EL CODIGO VALIDADOR BANCARIO. CASO BOD, BANSECO.

		A continuación se describen las especificaciones del algoritmo y pasos a seguir en
		la generación del Código Validador Bancario.
			1.	Los parámetros que se utilizaran en la generación del Código Validador Bancario serán los siguientes:
				a.	Número de Recibo: Este número consta de 10 dígitos.
				b.	Fecha de Vencimiento del Recibo: Este parámetro consta de 10 caracteres (99/99/9999 o 99-99-9999).
				c.	Monto Total a Pagar del Recibo: Este parámetro consta de 19 dígitos (17 enteros y 2 decimales).
			2.	El Código Validador Bancario estará conformado por 3 dígitos numéricos. Lo que implica que la
			aplicación del algoritmo debe generar como salida un entero de 3 dígitos (999).

		PASOS DEL ALGORITMO PARA GENERAR EL CODIGO VALIDADOR BANCARIO.
		Número de Recibo: Se toma el número de recibo y se procede de la siguiente manera.

		1.	Si el número de recibo es menor a los diez (10) dígitos se complementaran con ceros  (0) a la izquierda
		hasta completar los diez (10) dígitos. Ejemplo: Si el número de recibo  es el 562278, se complementaran con
		cero a la izquierda quedando 0000562278, este será el número de recibo resultante a utilizar para el algoritmo.
		Si el número de recibo supera los diez (10) dígitos se tomaran los 10 dígitos más significativos de la derecha.
		Ejemplo: Si el número de recibo es el 32056298854, se tomaran los diez (10) dígitos más significativo a la derecha,
		quedando el numero en 2056298854, este será el número de recibo a utilizar en el algoritmo.

		2.	Se toma el número de recibo resultante del paso anterior y se procede a realizar la suma de sus dígitos Ejemplo
		si el numero resultante del paso anterior es el 0000562278, se procede a la suma:
		0 + 0 + 0 + 0 + 5 + 6 + 2 + 2 + 7 + 8 = 30
		Monto suma recibo (MSR): 30.

		3.	Se toma el número de recibo resultante del paso 1 y procede a realizar el producto de cada digito del recibo
		resultante contra su índice de posición, cada uno de estos productos se acumula en una variable. Se comienza por
		el digito más significativo a la izquierda (posición 1), hasta el más significativo a la derecha (posición 10).
		Ejemplo si el numero resultante del paso 1 es el 0000562278, se procede como sigue:

		0 * 1 = 0
		0 * 2 = 0
		0 * 3 = 0
		0 * 4 = 0
		5 * 5 = 25
		6 * 6 = 36
		2 * 7 = 14
		2 * 8 = 16
		7 * 9 = 63
		8 * 10 = 80

		Monto suma producto (SPR): 234
		4.	Se suma la resultante del paso 2 (MSR) y paso 3 (SPR).
		ACUM1 = MSR + SPR. Ejemplo 30 + 234 = 264.

		5.	Se multiplica la resultante del paso 2 (MSR) y el paso 3 (SPR).
		ACUM2 = MSR * SPR. Ejemplo 30 * 234 = 7020.

		6.	Si el resultado de ACUM1 es menor a cien (100) se multiplica por cien (100). En caso contrario no se
		realiza ninguna operación. Ejemplo: ACUM1 = ACUM1 * 100

		7.	Si el resultado de ACUM2 es menor a cien (100) se multiplica por cien (100). En caso contrario no se
		realiza ninguna operación. Ejemplo: ACUM2 = ACUM2 * 100


		Fecha de Vencimiento del Recibo: Se toma la fecha de vencimiento del recibo y se procede de la siguiente manera:
		1.	Se convierte la fecha en una cadena de dígitos en formato AAAAMMDD. Ejemplo 20/04/2016 resultara en 20160420.

		2.	Se toma la fecha resultante anterior y se realiza una suma de cada uno de sus dígitos y se acumula en una
		variable. Ejemplo: Tomando la fecha resultante anterior 20160420.

		2 + 0 + 1 + 6 + 0 + 4 + 2 + 0 = 15

		Monto suma fecha (MSF): 15

		3.	Se procede a realizar el producto de cada digito de la fecha resultante contra su índice de posición dentro
		de la fecha resultante. Este producto se va acumulando en una variable. Comenzando por el digito más significativo
		a la izquierda (posición 1) hasta el digito más significativo a la derecha (posición 8). Ejemplo: Tomando la fecha
		resultante anterior 20160420.

		2 * 1 = 2
		0 * 2 = 0
		1 * 3 = 3
		6 * 4 = 24
		0 * 5 = 0
		4 * 6 = 24
		2 * 7 = 14
		0 * 8 = 0

		Monto suma producto fecha (MPF): 67

		4.	Se suma el resultado del paso 2 (MSF) y el paso 3 (MPF).
		ACUM3  = MSF + MPF. Ejemplo: 15 + 67 = 72.

		5.	Se multiplica el resultante del paso 2 (MSF) y el paso (MPF).
		ACUM4 = MSF * MPF. Ejemplo: 15 * 67 = 1005.

		Monto total a pagar del Recibo: Se toma el monto a pagar del recibo y se procede de la siguiente manera:
		1.	Se toma el monto total a pagar del recibo y se convierte en una cadena de dígitos (sin punto ni comas). Si
		la cadena resultante de dígitos es menor a 19 dígitos, se debe completar el mismo con ceros (0) a la izquierda.
		Ejemplo: El monto 1564,02 quitando la coma del decimal quedaría 156402 como esta cadena tiene menos de 19
		dígitos se debe complementar el mismo con ceros (0) a la izquierda. El resultado de la operación será 000000156402.
		Si al suprimir los puntos y comas del monto a pagar  da como resultado una cadena de dígitos mayor a doces (19),
		se deberá tomar los doces (19) dígitos más significativos a la derecha de la cadena resultante para utilizarlos
		en el algoritmo. Ejemplo: Un monto a pagar de 12.356.025.451,25 al quitar los puntos y coma queda 1235602545125,
		luego se deben tomar los 19 dígitos más significativos a la derecha 235602545125 para utilizarlos en el algoritmo.

		2.	Se toma la cadena de dígitos del paso anterior y se suma cada dígito y se acumula en una variable. Ejemplo:
		tomando el monto a pagar 1564,02 al realizar la conversión quedara lo siguiente: 000000156402, luego se suman los
		dígitos:
		0 + 0 +0 + 0 + 0 + 0 + 1 + 5 + 6 + 4 + 0 + 2 = 18
		Monto suma monto (MSM): 18.

		3.	Se realiza el producto entre los dígitos del monto a pagar contra su posición dentro de la cadena de dígitos.
		Este producto se va acumulando en una variable. Comenzando por el digito más significativo a la izquierda (posición 1)
		hasta el digito más significativo a la derecha (posición 12). Ejemplo si se tiene el monto a pagar 1564,02 al realizar
		la conversión quedara lo siguiente: 000000156402, luego la sumatoria de producto:

		0 * 1 = 0
		0 * 2 = 0
		0 * 3 = 0
		0 * 4 = 0
		0 * 5 = 0
		0 * 6 = 0
		1 * 7 = 7
		5 * 8 = 40
		6 * 9 = 54
		4 * 10 = 40
		0 * 11 = 0
		2 * 12 = 24

		La suma será: 165
		Monto suma producto monto (MPM): 165

		4.	Luego se suma el resulta de MSM y MPM, y se guarda en una variable:
		ACUM5 = MSM + MPM. Ejemplo 18 + 165 = 183

		5.	Luego se multiplica el resultado de MSM y MPM, y se guarda en una variable:
		ACUM6 = MSM * MPM. Ejemplo 18 * 165 = 2970.


		Totalizar:
		1.	Se suma el resultado de las variables ACUM1, ACUM2, ACUM3, ACUM7, ACUM5 y ACUM6.

			SUBTOTAL = ACUM1 + ACUM2 + ACUM3 + ACUM4 + ACUM5 + ACUM6

		2.	Luego se divide SUBTOTAL entre 11 y se toma la parte entero de dicha división.

		  	MOD = trunc(SUBTOTAL/11)

		3.	Luego se suma el resultado del paso anterior contra la longitud del monto a pagar del recibo
		(incluyendo enteros y decimales).

			RESULTADO = MOD + longitud(monto a pagar del recibo).

		4.	Si RESULTADO es mayor 999 RESULTADO será igual a trunc(RESULTADO / 10). Si RESULTADO es menor a 100
		entonces RESULTADO sera igual a RESULTADO por 10

	 */
	class GenerarValidadorReciboTresDigito
	{

		/**
		 * modelo del tipo clase "Deposito"
		 * @var Deposito
		 */
		private $_model;

		private $_cvbRecibo = '';

		/**
		 * integer de 2 digito
		 * @var integer
		 */
		private $_codigoContribuyente;
		private $_codigoRecibo;
		private $_codigoMonto;
		private $_codigoFechaVcto;

		private $_acumulado1;
		private $_acumulado2;
		private $_acumulado3;
		private $_acumulado4;
		private $_acumulado5;
		private $_acumulado6;

		private $_subTotal;

		private $_ente;

		CONST LONG_RECIBO = 10;
		CONST LONG_MONTO = 19;
		CONST LONG_FECHA = 8;



		/**
		 * Metodo constructor de la clase.
		 * @param Deposito $model modelo del tipo clase "Deposito".
		 */
		public function __construct($model)
		{
			$this->_model = $model;
			$e = '000' . Yii::$app->ente->getEnte();
			$this->_ente = substr($e, -3);
		}



		/**
		 * Metodo que inicia el proceso para obtener el codigo validor bancario del
		 * recibo de pago.
		 * @return string retorna un string con la estructura del codigo validador
		 * bancario del recibo.
		 */
		public function getCodigoValidadorRecibo()
		{

			// Los siguiente son los enteros sin formato con los ceros justificados
			// a la izquierda.
			$this->_codigoRecibo = self::generarCodigoRecibo();
			$this->_codigoFechaVcto = self::generarCodigoFechaVcto();
			$this->_codigoMonto = self::generarCodigoMonto();

			$this->_subTotal = self::getSubTotal();

			// parte entera de la division.
			$mod = self::getModulo($this->_subTotal, 11);

			$resultado = $mod + self::getLongitud(self::formatearMonto($this->_model->monto));

			$this->_cvbRecibo = self::getCodigoValidador($resultado);

			return $this->_cvbRecibo;
		}




		/***/
		private function getCodigoValidador($resultado)
		{
			if ( $resultado > 999 ) {
				return $result = self::getModulo($resultado, 10);
			} elseif ( $resultado < 100 ) {
				return $result = $resultado * 10;
			}
			return $resultado;
		}



		/**
		 * Metodo que generara el codigo de 2 digitos que pertenece la contribuyente
		 * @return
		 */
		private function generarCodigoContribuyente()
		{

			$long = self::getLongitud($this->_model->id_contribuyente);
			$this->_codigoContribuyente = $long;
		}



		/***/
		private function generarCodigoRecibo()
		{
			return self::getNumeroBase($this->_model->recibo, self::LONG_RECIBO);
		}




		/**
		 * Metodo que generara el codigo de 2 digito que pertenece al monto del recibo.
		 * Se quitan los sepparadores de miles y decimales, se busca generar una cadena
		 * de digitos consecutivos. Para el casode los decimales que comienza en cero (0)
		 * el resultado debe ser al similar a 099. Por ejemplo:
		 * 0,25 o 0.25 debe generar 025.
		 * @return
		 */
		private function generarCodigoMonto()
		{
			$cadena = self::formatearMonto($this->_model->monto);
			return self::getNumeroBase($cadena, self::LONG_MONTO);

		}




		/***/
		private function formatearMonto($montoBase)
		{
			$monto = number_format($montoBase, 2);

			// se debe quitar los separadores de punto y decimales del monto.
			// El resultado debe ser una cadena de digito.
			$cadena = str_replace(".", "", $monto);
			$cadena = str_replace(",", "", $cadena);

			return $cadena;
		}




		/**
		 * Metodo que debe crear una cadena continua con el valor de la fecha, este
		 * atributo debe tener el formato dd-mm-yyyy o dd/mm/yyyy y convertirlo en
		 * una cadena ddmmyyyy.
		 * @return
		 */
		private function generarCodigoFechaVcto()
		{
			$cadena = self::formatearFecha($this->_model->fecha);
			return self::getNumeroBase($cadena, self::LONG_FECHA);

		}





		/***/
		private function formatearFecha($fechaBase)
		{
			$fecha = date('Y-m-d', strtotime($fechaBase));

			// se debe quitar los separadores de la fecha.
			// El resultado debe ser una cadena de digito.
			$cadena = str_replace("-", "", $fecha);
			$cadena = str_replace("/", "", $cadena);

			return $cadena;
		}




		/**
		 * Metodo que determinara la longitud de un numero entero segun la politica que se
		 * defino.
		 * @param integer $numeroSinFormato numero entero.
		 * @return integer retorna un entero que indica la longitud del numero base o de
		 * su equivalente segun la politica.
		 */
		private function getLongitud($numeroSinFormato)
		{
			$long = 0;
			return $long = strlen($numeroSinFormato);
		}




		/**
		 * Metodo que recibe un numero entero y concatena los ceros a la izquierda
		 * para retornar un numero formateado con ceros a la izquierda o si el numero
		 * base supero el maximo permitido retornara los x mas significativos a la derecha.
		 * @param  integer $numeroSinFormato entero
		 * @param  integer $longitud longitud maxima del numero base.
		 * @return string
		 */
		private function getNumeroBase($numeroSinFormato, $longitud)
		{
			$ceros = '0';
			$cadena = '';
			$numero = '';

			for ( $i = 1; $i <= $longitud; $i++ ) {
				$ceros .= $ceros;
			}

			$cadena = $ceros . $numeroSinFormato;
			$numero = substr($cadena, -($longitud));
			return $numero;

		}



		/**
		 * Metodo que realiza la sumatoria de los digitos del numero.
		 * @param string $numero cadena de digitos que puede estar justificada a la izquierda
		 * con ceros.
		 * @return integer.
		 */
		private function sumaDigito($numero)
		{
			$suma = 0;
			$arreglo = [];
			foreach (str_split($numero) as $key => $value ) {
				$arreglo[$key+1] = $value;
			}

			foreach ( $arreglo as $key => $value ) {
				$suma = $suma + $value;
			}

			return $suma;
		}



		/**
		 * Metodo que realiza la sumatoria de los productos del digito contra su indice-posicion
		 * en la cedena del numero.
		 * @param string $numero cadena de digotos que puede estar justificada a la izquierda
		 * con ceros.
		 * @return integer.
		 */
		private function sumaProductoDigito($numero)
		{
			$suma = 0;
			$arreglo = [];
			foreach (str_split($numero) as $key => $value ) {
				$arreglo[$key+1] = $value;
			}

			foreach ( $arreglo as $key => $value ) {
				$suma = $suma + ( $key * $value );
			}

			return $suma;
		}




		/***/
		private function getModulo($monto, $base)
		{
			return round($monto / $base);
		}



		/***/
		private function getAcumulado1()
		{
			$this->_acumulado1 = self::sumaDigito($this->_codigoRecibo) + self::sumaProductoDigito($this->_codigoRecibo);
			if ( $this->_acumulado1 < 100 ) {
				$this->_acumulado1 = $this->_acumulado1 * 100;
			}
			return $this->_acumulado1;
		}


		/***/
		private function getAcumulado2()
		{
			$this->_acumulado2 = self::sumaDigito($this->_codigoRecibo) * self::sumaProductoDigito($this->_codigoRecibo);
			if ( $this->_acumulado2 < 100 ) {
				$this->_acumulado2 = $this->_acumulado2 * 100;
			}
			return $this->_acumulado2;
		}



		/***/
		private function getAcumulado3()
		{
			$this->_acumulado3 = self::sumaDigito($this->_codigoFechaVcto) + self::sumaProductoDigito($this->_codigoFechaVcto);
			return $this->_acumulado3;
		}


		/***/
		private function getAcumulado4()
		{
			$this->_acumulado4 = self::sumaDigito($this->_codigoFechaVcto) * self::sumaProductoDigito($this->_codigoFechaVcto);
			return $this->_acumulado4;
		}


		/***/
		private function getAcumulado5()
		{
			$this->_acumulado5 = self::sumaDigito($this->_codigoMonto) + self::sumaProductoDigito($this->_codigoMonto);
			return $this->_acumulado5;
		}


		/***/
		private function getAcumulado6()
		{
			$this->_acumulado6 = self::sumaDigito($this->_codigoMonto) * self::sumaProductoDigito($this->_codigoMonto);
			return $this->_acumulado6;
		}


		/***/
		private function getSubTotal()
		{
			return $subTotal = self::getAcumulado1() + self::getAcumulado2() + self::getAcumulado3() + self::getAcumulado4() + self::getAcumulado5() + self::getAcumulado6();
		}


	}

?>