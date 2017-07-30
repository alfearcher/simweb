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
 *  @file MostrarArchivoTxt.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class MostrarArchivoTxt
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
    use yii\data\ArrayDataProvider;




	/**
	 *
	 */
	class MostrarArchivoTxt
	{

		/**
		 * Ruta donde se encuentra el archivo txt
		 * @var string
		 */
		private $_ruta;


		/**
		 * Nombre del archivo txt, incluido la extencion (.txt)
		 * @var string
		 */
		private $_nombre_archivo;


        /**
         * Fecha de pago
         * @var date
         */
        private $_fecha_pago;

        /**
         * Variable donde se guarda el contenido del archivo txt de pago, por linea.
         * Cada linea es un elemento en dicho arreglo.
         * @var array
         */
        private $_arreglo_pago = [];

 		private $_errores = [];

        const NUMERO_COLUMNA = 16;




		/**
		 * Metodo constructor de la clase.
		 * @param string $ruta directorio donde se encuentra el archivo.
		 * @param string $archivo nombre del archivo.
		 */
		public function __construct($ruta, $archivo)
		{
			$this->_ruta = $ruta;
			$this->_nombre_archivo = $archivo;
		}



		/**
		 * Metodo que inicia el proceso
		 * @return
		 */
		public function iniciarMostrarArchivo()
		{
			$this->_arreglo_pago = [];
			self::lecturaArchivo();
		}



		/**
		 * Metodo getter de _arreglo_pago, contenido del archivo txt de pago.
		 * @return array
		 */
		public function getListaPago()
		{
			return $this->_arreglo_pago;
		}



		/**
		 * Metodo que permite retornar la cantidad de registros existente en el
		 * archivo txt de pago.
		 * @return integer
		 */
		public function getTotalLinea()
		{
			return count($this->_arreglo_pago);
		}




		/**
		 * Metodo setting de errores
		 * @param string $mensaje mensaje de error.
		 */
		private function setError($mensaje)
		{
			$this->_errores[] = $mensaje;
		}



		/**
		 * Metodo getter de los mensaje de error.
		 * @return array
		 */
		public function getError()
		{
			return $this->_errores;
		}



		/**
		 * Metodo que retorna la ruta donde se encuentra el archivo.
		 * @return string
		 */
		public function getRuta()
		{
			return $this->_ruta;
		}




		/**
		 * Metodo getter del nombre del archivo.
		 * @return string
		 */
		public function getNombre()
		{
			return $this->_nombre_archivo;
		}





		/**
		 * Metodo que retorna la ruta completa donde esta localizado el archivo con los
		 * registros de pagos.
		 * @return string
		 */
		public function getRutaArchivoTxt()
		{
			return $ruta = self::getRuta() . self::getNombre();
		}



		/**
		 * Metodo que determina si un archivo existe.
		 * @return boolean
		 */
		public function existeArchivo()
		{
			$existe = true;
			if ( !file_exists(self::getRutaArchivoTxt()) ) {
				$mensaje = Yii::t('backend', 'El archivo no existe');
				self::setError($mensaje);
				$existe = false;
			}
			return $existe;
		}



		/**
		 * Metodo para obtener el data provider del contenido del archivo txt
		 * @return ArrayDataProvider.
		 */
		public function getDataProvider()
		{
			$data = self::getListaPago();
			$provider = New ArrayDataProvider([
				'key' => 'recibo',
				'allModels' => $data,
				'pagination' => false,
				'sort' => [
					'attributes' => ['recibo'],
				],
			]);
			return $provider;
		}



		/***/
		public function calcularTotalByRenglon($model, $nombreItem)
		{
			$suma = 0;
			if ( count($model) > 0 ) {
				foreach ( $model as $key => $value ) {
					$monto = 0;

					// Se convierte el string en un float para procesarlo.
					$monto = (float)$value[$nombreItem]/100;

					$suma = $suma + $monto;
				}
			}
			return $suma;
		}




		/**
		 * Metodo que realiza la lectura del archivo
		 * @return
		 */
		public function lecturaArchivo()
		{

			if ( self::existeArchivo() ) {

				// Ruta y nonmbre del archivo
				//$ruta = self::getRuta() . self::getNombre();
				$ruta = self::getRutaArchivoTxt();

				$fp = fopen($ruta, "r");
				$ct = 0;
				while(!feof($fp)) {
					$linea = fgets($fp);
					if ( $ct > 0 ) {
						if ( $linea !== null ) {
							//echo $linea . "<br />";
							self::armarItemPago($linea, $ct);
						}
					}
					$ct++;
				}
				fclose($fp);
			}
		}





		/**
		 * Metodo que convierte una linea del archivo txt en un arreglo de campos-valores.
		 * @param string $lineaPago representa una linea del archivo txt, esta linea contiene
		 * los datos del recibo y las frma en que se pago el recibo, ademas de los datos de las
		 * cuentas recaudadora. Luego envia el arreglo armado para que el mismo sea agregado
		 * a un arreglo de pagos general ($this->_arreglo_pago).
		 * @return
		 */
		private function armarItemPago($lineaPago, $linea = 0)
		{
			// se crea un arreglo con la estructura
			// {
			// 		campo1 => valor1,
			// 		campo2 => valor2,
			// 		.
			// 		.
			// 		campoN => valorN
			// }
			//
			$items = explode(';', $lineaPago);

			if ( (int)count($items) == self::NUMERO_COLUMNA ) {
				foreach ( $items as $key => $value ) {
					$pago[self::campos()[$key]] = trim($value);
				}
				self::addItem($pago);

			} else {
				$mensaje = Yii::t('backend', 'La linea del archivo no cumple con las especificaciones del numero de columnas. Linea: ') . $linea;
				self::setError($mensaje);
			}
		}




		/**
		 * Metodo que agrega un elemento al arreglo.
		 * @param array $itemPago arreglo de campos-valores de un registro de pago
		 * obtenido del archivo txt. Cada uno de estos itemPago representa una linea
		 * del archivo txt.
		 */
		private function addItem($itemPago)
		{
			$this->_arreglo_pago[] = $itemPago;
		}



		/**
		 * Metodo que retorna una estructura de campos que debe coincidir
		 * con las columnas del archivo txt.
		 * @return array
		 */
		private function campos()
		{
			return [
				0 => 'recibo',
				1 => 'monto_recibo',
				2 => 'fecha_pago',
				3 => 'monto_efectivo',
				4 => 'monto_cheque',
				5 => 'cuenta_cheque',
				6 => 'nro_cheque',
				7 => 'fecha_cheque',
				8 => 'monto_debito',
				9 => 'nro_debito',
				10 => 'monto_credito',
				11 => 'nro_credito',
				12 => 'monto_transferencia',
				13 => 'nro_transaccion',
				14 => 'monto_total',
				15 => 'nro_cuenta_recaudadora'
			];
		}

	}

?>