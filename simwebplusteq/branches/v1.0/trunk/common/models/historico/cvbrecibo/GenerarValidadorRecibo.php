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
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\calculo\cvb\ModuloValidador;


	/**
	* Clase que recibo el modelo del tipo calse "Deposito" para generar el
	* codigo validador bancario (cvb) a partir de dicho modelo. Debera retorna
	* un string con la estructura 123-1234-1234. Se aplica la logica que se
	* debe aplicar para generar dicho cvb. En lageneracion de dicho numero se
	* debe de considerar la longitud de cada numero base que se utilizara.
	* Numero Base seran:
	* - Identificador del Contribuyente.
	* - Numero de Recibo.
	* - Monto de pago del Recibo.
	* - Fecha de Vencimiento del Recibo.
	* Se determinara el codigo validador de cada numero base, y despues se le
	* concatenara la longitud de dicho numero base al codigo validador resultante.
	* Para los casos donde la longitud del numero base supere los 9 digitos, se
	* tomara el digito más significativo a la derecha de dicha longitud. Ejemplo,
	* si se tiene un identificador del Contribuyente 105500356873, la longitud
	* de este será 12, lo que implicaría tomar el dos (2) como valor a concatenar
	* al codigo validar.
	*
	*/
	class GenerarValidadorRecibo extends ModuloValidador
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

		private $_ente;


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

			self::generarCodigoContribuyente();
			self::generarCodigoRecibo();
			self::generarCodigoMonto();
			self::generarCodigoFechaVcto();

			$this->_cvbRecibo = $this->_ente . '-' . $this->_codigoContribuyente . $this->_codigoRecibo . '-' . $this->_codigoMonto . $this->_codigoFechaVcto;

			return $this->_cvbRecibo;
		}



		/**
		 * Metodo que generara el codigo de 2 digitos que pertenece la contribuyente
		 * @return
		 */
		private function generarCodigoContribuyente()
		{

			$digito = self::getDigitoControlNumero($this->_model->id_contribuyente);
			$long = self::getLongitud($this->_model->id_contribuyente);
			$this->_codigoContribuyente = $digito . $long;
		}



		/**
		 * Metodo que generara el codigo de 2 digito que pertenece al numero de recibo.
		 * @return
		 */
		private function generarCodigoRecibo()
		{

			$digito = self::getDigitoControlNumero($this->_model->recibo);
			$long = self::getLongitud($this->_model->recibo);
			$this->_codigoRecibo = $digito . $long;
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
			$monto = number_format($this->_model->monto, 2);

			// se debe quitar los separadores de punto y decimales del monto.
			// El resultado debe ser una cadena de digito.
			$cadena = str_replace(".", "", $monto);
			$cadena = str_replace(",", "", $cadena);

			$digito = self::getDigitoControlNumero($cadena);
			$long = self::getLongitud($cadena);

			$this->_codigoMonto = $digito . $long;

		}



		/**
		 * Metodo que debe crear una cadena continua con el valor de la fecha, este
		 * atributo debe tener el formato dd-mm-yyyy o dd/mm/yyyy y convertirlo en
		 * una cadena ddmmyyyy.
		 * @return
		 */
		private function generarCodigoFechaVcto()
		{
			$fecha = date('d-m-Y', strtotime($this->_model->fecha));

			// se debe quitar los separadores de la fecha.
			// El resultado debe ser una cadena de digito.
			$cadena = str_replace("-", "", $fecha);
			$cadena = str_replace("/", "", $cadena);

			$digito = self::getDigitoControlNumero($cadena);
			$long = self::getLongitud($cadena);

			$this->_codigoFechaVcto = $digito . $long;

		}




		/**
		 * Metodo que se encargara de obtener el digito control del identificador del
		 * contribuyente
		 * @param string $numeroBase cadena de digito.
		 * @return integer retorna el codigo control del identificador del contribuyente
		 */
		public function getDigitoControlNumero($numeroBase)
		{
			return $this->getDigitoControl($numeroBase);
		}




		/**
		 * Metodo que determinara la longitud de un numero entero segun la politica que se
		 * defino.
		 * @param integer $numeroBase numero entero.
		 * @return integer retorna un entero que indica la longitud del numero base o de
		 * su equivalente segun la politica.
		 */
		private function getLongitud($numeroBase)
		{
			$long = 0;
			$long = strlen($numeroBase);
			if ( $long > 0 ) {
				if ( $long > 9 ) {
					// El digito más significativo a la derecha.
					return $long = (int)substr($long, -1);

				} else {
					return $long;
				}
			}
			return false;
		}

	}

?>