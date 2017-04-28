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
 *  @file PagoReciboLoteSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class PagoReciboLoteSearch
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

	namespace backend\models\recibo\pago\lote;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use common\models\planilla\PlanillaSearch;
    use backend\models\recibo\depositodetalle\DepositoDetalle;
    use yii\data\ArrayDataProvider;
    use backend\models\utilidad\tipotarjeta\TipoTarjetaSearch;

    use backend\models\recibo\pago\lote\MostrarArchivoTxt;
    use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\pago\individual\PagoReciboIndividual;
    use backend\models\recibo\txt\RegistroTxtRecibo;

    use common\models\numerocontrol\NumeroControlSearch;



	/**
	* Clase que permite gestionar el pago en lote de los recibos contenidos
	* en el archivo txt de pago enviado por el banco. Se recibe los parametros
	* de localizacion del archivo (ruta y nombre del archivo). Se busca el archivo
	* luego se analizara el contenido del mismo para cerificar que se cumpla con
	* las especificaciones establecidas para su procesamiento. Esta especificaciones
	* son:
	* - Cantidad de columnas de ser igual a 16.
	* - Longitud del contenido de las columnas debe ser igaul a las especificaciones
	* indicadas en el documento enviado al banco.
	*
	* ==============================================================================================================
	* Campo 				Longitud 		Tipo 					Observacion
	* ==============================================================================================================
	* Numero de Recibo   		10 		  Numerico 		Numero del recibo de pago. Auto-incremental de la entidad
	* --------------------------------------------------------------------------------------------------------------
	* Monto del Recibo 			19 		  numerico 		Monto total del recibo.
	* --------------------------------------------------------------------------------------------------------------
	* Fecha de Pago 			08 		  Numerico 		Fecha de pago
	* --------------------------------------------------------------------------------------------------------------
	* Monto Efectivo 			19 		  Numerico		Monto por concepto de Efectivo, forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* Monto Cheque 				19		  Numerico 		Monto por concepto de Cheque, forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* Cta Cheque 				25 	      Numerico 		Numero de cuenta asociada al cheque.
	* --------------------------------------------------------------------------------------------------------------
	* Nro. Cheque 			    15		  Numerico 		Numero del cheque. Forma de Pago
	* --------------------------------------------------------------------------------------------------------------
	* Fecha Cheque  			08 		  Numerico 		Fecha de pago, registrado por el banco. ddmmyyyy
	* --------------------------------------------------------------------------------------------------------------
	* Monto TDD 			    19 		  Numerico 		Monto por concepto de tarjeto de debito. Forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* Nro. TDD 					19 		  Numerico 	    Numero de la tarjeta de debito
	* --------------------------------------------------------------------------------------------------------------
	* Monto TDC                 19        Numerico 		Monto por concepto de tarjeta de credito. Forma de pago.
	* --------------------------------------------------------------------------------------------------------------
	* Nro. TDC                  19		  Numerico 	    Numero de la tarjeta de credito.
	* --------------------------------------------------------------------------------------------------------------
	* Monto Transferencia 		19 		  Numerico      Monto por concepto de transferencia. Forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* Nro. Transaccion          19        Numerico      Numero de la transferencia.
	* --------------------------------------------------------------------------------------------------------------
	* Monto Total               19        Numerico      Monto total del registro. Sumatoria de las formas de pago.
	* --------------------------------------------------------------------------------------------------------------
	* Nro. Cta. Recaudadora     25        Numerico 		Numero de la cuenta recaudadora asociada a la Alcaldia.
	* --------------------------------------------------------------------------------------------------------------
	*
	*/
	class PagoReciboLoteSearch
	{

		private $_nro_control;			// Numro de control de la operacion.
    	private $_mostarArchivo;		// Instancia de la clase MostrarArchivoTxt().




		/***/
		public function __construct($ruta, $archivo)
		{
			$this->_nro_control = 0;
			$this->_mostarArchivo = New MostrarArchivoTxt($ruta, $archivo);
		}



		/**
		 * Metodo que instancia la clase que genera el numero de control que se utilizara
		 * en la operacion de insercion de los registros existentes en el archivo txt de pagos.
		 * @return
		 */
		private function generarNumeroControlOperacion()
		{
			$numeroControlSearch = New NumeroControlSearch();
			$this->_nro_control = $numeroControlSearch->generarNumeroControl();
		}


		/**
		 * Metodo getter del numero de control generado.
		 * @return integer.
		 */
		public function getNroControl()
		{
			return $this->_nro_control;
		}



		/***/
		private function validarArchivo()
		{
			$this->_mostarArchivo->iniciarMostrarArchivo();
			if ( count($this->_mostarArchivo->getError()) == 0 ) {

			} else {
				// Enviar mensaje de error.
			}

		}

		/***/
		private function getRegistrosTxtPago()
		{

		}








	}

?>