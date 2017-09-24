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
 *  @file ConfiguracionChequeDevuelto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-09-2017
 *
 *  @class ConfiguracionChequeDevuelto
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

	namespace backend\models\configuracion\cheque\devuelto;

 	use Yii;
	use backend\models\configuracion\cheque\devuelto\ConfigurarChequeDevuelto;

	/**
	* Clase
	*/
	class ChequeDevueltoSearch
	{


		/**
		 * Metodo modelo para realizar la consulta de la configuracion de los
		 * registros en cheques devueltos. Solo se buscan los registros activos.
		 * @return ConfigurarChequeDevuelto
		 */
		public function findConfigurarChequeDevueltoModel()
		{
			return ConfigurarChequeDevuelto::find()->alias('A')->where(['A.inactivo' => 0]);
		}


		/**
		 * Metodo que permite obtener la informacion de la tasa involucradas en la liquidacion
		 * de los montos por cheques devueltos. Esta informacion corresponde con los campos
		 * existente en la entidad que guarda los identificadores de las tasas. Se retorna un
		 * arreglo con la informacion de la configuracion y las tasas involucradas.
		 * @param integer $añoImpositivo año de la consulta que se desea realizar, puede ser
		 * el año actual o año anteriores.
		 * @return array
		 */
		public function infoConfigTasaSegunAnoImpositivo($añoImpositivo = 0)
		{
			$findModel = self::findConfigurarChequeDevueltoModel();
			if ( $añoImpositivo == 0 ) {
				$results = $findModel->joinWith('tasa T', true, 'INNER JOIN')
							     	 ->asArray()
							     	 ->all();
			} else {
				$results = $findModel->joinWith('tasa T', true, 'INNER JOIN')
							     	 ->andWhere('T.ano =:ano_impositivo',
							   							[':ano_impositivo' => $añoImpositivo])
							     	 ->asArray()
							     	 ->all();
			}
			return $results;
		}


		/***/
		public function getConfigurarChequeDevuelto()
		{

		}
	}

?>