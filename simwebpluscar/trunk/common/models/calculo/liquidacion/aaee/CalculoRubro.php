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
	use backend\models\utilidad\ut\UnidadTributariaForm;



	/**
	* 	Clase que gestiona el calculo segun el rubro. Calculo el impuesto
	* 	por rubro.
	*
	*/
	class CalculoRubro extends Rubro
	{
		public $idRubro;
		private $_rubro;
		private $_calculo;
		private $_anoImpositivo;
		private $_ut;



		/**
		 * [__construct description]
		 * @param Long $id, identificador del rubro, id_rubro.
		 */
		public function __construct($id)
		{
			$this->_calculo = 0;
			$this->_rubro = null;
			$this->idRubro = $id;
		}


		/**
		 * Metodo que define la metodologia de calculo que se debe aplicar para
		 * la determinacion del impuesto por el rubro. Primero se obtiene los datos
		 * del rubro.
		 * @return Returna monto calculado por el rubro.
		 */
		public function getCalcular()
		{
			$this->_calculo = 0;
			// Se obtiene los datos del rubro.
			$this->_rubro = $this->getInfoRubro();
			if ( isset($this->_rubro) ) {
				if ( $this->_rubro->id_metodo == 1 ) {				// Calculo por declaracion de ingresos brutos.

				} elseif ( $this->_rubro->id_metodo == 2 ) {		// Calculo por unidades.

				} else {
					return null;
				}
			}
			return $this->_calculo;
		}



		/**
		 * Metodo para obtener los datos de la entidad rubros.
		 * @return Returna un ActiveRecord del la entidad rubros.
		 */
		private function getInfoRubro()
		{
			$model = Rubro::find()->where(['id_rubro' => $this->idRubro])->one();
			if ( isset($model) ) {
				return $model;
			} else {
				return null;
			}
		}



		/**
		 * Metodo que permite obtener el monto de la unidad tributaria, este metodo
		 * recibe una variable $param, que puede ser una fecha o un año.
		 * @param  Variable que puede ser una fecha o un año.
		 * @return Retorna un monto de la unidad tributaria.
		 */
		public function getUnidadTributaria($param)
		{
			$this->_ut = 0;
			if ( is_integer($param) ) {
				$this->_ut = UnidadTributariaForm::getUnidadTributariaPorAnoImpositivo($param);
			} elseif ( date($param) ) {
				$this->_ut = 0;
			}
			return $this->_ut;
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