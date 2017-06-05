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
 *  @file TarifaParametroVehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-04-2016
 *
 *  @class TarifaParametroVehiculo
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

	namespace backend\models\utilidad\tarifa\vehiculo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculo;
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculoDetalle;
	use common\models\vehiculo\clasevehiculo\ClaseVehiculo;
	use backend\models\utilidad\tiporango\TipoRango;


	/**
	* 	Clase
	*/
	class TarifaParametroVehiculo extends ActiveRecord
	{




		/***/
		public function getTarifasVehiculoSegunClase($idOrdenanza, $claseVehiculo)
		{
			$modelTarifa = TarifaVehiculo::find()->where([
												  		'id_ordenanza' => $idOrdenanza,
												  		TarifaVehiculo::tableName() . '.clase_vehiculo' => $claseVehiculo,
												  		'inactivo' => 0,
													])
												 ->joinWith('tipoRango')
												 ->joinWith('claseVehiculo')
												 ->orderBy([
												 		'id_tarifa_vehiculo' => SORT_ASC,
												 	])
												 ->asArray()
												 ->all();
			return $modelTarifa;
		}




		/***/
		public function getDetalleTarifaVehiculo($idTarifaVehiculo)
		{
			$modelDetalle = TarifaVehiculoDetalle::find()->where([
																'id_tarifa_vehiculo' => $idTarifaVehiculo,
																'inactivo' => 0,
																])
														 ->joinWith('tipoRango')
														 ->orderBy([
												 				'rango_desde' => SORT_ASC,
												 				'rango_hasta' => SORT_ASC,
												 			])
												 		 ->asArray()
												 		 ->all();
			return $modelDetalle;
		}

	}

?>