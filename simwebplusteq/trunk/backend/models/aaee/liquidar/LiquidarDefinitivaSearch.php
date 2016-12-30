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
 *  @file LiquidarDefinitivaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class LiquidarDefinitivaSearch
 *  @brief Clase Modelo principal
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

 	namespace backend\models\aaee\liquidar;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\actecon\ActEconSearch;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoSearch;
	use common\models\ordenanza\OrdenanzaBase;
	use yii\helpers\ArrayHelper;
	use backend\models\aaee\rubro\Rubro;
	use common\models\calculo\liquidacion\aaee\CalculoRubro;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use common\models\planilla\PagoDetalle;



	/**
	 * Clase que gestiona el proceso de liquidacion de la declaracion definitiva
	 *
	 */
	class LiquidarDefinitivaSearch
	{

		private $_id_contribuyente;
		private $_ano_impositivo;
		private $_periodo;

		/**
		 * Variable que contiene:
		 * - rubro
		 * - descripcion del rubro
		 * - alicuota
		 * - minino
		 * - año
		 * - id-impuesto
		 * - id-rubro
		 * - impuesto calculado
		 * - monto declarado por el contribuyente.
		 * @var array
		 */
		private $_detalleDeclaracion;


		/**
		 * Variable que contiene todos los detalles de la liquidacion, specialmente
		 * lo contenido en la entidad PagoDetalle.
		 * @var array
		 */
		private $detalleLiquidacion;


		/**
		 * Variable instancia de la clase DeclaracionBaseSearch()
		 * @var DeclaracionBaseSearch
		 */
		public $declaracionSearch;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $añoImpositivo año impositivo de la declaracion.
		 * @param integer $periodo periodo correspondiente.
		 */
		public function __construct($idContribuyente, $añoImpositivo, $periodo)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_ano_impositivo = $añoImpositivo;
			$this->_periodo = $periodo;

			$this->declaracionSearch = New DeclaracionBaseSearch($idContribuyente);
		}




		/**
		 * Metodo que genera el modelo general de consulta de los rubros con sus respectivas
		 * montos declrados.
		 * @return ActEconIngreso
		 */
		public function findDetalleDeclaracionModel()
		{
			return $this->declaracionSearch->findRubrosRegistrados($this->_ano_impositivo, $this->_periodo);
		}





		/**
		 * Metodo que determina si existe una definitiva anterior a la que se pretende
		 * liquidar faltante. El metodo debe retornar una arreglo de mensaje con los
		 * lapsos faltantes.
		 * @return array retorna arreglo de mensaje.
		 */
		public function determinarLiquidacionFaltante()
		{
			$mensaje = [];
			$existe = true;
			$resultados = ActEcon::find()->alias('A')
										 ->distinct('ano_impositivo')
	                                     ->where('id_contribuyente =:id_contribuyente',
	                                   			[':id_contribuyente' => $this->_id_contribuyente])
	                                     ->andWhere('ano_impositivo <:ano_impositivo',
	                                   			[':ano_impositivo' => $this->_ano_impositivo])
	                                     ->andWhere('estatus =:estatus',[':estatus' => 0])
	                                     ->andWhere('inactivo =:inactivo',[':inactivo' => 0])
	                                     ->joinWith('actividadDetalle I', true, 'INNER JOIN')
	                                     ->orderBy([
                                     			'ano_impositivo' => SORT_ASC,
                                   	     	])
	                                     ->asArray()
                                         ->all();

		    if ( count($resultados) > 0 ) {
		    	foreach ( $resultados as $key => $value ) {
		    		$existe = self::existePlanillaDefinitiva((int)$value['ano_impositivo'],
		    												 (int)$value['actividadDetalle'][$key]['exigibilidad_periodo']);

		    		if ( !$existe ) {
		    			$mensaje[] = Yii::t('backend', 'Falta por liquidar el laspo ' . $value['ano_impositivo'] . ' - ' . $value['actividadDetalle'][$key]['exigibilidad_periodo']);
		    		}
		    	}
		    }

		    return $mensaje;

		}




		/**
		 * Metodo que determina si una planilla de la definituva para un lapso determinado
		 * existe.
		 * @param integer $añoImpositivo año impositivo consultado.
		 * @param  integer $periodo  periodo consultado.
		 * @return boolean
		 */
		public function existePlanillaDefinitiva($añoImpositivo, $periodo)
		{
			return PagoDetalle::find()->alias('D')
			                          ->where('id_contribuyente =:id_contribuyente',
			                          			[':id_contribuyente' => $this->_id_contribuyente])
			                          ->andWhere('ano_impositivo =:ano_impositivo',
			                          			[':ano_impositivo' => $añoImpositivo])
			                          ->andWhere('trimestre =:trimestre',
			                          			[':trimestre' => $periodo])
			                          ->andWhere('impuesto =:impuesto',[':impuesto' => 1])
			                          ->andWhere('referencia =:referencia',[':referencia' => 1])
			                          ->andWhere('pago !=:pago',[':pago' => 9])
			                          ->joinWith('pagos P', true, 'INNER JOIN')
			                          ->exists();
		}





		/***/
		public function datosDeclaracionImpuesto()
		{
			$findModel = self::findDetalleDeclaracionModel();
			$resultados = $findModel->asArray()->all();
			$data = [];


			foreach ( $resultados as $key => $value ) {

				$calculo = New CalculoRubro($value);

				$data[] = [
					'id_rubro' => $value['id_rubro'],
					'rubro' => $value['rubroDetalle']['rubro'],
					'minino' => $calculo->getMinimoTributableRubro(),
					'minimo_ut' => $value['rubroDetalle']['minimo_ut'],
					'ano_impositivo' => $value['actividadEconomica']['ano_impositivo'],
					'descripcion' => $value['rubroDetalle']['descripcion'],
					'declaracion' => $value['reales'],
					'impuesto' => $calculo->getCalcularPorTipoDeclaracion('reales'),
				];
			}

			return $data;
		}






		/***/
		public function getArrayDataProviderDeclaracionImpuesto()
		{
			$data = self::datosDeclaracionImpuesto();
			$provider = New ArrayDataProvider([
						'allModels' => $data,
						'pagination' => false,

					]);

			return $provider;
		}




		/***/
		public function sumaDeclarado($data)
		{
			if ( count($data) == 0 ) {
				$data = self::datosDeclaracionImpuesto();
			}

			$suma = 0;
			if ( count($data) > 0 ) {
				foreach ( $data as $d ) {
					$suma = $suma + $d['declaracion'];
				}
			}

			return $suma;
		}




		/***/
		public function sumaImpuesto($data)
		{
			if ( count($data) == 0 ) {
				$data = self::datosDeclaracionImpuesto();
			}

			$suma = 0;
			if ( count($data) > 0 ) {
				foreach ( $data as $d ) {
					$suma = $suma + $d['impuesto'];
				}
			}

			return $suma;
		}


	}
 ?>