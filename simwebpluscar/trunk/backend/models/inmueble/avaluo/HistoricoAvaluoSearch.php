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
 *  @file HistoricoAvaluoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-11-2016
 *
 *  @class HistoricoAvaluoSearch
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

	namespace backend\models\inmueble\avaluo;

 	use Yii;
	use backend\models\inmueble\avaluo\HistoricoAvaluo;




	/**
	* Clase
	*/
	class HistoricoAvaluoSearch extends HistoricoAvaluo
	{
		private $_id_contribuyente;
		private $_id_impuesto;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $IdImpuesto identificador de inmueble.
		 */
		public function __construct($idContribuyente, $idImpuesto)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_id_impuesto = $idImpuesto;
		}





		/**
		 * Metodo que genera el modelo general de consulta del historico avaluo.
		 * @return HistoricoAvaluo.
		 */
		private function findHistoricoAvaluoModel()
		{
			return HistoricoAvaluo::find()->alias('H')
										  ->where('id_impuesto =:id_impuesto',
										  			[':id_impuesto' => $this->_id_impuesto])
										  ->andWhere('inactivo =:inactivo',[':inactivo' => 0]);

		}



		/**
		 * Metodo que crea un modelo de consulta de los historicos avaluos anteriores
		 * a una año impositivo especifico. Se retornara una lista de los historico
		 * ordenado ascedente por año.
		 * @param integer $año año de consulta.
		 * @return HistoricoAvaluo
		 */
		public function findHistoricoAvaluoAnterior($año)
		{
			$findModel = self::findHistoricoAvaluoModel();
			return $findModel->andWhere('ano_impositivo <=:ano_impositivo',
											[':ano_impositivo' => $año])
							 ->orderBy([
							   	'ano_impositivo' => SORT_ASC,
							   	'fecha' => SORT_ASC,
							 ]);
		}


		/***/
		public function findHistoricoAvaluoPosterior($año)
		{
			$findModel = self::findHistoricoAvaluoModel();
			return $findModel->andWhere('ano_impositivo >=:ano_impositivo',
											[':ano_impositivo' => $año])
							 ->orderBy([
							   	'ano_impositivo' => SORT_ASC,
							   	'fecha' => SORT_ASC,
							 ]);
		}





		/***/
		public function getUltimoAvaluoSegunAnoImpositivo($año)
		{
			$findModel = self::findHistoricoAvaluoAnterior($año);
			$models = $findModel->asArray()->all();
			if ( count($models) > 0 ) {
				// Aqui se debe buscar el utlimo registro. Este sera el mas cercano
				// al año impositivo enviado.

				return $result = end($models);
			} else {
				// Se busca auellos historicos posteriores a año impositivo enviado.
				$findModel = self::findHistoricoAvaluoPosterior($año);
				$models = $findModel->asArray()->all();
				if ( count($models) > 0 ) {
					// Aqui se debe buscar el primer registro. Este sera el mas cercano
					// al año impositivo enviado.

					return $result = $models[0];
				}
			}

			return null;
		}


	}
?>