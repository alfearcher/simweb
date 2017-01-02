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



		/**
		 * Metodo que permite obtener un modelo para la consulta de los registros en pagos
		 * pagos-detalle.
		 * @return PagoDetalle
		 */
		private function getPagoModel()
		{
			return PagoDetalle::find()->alias('D')
									  ->where('pago =:pago',[':pago' => 1])
									  ->andWhere('referencia =:referencia',
									  		[':referencia' => 0])
									  ->andWhere('trimestre >:trimestre',
									  		[':trimestre' => 0])
									  ->joinWith('pagos P', true, 'INNER JOIN')
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
					$exigibilidadLiq = OrdenanzaBase::getExigibilidadLiquidacion($result['ano_impositivo'], $result['impuesto']);

					if ( $result !== false ) {
						if ( (int)$result['trimestre'] == (int)$exigibilidadLiq['exigibilidad'] ) {		// Cumple con lo establecido.
							// El descuento se aplica a todo el año.

							self::AplicarDescuentoAnoImpositivo($config, $model, $exigibilidadLiq['exigibilidad']);
						}
					}

				} elseif ( $config['aplicar_solo_periodo'] == 1 ) {
					// El descuento se aplica solo al periodo.
					foreach ( $model as $key => $value ) {

						if ( $value['trimestre'] == $config['periodo'] ) {	// Cumple con la condicion

						}
					}

				}

			}
		}



		/***/
		private function AplicarDescuentoAnoImpositivo($config, $model, $exigibilidadLiq)
		{
			$result = false;
			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();

  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$totalPeriodo = count($model);
			$totalPeriodoPago = self::getCantidadPeriodoPago($model[0]['pagos']['id_contribuyente'], $model[0]['ano_impositivo'], $model[0]['impuesto']);
			$montoPeriodoPagado = self::getMontoPeriodoPago($model[0]['pagos']['id_contribuyente'], $model[0]['ano_impositivo'], $model[0]['impuesto']);

			// Se suma el monto del impuesto.
			$sumaImpuesto = 0;
			foreach ( $model as $key => $value ) {
				$sumaImpuesto = $sumaImpuesto + $value['monto'];
			}

			if ( $totalPeriodo == $exigibilidadLiq ) {
				// Quiere decir que la planilla tiene la cantidad de periodos de todo el año.

				$montoDescuento = self::determinarDescuentoMonto($config, $sumaImpuesto);
				$descuento = str_replace(',','',number_format($montoDescuento / $totalPeriodo, 2));

				foreach ( $model as $key => $value ) {
					$result = self::aplicarDescuentoMonto($config, $descuento, $value, $this->_conexion, $this->_conn);
					if ( !$result ) { break; }
				}

			} elseif ( $totalPeriodo < $exigibilidadLiq ) {
				// Quiere decir que la planilla actual tiene menos periodos de los que exige la
				// exigibilidad de liquidacion, lo que debe indicar que elcontribuyente pago unos
				// periodos en otra planilla. Si pago el resto dentro del lapso indicado para el
				// descuento se debe aplicar la totalidad del descuento sobre el monto que quede.


				$sumaImpuesto = $sumaImpuesto + $montoPeriodoPagado;
				$montoDescuento = self::determinarDescuentoMonto($config, $sumaImpuesto);

				$descuento = str_replace(',','',number_format($montoDescuento / $totalPeriodo, 2));

				foreach ( $model as $key => $value ) {
					$result = self::aplicarDescuentoMonto($config, $descuento, $value, $this->_conexion, $this->_conn);
					if ( !$result ) { break; }
				}

			}

			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rolBack();
			}
			$this->_conn->close();

		}




		/***/
		private function aplicarDescuentoMonto($config, $descuento, $model, $conexion, $conn)
		{
			$result = false;
			$tabla = PagoDetalle::tableName();

			$porcentaje = $config['porc_monto'] / 100;
			$arregloDato['descuento'] = $descuento;
			if ( trim($model['descripcion']) == '' ) {
				$arregloDato['descripcion'] = 'DESCUENTO DEL ' . $config['porc_monto'] . '%, SOBRE EL MONTO DEL IMPUESTO DEL AÑO ' . $model['ano_impositivo'];
			} else {
				$arregloDato['descripcion'] = 'DESCUENTO DEL ' . $config['porc_monto'] . '%, SOBRE EL MONTO DEL IMPUESTO DEL AÑO ' . $model['ano_impositivo'];
			}

			$arregloCondicion['id_detalle'] = $model['id_detalle'];
			$result = $conexion->modificarRegistro($conn, $tabla, $arregloDato, $arregloCondicion);

			return $result;
		}




		/***/
		private function determinarDescuentoMonto($config, $montoTotal)
		{
			$descuento = 0;
			if ( $config['porc_monto'] > 0 ) {
				$porcentaje = $config['porc_monto'] / 100;
				$descuento = ( $montoTotal * $porcentaje );
			}

			return str_replace(',','',number_format($descuento,2));
		}




		/**
		 * Metodo que cuenta la cantidad de peiodo pagados para un año especifico.
		 * Estos periodos pertencen al impuesto de actividad economica, sin considerar
		 * los periodos de ajustes o definitivas.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @param  integer $añoImpositivo año impositivo.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return integer retorna la cantidad de periodos pagados.
		 */
		private function getCantidadPeriodoPago($idContribuyente, $añoImpositivo, $impuesto)
		{
			$findModel = self::getPagoModel();
			return $model = $findModel->andWhere('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $idContribuyente])
							          ->andWhere('ano_impositivo =:ano_impositivo',
							   						[':ano_impositivo' => $añoImpositivo])
							          ->andWhere('impuesto =:impuesto',
							          				[':impuesto' => $impuesto])
							          ->count();
		}




		/**
		 * Metodo que determina la suma por impuesto (monto) pagado para un año impositivo.
		 * @param  integer $idContribuyente identificador del contribuyente
		 * @param  integer $añoImpositivo año impositivo.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return double monto suma de lo pagado para un año.
		 */
		private function getMontoPeriodoPago($idContribuyente, $añoImpositivo, $impuesto)
		{
			$suma = 0;
			$findModel = self::getPagoModel();
			$model = $findModel->andWhere('id_contribuyente =:id_contribuyente',
											[':id_contribuyente' => $idContribuyente])
						       ->andWhere('ano_impositivo =:ano_impositivo',
						   					[':ano_impositivo' => $añoImpositivo])
						       ->andWhere('impuesto =:impuesto',
							          		[':impuesto' => $impuesto])
						       ->asArray()
						       ->all();

			if ( count($model) > 0 ) {
				foreach ( $model as $key => $value ) {
					$suma = $suma + $value['monto'];
				}
			}

			return $suma;
		}






	}

?>