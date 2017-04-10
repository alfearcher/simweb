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
	use backend\models\recibo\deposito\Deposito;
	use common\models\planilla\Pago;
	use common\models\referencia\GenerarReferenciaBancaria;




	/**
	* 	Clase
	*/
	class PreReferenciaPlanillaSearch extends PreReferenciaPlanilla
	{

		private $planilla;
		private $recibo;
		private $_conn;
		private $_conexion;

		private $_errores;


		/***/
		public function __construct($recibo, $conexion, $conn)
		{
			$this->_recibo = $recibo;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
		}



	}

?>