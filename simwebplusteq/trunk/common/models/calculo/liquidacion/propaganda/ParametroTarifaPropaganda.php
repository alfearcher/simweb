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
 *  @file ParametroTarifaPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-01-2017
 *
 *  @class ParametroTarifaPropaganda
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

	namespace common\models\calculo\liquidacion\propaganda;


 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\tarifa\propaganda\TarifaPropaganda;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\utilidad\ut\UnidadTributariaForm;
	use backend\models\propaganda\tipo\TipoPropaganda;



	/***/
	class ParametroTarifaPropaganda
	{

		private $_añoImpositivo;
		private $_datosPropaganda;
		private $_montoAplicar;
		private $_tarifa;

		const IMPUESTO = 4;


		/**
		 * Metodo constructor de la clase
		 * @param Array $datosPropaganda arreglo uni-dimensional que contiene los datos
		 * de la propaganda.
		 * Este arreglo se refiere a la entidad "propagandas".
		 * @param Integer $añoImpositivo Año impositivo al cual se le calculara el impuesto.
		 */
		public function __construct($datosPropaganda, $añoImpositivo)
		{
			$this->_montoAplicar = 0;
			$this->_añoImpositivo = $añoImpositivo;
			$this->_datosPropaganda = $datosPropaganda;
		}



		/**
		 * Metodo que permite obtener el Año de creacion de la ordenanza.
		 * @return Integer, Retorna un entero de 4 digitos si encuentra el año,
		 * en caso contrario retornara 0.
		 */
		private function getAnoOrdenanza()
		{
			$año = 0;
			if ( $this->_añoImpositivo > 0 ) {
				$año = OrdenanzaBase::getAnoOrdenanzaSegunAnoImpositivoImpuesto($this->_añoImpositivo, self::IMPUESTO);
			}
			return $año;
		}




		/**
		 * Metodo que determina el identificador de la ordenanza, segun los parametros
		 * año e impuesto (en este caso 4).
		 * @return Array, Retorna un arreglo donde contiene el identificador de la ordenanza,
		 * año de creacion de la misma y el impuesto respectivo. Sino consigue nada retorna null.
		 */
		private function getIdOrdenanza()
		{
			$idOrdenanza = null;
			$año = self::getAnoOrdenanza();

			if ( $año > 0 ) {
				$idOrdenanza = OrdenanzaBase::getIdOrdenanza($año, self::IMPUESTO);
				if ( count($idOrdenanza) == 0 || $idOrdenanza == false ) {
					$idOrdenanza = null;
				}
			}
			return $idOrdenanza;
		}



		/***/
		public function getMontoAplicar()
		{
			return $this->_montoAplicar;
		}



		/**
		 * Metodo que reune todos los parametros necesarios para realizar el calculo del impuesto.
		 * @return Double Retorna el monto calculado del impuesto, si el monto es cero significa que
		 * falto algun parametro de calculo.
		 */
		public function determinarMontoAplicar()
		{
			// Se obtienen los parametros o tarifas que se aplicaran en los calculo.
			$this->_tarifa = self::getParametroTarifa($this->_datosPropaganda);

			if ( count($this->_tarifa) > 0 ) {

				$montoAplicar = $this->_tarifa['monto_aplicar'];
				$montoAdicional = $this->_tarifa['monto_adicional'];
				$montoAdicionalPorAlcohol = $this->_tarifa['monto_adicional_alcohol'];
				$montoAdicionalPorIdioma = $this->_tarifa['monto_adicional_idioma'];
				$montoDeduccion = $this->_tarifa['monto_deduccion'];
				$montoAgregar = 0;


				if ( $this->_datosPropaganda['cigarros'] == 1 ) {
					$montoAgregar = $montoAdicional;
				}

				if ( $this->_datosPropaganda['bebidas_alcoholicas'] == 1 ) {
					$montoAgregar = $montoAgregar + $montoAdicionalPorAlcohol;
				}

				if ( $this->_datosPropaganda['idioma'] == 1 ) {
					$montoAgregar = $montoAgregar + $montoAdicionalPorIdioma;
				}

				$montoCalculo = ( $montoAplicar + $montoAgregar ) - $montoDeduccion;

				if ( $this->_tarifa['tipoRango']['tipo_rango'] == 0 ) {
					$this->_montoAplicar = $montoCalculo;
				} elseif ( $this->_tarifa['tipoRango']['tipo_rango'] == 1 ) {
					$this->_montoAplicar = $montoCalculo * self::getMontoUnidadTributaria();
				}

			}

			return $this->_montoAplicar;
		}




		/**
		 * Metodo que permite obtener los parametros de calculo de la entidad de tarifas.
		 * Dicha tarifa permite obtener los parametros segun el uso del inmueble y el año
		 * de la ordenanza a traves del año impositivo.
		 * Lo recibido sera un arreglo de los campos de la entidad.
		 * @return Array Retorna un  arreglo de los campos de al entidad de "tarifas-propagandas"
		 */
		public function getParametroTarifa($datosPropaganda)
		{
			$idOrdenanza = self::getIdOrdenanza();
			if ( $idOrdenanza !== null ) {

				return TarifaPropaganda::find()->where('tipo_propaganda =:tipo_propaganda',
															[':tipo_propaganda' => $datosPropaganda['tipo_propaganda']])
											   ->andWhere('id_ordenanza =:id_ordenanza',
											   				[':id_ordenanza' => $idOrdenanza[0]['id_ordenanza']])
											   ->joinWith('tipoRango R', true, 'INNER JOIN')
				                               ->asArray()
				                               ->one();
			}

			return null;
		}



		/***/
		public function getMontoUnidadTributaria()
		{
			$ut = New UnidadTributariaForm();
			$montoUt = $ut->getUnidadTributaria((int)$this->_añoImpositivo);
			return $montoUt;
		}



		/***/
		public function getCantidadTiempo($tiempo = '')
		{
			$cantidadTiempo = $this->_datosPropaganda['cantidad_tiempo'];

			if ( $tiempo == 'dia' ) {
				// horas
				$i = 'd';

			} elseif ( $tiempo == 'dia' ) {
				// dias
				$i = 'd';

			} elseif ( $tiempo == 'semana' ) {
				// semanas
				$i = 'w';

			} elseif ( $tiempo == 'mes' ) {
				// meses
				$i = 'm';

			} elseif ( $tiempo == 'año' ) {
				// años
				$i = 'y';

			} elseif ( $tiempo == '' ) {
				$i = 'y';
			}

			// Se determina la cantidad de tiempo entre las fecha inicio y fecha final de
			// publicacion de la propaganda.
			$interval = date_diff(date_create($this->_datosPropaganda['fecha_inicio']), date_create($this->_datosPropaganda['fecha_fin']));

			return $interval->{$i};

		}



		/***/
		public function getBaseCalculoPorTipo()
		{
			$model = TipoPropaganda::findOne($this->_datosPropaganda['tipo_propaganda']);
			return $model->base_calculo;
		}


	}

?>