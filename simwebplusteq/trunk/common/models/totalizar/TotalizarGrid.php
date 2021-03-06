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


		/***/
		public static function totalizarPlanilla($provider)
		{
			$totalMonto = 0;
			$totalRecargo = 0;
			$totalInteres = 0;
			$totalDescuento = 0;
			$totalMontoRec = 0;
			$total = 0;

			$totalMonto = self::getTotalizar($provider, 'monto');
			$totalRecargo = self::getTotalizar($provider, 'recargo');
			$totalInteres = self::getTotalizar($provider, 'interes');
			$totalDescuento = self::getTotalizar($provider, 'descuento');
			$totalMontoRec = self::getTotalizar($provider, 'monto_reconocimiento');

			$total = $totalMonto + $totalRecargo + $totalInteres + $totalDescuento + $totalMontoRec;
			return $total;
		}


		/***/
		public static function totalizarCondicional($provider, $columna, $columnaCondicion,  $valorCondicion)
		{
			$total = (float)0;
			$models = $provider->getModels();
			foreach ( $models as $key => $model ) {
				if ( $model[$columnaCondicion] == $valorCondicion ) {
					$total += (float)$model[$columna];
				}
			}
			return $total;
		}



	}

?>