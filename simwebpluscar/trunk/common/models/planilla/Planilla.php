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
 *  @file Planilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-03-2016
 *
 *  @class Planilla
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

	namespace common\models\planilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
 	use common\models\planilla\Pago;
 	use common\models\planilla\PagoDetalle;
 	use common\models\contribuyente\ContribuyenteBase;
 	use common\models\ordenanza\OrdenanzaBase;
 	use common\models\planilla\NumeroPlanillaSearch;

	/**
	* 	Clase
	*/
	class Planilla
	{

		public $numeroPlanilla;
		private $_ente;
		private $_idContribuyente;
		private $_idImpuesto;
		private $_impuesto;
		private $_ultimaLiquidacion;
		private $_fechaInicio;






		/**
		 * Metodo que permite obtener el primer periodo liquidado del contribuyente
		 * en el impuesto de Actividad Economica para un año especifico.
		 * @param  Integer $año, entero de 4 digitos que representa el año impositivo.
		 * @param  Long  $idContribuyente, identificador del contribuyente.
		 * @return Retorna un model con el primer registro liquidado. Null si no encuentra nada.
		 */
		public function getPrimerPeriodoLiquidadoActividadEconomica($idContribuyente, $año = 0)
		{
			if ( $idContribuyente > 0 && $año == 0 ) {
				$model = PagoDetalle::find()->where('id_contribuyente =:id_contribuyente',[':id_contribuyente' => $idContribuyente])
									 	    ->andWhere('impuesto =:impuesto', [':impuesto' => 1])
									        ->andWhere('trimestre >:trimestre', [':trimestre' => 0])
									        ->andWhere('pago !=:pago', [':pago' => 9])
									        ->andWhere('referencia =:referencia',[':referencia' => 0])
									        ->joinWith('pagos')
									 		->orderBy([
							 					'ano_impositivo' => SORT_ASC,
							 					'trimestre' => SORT_ASC,
							 				])
									 		->asArray()
									 		->one();

			} elseif ( $idContribuyente > 0 && $año > 0 ) {
				$model = PagoDetalle::find()->where('id_contribuyente =:id_contribuyente',[':id_contribuyente' => $idContribuyente])
									 	    ->andWhere('impuesto =:impuesto', [':impuesto' => 1])
									        ->andWhere('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $año])
									        ->andWhere('trimestre >:trimestre', [':trimestre' => 0])
									        ->andWhere('pago !=:pago', [':pago' => 9])
									        ->andWhere('referencia =:referencia',[':referencia' => 0])
									        ->joinWith('pagos')
									        ->orderBy([
							 					'ano_impositivo' => SORT_ASC,
							 					'trimestre' => SORT_ASC,
							 				])
									 	    ->asArray()
									 		->one();
			}
			if ( count($model) > 0 ) {
				return $model;
			}
			return null;
		}




		/**
		 * Metodo que permite obtener el registro de la ultima liquidacion que presenta (de haberla)
		 * el contribuyente, se obtiene entre otros datos la planilla y los detalles de la misma.
		 * @return Array, Retorna un array con los datos principales de la entidad "pagos" y la entidad
		 * "pagos-detalle".
		 */
		public function getUltimoLapsoActividadEconomica($idContribuyente, $año = 0)
		{
			if ( $idContribuyente > 0 && $año == 0 ) {
				$model = PagoDetalle::find()->where('id_contribuyente =:id_contribuyente',[':id_contribuyente' => $idContribuyente])
									        ->andWhere('impuesto =:impuesto', [':impuesto' => 1])
									        ->andWhere('trimestre >:trimestre', [':trimestre' => 0])
									        ->andWhere('pago !=:pago', [':pago' => 9])
									        ->andWhere('referencia =:referencia',[':referencia' => 0])
									        ->joinWith('pagos')
									        ->orderBy([
					 							'ano_impositivo' => SORT_DESC,
					 							'trimestre' => SORT_DESC,
					 				 		])
					 				 		->asArray()
									 		->one();

			} elseif ( $idContribuyente > 0 && $año > 0 ) {
				$model = PagoDetalle::find()->where('id_contribuyente =:id_contribuyente',[':id_contribuyente' => $idContribuyente])
									 	    ->andWhere('impuesto =:impuesto', [':impuesto' => 1])
									        ->andWhere('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $año])
									        ->andWhere('trimestre >:trimestre', [':trimestre' => 0])
									        ->andWhere('pago !=:pago', [':pago' => 9])
									        ->andWhere('referencia =:referencia',[':referencia' => 0])
									        ->joinWith('pagos')
									        ->orderBy([
					 							'ano_impositivo' => SORT_DESC,
					 							'trimestre' => SORT_DESC,
					 				 		])
					 				 		->asArray()
									 		->one();
			}
			if ( count($model) > 0 ) {
				return $model;
			}
			return null;
		}






		/**
		 * Metodo que permite obtener el primer periodo liquidado de un objeto imponible,
		 * para un año especifico.
		 * @param  Integer $año, entero de 4 digitos que representa el año impositivo.
		 * @param  Long $idImpuesto, identificador del objeto.
		 * @param  Long $impuesto, identificador del impuesto.
		 * @return Retorna un model con el primer registro liquidado. Null si no encuentra nada.
		 */
		public function getPrimerPeriodoLiquidadoObjeto($año, $idImpuesto, $impuesto)
		{
			if ( $idImpuesto > 0 and $impuesto > 0 ) {
				$model = PagoDetalle::find()->where('id_impuesto =:id_impuesto',[':id_impuesto' => $idImpuesto])
									 		->andWhere('impuesto =:impuesto', [':impuesto' => 1])
									 		->andWhere('trimestre >:trimestre', [':trimestre' => 0])
									 		->andWhere('pago !=:pago', [':pago' => 9])
									 		->andWhere('referencia =:referencia',[':referencia' => 0])
									 		->joinWith('pagos')
									 		->orderBy([
							 					'ano_impositivo' => SORT_ASC,
							 					'trimestre' => SORT_ASC,
							 				])
									  		->asArray()
									 		->one();
			}
			if ( count($model) > 0 ) {
				return $model;
			}
			return null;
		}



		/***/
		public function crearNumeroPlanilla($conexion, $conn)
		{
			$planilla = New NumeroPlanillaSearch($conexion, $conn);
			$ultimaPlanilla = $planilla->getGenerarNumeroPlanilla();

			return $ultimaPlanilla;
		}




		/**
		 * [iniciarGuadrarPlanilla description]
		 * @param  [type] $conexion     [description]
		 * @param  [type] $conn         [description]
		 * @param  [type] $idContribuyente [description]
		 * @param  Array $arrayDetalle, arreglo de datos donde el indice del array es el año
		 * impositvo y el elemento del array es otro array que contiene los campos de la entidad
		 * "pagos=detalle"
		 * @return [type]               [description]
		 */
		protected function iniciarGuadrarPlanilla($conexion, $conn, $idContribuyente, $arrayDetalle)
		{
			return self::iniciarCicloDetalle($conexion, $conn, $idContribuyente, $arrayDetalle);
		}



		/***/
		private function iniciarCicloDetalle($conexion, $conn, $idContribuyente, $arrayDetalle)
		{
			// Se inicia guardano los datos maestro de la planilla.
			// $key es el año impositivo.
			// $value es un arreglo que representa cada registro del detalle que sera
			// guardado. Ejemplo:
			// [0] => [
			// 		['campo01'] => valor01,
			// 		['campo02'] => valor02,
			// ]
			// [1] => [
			// 		['campo11'] => valor11,
			// 		['campo12'] => valor12,
			// ]
			$result = false;
			$idPago = 0;
			foreach ( $arrayDetalle as $key => $valueDetalle ) {
				$detalle = $valueDetalle;
				foreach ( $detalle as $key => $value ) {
					$idPago = self::guardarPlanilla($conexion, $conn, $idContribuyente);
					if ( $idPago > 0 ) {
						$value['id_pago'] = $idPago;
						$result = self::guardarDetallePlanilla($conexion, $conn, $value);
					} else {
						$result = false;
					}
					if ( !$result ) { break; }
				}
				if ( !$result ) { break; }
			}
			return $result;
		}




		/***/
		protected function guardarPlanilla($conexion, $conn, $idContribuyente)
		{
			$model = New Pago();

			$tableName = $model->tableName();
			$arregloDatos = $model->attributes;
			$idPago = 0;

			// Iniciando los valores del arreglo.
			foreach ( $arregloDatos as $key => $value ) {
				$arregloDatos[$key] = 0;
			}

			$numeroPlanilla = self::crearNumeroPlanilla($conexion, $conn);
			if ( $numeroPlanilla > 0 ) {
				$arregloDatos['id_pago'] = null;
				$arregloDatos['ente'] = Yii::$app->ente->getEnte();
				$arregloDatos['id_contribuyente'] = $idContribuyente;
				$arregloDatos['planilla'] = $numeroPlanilla;
				$arregloDatos['ult_act'] = date('Y-m-d');
				$arregloDatos['id_moneda'] = 1;

				if ( $conexion->guardarRegistro($conn, $tableName, $arregloDatos) ) {
					$idPago = $conn->getLastInsertID();
				}
			}
			return $idPago;

		}




		/***/
		protected function guardarDetallePlanilla($conexion, $conn, $arregloDetalle)
		{
			$result = false;
			$model = New PagoDetalle();
			$tableName = $model->tableName();

			$arregloDatos = $model->attributes;

			// Iniicando los valores del arreglo.
			foreach ( $arregloDatos as $key => $value ) {
				if ( isset($arregloDetalle[$key]) ) {
					$arregloDatos[$key] = $arregloDetalle[$key];
				}
			}
			$arregloDatos['id_detalle'] = null;
// die(var_dump($arregloDatos));

			if ( $conexion->guardarRegistro($conn, $tableName, $arregloDatos) ) {
				$result = true;
			}

			return $result;
		}


	}

?>