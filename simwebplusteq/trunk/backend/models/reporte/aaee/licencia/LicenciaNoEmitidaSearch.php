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
 	use yii\helpers\Html;
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
	use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;
	use backend\models\utilidad\licencia\reporte\LicenciaReporteSearch;


	/**
	* Clase que permite realizar la consulta a traves de los parametros indicado
	* en el formulario de consulta de los contribuyentes que no han solicitado la
	* emision de la licencias.
	*/
	class LicenciaNoEmitidaSearch extends LicenciaNoEmitidaBusquedaForm
	{
		private $_errores;
		private $_data;			// Fuente para crear el data provider.
		/**
		 * Instancia de la clase
		 * @var CausaNoEmisionLicencia
		 */
		private $_causaNoEmision;
		private $_usuario;


		/**
		 * Metodo constructor de la clase.
		 */
		public function __construct()
		{
			$this->_causaNoEmision = CausaNoEmisionLicencia::find()->all();
			$this->_usuario = Yii::$app->identidad->getUsuario();
		}


		/**
		 * Metodo inicial.
		 * @return none
		 */
		public function init()
		{
			$contribuyentes = self::findContribuyente();
			self::armarData($contribuyentes);
			return self::insertarData();
		}


		/**
		 * Metodo que inserta la data
		 * @return boolean.
		 */
		private function insertarData()
		{
			$result = false;
			if ( count($this->_data) > 0 ) {
				$reporteSearch = New LicenciaReporteSearch();
				$result = $reporteSearch->insertarLote($this->_data);
			}
			return $result;
		}



		/**
		 * Metodo para obtener la data armada.
		 * @return array
		 */
		public function getData()
		{
			return $this->_data;
		}



		/**
		 * Metodo que arma el arreglo de datos con la informacion del
		 * contribuyente y las causas que ocacionaron que no poseea su
		 * licencia.
		 * @param array $contribuyentes arreglo con algunos atributos de
		 * la entidad "contribuyentes" y las causas que inidica el porque
		 * no ha emitido su licencia. EL arreglo representa uno o varios
		 * contribuyentes.
		 * @return array
		 */
		private function armarData($contribuyentes)
		{
			if ( count($contribuyentes) > 0 ) {
				foreach ( $contribuyentes as $contribuyente ) {
					$observacion = self::verificarEvento($contribuyente);
					$observacion = count($observacion) > 0 ? json_encode($observacion) : '';
					if ( trim($observacion) !== '' ) {
						$this->_data[] = [
							'id_contribuyente' => $contribuyente['id_contribuyente'],
							'naturaleza' => $contribuyente['naturaleza'],
							'cedula' => $contribuyente['cedula'],
							'tipo' => $contribuyente['tipo'],
							'razon_social' => $contribuyente['razon_social'],
							'tlf_ofic' => $contribuyente['tlf_ofic'],
							'tlf_ofic_otro' => $contribuyente['tlf_ofic_otro'],
							'tlf_celular' => $contribuyente['tlf_celular'],
							'domicilio_fiscal' => $contribuyente['domicilio_fiscal'],
							'email' => $contribuyente['email'],
							'observacion' => $observacion,
						];
					}
				}
			}
		}



		/**
		 * Metodo que realiza la verificacion de las causas por las cuales un
		 * contribuyente no posee licencia. Si encuentra alguna causa retornara
		 * uno o varios mensajes.
		 * @param array $contribuyente arreglo que contiene algunos atributos
		 * del contribuyente.
		 * @return array.
		 */
		private function verificarEvento($contribuyente)
		{
			$mensajes = [];
			$yaBusqueSolicitud = false;

			// Se verifica si tiene rubros para el año actual.
			$añoActual = (int)date('Y');
			$idContribuyente = (int)$contribuyente['id_contribuyente'];

			// No ha realizado la solicitud
			if ( self::existeCausa(1) ) {
				// Se buscan las solicitudes pendientes de emision de licencia
				$results = self::findSolicitudLicenciaPendiente($añoActual, $idContribuyente);
				if ( count($results) == 0 || $results == null ) {
					$mensajes[] = self::determinarCausaNoEmision(1);
				}
			}

			// Realizo la solicitud y no ha pagada la tasa.
			if ( self::existeCausa(2) ) {
				if ( self::existeCausa(1) ) {
					if ( count($results) == 1 ) {
						$mensaje = self::verificarCondicionTasaSolicitudLicencia($results);
						if ( count($mensaje) > 0 ) {
							foreach ( $mensaje as $key => $msj ) {
								$mensajes[] = $msj;
							}
						}
					} elseif ( count($results) > 1 ) {
						foreach ( $results as $result ) {
							$mensaje = self::verificarCondicionTasaSolicitudLicencia($result);
							if ( count($mensaje) > 0 ) {
								foreach ( $mensaje as $key => $msj ) {
									$mensajes[] = $msj;
								}
							}
						}
					}
				} else {
					$results = self::findSolicitudLicenciaPendiente($añoActual, $idContribuyente);
					if ( count($results) == 1 ) {
						$mensaje = self::verificarCondicionTasaSolicitudLicencia($results);
						if ( count($mensaje) > 0 ) {
							foreach ( $mensaje as $key => $msj ) {
								$mensajes[] = $msj;
							}
						}
					} elseif ( count($results) > 1 ) {
						foreach ( $results as $result ) {
							$mensaje = self::verificarCondicionTasaSolicitudLicencia($result);
							if ( count($mensaje) > 0 ) {
								foreach ( $mensaje as $key => $msj ) {
									$mensajes[] = $msj;
								}
							}
						}
					}
				}
			}


			// No tiene registrados rubros para el año actual.
			if ( self::existeCausa(3) ) {
				if ( !self::existeRubro($añoActual, $idContribuyente) ) {
					$mensajes[] = self::determinarCausaNoEmision(3);
				}
			}

			return $mensajes;
		}


		/**
		 * Metodo que retorna la descripcion de la causa.
		 * @param integer $causa identificador de la causa de no emision de licencia.
		 * @return string
		 */
		public function determinarCausaNoEmision($causa)
		{
			foreach ( $this->_causaNoEmision as $key => $value ) {
				if ( $value['id_causa'] == $causa ) {
					return $value['descripcion'];
				}
			}
			return '';
		}



		/**
		 * Metodo que permite determinar si existe dentro de las causas enviadas desde
		 * el forulario alguna en particular. Se envia el numero de causa ($causa) que es
		 * el identificador dentro de la entidad respectiva. Si retorna true existe la
		 * causa dentro de las enviadas para su consulta, false sera lo contrario.
		 * @param integer $causa identificador de la causa.
		 * @return boolean.
		 */
		private function existeCausa($causa)
		{
			return in_array($causa, $this->chkCausa);
		}



		/**
		 * Metodo que setea los mensaje de errores, se envia un mensaje
		 * y se acumula en un arreglo.
		 * @param string $mensaje mensaje de error.
		 */
		public function setError($mensaje)
		{
			$this->_errores[] = $mensaje;
		}



		/**
		 * Metodo getter sobre la lista de errores.
		 * @return array lista de errores ocurridos.
		 */
		public function getErrores()
		{
			return $this->_errores;
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
				$results = $findModel->asArray()->limit(205)->all();
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
			return LicenciaSolicitud::find()->alias('L')
											->select(['*'])
										    ->distinct(['L.nro_solicitud'])
											->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $idContribuyente])
										    ->andWhere('ano_impositivo =:ano_impositivo',
										  						[':ano_impositivo' => $añoImpositivo])
										    ->andWhere(['IN', 'estatus', [0]])
										    ->one();
		}



		/**
		 * Metodo que permite determinar la condicion de la tasa asosiada a la solicitud de licencia
		 * elaborada por el usuario. Esta tasa se liquida segun especificaciones de la configuracion,
		 * que luego debe ser pagada por el contribuyente, se verican las siguientes condiciones:
		 * - Pendiente. Condicion de la tasa (planilla) que indica que aun no se ha pagado.
		 * - Anulada.
		 * @param LicenciaSolicitud $licenciaSolicitudes consulta de tipo LicenciaSolicitud::find()->all()
		 * que contiene todas las solicitudes de licencias pendientes elaboradas por el contribuyente.
		 * @return array. Arreglo de mensaje.
		 */
		private function verificarCondicionTasaSolicitudLicencia($licenciaSolicitud)
		{
			$mensajes = [];
			// Se determina si la planilla corespondiente la a solicitud esta pagada.
			$solicitudPlanilla = SolicitudPlanilla::find()->where(['inactivo' => 0])
													 ->andWhere('nro_solicitud =:nro_solicitud',
													 				[':nro_solicitud' => $licenciaSolicitud->nro_solicitud])
													 ->asArray()
													 ->all();
			if ( count($solicitudPlanilla) > 0 ) {
				foreach ( $solicitudPlanilla as $planillas ) {
					$searchPlanilla = New PlanillaSearch((int)$planillas['planilla']);
					$condicionPlanilla = $searchPlanilla->condicionPlanilla();
					if ( (int)$condicionPlanilla[0]['pago'] == 0 ) {
						$mensajes[] = Yii::t('backend', "La tasa {$planillas['planilla']}, no se encuentra pagada.");
					} elseif ( (int)$condicionPlanilla[0]['pago'] == 9 ) {
						$mensajes[] = Yii::t('backend', "La tasa {$planillas['planilla']}, se encuentra anulada.");
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
	    	$query = LicenciaReporteSearch::findLicenciaReporteModel();
	    	if ( $export ) {
		    	$dataProvider = New ActiveDataProvider([
					'query' => $query,
					'pagination' => false,
				]);
		    } else {
		    	$dataProvider = New ActiveDataProvider([
		    		'query' => $query,
		    		'pagination' => [
		    			'pageSize' => 100,
		    		],
		    	]);
		    }
		    $query->joinWith('contribuyente C', true, 'INNER JOIN')
		    	  ->where('usuario =:usuario',
								[':usuario' => $this->_usuario])
		    	  ->all();
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
                //'setFirstTitle' => 'Hola',
    			'mode' => 'export', //default value as 'export'
    			'columns' => [
	            	[
	            		'attribute' => 'ID',
	                    'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                    'value' => function($model) {
										return $model->id_contribuyente;
									},
	                ],
	                [
	                	'attribute' => 'RIF',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->contribuyente->naturaleza . '-' . $model->contribuyente->cedula . '-' . $model->contribuyente->tipo;
									},
	                ],
	                [
	                	'attribute' => 'Razon Social',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->contribuyente->razon_social;
									},
	                ],
	                [
	                	'attribute' => 'Domicilio',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->contribuyente->domicilio_fiscal;
									},
	                ],
	                [
	                	'attribute' => 'Telefono(s)',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->contribuyente->tlf_ofic . ' / ' . $model->contribuyente->tlf_ofic_otro . ' / ' . $model->contribuyente->tlf_celular;
									},
	                ],
	                [
	                	'attribute' => 'Correo Electronico',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										return $model->contribuyente->email;
									},
	                ],
	                [
	                	'attribute' => 'Causa de No Emision',
	                	'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
	                	'format' => 'raw',
	                    'value' => function($model) {
										$nota = '';
				                    	$fuente = json_decode($model->observacion, true);
	                    				if ( count($fuente) > 0 ) {
	                    					foreach ( $fuente as $key => $obs ) {
	                    						$nota .= $obs . ' / ';
	                    					}
	                    				}
										return $nota;
									},
	                ],

	        	]
			]);
		}

	}

?>