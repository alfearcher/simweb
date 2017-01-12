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
 *  @file CodigoValidadorBancario.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-09-2016
 *
 *  @class CodigoValidadorBancario
 *  @brief Clase
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

	namespace common\models\calculo\cvb;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\calculo○\cvb\ModuloValidador;


	/**
	 * Clase que gestiona los diferentes metodos de generacion de codigos validadores.
	 */
	class CodigoValidadorBancario
	{

		private $_recibo;
		private $_id_contribuyente;
		private $_montoRecibo;
		private $_fechaVcto;			// Fecha de vcto del recibo.



		/***/
		public function __construct($nroRecibo, $idContribuyente, $monto, $fechaVcto)
		{
			$this->_recibo = $nroRecibo;
			$this->_id_contribuyente = $idContribuyente;
			$this->_fechaVcto = $fechaVcto;
			$this->_montoRecibo = $monto;
		}


	}

?>