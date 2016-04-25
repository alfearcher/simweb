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
 *  @file TipoSolicitudSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-04-2016
 *
 *  @class TipoSolicitudSearch
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

	namespace backend\models\configuracion\tiposolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\Impuesto;
	use yii\data\ActiveDataProvider;

	/**
	* 	Clase
	*/
	class TipoSolicitudSearch extends ActiveRecord
	{


		/***/
		public function findImpuestoTipoSolicitud($impuesto = 0)
		{
			if ( $impuesto > 0 ) {
				$model = Impuesto::find()->where(Impuesto::tableName().'.impuesto =:impuesto', [':impuesto' => $impuesto])
				                              ->joinWith('tipoSolicitud')
				                              ->orderBy([
				                              		'impuesto' => SORT_ASC,
				                              		'tipo_solicitud' => SORT_ASC
				                              	]);
			} else {
				$model = Impuesto::find()->joinWith('impuesto')
			                             ->orderBy([
			                             	'impuesto' => SORT_ASC,
			                              	'tipo_solicitud' => SORT_ASC
			                             ]);
			}
			return isset($model) ? $model :  null;
		}



		/***/
		public function findTipoSolicitudImpuesto($impuesto = 0)
		{
			if ( $impuesto > 0 ) {
				$model = TipoSolicitud::find()->where(TipoSolicitud::tableName().'.impuesto =:impuesto', [':impuesto' => $impuesto])
				                              ->joinWith('impuesto')
				                              ->orderBy([
				                              		'impuesto' => SORT_ASC,
				                              		'tipo_solicitud' => SORT_ASC
				                              	]);
			} else {
				$model = TipoSolicitud::find()->joinWith('impuesto')
				                              ->orderBy([
				                              		'impuesto' => SORT_ASC,
				                              		'tipo_solicitud' => SORT_ASC
				                              	]);
			}
			return isset($model) ? $model :  null;
		}



		/***/
		public function getDataProviderImpuestoSolicitud($impuesto = 0)
		{
			$model = $this->findImpuestoTipoSolicitud($impuesto);

			$dataProvider = New ActiveDataProvider([
	    						'query' => $query,
	    	]);

	    	return $dataProvider;
		}
	}

?>