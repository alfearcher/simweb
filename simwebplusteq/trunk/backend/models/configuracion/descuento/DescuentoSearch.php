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
 *  @file DescuentoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-11-2016
 *
 *  @class DescuentoSearch
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

	namespace backend\models\configuracion\descuento;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\configuracion\descuento\Descuento;
	use backend\models\impuesto\Impuesto;
	use yii\data\ActiveDataProvider;

	/**
	* 	Clase
	*/
	class DescuentoSearch
	{

		private $_impuesto;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $impuesto identificador del impuesto.
		 */
		public function __construct($impuesto)
		{
			$this->_impuesto = $impuesto;
		}



		/**
		 * Metodo que retorna el modelo principal de consulta. Este modelo se utilizara
		 * para realizar todas las consultas que vayan contra la entidad "descuentos".
		 * @return array retorna un arreglo que representa el modelo principal de consulta.
		 */
		private function findDescuentoModel()
		{
			$findModel = Descuento::find()->alias('D')
										  ->where('inactivo =:inactivo',
										  			[':inactivo' => 0])
										  ->andWhere('D.impuesto =:impuesto',
										  			[':impuesto' => $this->_impuesto]);

			return $findModel;
		}






		/**
		 * Metodo que retorna la configuracion del descuento segun el tipo de liquidacion
		 * impuesto,
		 * @param  integer $tipoLiquidacion tipo de liquiadcion que representa la planilla
		 * puede ser una planilla de liquidacion normal o de definitiva de actividad economica.
		 * @return array retotna los registros encontrados segun la consulta.
		 */
		public function getConfiguracion($tipoLiquidacion)
		{
			$findModel = self::findDescuentoModel();

			return $model = $findModel->andWhere('tipo_liquidacion =:tipo_liquidacion',
												['tipo_liquidacion' => $tipoLiquidacion])
									  ->andWhere('fecha_desde <=:fecha_desde',[':fecha_desde' => date('Y-m-d')])
									  ->andWhere('fecha_hasta >=:fecha_desde',[':fecha_desde' => date('Y-m-d')])
									  ->orderBy([
											'ano_impositivo' => SORT_ASC,
											'periodo' => SORT_ASC,
										])
									  ->asArray()
									  ->all();
		}





		/***/
		public function getConfigDescuentoTodoAno($añoImpositivo)
		{
			$config = [];
			$descuentos = self::getDescuentoSegunAnoImpositivo($añoImpositivo);
			if ( count($descuentos) > 0 ) {
				foreach ( $descuentos as $descuento ) {
					if ( $descuento['tipo_liquidacion'] == 0 ) {
						if ( $descuento['aplicar_solo_periodo'] == 0 && $descuento['periodo'] == 1 ) {
							$config = $descuento;
							break;
						}
					}
				}
			}

			return $config;
		}






		/***/
		private function getDescuentoSegunAnoImpositivo($añoImpositivo)
		{
			$descuentos = [];
			$model = self::findDescuentoModel()->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo]);

			$descuentos = $model->asArray()->all();
			return $descuentos;
		}




		/**
		 * Metodo que determina el descuento que se debe aplicar a una monto especifico
		 * para un año especifico e impuesto. Se busca los datos en la configuracion
		 * para determinar el porcentaje. Y se arma un arreglocon la siguiente estructura
		 * para retornar.
		 * {
		 * 		['descuento'] => monto del descuento,
		 *   	['fecha_desde'] => inicio de la configuracion,
		 *    	['fecha_hasta'] => final de la configuracion
		 * }
		 * @param  integer $añoImpositivo año impositivo al cual pertenece el lapso.
		 * @param  double $monto monto al cual de le aplicara el descuento.
		 * @return array retorna un arreglo donde un elemento tiene el monto por descuento
		 * y en los demas elementos se coloca el rango de vigencia del descuento, segun la
		 * configuracion.
		 */
		public function getMontoDescuentoAnual($añoImpositivo, $monto)
		{
			$descuento = [];
			$config = self::getConfigDescuentoTodoAno($añoImpositivo);
			if ( count($config) > 0 ) {
				$descuento['descuento'] = number_format(($config['porc_monto']/100) * $monto, 2);
				$descuento['fecha_desde'] = $config['fecha_desde'];
				$descuento['fecha_hasta'] = $config['fecha_hasta'];

			}
			return $descuento;
		}


	}

?>