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
 *  @file InscripcionSucursalSeaech.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-07-2016
 *
 *  @class InscripcionSucursalSearch
 *  @brief Clase modelo
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

	namespace backend\models\aaee\inscripcionsucursal;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;

	/**
	* 	Clase
	*/
	class InscripcionSucursalSearch
	{
		private $_id_contribuyente;


		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}



		/**
		 * Metodo que permite determinar el tipo de naturaleza del contribuyente.
		 * Para el momento en que se realizo esta documentacion solo se manejaban
		 * los tipos de naturaleza:
		 * - Natural.
		 * - Juridico.
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return string retorna una cadena donde indica que tipo de naturaleza
		 * es el contribuyente.
		 */
		public function getDescripcionTipoNaturaleza()
		{
			return ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->_id_contribuyente);
		}





	}


?>