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
 *  @file AplicarDescuenyo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-11-2016
 *
 *  @class AplicarDescuenyo
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

	namespace common\models\descuento;

 	use Yii;
	use backend\models\configuracion\descuento\DescuentoSearch;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;
	use common\conexion\ConexionController;



	/**
	* 	Clase
	*/
	class AplicarDescuento
	{

		private $_planilla;
		private $_configDescuento;
		private $_descripcion;
		private $_id_contribuyente;

		private $_conn;
		private $_conexion;
		private $_transaccion;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de la planilla
		 */
		public function __construct($planilla)
		{
			$this->_planilla = $planilla;
		}


		/***/
		public function iniciarDescuento()
		{
			self::getConfiguracionDescuento();
			self::determinarParametro();
		}




		/**
		 * Metodo que permite obtener la configuracion del descuento.
		 * @return [type] [description]
		 */
		public function getConfiguracionDescuento()
		{
			$model = self::getInfoPlanillaModel();
			$infoPlanilla = $model->asArray()->all();
			if ( count($infoPlanilla) > 0 ) {
				$referencia = $infoPlanilla[0]['referencia'];		// Tipo de liquidacion 0 o 1.
				$impuesto = $infoPlanilla[0]['impuesto'];
				$this->_descripcion = $infoPlanilla[0]['descripcion'];
				$this->_id_contribuyente = $infoPlanilla[0]['pagos']['id_contribuyente'];

				$descuento = New DescuentoSearch($impuesto);
				$this->_configDescuento = $descuento->getConfiguracion($referencia);
			}
		}




		/**
		 * Metodo que realiza la consulta de la planilla.
		 * @return array retorna todos los registros de la planilla, entidad
		 * pagos y pagos-detalle.
		 */
		private function getInfoPlanillaModel()
		{
			return PagoDetalle::find()->alias('D')
									  ->where('planilla =:planilla',
									  			[':planilla' => $this->_planilla])
									  ->joinWith('pagos P', true, 'INNER JOIN')
									  ->joinWith('impuestos I', true, 'INNER JOIN')
									  ->orderBy([
									  		'ano_impositivo' => SORT_ASC,
									  		'trimestre' => SORT_ASC,
									  	]);
		}




		/***/
		private function determinarParametro()
		{
			foreach ( $this->_configDescuento as $key => $value ) {
				self::determinarCondicionPlanilla($value);
			}
		}




		/***/
		private function determinarCondicionPlanilla($config)
		{
			if ( count($config) > 0 ) {
				$findModel = self::getInfoPlanillaModel();
				$model = $findModel->andWhere('ano_impositivo =:ano_impositivo',
											[':ano_impositivo' => $config['ano_impositivo']])
								   ->andWhere('D.impuesto =:impuesto',
								   			[':impuesto' => $config['impuesto']])
								   ->andWhere('referencia =:referencia',
								   			[':referencia' => $config['tipo_liquidacion']])
								   ->asArray()
								   ->all();

				if ( $config['aplicar_solo_periodo'] == 0 ) {

					$result = end($model);
					if ( $result !== false ) {
						if ( $result['trimestre'] == $config['periodo'] ) {		// Cumple con lo establecido.

						}
					}

				} elseif ( $config['aplicar_solo_periodo'] == 1 ) {

					foreach ( $model as $key => $value ) {

						if ( $value['trimestre'] == $config['periodo'] ) {	// Cumple con la condicion

						}
					}

				}

			}
		}



		/***/
		private function AplicarDescuento($config, $añoImpositivo, $periodo)
		{
			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();




		}





	}

?>