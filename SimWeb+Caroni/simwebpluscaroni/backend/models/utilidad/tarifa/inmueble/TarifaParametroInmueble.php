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
 *  @file TarifaParametroInmueble.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-04-2016
 *
 *  @class TarifaParametroInmueble
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

	namespace backend\models\utilidad\tarifa\inmueble;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\tarifa\inmueble\TarifaAvaluo;
	use backend\models\utilidad\tarifa\inmueble\Tarifa;
	use common\models\ordenanza\OrdenanzaBase;



	/**
	* Clase
	*/
	class TarifaParametroInmueble extends ActiveRecord
	{



		/**
		 * Metodo que busca los parametros de calculo para el inmueble, segun la ubicacion
		 * del mismo ($manzanaLimite) y del año impositivo ($añoImpositivo). Localiza los
		 * valores existentes para realizar el calculo del impuesto de inmueble, estos parametros
		 * se complementan con los datos del avaluo del inmueble.
		 * @param  Long $manzanaLimite, ubicacion del inmueble, este parametro se encuentra en la
		 * entidad "inmuebles".
		 * @param  Integer $añoImpositivo, año al cual se le calculara el impuesto.
		 * @return ActiveRecord Retornara un instancia del tipo Active Record. Modelo de la entidad.
		 */
		public function getTarifaAvaluoSegunLocalizacion($manzanaLimite, $añoImpositivo)
		{
			$model = null;
			settype($manzanaLimite, 'integer');
			settype($añoImpositivo, 'integer');
			if ( $manzanaLimite > 0 && $añoImpositivo > 0 ) {
				$model = TarifaAvaluo::find()->where('manzana_limite =:manzana_limite', [':manzana_limite' => $manzanaLimite])
											 ->andWhere('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $añoImpositivo])
											 ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
											 ->orderBy([
											 		'id_tarifa_avaluo' => SORT_ASC,
											 	])
											 ->asArray()
											 ->one();
			}

			return isset($model) ? $model : null;

		}




		/**
		 * Metodo que busca los parametros para el calculo del inmueble.
		 * @param  integer $usoInmueble uso del inmueble.
		 * @param  integer $añoImpositivo año impositivo que se quiere calcular.
		 * @return double retorna monto del calculo o null sino consiguenada.
		 */
		public function getAlicuotaSegunUsoInmuebleOrdenanza($usoInmueble, $añoImpositivo)
		{
			if ( $usoInmueble > 0 && $añoImpositivo > 0 ) {

				$añoOrdenanza = OrdenanzaBase::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoImpositivo, 2);

				if ( $añoOrdenanza > 0 ) {
					return $model = Tarifa::find()->where('uso =:uso',
														[':uso' => $usoInmueble])
												  ->andWhere('ano_impositivo =:ano_impositivo',
												  		[':ano_impositivo' => $añoOrdenanza])
												  ->asArray()
												  ->all();
				}
			}
			return null;
		}



	}

?>