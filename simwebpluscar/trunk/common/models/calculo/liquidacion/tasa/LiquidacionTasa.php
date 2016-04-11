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
 *  @file LiquidacionTasa.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-04-2016
 *
 *  @class LiquidacionTasa
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

	namespace common\models\calculo\liquidacion\tasa;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\db\Exception;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\tasa\TasaForm;
	use backend\models\utilidad\ut\UnidadTributariaForm;

	/**
	* 	Clase que gestiona el calculo anual del impuesto por actividad economica,
	*
	*/
	class LiquidacionTasa
	{

		private $_calculoAnual;
		private $_idImpuesto;
		private $_parametro;
		private $_multiplicador;


		/**
		 * Metodo constructor
		 * @param Long $id identificador del Contribuyente (IdContribuyente).
		 */
		public function __construct($idImpuesto, $factorMultiplicador = 0)
		{
			$this->_calculoAnual = 0;
			$this->_parametro = null;
			$this->_idImpuesto = $idImpuesto;
			$this->_multiplicador = $factorMultiplicador;
		}



		/**
		 * Metodo donde comienza el proceso.
		 * @param  [type] $año             [description]
		 * @param  [type] $periodo         [description]
		 * @param  [type] $tipoDeclaracion [description]
		 * @return Array retorna un arreglo con los siguientes valores:
		 * id-impuesto, impuesto, año impositivo, descripcion y monto calculado.
		 */
		public function iniciarCalcularLiquidacionTasa()
		{
			$this->_calculoAnual = 0;
			$this->_parametro = null;
			if ( isset($this->_idImpuesto) ) {
				$this->calculoTasa();
				$monto = $this->_multiplicador > 0 ? $this->getCalculoAnual() * $this->_multiplicador : $this->getCalculoAnual();
				$this->_parametro['monto'] = $monto;
			}
			return $this->_parametro;
		}



		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}




		/**
		 * Metodo que obtiene los parametros (campos del registro) de la entidad.
		 * @return Array Retorna un arreglo con los campos de la entidad segun el identificador.
		 */
		private function getParametrosTasa()
		{
			$tasa = TasaForm::getValoresTasa($this->_idImpuesto);
			return $tasa;
		}




		/**
		 * Metodo que determina que tipo de operacion se debe realizar para calcular el monto
		 * de la tasa. Aqui se maneja dos tipos de operacion, por bolivares o unidades tributarias.
		 * @return Double Retornara monto calculado, el monto esta representado en Bolivares.
		 */
		private function calculoTasa()
		{
			$montoCalculado = 0;
			if ( isset($this->_idImpuesto) ) {
				$tasa = $this->getParametrosTasa();
				if ( isset($tasa) ) {
					$this->_parametro = [
								'id_impuesto' => $tasa['id_impuesto'],
								'impuesto' => $tasa['impuesto'],
								'ano_impositivo' => $tasa['ano_impositivo'],
								'descripcion' => $tasa['descripcion'],
								'monto' => 0,
					];
					$this->_descripcion = $tasa['descripcion'];
					if ( $tasa['tipo_rango'] == 0 ) {			// Moneda Nacional.
						$montoCalculado = $tasa['monto'];

					} elseif ( $tasa['tipo_rango'] == 1 ) {		// Unidad tributaria.
						// Se obtiene la unidad tributaria del año.
						$año = settype($tasa['ano_impositivo'], 'integer');
						$unidadTributariaDelAño = UnidadTributariaForm::getUnidadTributaria($años);
						if ( isset($unidadTributariaDelAño) ) {
							$montoCalculado = $unidadTributariaDelAño * $tasa['monto'];

						}
					}
				}
			}
			$this->_calculoAnual = $montoCalculado;
		}





	}

?>