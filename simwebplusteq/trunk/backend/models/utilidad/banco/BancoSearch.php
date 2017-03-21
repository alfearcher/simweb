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
 *  @file BancoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-03-2017
 *
 *  @class BancoSearch
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

	namespace backend\models\utilidad\banco;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\banco\Banco;
	use yii\helpers\ArrayHelper;


	/**
	* Clase
	*/
	class BancoSearch extends Banco
	{



		/**
		 * Metodo que realiza una consulta de los registros activos en la entidad
		 * "bancos".
		 * @return Banco
		 */
		public function getBanco()
		{
			return $banco = Banco::find()->where('status =:status', [':status' => 1])->all();
		}



		/**
		 * Metodo que permite obtener una lista de los registros de la entidad
		 * "bancos", esta lista se puede utilizar para los combos-listas. El key
		 * del arreglo corresponde al codigo del banco en el pais local y no
		 * corresponde al indice de la entidad.
		 * @param string $campoClave, campo que se utilizara como campo clave en
		 * el arreglo del listado. Por defecto se toma el campo codigo.
		 * @return array
		 */
		public function getListaBanco($campoClave = 'codigo')
		{
			if ( in_array($campoClave, ['codigo', 'id_banco']) ) {
				$model = self::getBanco();
				return $lista = ArrayHelper::map($model, $campoClave, 'nombre');
			}
			return null;
		}




		/**
		 * Metodo que realiza una consulta del tipo "LIKE" sobre la entidad
		 * "bancos", para encontrar aquellos que cumplan la cosdicion. La consulta
		 * se realizasobre los atributos "nombre" y "nombre_corto".
		 * @param string $params valor del aparametro que se utilizara en la consulta.
		 * @return Banco
		 */
		public function findBancoPorCaracteristica($params)
		{
			$results = Banco::find()->where('status =:status', [':status' => 1])
			                        ->orWhere(['LIKE', 'nombre', $params])
			                        ->orWhere(['LIKE', 'nombre_corto', $params])
			                        ->all();

			return $results;
		}



		/**
		 * Metodo que rezliza la consulta segun una condicion especifica y determina
		 * si existe el registro.
		 * @param array $arregloCondicion arreglo que especifica el atributo y el valor
		 * del mismo, este arreglo se utilizara en el where condition de la consulta.
		 * Esquema del arreglo:
		 * 	[
		 *  	atributo => valor del atributo,
		 *  ]
		 * @return boolean
		 */
		public function existeBanco($arregloCondicion)
		{
			return Banco::find()->where($arregloCondicion)->exists();
		}
	}

?>