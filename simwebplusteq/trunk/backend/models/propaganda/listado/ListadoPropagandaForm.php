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
 * @file ListadoPropaganda.php
 *
 * @author Jose Perez
 *
 * @date 26-01-2017
 *
 * @class ListadoPropaganda
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

	namespace backend\models\propaganda\listado;

	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\propaganda\Propaganda;



	class ListadoPropagandaForm extends Propaganda
	{

		public $id_contribuyente;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->id_contribuyente = $idContribuyente;
		}




		/***/
		public function search($params)
		{
			$query = Propaganda::find();

			$dataProvider = New ActiveDataProvider([
				'query' => $query,
			]);

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
				],
			]);

			$this->load($params);

			$query->filterWhere(['id_contribuyente' => $this->id_contribuyente]);


			return $dataProvider;
		}



		/***/
		public function findPropaganda($idImpuesto)
		{
			return $findModel = Propaganda::findOne($idImpuesto);
		}

	}