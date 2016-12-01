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
 *  @file SolvenciaVehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class SolvenciaVehiculo
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

	namespace backend\models\vehiculo\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\solvencia\SolvenciaSolicitud;
	use backend\models\vehiculo\VehiculosForm;


	/**
	* Clase modelo de la solicitud de la solvencia de Vehiculo
	*/
	class SolvenciaVehiculo extends SolvenciaSolicitud
	{


		/**
		 * Relacion con la entidad "vehiculos"
		 * @return
		 */
		public function getVehiculo()
		{
			return $this->hasOne(VehiculosForm::className(),['id_vehiculo' => 'id_impuesto']);
		}
	}

?>