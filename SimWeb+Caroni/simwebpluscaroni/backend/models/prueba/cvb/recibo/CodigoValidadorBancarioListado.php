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
 *  @file CodigoValidadorBancarioListado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-02-2017
 *
 *  @class CodigoValidadorBancarioListado
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

	namespace backend\models\prueba\cvb\recibo;

 	use Yii;
 	use backend\models\recibo\deposito\Deposito;
 	use yii\data\ActiveDataProvider;
 	use yii\base\Model;
 	use yii\data\Pagination;
 	use common\models\historico\cvbrecibo\GenerarValidadorReciboTresDigito;


	/**
	* Clase para generar los casos de usosque seran enviados como pruebas
	*/
	class CodigoValidadorBancarioListado extends Model
	{



		/***/
		public function search($params)
		{
			$recibos = [
				69831,
				38753,
				84557,
				142,
				9,
				9154,
				62218,
				48684,
				180,
				22846,
				23719,
			];


			$recibos = [
				93224,
				97064,
				91103,
				101538,
				100951,
				101475,
				7,
				101346,
			];


			$recibos = [
				95244,
				101254,
				102337,
				102672,
				102445,
				3,
				99,
			];

			$recibos = [
				90,
				79990,
				89987,
				99999,
				101371,
				102645,
				102763,

			];

			$query = Deposito::find()->where(['IN', 'recibo', $recibos]);

			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => 50,
				],
				'sort' => [
					'defaultOrder' => [
						'monto' => SORT_DESC,
						'recibo' => SORT_DESC,
					]
				],
			]);

			$this->load($params);

			return $dataProvider;
		}



		/***/
		public function generarValidadorBancario($recibo)
		{
			$model = Deposito::findOne($recibo);
			$generar = New GenerarValidadorReciboTresDigito($model);
			return $generar->getCodigoValidadorRecibo();
		}
	}

?>