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
 *  @file DepositoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @class DepositoSearch
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

	namespace backend\models\recibo\deposito;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use backend\models\recibo\deposito\Deposito;
	use common\models\planilla\Pago;


	/**
	* 	Clase
	*/
	class DepositoSearch
	{

		protected $planilla;
		protected $recibo;


		/***/
		public function __construct()
		{}


		/**
		 * Metodo que genera el modelo principal de la relacion entre las entidades
		 * "depositos-planillas" y "pagos". En realidad es un inner join entre las
		 * dos entidades
		 * @return active record retorna un model principal de consulta.
		 */
		private function findDepositoPlanillaPago()
		{
			return DepositoPlanilla::find()->joinWith('pago', true, 'INNER JOIN');
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
			$tablaDepPlanilla = DepositoPlanilla::tableName();
			$tablaPago = Pago::tableName();

			$findModel = self::findDepositoPlanillaPago();
			if ( count($findModel) > 0 ) {
				$model = $findModel->where($tablaDepPlanilla . '.planilla =:planilla',
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



		/***/
		public function findDeposito($recibo)
		{
			return Deposito::findOne($recibo);
		}




	    /***/
	    public function guardar($arregloDatos)
	    {

	    }
	}

?>