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
 *  @file HistoricoCodigoValidadorBancarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 HistoricoCodigoValidadorBancarioForm
 *
 *  @class HistoricoCodigoValidadorBancarioForm
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

	namespace common\models\historico\cvbrecibo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\historico\cvbrecibo\HistoricoCodigoValidadorBancario;


	/**
	* Clase que permite guardar el historico del codigo validador bancario
	* cada vez que se genere el pdf del recibo de pago, se guardara el cvb
	* que se genere y el mismo se guardara en un historico con otros datos
	* que sirvieron para generar dicho cvb.
	*/
	class HistoricoCodigoValidadorBancarioForm
	{

		/**
		 * recibo de pago
		 * @var integer
		 */
		private $_recibo;


		/**
		 * Constructor de la clase
		 * @param integer $recibo recibo de pago.
		 */
		public function __construct($recibo)
		{

		}

	}

?>