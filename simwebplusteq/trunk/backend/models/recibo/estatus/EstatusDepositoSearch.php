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
 *  @file ReciboSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @class ReciboSearch
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

	namespace backend\models\recibo\estatus;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\recibo\estatus\EstatusDeposito;


	/**
	* 	Clase
	*/
	class EstatusDepositoSearch extends EstatusDeposito
	{

		/***/
		public function getListaEstatus($estatus = 0)
		{
			if ( $estatus == 0 ) {
				$model = EstatusDeposito::find()->all();
			} else {
				$model = EstatusDeposito::find()->where('estatus =:estatus',
														[':estatus' => $estatus])
												->all();
			}

			if ( count($model) > 0 ) {
				return ArrayHelper::map($model, 'estatus', 'descripcion');
			}
			return null;
		}

	}

?>