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
 *  @file VaucheDetalleSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2017
 *
 *  @class VaucheDetalleSearch
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

	namespace backend\models\recibo\vauchedetalle;

 	use Yii;
	use backend\models\recibo\vauchedetalle\VaucheDetalle;




	/**
	* Clase
	*/
	class VaucheDetalleSearch extends VaucheDetalle
	{

		private $_conn;
		private $_conexion;



		/**
		 * Metodo constructor de la clase.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 */
		public function __construct($conexion, $conn)
		{
			$this->_conexion = $conexion;
			$this->_conn = $conn;
		}




		/**
		 * Metodo que realiza un insert en la entidad respectiva.
		 * @param array $arregloDato arreglo de datos que seran insertados.
		 * @return boolean.
		 */
		public function guardar($arregloDato)
		{
			$result = false;
			$tabla = $this->tableName();
			$this->attributes = $arregloDato;
			foreach ( $this->attributes as $key => $value ) {
				if ( isset($arregloDato[$key]) ) {
					$this->$key = $arregloDato[$key];
				}
			}
			$this->id_vauche = null;
			return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $this->attributes);
		}


	}

?>