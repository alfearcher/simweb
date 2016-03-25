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

		private $_calculoAnual;
		public $_idContribuyente;
		private $_anoImpositivo;
		private $_declaracion;
		private $_tipoDeclaracion;
		private $_periodo;
		private $_exigDeclaracion;
		// Se crea un detalle de la liquidacion donde se muestra lo siguiente:
		// año.
		// rubro (codigo del rubro, no el identificador).
		// descripcion.
		// monto declarado.
		// alicuota.
		// minimo.
		// impuesto.
		private $_calculoDetallado = [];



		/**
		 * Metodo constructor
		 * @param Long $id identificador del Contribuyente (IdContribuyente).
		 */
		public function __construct($id)
		{
			$this->_idContribuyente = $id;
		}



		/**
		 * Metodo que permite obtener la declaracion de un lapso especifico
		 * @return Array, el arreglo retornado contiene los datos de la entidad
		 * "act-econ" y "act-econ-ingresos". Los campos pertenecientes a la entidad
		 * "act-econ-ingresos" estan contenido un un arreglo con el indice "actividadDetalle".
		 */
		protected function getDeclaracionContribuyente()
		{
			if ( $this->_anoImpositivo > 0 && $this->_periodo > 0 ) {
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
		public function iniciarCalcularLiquidacion($ano, $periodo, $tipoDeclaracion)
		{
			$this->_anoImpositivo = $ano;
			$this->_periodo = $periodo;
			$this->_tipoDeclaracion = $tipoDeclaracion;
			$this->_declaracion = $this->getDeclaracionContribuyente();
			$this->calculoRubroDeclarado();
		}



		/***/
		public function getCalculoAnual()
		{
			return $this->_calculoAnual;
		}



		/**
		 * Metodo que toma la declaracion y obtiene de ella solo los rubros
		 * que tiene autorizado la misma. Con la intencion de realizar un
		 * ciclo de calculo por cada rubro.
		 * @return [type] [description]
		 */
		private function calculoRubroDeclarado()
		{
			if ( isset($this->_declaracion) ) {
				// Se obtienen los rubros que posee la declaracion.
				$rubros = $this->_declaracion[0]['actividadDetalle'];

				// Se comienza un ciclo donde se calcularan los impuestos por rubro declarado.
				foreach ( $rubros as $rubro ) {
					$calculoRubro = New CalculoRubro($rubro);
					$montoCalculadoRubro = $calculoRubro->getCalcularPorTipoDeclaracion($this->_tipoDeclaracion);
					$this->_calculoAnual = $this->getCalculoAnual() + $montoCalculadoRubro;
				}
			}
		}





	}

?>