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
 *  @file TarifaTransaccionInmobiliariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-06-2016
 *
 *  @class TarifaTransaccionInmobiliariaForm
 *  @brief Clase Modelo del formulario para
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

	namespace backend\models\utilidad\tarifa\transaccioninmobiliaria;

 	use Yii;
	//use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\tarifa\transaccioninmobiliaria\TarifaTransaccionInmobiliaria;

	/**
	* 	Clase
	*/
	class TarifaTransaccionInmobiliariaSearch extends TarifaTransaccionInmobiliaria
	{


		/**
		 * Metodo que permite obtener el modelo de la entidad tarifas-trans-inmobiliaria.
		 * @param  Integer $añoImpositivo año impositivo donde se buscaran los parametros
		 * de calculos de la catalogo-tarifas.
		 * @param  Integer $tipoTransaccion identificador del tipo de transaccion.
		 * @param  Double $precioInmueble monto que se refiere el precio del inmueble.
		 * @return Active Record.
		 */
		public function findTarifaTransaccion($añoImpositivo, $tipoTransaccion, $precioInmueble)
		{
			$modelFind = TarifaTransaccionInmobiliaria::find()->where('ano_impositivo =:ano_impositivo',
												  								[':ano_impositivo' => $añoImpositivo])
															  ->andWhere('tipo_transaccion =:tipo_transaccion',
															  					[':tipo_transaccion' => $tipoTransaccion])
															  ->andWhere('monto_desde <=:monto_desde',
															   					[':monto_desde' => $precioInmueble])
												  			  ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
														      ->orderBy([
																	'monto_desde' => SORT_ASC,
																]);
			return isset($modelFind) ? $modelFind : null;
		}



	}
?>