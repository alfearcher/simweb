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
 *  @file Declaracion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-03-2016
 *
 *  @class Declaracion
 *  @brief Clase Modelo que permitira la consulta de loas declaraciones.
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

	namespace backend\models\aaee\declaracion;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\acteconingreso\ActEconIngreso;


	/**
	* 	Clase
	*/
	class Declaracion extends ActEcon
	{
		private $_añoImpositivo;
		private $_periodo;
		private $_idContribuyente;
		private $_fechaDesde;
		private $_fechaHasta;
		private $_idImpuesto;



		/**
		 * [__construct description]
		 * @param Long $id, identificador del contribuyente.
		 */
		public function __construct($id)
		{
			$this->_idContribuyente = $id;
		}


		/***/
		public function setAnoImpositivo($año)
		{
			$this->_añoImpositivo = $año;
		}


		/***/
		public function setPeriodo($periodo)
		{
			$this->_periodo = $periodo;
		}


		/***/
		public function setFechaDesde($fechaDesde)
		{
			$this->_fechaDesde = $fechaDesde;
		}


		/***/
		public function setFechaHasta($fechaHasta)
		{
			$this->_fechaHasta = $fechaHasta;
		}



		/***/
		public function setLapsoPeriodo($añoImpositivo, $periodo)
		{
			$this->setAnoImpositivo($añoImpositivo);
			$this->setPeriodo($periodo);
		}



		/***/
		public function setLapsoFecha($fechaDesde, $fechaHasta)
		{
			$this->setFechaDesde($fechaDesde);
			$this->setFechaHasta($fechaHasta);
		}



		/***/
		public function setIdImpuesto($idImpuesto)
		{
			$this->_idImpuesto = $idImpuesto;
		}



		/***/
		public function getIdImpuesto()
		{
			return $this->_idImpuesto;
		}




		/**
		 * Metodo que permite obtener la declaracion de un lapso especifico
		 * @return Array, el arreglo retornado contiene los datos de la entidad
		 * "act-econ" y "act-econ-ingresos". Los campos pertenecientes a la entidad
		 * "act-econ-ingresos" estan contenido un un arreglo con el indice "actividadDetalle".
		 */
		public function getDeclaracionContribuyente()
		{
			if ( $this->_añoImpositivo > 0 && $this->_periodo > 0 ) {
				$modelfind = ActEcon::find()->where([
												'id_contribuyente' => $this->_idContribuyente,
												'ano_impositivo' => $this->_añoImpositivo,
												'exigibilidad_periodo' => $this->_periodo,
												'estatus' => 0,
												'inactivo' => 0,
													])
				                            ->joinWith('actividadDetalle')
				                            ->orderBy([
				                            	'ano_impositivo' => SORT_ASC,
				                            	'exigibilidad_periodo' => SORT_ASC,
				                            	     ])
				                            ->asArray()
				                            ->all();
				if ( count($modelfind) > 0 ) {
					return $modelfind;
				}
			}
			return null;
		}




		/***/
		private function getRubroDetalle()
		{
			if ( $this->_añoImpositivo > 0 && $this->_idImpuesto > 0 ) {
				$modelfind = ActEconIngreso::find()->where([
														'id_impuesto' => $this->_idImpuesto,
														'ano_impositivo' => $this->_añoImpositivo,
														'exigibilidad_periodo' => $this->_periodo,
														'inactivo' => 0,
													])
				                            		->joinWith('rubroDetalle')
				                            		->orderBy([
				                            				'ano_impositivo' => SORT_ASC,
				                            				'exigibilidad_periodo' => SORT_ASC,
				                            				'rubro' => SORT_ASC,
				                            	     ])
				                            		->asArray()
				                            		->all();
				if ( count($modelfind) > 0 ) {
					return $modelfind;
				}
			}
			return null;
		}




		/***/
		public function getIdImpuestoSegunAnoImpositivo($añoImpositivo)
		{
			if ( $añoImpositivo > 0 ) {
				$this->setAnoImpositivo($añoImpositivo);
			}
			$modelFind = ActEcon::find()->select('id_impuesto')
										->where([
											'id_contribuyente' => $this->_idContribuyente,
											'ano_impositivo' => $añoImpositivo,
										])
										->one();
			if ( isset($modelFind) ) {
				return $modelFind;
			}
			return null;
		}



		/***/
		public function getRubroDeclarado($añoImpositivo, $periodo)
		{
			$this->setAnoImpositivo($añoImpositivo);
			$this->setPeriodo($periodo);
			$this->setIdImpuesto(0);
			$model = $this->getIdImpuestoSegunAnoImpositivo();
			if ( isset($model->id_impuesto) ) {
				$this->setIdImpuesto($model->id_impuesto);
				return $this->getRubroDetalle();
			}
			return null;
		}

	}

?>