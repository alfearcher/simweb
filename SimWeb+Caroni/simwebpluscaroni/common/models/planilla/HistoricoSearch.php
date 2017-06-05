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
 *  @file HistoricoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-10-2016
 *
 *  @class HistoricoSearch
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
	use backend\models\recibo\depositoplanilla\DepositoPlanillaSearch;

	/**
	* 	Clase
	*/
	class HistoricoSearch
	{


		/***/
		public function __construct()
		{}



		/**
		 * Metodo que define el modelo principal de la consulta sobre las entidades
		 * "pagos"y "pagos-detalle".
		 * @return active record retorna un modelo principal de consulta.
		 */
		private function detalleLiquidados()
		{
			return $findModel = PagoDetalle::find()->joinWith('pagos', true, 'INNER JOIN');
		}



		/**
		 * Metodo que permite complementar el modelo principal de consulta, agrenagdole
		 * las condiciones de la misma. Se agregan los parametros de consulta.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return active record retorna un modelo con los parametros asignados.
		 */
		private function definitivaLiquidadas($añoImpositivo, $periodo, $idContribuyente)
		{
			$model = null;
			$detalleModel = self::detalleLiquidados();
			if ( count($detalleModel) > 0 ) {
				$model = $detalleModel->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $idContribuyente])
									  ->andWhere('ano_impositivo =:ano_impositivo',
									  				[':ano_impositivo' => $añoImpositivo])
									  ->andWhere('trimestre =:trimestre',
									  				[':trimestre' => $periodo])
									  ->andWhere('impuesto =:impuesto',
									  				[':impuesto' => 1])
									  ->andWhere('referencia =:referencia',
									  				[':referencia' => 1]);
			}

			return $model;
		}



		/**
		 * Metodo que determina en que condicion esta un lapso de definitiva.
		 * Recibe el modelo con los paramteros de la consulta y en el metodo
		 * se ejecuta la consulta,luego se determina segun el atributo "pago"
		 * la condicion del registro.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return integer|boolean retorna un entero que indica la condicion del
		 * registro o un boolean (false) sino encuntra registro para el lapso
		 * determinado.
		 */
		public function condicionDefinitiva($añoImpositivo, $periodo, $idContribuyente)
		{
			$findDefinitiva = self::definitivaLiquidadas($añoImpositivo, $periodo, $idContribuyente);
			$result = $findDefinitiva->asArray()->all();
			if ( count($result) == 0 ) {	// No existe registro para el lapso.
				return false;
			} else {
				return (int)$result[0]['pago'];
			}
		}



		/**
		 * Metodo que permite buscar la o las planillas de definitivas relacionadas
		 * a un lapso. Las planillas deben estar pendiente.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return array retorna un arreglo de planillas.
		 */
		public function planillaDefinitivaRelacionadaLapso($añoImpositivo, $periodo, $idContribuyente)
		{
			// Datos de prueba
			// $añoImpositivo = 2012;
			// $periodo = 1;
			// $idContribuyente = 10797;

			$planilla = [];
			// Se busca las planillas de definitivas asociadas a los parametros enviados
			// las planillas deben estar pendientes por pagar. pago=0
			$findDefinitiva = self::definitivaLiquidadas($añoImpositivo, $periodo, $idContribuyente);

			$result = $findDefinitiva->andWhere('pago =:pago',
											[':pago' => 0])
									 ->asArray()
			                         ->all();

			if ( count($result) > 0 ) {
				foreach ( $result as $planillas ) {
					$planilla[] = $planillas['pagos']['planilla'];
				}
			}

			return $planilla;

		}




	}

?>