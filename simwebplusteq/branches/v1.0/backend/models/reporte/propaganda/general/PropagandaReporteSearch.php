<?php
/**
 * @copyright © by ASIS CONSULTORES 2012 - 2016
 * All rights reserved - SIMWebPLUS
 */

 /**
 *
 * > This library is free software; you can redistribute it and/or modify it under
 * > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 * > Software Foundation; either version 2 of the Licence, or (at your opinion)
 * > any later version.
 * >
 * > This library is distributed in the hope that it will be usefull,
 * > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 * > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 * > for more details.
 * >
 * > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 * @file PropagandaReporteSearch.php
 *
 * @author Jose Perez
 *
 * @date 07-07-2017
 *
 * @class PropagandaReporteSearch
 * @brief Clase contiene .
 *
 *
 *
 * @property
 *
 * @method
 *
 * @inherits
 *
 */

	namespace backend\models\reporte\propaganda\general;

	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\propaganda\Propaganda;
	use backend\models\reporte\contribuyente\general\ContribuyenteObjetoBusquedaForm;
	use moonland\phpexcel\Excel;



	/**
	 * Clase que gestiona la generacion de la informacion para el reporte de las
	 * propagnadas.
	 */
	class PropagandaReporteSearch extends ContribuyenteObjetoBusquedaForm
	{

		/**
		 * Metodo que genera la consulta modelo de las propagandas.
		 * @return Propaganda model
		 */
		public function findPropagandaModel()
		{
			return Propaganda::find()->alias('P')
									 ->joinWith('contribuyente C', true, 'INNER JOIN');
		}




		/**
		 * Metodo que retorna el data provider
		 * @param array $params Yii::$app->request->queryParams
		 * @return ActiveDataProvider
		 */
		public function getDataProvider($params, $export = false)
		{
			$query = self::findPropagandaModel();

			if ( $export ) {
				$dataProvider = New ActiveDataProvider([
					'query' => $query,
					'pagination' => false,
				]);
			} else {
				$dataProvider = New ActiveDataProvider([
					'query' => $query,
					'pagination' => [
						'pageSize' => 50,
					],
				]);
			}

			$query->joinWith('clase');
			$query->joinWith('uso');
			$query->joinWith('tipoPropaganda');

			$dataProvider->setSort([
				'attributes' => [
					'id_impuesto',
					'nombre_propaganda' => [
						'asc' => ['nombre_propaganda' => SORT_ASC],
						'desc' => ['nombre_propaganda' => SORT_DESC],
					],
					'clase_propaganda',
					'uso_propaganda',
					'id_contribuyente',
				],
			]);

			$this->load($params);

			if ( $this->id_contribuyente > 0 ) {
				$query->filterWhere(['P.id_contribuyente' => $this->id_contribuyente]);
			} elseif ( $this->todos > 0 ) {
				$query->filterWhere(['C.inactivo' => $this->condicion_contribuyente]);
			}
			$query->andFilterWhere(['P.inactivo' => $this->condicion_objeto]);

			return $dataProvider;
		}



		/**
		 * Metodo que realiza la consulta sobre la entidad propaganda
		 * @param integer $idImpuesto identificador de la propaganda
		 * @return Propaganda model
		 */
		public function findPropaganda($idImpuesto)
		{
			return $findModel = Propaganda::findOne($idImpuesto);
		}



		/**
		 * Metodo que exporta el contenido de la consulta a formato excel
		 * @return view
		 */
		public function exportarExcel($model)
		{

			return Excel::widget([
    			'models' => $model,
    			'format' => 'Excel2007',
                'properties' => [

                ],
    			'mode' => 'export', //default value as 'export'
    			'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
	            	[
	            		'attribute' => 'id_impuesto',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->id_impuesto;
									},
	                ],
	                [
	                	'attribute' => 'nombre_propaganda',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($data) {
										return $data->nombre_propaganda;
									},
	                ],
	               	[
	               		'attribute' => 'clase_propaganda',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->clase->descripcion;
									},
	                ],
	                [
	                	'attribute' => 'uso_propaganda',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->uso->descripcion;
									},
	                ],
	                [
	                	'attribute' => 'tipo_propaganda',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->tipoPropaganda->descripcion;
									},
	                ],
	                [
	                	'attribute' => 'condicion',
	                    'contentOptions' => [
		                	'style' => 'text-align:center;font-size:90%;',
		                ],
	                    'value' => function($data) {
										if ( $data->inactivo == 1 ) {
											return 'INACTIVO';
										} else {
											return 'ACTIVO';
										}
									},
	                ],
	                [
	                	'attribute' => 'id_contribuyente',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->id_contribuyente;
									},
	                ],
	                 [
	                 	'attribute' => 'contrivuyente',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($data) {
										return $data->contribuyenteName;
									},
	                ],
	                [
	                	'attribute' => 'condicion',
	                    'contentOptions' => [
		                	'style' => 'text-align:center;font-size:90%;',
		                ],
	                    'value' => function($data) {
										if ( $data->contribuyente->inactivo == 1 ) {
											return 'INACTIVO';
										} else {
											return 'ACTIVO';
										}
									},
	                ],
	        	]
			]);
		}

	}