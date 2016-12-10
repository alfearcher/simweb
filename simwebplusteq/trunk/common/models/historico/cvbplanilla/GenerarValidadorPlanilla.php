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
 *  @file GenerarValidadorPlanilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 GenerarValidadorPlanilla
 *
 *  @class GenerarValidadorPLanilla
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

	namespace common\models\historico\cvbplanilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\planilla\PlanillaSearch;
	use common\models\historico\cvbplanilla\HistoricoCodigoValidadorPlanillaForm;


	/**
	 * Clase que genera el CODIGO VERIFICADOR BANCARIO para la planilla de pago. El procedimiento consiste
	 * en aplicar un algoritmo en dos fases.
 	 * 	En esta fase se tomaran como entrada del algoritmo los siguientes valores:
     *  a - Identificador del Contribuyente (ID Contribuyente).
     *  b - Numero de Liquidacion (Numero de Planilla).
 	 *  c - Monto de la Planilla de pago.
	 *
	 *
	 *   		RUTINA DEL PRIMER ALGORITMO PARA GENERAR EL CODIGO DE VERIFICACION BANCARIO
	 *				 	(VERSION 6 DIGITOS). CON EL IDENTIFICADOR DE LA ALCALDIA.
     *
	 *	1.	Se suma cada digito del ID del Contribuyente con su correspondiente índice (posición en el numero,
	 *	 	empezando desde el numero uno (1)) y se van sumando.
	 *
	 *			Ejemplo. ID del Contribuyente: 150248
	 *		 	1 + 1 = 2
	 *		  	5 + 2 = 7
	 *		   	0 + 3 = 3
	 *		    2 + 4 = 6
	 *		    4 + 5 = 9
	 *		    8 + 6 = 14
     *
	 *		Sumatoria  =  41
	 *
	 *	2.	La resultante del paso anterior se divide entre la cantidad de dígitos que posea el ID del Contribuyente.
	 *		En este caso es seis (6), y se toma el resto de la división.
     *
     *			Resto 41 / 6 = 5  Nota: Si el valor del resto es mayor a 10 se tomara cero (0).
	 *
	 *	3.	Luego se concatenan el Resto obtenido de la operación anterior con el índice de mayor posición del ID del
	 *		Contribuyente, en este caso es seis (6). Si el ultimo índice es mayor o igual a 10, se tomara el ultimo
	 *		digito del ID del Contribuyente como valor alternativo para concatenar. Luego el valor obtenido después de
	 *		la concatenación será 56.
     *
	 *	4.	Seguidamente se toma el Nro. De Liquidación (Nro. De Planilla) y se repite el proceso explicado en los
	 *	    pasos 1, 2 y 3.
	 *
	 *			Ejemplo: Nro. de Liquidación 1921604
	 *			1 + 1 = 2
	 *			9 + 2 = 11
	 *			2 + 3 = 5
	 *			1 + 4 = 5
	 *			6 + 5 = 11
	 *			0 + 6 = 5
	 *			4 + 7 = 11
     *
	 *			Sumatoria = 50.
     *
	 *	5.	La resultante obtenida en el paso 4 se divide entre la cantidad de dígitos que tenga el Nro. de Liquidación,
	 *	 	en este caso son siete (7), y se toma el resto de la operación.
     *
	 *			Resto 50 / 7 = 1  Nota: Si el valor del resto es mayor a 10 se tomara cero (0).
     *
	 * 	6.	Luego se concatena al Resto obtenido en el paso anterior (paso 5) con el índice de mayor valor, que en este
	 *	 	caso es siete (7). Si el último índice es mayor o igual a 10, se tomara el último digito del Nro. de
	 *	 	Liquidación como valor alternativo para concatenar. Obteniendo así el segundo par de valores; 17.
     *
	 *	7.	Para obtener el último par de valores, se utilizará el Monto a Pagar en la planilla. Se tomara solo los dígitos,
	 *	 	sin la coma o punto.
	 *
	 *			Ejemplo 1.205,75 Bs.F el valor a tomar será 120575
     *
	 *	8.	Se repiten los pasos 1, 2 y 3 para este caso.
	 *
	 *			1 + 1 = 2
	 *		 	2 + 2 = 3
	 *		  	0 + 3 = 3
	 *		   	5 + 4 = 9
	 *			7 + 5 = 12
	 *			5 + 6 = 11
     *
	 *			Sumatoria = 40
     *
	 *	9.	La sumatoria anterior se divide entre la cantidad total de dígitos del Monto a Pagar en la planilla. Que en este
	 *	 	caso es seis (6) y se toma el resto de la operación de forma similar como en los caso anteriormente explicados.
     *
	 *			Resto  40 / 6 = 4 Nota: Si el valor del resto es mayor a 10 se tomara cero (0).
     *
	 * 	10.	Luego se procederá a concatenarle al Resto obtenido en el paso anterior, con el índice de mayor valor, que en este
	 *	 	caso es seis (6). Si el último índice es mayor o igual a 10, se tomara el último digito del Monto a Pagar en la
	 *	 	planilla como valor alternativo para concatenar. Después de concatenar ambos valores, el último par de valores
	 *	 	será 46.
     *
	 *	11.	Después de aplicar el primer algoritmo el mismo nos dará como resultante los tres (3) pares de valores siguientes
	 *	 	561746.
     *
	 *				RUTINA DEL SEGUNDO ALGORITMO PARA GENERAR EL CODIGO DE VERIFICACION BANCARIO
	 *						(VERSION 6 DIGITOS). CON EL IDENTIFICADOR DE LA ALCALDIA.
     *
	 *	12.	Se toma el valor resultante del paso 11 y a cada digito se le sumara el valor mas significativo a la derecha
	 *	 	(ultimo digito) del IDENTIFICADOR DE LA ALCALDIA, si la resultante de la suma genera un valor mayor a 9, se tomara
	 *	 	como digito resultante el valor mas significativo a la derecha del mismo.
     *
	 *			Ejemplo: En el paso 11 obtuvimos el siguiente valor del primer algoritmo 561746, asumiendo que el IDENTIFICADOR
	 *			DE LA ALCALDIA es igual a 13, entonces se procede de la siguiente manera:
     *
	 *			5 + 3 = 8	=>   valor a tomar 8
	 *			6 + 3 = 9   =>   valor a tomar 9
	 *			1 + 3 = 4   =>   valor a tomar 4
	 *			7 + 3 = 10  =>   valor a tomar 0
	 *			4 + 3 = 7   =>   valor a tomar 7
	 *			6 + 3 = 9   =>   valor a tomar 9
     *
	 *	13.	Luego el CODIGO VERIFICADOR BANCARIO será 894079
	 *
	 */
	class GenerarValidadorPlanilla extends PlanillaSearch
	{

		private $_planilla;				// Numero de planilla.
		private $_id_contribuyente;		// Identificador del Contribuyente.
		private $_monto;				// Monto total a pagar de la planilla.

		private $_cvbPrimeraFase = '';	// Codigo validador bancario de la primera fase.
		private $_cvbPlanilla = '';		// codigo validaro bancario final. Despues de la segunda fase.

		/**
		 * integer de 2 digito
		 * @var string
		 */
		private $_codigoContribuyente;	// Par de codigos validadores
		private $_codigoPlanilla;		// Par de codigos validadores
		private $_codigoMonto;			// Par de codigos validadores.

		private $_ente;					// Identificador de la Alcaldia.

		/**
		 * Instancia de la clase PlanillaSearch.
		 * @var PlanillaSearch.
		 */
		private $_searchPlanilla;


		private $_historico;	// Instacia de la clase HistoricoCodigoValidadorPlanillaForm()


		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de planilla de la liquidacion.
		 */
		public function __construct($planilla)
		{
			$this->_planilla = $planilla;
			$e = '000' . Yii::$app->ente->getEnte();
			$this->_ente = substr($e, -3);
			$this->_cvbPlanilla = '';

			// Instancia de la clase padre.
			parent::__construct($planilla);

			$this->_historico = New HistoricoCodigoValidadorPlanillaForm($this->_planilla);
		}



		/**
		 * Metodo que inicia el proceso para obtener el codigo validor bancario del
		 * recibo de pago.
		 * @return string retorna un string con la estructura del codigo validador
		 * bancario del recibo.
		 */
		public function getCodigoValidadorBancarioPlanilla()
		{

			$montoTotal = 0;
			$detallePlanilla = $this->getResumenGeneral();

			$this->_id_contribuyente = $detallePlanilla['id_contribuyente'];
			$montoTotal = (( $detallePlanilla['sum_monto'] + $detallePlanilla['sum_recargo'] + $detallePlanilla['sum_interes'] ) - ( $detallePlanilla['sum_descuento'] + $detallePlanilla['sum_monto_reconocimiento'] ));

			$this->_monto = $montoTotal;

			// Primera fase
			self::getCodigoValidadorContribuyente();
			self::getCodgiovalidadorPlanilla();
			self::getCodigoValidadorMonto();

			$this->_cvbPrimeraFase = $this->_codigoContribuyente . $this->_codigoPlanilla . $this->_codigoMonto;

			// Segunda fase
			if ( strlen($this->_cvbPrimeraFase) == 6 ) {
				$this->_cvbPlanilla = self::aplicarSegundaFaseAlgoritmo();
				$arregloDatos = $this->_historico->attributes;
				$arregloDatos['planilla'] = $this->_planilla;
				$arregloDatos['id_contribuyente'] = $this->_id_contribuyente;
				$arregloDatos['codigo'] = $this->_cvbPlanilla;
				$arregloDatos['monto'] = self::getMontoPlanilla();

				$this->_historico->guardarHistorico($arregloDatos);
			}

			return $this->_cvbPlanilla;
		}



		/***/
		public function getCodigoContribuyente()
		{
			return $this->_codigoContribuyente;
		}


		/***/
		public function getCodigoPlanilla()
		{
			return $this->_codigoPlanilla;
		}


		/***/
		public function getCodigoMonto()
		{
			return $this->_codigoMonto;
		}


		/***/
		public function getMontoPlanilla()
		{
			return $this->_monto;
		}


		/***/
		public function getIdContribuyente()
		{
			return $this->_id_contribuyente;
		}


		/***/
		public function getCodigoValidadorPlanillaGenerado()
		{
			return $this->_cvbPlanilla;
		}



		/**
		 * Metodo que suma el valor de cada digito del numero base contra su indice-posicion en el numero
		 * base. Como el algoritmo define como primer indice la posicion con el numero uno (1), se debe
		 * realizar un ajuste en la operacion, debido a que los array comienza en cero (0).
		 * @param  integer $numeroBase entero que sera tratado
		 * @return integer retorna un entero producto de la suma, sino define el numero base correctamente
		 * devolvera cero (0).
		 */
		private function sumarContraIndice($numeroBase)
		{
			$sumaIndice = 0;
			if ( trim($numeroBase) !== '' ) {
				$arregloBase = str_split($numeroBase);

				// Se suma cada digito del numero base contra su indice-posicion en el numero.
				// Como el indice del arreglo comienza con el cero (0), se debe agregar a la suma
				// un uno (1).
				foreach ( $arregloBase as $key => $value ) {
					$sumaIndice = ( ( $key + 1 ) + $value ) + $sumaIndice;
				}
			}
			return $sumaIndice;
		}



		/**
		 * Metodo que determina el resto de la division de la sumatoria de los digitos numero base contra
		 * sus indices-posicion y el indice de mayor posicion o longitud del numero base.
		 * @param  integer $numeroBase entero
		 * @return integer|boolean retorna un entero si determina el resto de la division, encaso contrario
		 * retornara false.
		 */
		private function getPrimerDigito($numeroBase)
		{
			$resto = 0;
			$sumaIndice = self::sumarContraIndice($numeroBase);
			if ( $sumaIndice > 0 ) {

				// Se crea un arreglo con los enteros que conforman el numeroBase.
				$arregloBase = str_split($numeroBase);

				// Tamaño del arreglo.
				$ultimo = count($arregloBase);

				if ( $ultimo > 0 ) {
					// Se obtiene el resto de la division.
					$resto = $sumaIndice % $ultimo;
					if ( $resto >= 10 ) {
						$resto = 0;
					}

				} else {
					return false;
				}

			} else {
				return false;
			}

			return $resto;
		}




		/**
		 * Metodo que determina el segundo valor de cada grupo de pares de digitos.
		 * @param  integer $numeroBase entero a considerar.
		 * @return integer|string retorna un entero si genera el par de digitos, en csaso cantrario
		 * un string vacio.
		 */
		private function getSegundoDigito($numeroBase)
		{
			$segundo = '';
			$total = strlen($numeroBase);
			if ( $total >= 10 ) {

				// Se obtiene el mas significstiv a la derecha.
				$segundo = substr(trim($numeroBase), -1);

			} else {
				$segundo = $total;
			}

			return $segundo;
		}





		/**
		 * Metodo que crea el primer par de digitos del cvb.
		 * @return integer|string retorna un entero si genera el par de digitos, en csaso cantrario
		 * un string vacio.
		 */
		private function getCodigoValidadorContribuyente()
		{
			$this->_codigoContribuyente = '';
			if ( $this->_id_contribuyente > 0 ) {
				$primerDigito = self::getPrimerDigito($this->_id_contribuyente);
				if ( is_int($primerDigito) ) {
					$segundoDigito = self::getSegundoDigito($this->_id_contribuyente);
					if ( $segundoDigito !== false ) {
						$this->_codigoContribuyente = $primerDigito . $segundoDigito;
					}
				}
			}
			return $this->_codigoContribuyente;
		}



		/**
		 * Metodo que crea el segundo par de digitos del cvb.
		 * @return integer|string retorna un entero si genera el par de digitos, en csaso cantrario
		 * un string vacio.
		 */
		private function getCodigoValidadorMonto()
		{
			$this->_codigoMonto = '';
			$monto = number_format($this->_monto, 2);

			// se debe quitar los separadores de punto y decimales del monto.
			// El resultado debe ser una cadena de digito.
			$cadena = str_replace(".", "", $monto);
			$cadena = str_replace(",", "", $cadena);

			$primerDigito = self::getPrimerDigito($cadena);
			if ( is_int($primerDigito) ) {
				$segundoDigito = self::getSegundoDigito($cadena);
				if ( $segundoDigito !== false ) {
					$this->_codigoMonto = $primerDigito . $segundoDigito;
				}
			}

			return $this->_codigoMonto;
		}



		/**
		 * Metodo que crea el tercer par de digitos del cvb.
		 * @return integer|string retorna un entero si genera el par de digitos, en csaso cantrario
		 * un string vacio.
		 */
		private function getCodgiovalidadorPlanilla()
		{
			$this->_codigoPlanilla = '';
			if ( $this->_planilla > 0 ) {
				$primerDigito = self::getPrimerDigito($this->_planilla);
				if ( is_int($primerDigito) ) {
					$segundoDigito = self::getSegundoDigito($this->_planilla);
					if ( $segundoDigito !== false ) {
						$this->_codigoPlanilla = $primerDigito . $segundoDigito;
					}
				}
			}
			return $this->_codigoPlanilla;
		}



		/**
		 * Metodo que aplica la segunda fase del algoritmo para determinar el codigo validador bancario
		 * de la planilla
		 * @return integer|string retorna un entero de 6 digitos o un string vacio.
		 */
		private function aplicarSegundaFaseAlgoritmo()
		{
			$cvb = '';
			$digitoAlcaldia = substr($this->_ente, -1);

			$arregloFase = str_split($this->_cvbPrimeraFase);

			foreach ( $arregloFase as $key => $value ) {
				$suma = 0;
				$suma = $digitoAlcaldia + $value;
				if ( $suma >= 10 ) {
					$d = substr($suma, -1);
				} else {
					$d = $suma;
				}
				if ( trim($cvb) == '' ) {
					$cvb = $d;
				} else {
					$cvb = $cvb . $d;
				}
			}

			return $cvb;
		}

	}

?>