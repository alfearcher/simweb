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
 *  @file CalculoRubro.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class CalculoRubro
 *  @brief Clase Modelo que maneja la politica
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

	namespace common\models\calculo\liquidacion\aaee;

 	use Yii;
	use yii\db\ActiveRecord;
	use backend\models\aaee\rubro\Rubro;

	/**
	* 	Clase que gestiona el calculo segun el rubro. Calculo el impuesto
	* 	por rubro.
	*
	*/
	class CalculoRubro extends Rubro
	{


		public $idRubro;
		public $rubro;
		public $calculo;


		public function __construct($id)
		{
			$this->calculo = 0;
			$this->rubro = null;
			$this->idRubro = $id;
		}


		public function getCalcular()
		{
			$this->rubro = $this->getInfoRubro();
			if ( isset($this->rubro) ) {
				if ( $rubro->id_metodo == 1 ) {		// Calculo por declaracion de ingresos brutos.

				} elseif ( $rubro->id_metodo == 2 ) {		// Calculo por unidades.

				} else {
					return null;
				}
			}
		}



		/***/
		private function getInfoRubro()
		{
			$model = Rubro::find()->where(['id_rubro' => $this->idRubro])->one();
			if ( isset($model) ) {
				return $model;
			} else {
				return null;
			}
		}



		/***/
		public function getCalculoPorDeclaracion()
		{

		}



		/***/
		public function getCalculoPorUnidad()
		{

		}

	}

?>