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
 *  @file TipoDepositoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-03-2017
 *
 *  @class TipoDepositoSearch
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

	namespace backend\models\recibo\tipodeposito;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\tipodeposito\TipoDeposito;


	/**
	* Clase
	*/
	class TipoDepositoSearch extends TipoDeposito
	{

		/**
		 * Metodo que realiza una consulta de los registros activos en la entidad
		 * "tipos_depositos".
		 * @return TipoDeposito
		 */
		public function getTipoDeposito()
		{
			return $TipoDeposito = TipoDeposito::find()->all();
		}



		/**
		 * Metodo que permite obtener una lista de los registros de la entidad
		 * "tipos_depositos", esta lista se puede utilizar para los combos-listas. El key
		 * del arreglo corresponde al indice de la entidad.
		 * @return array
		 */
		public function getListaTipoDeposito()
		{
			$model = self::getTipoDeposito();
			return $lista = ArrayHelper::map($model, 'tipo', 'descripcion');
		}


	}

?>