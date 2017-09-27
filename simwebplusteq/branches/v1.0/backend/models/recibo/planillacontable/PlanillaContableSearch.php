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
 *  @file PlanillaContableSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2017
 *
 *  @class PlanillaContableSearch
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

	namespace backend\models\recibo\planillacontable;

 	use Yii;
	use backend\models\recibo\planillacontable\PlanillaContable;
	use common\models\distribucion\presupuesto\GenerarPlanillaPresupuesto;



	/**
	* Clase
	*/
	class PlanillaContableSearch extends PlanillaContable
	{

		private $_recibo;
		private $_conn;
		private $_conexion;

		private $_errores;
		private $_fecha_pago;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero de recibo de pago.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 * @param string $fechaPago fecha de pago de la transaccion, este valor se debe
		 * asignar cuando se esta efectuando el pago, para los casos donde se este contabilizando
		 * nuevamente no es necesario asignarlo.
		 */
		public function __construct($recibo, $conexion, $conn, $fechaPago = null)
		{
			$this->_recibo = $recibo;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_fecha_pago = ( $fechaPago !== null ) ? date('Y-m-d', strtotime($fechaPago)) : null;
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
		 * Metodo que inicia el proceso de distribucion.
		 * @return boolean.
		 */
		public function iniciarDistribucionPresupuestaria()
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
			$distribucion = self::generarPlanillaPresupuesto();
			if ( count($distribucion) > 0 ) {
				foreach ( $distribucion as $item ) {
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
		private function generarPlanillaPresupuesto()
		{
			$generar = New GenerarPlanillaPresupuesto($this->_recibo, $this->_fecha_pago);
			$distribucion = $generar->iniciarPlanillaPresupuesto();
			if ( count($generar->getError()) > 0 ) {
				$distribucion = [];
			}
			return $distribucion;
		}

	}

?>