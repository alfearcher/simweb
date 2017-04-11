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
 *  @file PreReferenciaPlanillaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-03-2017
 *
 *  @class PreReferenciaPlanillaSearch
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

	namespace backend\models\recibo\prereferencia;

 	use Yii;
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;
	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;
	use common\models\referencia\GenerarReferenciaBancaria;





	/**
	* Clase
	*/
	class PreReferenciaPlanillaSearch extends PreReferenciaPlanilla
	{

		private $_modelSerial;
		private $_recibo;
		private $_conn;
		private $_conexion;

		private $_errores;


		/**
		 * Metodo constructor de la clase
		 * @param integer $recibo numero de recibo de pago.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 * @param SerialReferencia $modelSerial
		 */
		public function __construct($recibo, $conexion, $conn, $modelSerial = null)
		{
			$this->_recibo = $recibo;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_modelSerial = $modelSerial;

		}




		/**
		 * Metodo que inicia el proceso de guardado de las referencias
		 * @return boolean.
		 */
		public function iniciarPreReferencia()
		{
			if ( isset($this->_conexion) && isset($this->_conn) ) {
				if ( self::suprimirByRecibo() ) {
					return self::guardar();
				}
			}
			return false;
		}




		/**
		 * Metodo
		 * @return boolean.
		 */
		public function guardar()
		{
			$result = false;
			$tabla = $this->tableName();
			$referencia = self::generarReferenciaBancaria();
			if ( count($referencia) > 0 ) {
				foreach ( $referencia as $item ) {
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
		 * Metodo que suprime los registros de la entidad "planillas-conatbles".
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
		 * de distribucion presupuestario de ingresos municipales.
		 * @return array
		 */
		private function generarReferenciaBancaria()
		{
			$generar = New GenerarReferenciaBancaria($this->_recibo, $this->_modelSerial, '');
			$referencia = $generar->iniciarReferencia();
			if ( count($generar->getError()) > 0 ) {
				$referencia = [];
			}
			return $referencia;
		}

	}

?>