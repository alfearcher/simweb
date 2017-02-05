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
 	use common\models\historico\cvbrecibo\GenerarValidadorReciboTresDigito;


	/**
	* Clase para generar los casos de usosque seran enviados como pruebas
	*/
	class CodigoValidadorBancarioListado extends Model
	{



		/***/
		public function search($params)
		{
			$query = Deposito::find();
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => 30,
				],
				'sort' => [
					'defaultOrder' => [
						'monto' => SORT_DESC,
						'recibo' => SORT_DESC,
					]
				],
			]);

			$this->load($params);
			$query->limit(10)
				  ->filterWhere(['IN', 'estatus' , [1]])
				  ->andFilterWhere(['=', 'id_contribuyente', 0]);

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