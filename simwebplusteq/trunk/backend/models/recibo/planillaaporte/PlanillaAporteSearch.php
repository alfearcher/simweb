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
 *  @file PlanillaAporteSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-04-2017
 *
 *  @class PlanillaAporteSearch
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

	namespace backend\models\recibo\planillaaporte;

 	use Yii;
	use backend\models\recibo\planillaaporte\PlanillaAporte;
	use common\models\rafaga\GenerarRafagaPlanilla;



	/**
	* Clase
	*/
	class PlanillaAporteSearch extends PlanillaAporte
	{

		private $_recibo;
		private $_conn;
		private $_conexion;

		private $_errores;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero de recibo de pago.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 */
		public function __construct($recibo, $conexion, $conn)
		{
			$this->_recibo = $recibo;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
		}



		/**
		 * Metodo getter de los errores.
		 * @return array.
		 */
		public function getError()
		{
			return $this->_errores;
		}



		/**
		 * Metodo setter de los errores
		 * @param string $mensajeError mensaje de error.
		 */
		private function setError($mensajeError)
		{
			$this->_errores[] = $mensajeError;
		}




		/**
		 * Metodo que inicia el proceso de generacion de la rafaga.
		 * @return boolean.
		 */
		public function iniciarRafagaPlanilla()
		{
			if ( isset($this->_conexion) && isset($this->_conn) ) {
				if ( self::suprimirByRecibo() ) {
					return self::guardar();
				}
			}
			return false;
		}




		/**
		 * Metodo que guarda (insert) un registro.
		 * @return boolean.
		 */
		public function guardar()
		{
			$result = false;
			$tabla = $this->tableName();
			$rafaga = self::generarRafaga();

			if ( count($rafaga) > 0 ) {
				foreach ( $rafaga as $item ) {
					$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $item);
					if ( !$result ) { break; }
				}
			}
			return $result;
		}




		/**
		 * Metodo que suprime
		 * @param array $arregloCondicion arreglo que contiene el wher condition.
		 * @return boolean.
		 */
		private function suprimir($arregloCondicion)
		{
			$result = false;
			if ( count($arregloCondicion) > 0 ) {
				$tabla = $this->tableName();
				return $this->_conexion->eliminarRegistro($this->_conn, $tabla, $arregloCondicion);
			}
			return $result;
		}




		/**
		 * Metodo que suprime los registros de la entidad "planillas-aporte".
		 * @return boolean.
		 */
		private function suprimirByRecibo()
		{
			$result = false;
			if ( $this->_recibo > 0 ) {
				$arregloCondicion = [
					'recibo' => $this->_recibo,
				];
				$result = self::suprimir($arregloCondicion);
			}
			return $result;
		}



		/**
		 * Metodo que genera el arreglo con los datos que se guardaran por concepto
		 * de rafaga para la planilla.
		 * @return array
		 */
		private function generarRafaga()
		{
			$generar = New GenerarRafagaPlanilla($this->_recibo);
			$rafaga = $generar->iniciarRafaga();
			if ( count($generar->getError()) > 0 ) {
				$rafaga = [];
			}
			return $rafaga;
		}

	}

?>