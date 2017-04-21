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
 *  @file ListaArchivoTxt.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class ListaArchivoTxt
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
	class ListaArchivoTxt
	{

		/**
		 * VAriable identificador del banco en la entidad "bancos"
		 * @var integer
		 */
		private $_id_banco;


        /**
         * Fecha de pago
         * @var date
         */
        private $_fecha_pago;

        /**
         * Arreglo de alias de los bancos para armar el nombre del archivo txt
         * de pagos.
         * @var array
         */
        private $_listaAlias = [];

        /**
         * Alias del banco para armar el nombre del archivo.
         * String de longitud 3 caracteres.
         * @var string
         */
        private $_alias;

        private $_nombre_archivo;

        private $_arreglo_pago = [];

        private $_lista_archivo = [];

        const ALIAS_ALCALDIA = 'teq';
        const NOMBRE_BASE = 'bco';





		/**
		 * Metodo constructor de la clase.
		 * @param integer $idBanco entero que identifica al banco dentro de la entidad "bancos"
		 * @param date $fechaPago fecha de pago.
		 */
		public function __construct($idBanco, $fechaPago)
		{
			$this->_id_banco = $idBanco;
			$this->_fecha_pago = date('Y-m-d', strtotime($fechaPago));
			$this->_listaAlias = require('alias-banco-archivo-txt.php');
			$this->_alias = $this->_listaAlias[$idBanco];
		}



		/**
		 * Metodo que inicia el proceso
		 * @return
		 */
		public function iniciarListaArchivo()
		{
			self::armarNombreArchivo();
		}




		/**
		 * bcoban_aaa_20170420
		 * @return
		 */
		private function armarNombreArchivo()
		{
			$fecha = str_replace("-", "", $this->_fecha_pago);
			$fecha = str_replace("/", "", $fecha);
			$this->_nombre_archivo = self::NOMBRE_BASE . $this->_alias . "_" . self::ALIAS_ALCALDIA . "_" . $fecha;
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
		 * Metodo que retorna la ruta donde se encuentra el archivo.
		 * @return string
		 */
		private function getRuta()
		{
			$rutas = require('ruta-archivo-txt.php');
			return $rutas[$this->_id_banco];
		}




		/**
		 * Metodo que retorna la ruta completa donde esta localizado el archivo con los
		 * registros de pagos.
		 * @return string
		 */
		public function getRutaArchivo()
		{
			return $ruta = self::getRuta() . self::getNombre() . '.txt';
		}



		/**
		 * Metodo que recorre un directorio particular para crear un arreglo con los
		 * nombres de los archivos que se encuentren en dicho directorio. Pero se hara
		 * un filtro para obtener solo los archivos que cumplan una condicion especifica.
		 * @return array
		 */
		public function crearListaArchivo()
		{
			if ( is_dir(self::getRuta()) ) {
				$gestor = opendir(self::getRuta());
				while ( false !== ($file = readdir($gestor)) ) {
	        		if ( $file !== '.' && $file !== '..' && $file !== NULL && substr(trim($file), -4) == '.txt' ) {
	        			$this->_lista_archivo[] = [
	        				'file' => $file,
	        				'path' => self::getRuta(),
	        			];
	        		}
	    		}
	    		closedir($gestor);
			} else {
				$this->_lista_archivo = [];
			}
		}





		/**
		 * Metodo para obtener el data provider
		 * @return ArrayDataProvider.
		 */
		public function getDataProvider()
		{
			$data = self::getListaArchivo();
			$provider = New ArrayDataProvider([
				'key' => 'file',
				'allModels' => $data,
				'pagination' => false,
				'sort' => [
					'attributes' => ['file'],
				],
			]);
			return $provider;
		}





		/***/
		public function getListaArchivo()
		{
			return $this->_lista_archivo;
		}





		/***/
		public function lecturaArchivo()
		{

			if ( is_dir(self::getRuta()) ) {
				$ruta = self::getRuta() . self::getNombre() . '.txt';
				$fp = fopen($ruta, "r");
				$ct = 0;
				while(!feof($fp)) {
					$linea = fgets($fp);
					if ( $ct > 0 ) {
						if ( $linea !== null ) {
							//echo $linea . "<br />";
							self::armarItemPago($linea);
						}
					}
					$ct++;
				}
				fclose($fp);
			}
		}





		/***/
		private function armarItemPago($itemPago)
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
			$items = explode(';', $itemPago);
			foreach ( $items as $key => $value ) {
				$pago[self::campos()[$key]] = $value;
			}
			self::addItem($pago);
		}




		/***/
		private function addItem($itemPago)
		{
			$this->_arreglo_pago[] = $itemPago;
		}



		/***/
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
				8 => 'monto_tdd',
				9 => 'nro_tdd',
				10 => 'monto_tdc',
				11 => 'nro_tdc',
				12 => 'monto_transferencia',
				13 => 'nro_transaccion',
				14 => 'monto_total',
				15 => 'nro_cuenta_recaudadora'
			];
		}

	}

?>