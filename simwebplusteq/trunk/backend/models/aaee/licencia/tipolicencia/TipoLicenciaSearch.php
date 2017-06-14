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
 *  @file TipoLicenciaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-06-2017
 *
 *  @class TipoLicenciaSearch
 *  @brief Clase Modelo principal
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

 	namespace backend\models\aaee\licencia\tipolicencia;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\licencia\tipolicencia\TipoLicencia;
	use yii\helpers\ArrayHelper;



	/**
	 * Clase que permite consultas sobre la entidad "tipos-licencias"
	 */
	class TipoLicenciaSearch extends TipoLicencia
	{


		/**
		 * Metodo que genera el modelo de consulta basico sobre la entidad "tipos-licencias".
		 * @return TipoLicencia
		 */
		public function findTipoLicencia()
		{
			return TipoLicencia::find();
		}


		/**
		 * Metodo que retorna un arreglo de la entidad respectiva con los
		 * registros activos.
		 * @return array
		 */
		public function getListaTipo()
		{
			$model = self::findTipoLicencia()->where('inactivo =:inactivo',
			 											[':inactivo' => 0])
											 ->all();
			return ArrayHelper::map($model, 'descripcion', 'descripcion');
			//return ArrayHelper::map($model, 'tipo_licencia', 'descripcion');
		}


	}
 ?>