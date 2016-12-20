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
	use backend\models\aaee\rubro\RubroForm;


	/**
	* 	Clase que gestiona el calculo segun el rubro. Calculo el impuesto
	* 	por rubro.
	*
	*/
	class CalculoRubro extends Rubro
	{
		private $_idRubro;
		private $_rubro;
		private $_calculo;
		private $_anoImpositivo;
		private $_ut;
		private $_rubroDeclarado;
		private $_MinimoTributable;



		/**
		 * Metodo constructor de la clase.
		 * @param array $datosDeclaracion datos de la entidad "ect-econ-ingresos",
		 * detalles de la declaracion de un item de esta entidad. Previamente consultada.
		 *
		 */
		public function __construct($datosDeclaracion)
		{

			$this->_calculo = 0;
			$this->_rubro = null;

			// Datos de la declaracion, que incluyen el identificador del rubro entre otros.
			$this->_rubroDeclarado = is_array($datosDeclaracion) ? $datosDeclaracion : null;
		}




		/**
		 * Metodo que define la metodologia de calculo que se debe aplicar para
		 * la determinacion del impuesto por el rubro. Primero se obtiene los datos
		 * del rubro.
		 * @param  String $tipoDeclaracion, Nombre del campo que contiene la declaracion.
		 * puede ser estimado, reales, rectificatoria, auditoria, etc.
		 * @return Double, Monto calculado del impuesto segun la declaracion.
		 */
		private function getCalcular($tipoDeclaracion)
		{
			$this->_calculo = 0;
			// Se obtiene los datos del rubro.
			$this->_rubro = $this->getInfoRubro();

			if ( isset($this->_rubro) ) {
				if ( $this->_rubro->id_metodo == 1 ) {				// Calculo por declaracion de ingresos brutos.
					$this->getCalculoPorDeclaracion($tipoDeclaracion);

				} elseif ( $this->_rubro->id_metodo == 2 ) {		// Calculo por unidades.
					$this->getCalculoPorUnidad();
				}
			}
			return $this->getCalculo();
		}



		/***/
		public function getCalculo()
		{
			return $this->_calculo;
		}



		/**
		 * Metodo para obtener los datos de la entidad rubros.
		 * @return Returna un ActiveRecord del la entidad rubros.
		 */
		private function getInfoRubro()
		{
			if ( count($this->_rubroDeclarado) > 0 ) {
				$model = Rubro::find()->where(['id_rubro' => $this->_rubroDeclarado['id_rubro']])->one();
				if ( isset($model) ) {
					return $model;
				}
			}
			return null;
		}




		/**
		 * Metodo que realiza el calculo del impuesto. El calculo se realiza
		 * por tipo de declaracion ($descripcionDeclaracion)
		 * @param  String $descripcionDeclaracion, Nombre del campo que se va
		 * a utilizar en el calculo del impuesto. Campo que determina el monto
		 * declaradoo poe el tipo de declaracion, estimado, reales, sustitutiva, etc.
		 * @return [type]                         [description]
		 */
		private function getCalculoPorDeclaracion($descripcionDeclaracion)
		{
			// Minimo Tributable que debe tener el rubro, esto se refiere al minimo
			// que debe calcularse por el rubro. Si el calculo del impuesto por el rubro
			// no supera este minimo se debe tomar como monto del impuesto el minimo
			// tributable.
			$minimoTributable = $this->getMinimoTributableRubro();

			// Monto que esta declarado segun el tipo de declaracion.
			$montoDeclaracion = $this->_rubroDeclarado[$descripcionDeclaracion];

			// Se determina la alicuota a utilizar.
			if ( $this->_rubro['divisor_alicuota'] > 0 ) {
				$alicuota = $this->_rubro['alicuota']/$this->_rubro['divisor_alicuota'];
			} else {
			 	$alicuota = $this->_rubro['alicuota'];
			}

			// Calculo del impuesto, monto declarado por la alicuota respectiva.
			$calculoDeclarado = $montoDeclaracion * $alicuota;

			if ( $calculoDeclarado >= $minimoTributable ) {
				$this->_calculo = $calculoDeclarado;
			} else {
				$this->_calculo = $minimoTributable;
			}
		}




		/***/
		private function getCalculoPorUnidad($descripcionDeclaracion)
		{
			// Monto que esta declarado segun el tipo de declaracion.
			$montoDeclaracion = $this->_rubroDeclarado[$descripcionDeclaracion];

			$alicuota = $this->_rubro['alicuota'];

			// Calculo del impuesto, monto declarado por la alicuota respectiva.
			$calculoDeclarado = $montoDeclaracion * $alicuota;

			$this->_calculo = $calculoDeclarado;
		}




		/**
		 * Metodo que permite obtener el minimo tributable en bolivares
		 * que se debe declarar por elrubro.
		 * @return Double, monto.
		 */
		public function getMinimoTributableRubro()
		{
			return RubroForm::getMinimoTributableRubro($this->_rubroDeclarado['id_rubro']);
		}



		/***/
		public function getCalcularPorTipoDeclaracion($tipoDeclaracion)
		{
			return $this->getCalcular($tipoDeclaracion);
		}

	}

?>