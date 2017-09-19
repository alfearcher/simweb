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
 *  @file LicenciaEmitidaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @class LicenciaEmitidaSearch
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

	namespace backend\models\reporte\aaee\licencia;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use backend\models\reporte\aaee\licencia\LicenciaNoEmitidaBusquedaForm;
	use yii\helpers\ArrayHelper;
	use moonland\phpexcel\Excel;
	use backend\models\utilidad\causa\noemisionlicencia\CausaNoEmisionLicencia;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\licencia\LicenciaSolicitud;
	use common\models\planilla\PlanillaSearch;


	/**
	* Clase que permite realizar la consulta a traves de los parametros indicado
	* en el formulario de consulta de los contribuyentes que no han solicitado la
	* emision de la licencias.
	*/
	class LicenciaNoEmitidaSearch extends LicenciaNoEmitidaBusquedaForm
	{


		/***/
		public function __construct()
		{}


		/***/
		public function init()
		{
			$contribuyentes = self::findContribuyente();
			if ( count($contribuyentes) > 0 ) {
				foreach ( $contribuyentes as $contribuyente ) {
					self::verificarEvento($contribuyente);
				}
			}
		}



		/***/
		private function verificarEvento($contribuyente)
		{
			$mensajes = [];

			// Se verifica si tiene rubros para el año actual.
			$añoActual = (int)date('Y');
			$idContribuyente = (int)$contribuyente['id_contribuyente'];
			foreach ( $this->chkCausa as $key => $value ) {
				if ( $value == 1 ) {
					// Se buscan las solicitudes pendientes de emision de licencia
					$results = self::findSolicitudLicenciaPendiente($añoImpositivo, $idContribuyente);
					if ( $results == null ) {
						$mensajes[] = Yii::t('backend', 'No existe la solicitud de emision para el año ') . $añoImpositivo;
					}
				} elseif ( $value == 2 ) {

				} elseif ( $value == 3 ) {
					if ( !self::existeRubro($añoActual, $idContribuyente) ) {
						$mensajes[] = Yii::t('backend', 'No posee rubros registrados para el año ') . $añoActual;
					}
				}
			}


			// Se verifica la condicion de las solicitudes.
			$condiciones = self::verificarCondicionSolicitud($añoActual, $idContribuyente);
			if ( count($condiciones) > 0 ) {
				foreach ( $condiciones as $key => $condicion ) {
					$mensajes[] = $condicion;
				}
			}

die(var_dump($mensajes));
			return $mensajes;
		}



		/**
		 * Metodo que genera el modelo basico de consulta sobre la entidad "contribuyentes".
		 * @return ContribuyenteBase
		 */
		private function findContribuyenteModel()
		{
			return ContribuyenteBase::find()->alias('C');
		}



		/**
		 * Metodo que ejecuta la consulta para localizar al grupo de contribuyentes.
		 * Retorna un arreglo con los campos de la entidad respectiva u con todos
		 * los registros que cumplan las condiciones.
		 * @return array.
		 */
		private function findContribuyente()
		{
			$results = [];
			$findModel = self::findContribuyenteModel();
			$findModel = $findModel->select([
										'id_contribuyente',
										'naturaleza',
										'cedula',
										'tipo',
										'id_rif',
										'razon_social',
										'domicilio_fiscal',
										'tlf_ofic',
										'tlf_ofic_otro',
										'tlf_celular',
										'email',
									])
								   ->where(['inactivo' => 0])
								   ->andWhere(['tipo_naturaleza' => 1])
								   ->andWhere(['no_declara' => 0]);

			if ( (int)$this->todos_contribuyentes == 1 ) {
				// La consulta se realiza sobre un lote
				$results = $findModel->asArray()->limit(5)->all();
			} else {
				// La consulta se realiza sobre uno
				if ( (int)$this->id_contribuyente > 0 ) {
					$results = $findModel->andWhere('id_contribuyente =:id_contribuyente',
														[':id_contribuyente' => (int)$this->id_contribuyente])
										 ->asArray()
										 ->all();
				}
			}
			return $results;
		}



		/**
		 * Metodoq ue permite determinar si existe rubros para el año indicado.
		 * @param  integer $añoImpositivo año impositivo
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true si encuentra rubro, de lo contrario false.
		 */
		private function existeRubro($añoImpositivo, $idContribuyente)
		{
			 return ActEconIngreso::find()->alias('I')
										  ->where('id_contribuyente =:id_contribuyente',
											   					[':id_contribuyente' => $idContribuyente])
										  ->andWhere(['inactivo' => 0])
										  ->andWhere(['estatus' => 0])
										  ->andWhere('ano_impositivo =:ano_impositivo',
											   					[':ano_impositivo' => $añoImpositivo])
										  ->joinWith('actividadEconomica', true, 'INNER JOIN')
										  ->exists();
		}



		/**
		 * Metodo que permite determinar si existe una solicitud
		 * @param integer $añoImpositivo año impositivo
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return LicenciaSolicitud
		 */
		private function findSolicitudLicenciaPendiente($añoImpositivo, $idContribuyente)
		{
			return LicenciaSolicitud::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $idContribuyente])
										    ->andWhere('ano_impositivo =:ano_impositivo',
										  						[':ano_impositivo' => $añoImpositivo])
										    ->andWhere(['IN', 'estatus', [0]])
										    ->all();
		}



		/**
		 * Metodo que verifica la consicion de la solicitud de emision de la lciencia.
		 * @param integer $añoImpositivo año impositivo
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return array.
		 */
		private function verificarCondicionSolicitud($añoImpositivo, $idContribuyente)
		{
			$mensajes = [];
			// Se buscan las solicitudes pendientes de emision de licencia
			$results = self::findSolicitudLicenciaPendiente($añoImpositivo, $idContribuyente);
			if ( $results == null ) {
				$mensajes[] = Yii::t('backend', 'No existe la solicitud de emision para el año ') . $añoImpositivo;
			} else {
				// Se determina si la planilla corespondiente la a solicitud esta pagada.
				foreach ( $results as $result ) {
					$planilla = $result->getPlanillas();
					$searchPlanilla = New PlanillaSearch((int)$planilla['planilla']);
					$condicionPlanilla = $searchPlanilla->condicionPlanilla();
					if ( (int)$condicionPlanilla['pago'] == 0 ) {
						$mensajes[] = Yii::t('backend', "La tasa {$planilla['planilla']}, no se encuentra pagada.");
					} elseif ( (int)$condicionPlanilla['pago'] == 9 ) {
						$mensajes[] = Yii::t('backend', "La tasa {$planilla['planilla']}, se encuentra anulada.");
					}
				}
			}
			return $mensajes;
		}








		/**
		 * Metodo que retorna el data provider de las causas de la no emision de la
		 * licencias sobre actividades economicas.
		 * @return ActiveDataProvider
		 */
		public function dataProviderCausaNoEmision()
		{
			$query = CausaNoEmisionLicencia::find()->where(['inactivo' => 0]);
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);
			$query->all();
			return $dataProvider;
		}


	    /**
	     * Metodo que crea el data provider del historico de licencia
	     * @return ActiveDataProvider
	     */
	    public function getDataProvider($export = false)
	    {
	    	$query = self::armarConsultaHistoricoLicenciaModel();

	    	if ( $export ) {
		    	$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => false,
		    	]);
		    } else {
		    	$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => [
		    			'pageSize' => 50,
		    		],
		    	]);
		    }
	    	$query->all();
	    	return $dataProvider;
 	    }


 	    /**
		 * Metodo que exporta el contenido de la consulta a formato excel
		 * @return view
		 */
		public function exportarExcel($model)
		{

			return Excel::widget([
    			'models' => $model,
    			'format' => 'Excel2007',
                'properties' => [

                ],
    			'mode' => 'export', //default value as 'export'
    			'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
	            	[
	            		'attribute' => 'nro_solicitud',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->nro_solicitud;
									},
	                ],
	                [
	                	'attribute' => 'tipo',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->tipo;
									},
	                ],
	                [
	               		'attribute' => 'Lic. Generada',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->licencia;
									},
	                ],
	               	[
	               		'attribute' => 'Emision',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										$fuente = json_decode($model->fuente_json, true);
										return date('d-m-Y', strtotime($fuente['fechaEmision']));
									},
	                ],
	                [
	                	'attribute' => 'Vcto',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										$fuente = json_decode($model->fuente_json, true);
										return date('d-m-Y', strtotime($fuente['fechaVcto']));
									},
	                ],
	                [
	                	'attribute' => 'Condicion',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										if ( $model->inactivo == 1 ) {
											return 'INACTIVO';
										} else {
											return 'ACTIVO';
										}
									},
	                ],
	                [
	                	'attribute' => 'ID',
	                    'contentOptions' => [
		                	'style' => 'text-align:center;font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->id_contribuyente;
									},
	                ],
	                 [
	                 	'attribute' => 'Contribuyente',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										$fuente = json_decode($model->fuente_json, true);
										return $fuente['descripcion'];
									},
	                ],
	                [
	                	'attribute' => 'Serial Control',
	                    'contentOptions' => [
		                	'style' => 'text-align:center;font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->serial_control;
									},
	                ],
	                [
	                	'attribute' => 'Usuario',
	                    'contentOptions' => [
		                	'style' => 'text-align:center;font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->usuario;
									},
	                ],
	        	]
			]);
		}

	}

?>