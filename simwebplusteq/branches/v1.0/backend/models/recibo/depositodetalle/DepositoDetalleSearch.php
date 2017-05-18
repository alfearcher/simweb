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
 *  @file DepositoDetalleSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2017
 *
 *  @class DepositoDetalleSearch
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

	namespace backend\models\recibo\depositodetalle;

 	use Yii;
	use backend\models\recibo\depositodetalle\DepositoDetalle;



	/**
	* Clase
	*/
	class DepositoDetalleSearch extends DepositoDetalle
	{

		private $_conn;
		private $_conexion;
		private $_model;			// Modelo DepositoDetalle
		private $_id_generado;



		/**
		 * Metodo constructor de la clase.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 */
		public function __construct($conexion, $conn)
		{
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_id_generado = 0;
		}



		/**
		 * Metodo getter del autoincremental generado por el insert
		 * @return integer autoincremental generado por el insert.
		 */
		public function getIdGenerado()
		{
			return $this->_id_generado;
		}



		/**
		 * Metodo que realiza un insert en la entidad respectiva.
		 * @param array $arregloDato arreglo de datos que se insertaran en la entidad.
		 * @return boolean.
		 */
		public function guardar($arregloDato)
		{
			$this->_id_generado = 0;
			if ( isset($arregloDato['linea']) ) {
				$arregloDato['linea'] = null;
			}

			$result = false;
			$tabla = $this->tableName();
			$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDato);
			if ( $result ) {
				$result = true;
				$this->_id_generado = $this->_conn->getLastInsertID();
			}
			return $result;
		}

	}

?>