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
	use backend\models\aaee\declaracion\Declaracion;
	use yii\db\Exception;
	use common\models\calculo\liquidacion\aaee\CalculoRubro;
	use common\models\ordenanza\OrdenanzaBase;

	/**
	* 	Clase que gestiona el calculo anual del impuesto por actividad economica,
	*
	*/
	class LiquidacionActividadEconomica extends Declaracion
	{

		private $_calculoAnual;
		public $_idContribuyente;
		private $_añoImpositivo;
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
		private $_calculoDetallado = [
						'ano_impositivo' => 0,
						'periodo' => 0,
						'descripcion' => '',	// Descripcion del periodo.
						'monto' => 0,
						'recargo' => 0,
						'interes' => 0,
						'descuento' => 0,
						'monto_reconocimiento' => 0,
						'periodo_pago' => '',		// Descripcion del periodo limite para pagar el periodo respectivo.
						'periodo_recargo' => '' ,	// Descripcion del periodo a partir de donde se calcularan los recargos.
						'periodo_interes' => '', 	// Descripcion del periodo a partir de donde se calcularan el interes.
				];



		/**
		 * Metodo constructor
		 * @param Long $id identificador del Contribuyente (IdContribuyente).
		 */
		public function __construct($id)
		{
			parent::__construct($id);
			$this->_idContribuyente = $id;
		}



		/***/
		public function getDeclaracionContribuyente()
		{
			Declaracion::setLapsoPeriodo($this->_añoImpositivo, $this->_periodo);
			return Declaracion::getDeclaracionContribuyente();
		}




		/**
		 * Metodo donde comienza el proceso.
		 * @param  integer $año Año impositivo que se desea calcular
		 * @param  integer $periodo periodo del año.
		 * @param  string $tipoDeclaracion descripcion del atributo que especifica el tipo de
		 * ladeclaracion:
		 * - estimado
		 * - reales
		 * - etc
		 * @return [type]                  [description]
		 */
		public function iniciarCalcularLiquidacion($año, $periodo, $tipoDeclaracion)
		{
			$this->_añoImpositivo = $año;
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
				// Se obtienen los rubros, montos y tipo de declaracion que posee la declaracion.
				$rubros = $this->_declaracion[0]['actividadDetalle'];

				// Se comienza un ciclo donde se calcularan los impuestos por rubro declarado.
				foreach ( $rubros as $rubro ) {
					$calculoRubro = New CalculoRubro($rubro);

					$montoCalculadoRubro = $calculoRubro->getCalcularPorTipoDeclaracion($this->_tipoDeclaracion);
					//$this->_calculoDetallado[] = [];

					// Se va acumulando lo calculado por rubro.
					$this->_calculoAnual = $this->_calculoAnual + $montoCalculadoRubro;
				}
			}
		}










	}

?>