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
 *  @file HistoricoViviendaPrincipalSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-02-2017
 *
 *  @class HistoricoViviendaPrincipalSearch
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

	namespace backend\models\inmueble\principal;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\inmueble\principal\HistoricoViviendaPrincipal;
	use common\models\ordenanza\OrdenanzaBase;



	/**
	* Clase
	*/
	class HistoricoViviendaPrincipalSearch extends HistoricoViviendaPrincipal
	{

		private $_id_contribuyente;
		private $_id_impuesto;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $idImpuesto identificador del inmueble.
		 */
		public function __construct($idContribuyente, $idImpuesto)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_id_impuesto = $idImpuesto;
		}




		/**
		 * Metodo que genera el modelo de consulta para el historico de vivienda.
		 * @return HistoricoViviendaPrincipal.
		 */
		private function findHistoricoViviendaPrincipalModel()
		{
			return HistoricoViviendaPrincipal::find()->where('id_contribuyente =:id_contribuyente',
																[':id_contribuyente' => $this->_id_contribuyente])
												     ->andWhere('id_impuesto =:id_impuesto',
												     			[':id_impuesto' => $this->_id_impuesto]);

		}




		/**
		 * Metodo que genera la consulta y retorna el resultado. Consulta sobre
		 * lavivienda principal.
		 * @return array
		 */
		public function getHistoricoViviendaPrincipal()
		{
			$findModel = self::findHistoricoViviendaPrincipalModel();
			$model = $findModel->andWhere('estatus =:estatus',['estatus' => 0]);
			$results = $model->asArray()->all();

			return $results;
		}



		/**
		 * Metodo que permite determinar a que periodo petenece una fecha, segun
		 * la exigibilidad de liquidacion del impuesto, que en esta caso es inmuebles
		 * urbanos.
		 * @param integer $exigibilidad exigibilidad liquidacion de inmuebles urbanos.
		 * Este parametro pertenece a la plamilla de liquidacion del inmueble en cuestion.
		 * @param string $nombreFecha nombre de la fecha a la cual se le quiere determinar
		 * el periodo. En este caso "fecha_desde" y "fecha_hasta".
		 * @return integer.
		 */
		public function getPeriodoFechaDesde($exigibilidad, $nombreFecha)
		{
			$periodo = 0;
			$results = self::getHistoricoViviendaPrincipal();
			if ( count($results) > 0 ) {
				$periodo = OrdenanzaBase::getPeriodoSegunFecha($exigibilidad, $results[0][$nombreFecha]);
			}

			return $periodo;
		}

	}

?>