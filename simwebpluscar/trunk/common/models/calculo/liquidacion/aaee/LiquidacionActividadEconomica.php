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
 *  @file LiquidacionActividadEconomica.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class LiquidacionActividadEconomica
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
	use backend\models\aaee\actecon\ActEcon;
	use yii\db\Exception;
	use common\models\calculo\liquidacion\aaee\CalculoRubro;
	use common\models\ordenanza\OrdenanzaBase;

	/**
	* 	Clase que gestiona el calculo anual del impuesto por actividad economica,
	*
	*/
	class LiquidacionActividadEconomica extends OrdenanzaBase
	{

		public $_calculoAnual;
		public $_idContribuyente;
		private $_anoImpositivo;
		private $_declaracion;
		private $_periodo;



		/**
		 * Metodo constructor
		 * @param Long $id identificador del Contribuyente (IdContribuyente).
		 */
		public function __construct($id)
		{
			$this->_idContribuyente = $id;
		}



		/***/
		protected function getDeclaracionContribuyente()
		{
			if ( $this->_anoImpositivo > 0 ) {
				$modelfind = ActEcon::find()->where([
												'id_contribuyente' => $this->_idContribuyente,
												'ano_impositivo' => $this->_anoImpositivo,
												'exigibilidad_periodo' => $this->_periodo,
													])
				                            ->joinWith('actividadDetalle')
				                            ->orderBy([
				                            	'ano_impositivo' => SORT_ASC,
				                            	'exigibilidad_periodo' => SORT_ASC,
				                            	     ])
				                            ->asArray()
				                            ->all();
				if ( count($modelfind) > 0 ) {
					return $modelfind;
				}
			}
			return null;
		}




		/***/
		public function iniciarCalcularLiquidacion($ano, $periodo)
		{
			$this->_anoImpositivo = $ano;
			$this->_periodo = $periodo;
			$this->_declaracion = $this->getDeclaracionContribuyente();
			//$this->_calculoAnual = $this->getCalcularLiquidacion();
			//return $this->getCalculoAnual();
			return $this->getRubroDeclarado();
		}



		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}




		/***/
		public function getCalcularLiquidacion()
		{
			if ( isset($this->_declaracion) ) {

			} else {
				return $this->_declaracion;
			}
		}



		/**
		 * Metodo que toma la declaracion y obtiene de ella solo los rubros
		 * que tiene autorizado la misma. Con la intencion de realizar un
		 * ciclo de calculo por cada rubro.
		 * @return [type] [description]
		 */
		public function getRubroDeclarado()
		{
			if ( isset($this->_declaracion) ) {
				// Se obtienen los rubros que posee la declaracion.
				$rubros = $this->_declaracion[0]['actividadDetalle'];
				die(print_r($this->_declaracion));
				foreach ( $this->_declaracion as $key => $value ) {
					if ( is_array($value) ) {
						print_r($value);
					}
				}
				die();
			} else {
				return $this->_declaracion;
			}
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

	}

?>