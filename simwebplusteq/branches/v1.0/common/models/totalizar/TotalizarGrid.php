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
 *  @file TotalizarGrid.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-01-2016
 *
 *  @class TotalizarGrid
 *  @brief Clase que permite totalizar los montos de una columna de un gridview.
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

	namespace common\models\totalizar;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;

	/**
	* Clase que permite totalizar las columnas de un grid, se envia el provider de datos
	* y el nombre de la columna,
	*/
	class TotalizarGrid
	{


		/***/
		public static function getTotalizar($provider, $columna)
		{
			$total = 0;
			foreach ( $provider->models as $item ) {
				$total += $item[$columna];
			}

			return $total;
		}

	}

?>