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
 *  @file DepositoPlanillaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-10-2016
 *
 *  @class DepositoPlanillaSearch
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

	namespace backend\models\recibo\depositoplanilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use common\models\planilla\Pago;
	use yii\base\ErrorException;


	/**
	* 	Clase
	*/
	class DepositoPlanillaSearch
	{

		protected $planilla;
		protected $recibo;


		/***/
		public function __construct()
		{}



		/**
		 * Metodo que genera el modelo de consulta para la entidad "depositos-planillas".
		 * @param  integer $planilla numero de planilla.
		 * @return active record.
		 */
		private function findDepositoPlanillaSegunPlanilla($planilla)
		{
			return DepositoPlanilla::find()->alias('DP')
										   ->where('DP.planilla =:planilla',
														[':planilla' => $planilla]);
		}



		/**
		 * Metodo que permite determinar si una planilla puede ser seleccionada para
		 * crear un recibo. Esta informacion solo permite determinar si la planilla
		 * no esta relacionada a una recibo de pago que este en un estatus que no permita
		 * su utilizacion en la creacion de un recibo. La informacion no verifica si la
		 * planilla esta asociada a otro proceso o si esta en un estatus que no permita
		 * su utilizacion en la creeacion de un recibo.
		 * @param  integer $planilla numero de planilla.
		 * @return boolean retorna true o false.
		 */
		public function puedoSeleccionarPlanillaParaRecibo($planilla)
		{

			$recibos = [];
			$result = true;
			$findModel = self::findDepositoPlanillaSegunPlanilla($planilla);
			if ( count($findModel) > 0 ) {
				$recibos = $findModel->andWhere(['IN', 'estatus', [0,1]])->all();
				if ( count($recibos) > 0 ) {
					$result = false;
				}
			}
			return $result;

		}



		/**
		 * Metodo que genera el modelo principal de la relacion entre las entidades
		 * "depositos-planillas" y "pagos". En realidad es un inner join entre las
		 * dos entidades
		 * @return active record retorna un model principal de consulta.
		 */
		private function findDepositoPlanillaPago()
		{
			return DepositoPlanilla::find()->alias('DP')
										   ->joinWith('pago P', true, 'INNER JOIN');
		}




		/**
		 * Metodo que complementa  el modelo de consulta con los parametros de
		 * consultas.
		 * @param  integer $planilla numero de planilla.
		 * @param  integer $estatus conodicion del registro.
		 * @return active record retorna un modelo con los parametros agregados
		 * de la consulta.
		 */
		private function findRelacionPlanillaRecibo($planilla, $estatus)
		{
			//$tablaDepPlanilla = DepositoPlanilla::tableName();
			//$tablaPago = Pago::tableName();

			$findModel = self::findDepositoPlanillaPago();
			if ( count($findModel) > 0 ) {
				$model = $findModel->where('DP.planilla =:planilla',
											[':planilla' => $planilla])
								   ->andWhere('estatus =:estatus',
								   			[':estatus' => $estatus]);
			}

			return count($model) > 0 ? $model : [];
		}




		/**
		 * Metodo que permite armar un arreglo de los estatus de una planilla
		 * especifica. La planilla en este caso debe estar asociada a un recibo
		 * y el mismo debe estar pendiente por pagar. estatus = 0 en la entidad
		 * "depositos-planillas".
		 * @param  integer $planilla numero de planilla.
		 * @return array retorna una arreglo donde el indice del arreglo es el
		 * identificador de la entidad "depositos-planillas" y el valor del
		 * elemneto es una arreglo donde estan el numero de planilla con el nuemro
		 * de recibo asociado y el estatus del registro.
		 */
		public function planillaRelacionadaReciboPendiente($planilla)
		{
			$lista = [];
			$findModel = self::findRelacionPlanillaRecibo($planilla, 0);
			if ( count($findModel) > 0 ) {

				$result = $findModel->asArray()->all();
				if ( count($result) > 0 ) {

					foreach ( $result as $planillas ) {
						$lista[$planillas['linea']] = [
												'recibo'   => $planillas['recibo'],
												'planilla' => $planillas['planilla'],
												'estatus'  => $planillas['estatus'],
											];
					}
				}

			}

			return $lista;
		}
	}

?>