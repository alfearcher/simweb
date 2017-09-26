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

        private $_fecha_desde;
        private $_fecha_hasta;

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

        private $_lista_nombre_archivo = [];

        private $_arreglo_pago = [];

        private $_lista_archivo = [];

        const ALIAS_ALCALDIA = 'teq';
        const NOMBRE_BASE = 'bco';





		/**
		 * Metodo constructor de la clase.
		 * @param integer $idBanco entero que identifica al banco dentro de la entidad "bancos"
		 * @param string $fechaDesde fecha inicio de consulta.
		 * @param string $fechaHasta fecha final de consulta.
		 */
		public function __construct($idBanco, $fechaDesde, $fechaHasta)
		{
			$this->_id_banco = $idBanco;
			$this->_fecha_desde = date('Y-m-d', strtotime($fechaDesde));
			$this->_fecha_hasta = date('Y-m-d', strtotime($fechaHasta));
			$this->_listaAlias = require('alias-banco-archivo-txt.php');
			$this->_alias = $this->_listaAlias[$idBanco];
		}



		/**
		 * Metodo que inicia el proceso
		 * @return
		 */
		public function iniciarListaArchivo()
		{
			// Ciclo de fechas origen $this->_fecha_desde, final $this->_fecha_hasta.
			$arregloFecha = self::crearCicloFecha();
			if ( count($arregloFecha) > 0 ) {
				// Ciclo de nombres creados, segun la politica de formacion de los nombres.
				$arregloArchivo = self::crearCicloNombreArchivo($arregloFecha);
				if ( count($arregloArchivo) > 0 ) {
					$this->_lista_nombre_archivo = $arregloArchivo;
					self::crearListaArchivo($arregloArchivo);
				}
			}
		}




		/**
		 * bcoban_aaa_20170420
		 * @return string
		 */
		private function armarNombreArchivo($fecha)
		{
			$fechaArmada = str_replace("-", "", $fecha);
			$fechaArmada = str_replace("/", "", $fechaArmada);
			return self::NOMBRE_BASE . $this->_alias . "_" . self::ALIAS_ALCALDIA . "_" . $fechaArmada . ".txt";
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
		 * Metodo getter de fecha desde
		 * @return string retorna la fecha desde en formato YYYY-mm-dd.
		 */
		public function getFechaDesde()
		{
			return $this->_fecha_desde;
		}


		/**
		 * Metodo getter de fecha hasta
		 * @return string retorna la fecha hasta en formato YYYY-mm-dd.
		 */
		public function getFechaHasta()
		{
			return $this->_fecha_hasta;
		}



		/**
		 * Metodo que recorre un directorio particular para crear un arreglo con los
		 * nombres de los archivos que se encuentren en dicho directorio. Pero se hara
		 * un filtro para obtener solo los archivos que cumplan una condicion especifica.
		 * @param array $arregloArchivo arreglo de nombres de archivos de conciliacion.
		 * @return array
		 */
		public function crearListaArchivo($arregloArchivo)
		{
			if ( is_dir(self::getRuta()) ) {
				$gestor = opendir(self::getRuta());
die(var_dump(self::getRuta()));
				// Permite mostrar el contenido del direcorio y lo muestra en un arreglo
				// cada item encontrado sera un elemento del arreglo.
				//$ficheros1  = scandir(self::getRuta());

				while ( false !== ($file = readdir($gestor)) ) {
	        		if ( $file !== '.' && $file !== '..' && $file !== NULL && substr(trim($file), -4) == '.txt' ) {
	        			$key = array_search($file, array_column($arregloArchivo, 'nombre'));
	        			if ( $key !== false ) {
		        			$this->_lista_archivo[] = [
		        				'file' => $file,
		        				'path' => self::getRuta(),
		        				'fecha' => $arregloArchivo[$key]['fecha'],
		        			];
		        		}
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





		/**
		 * Metodo que retorna un arreglo con los nombres de los archivos.
		 * @return array. Arreglo de nombres de los archivos txt de recaudacion.
		 */
		public function getListaArchivo()
		{
			return $this->_lista_archivo;
		}



		/**
		 * Metodo que retorna un arreglo con la estructura
		 * {
		 * 		[n] = {
		 *   		['fecha'] => fecha,
		 *     		['nombre'] => nombre
		 * 		}
		 * }
		 * donde la fecha corresponde a la fecha armada segun el ciclo de fecha
		 * y el nombre corresponde al nombre de archivo que se armo segun especificaciones.
		 * @return array
		 */
		public function getListaNombreArchivo()
		{
			return $this->_lista_nombre_archivo;
		}



		/**
		 * Metodo que arma un arreglo de fechas tomando como fecha inicial y final
		 * las fechas $this->_fecha_desde y $this->_fecha_hasta.
		 * @return array. arreglo de fechas en formato YYYY-mm-dd.
		 */
		public function crearCicloFecha()
		{
			$f1 = self::getFechaDesde();
			$f2 = self::getFechaHasta();
			$arreglo = [];
			for( $i = $f1; $i <= $f2; $i = date("Y-m-d", strtotime($i ."+ 1 days")) ) {
    			//echo $i . "<br />";
    			$arreglo[] = $i;
    		}
    		return $arreglo;
		}




		/**
		 * Metodo que crea un arreglo de nombres de archivos relacionado a las
		 * fechas de recaudacion, estos archivos corresponde a la recaudacion
		 * y conciuliacion enviada por el banco.
		 * @param array $cicloFecha arreglo de fechas.
		 * @return array arreglo de nombres de archivos de conciliacion.
		 */
		private function crearCicloNombreArchivo($cicloFecha)
		{
			$arreglo = [];
			foreach ( $cicloFecha as $key => $fecha ) {
				$arreglo[] = [
					'fecha' => $fecha,
					'nombre' => self::armarNombreArchivo($fecha)
				];
			}
			return $arreglo;
		}

	}

?>