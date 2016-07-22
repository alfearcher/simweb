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
 *  @file ParametroSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13/05/2016
 *
 *  @class ParametroSolicitud
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *
 *  @inherits
 *
 */



	namespace common\models\configuracion\solicitud;

	use Yii;
	use backend\models\configuracion\solicitud\ConfigurarSolicitud;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\Impuesto;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumento;
	use backend\models\configuracion\detallesolicitud\SolicitudDetalle;
	use backend\models\configuracion\tasasolicitud\TasaMultaSolicitud;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;


	/**
	*
	*/
	class ParametroSolicitud
	{

		protected $idConfigSolicitud;
		protected $tipoSolicitud;
		protected $impuesto;

		public $configSolicitud;




		/***/
		public function __construct($idConfig)
		{
			$this->configurar($idConfig);
		}


		/**
		* Busca y configura la variable $config, con los datos de la entidad
		* "config-solicitudes", creando un modelo de dicha entidad.
		* @param $idConfig, long que identifica al registro de la entidad
		* "config-solicitudes".
		*/
		private function configurar($idConfig)
		{
			if ( $idConfig > 0 ) {
				$this->setIdConfig($idConfig);
				$config = ConfigurarSolicitud::findOne($this->getIdConfig());
				$this->setConfigSolicitud($config);
			}
		}



		/**
		* Returna los datos principales de la entidad "config-solicitudes".
		* @return model de configuracion de la solicitud especifica. Especificada
		* por la variables $this->configSolicitud.
		*/
		public function getConfigSolicitud()
		{
			return $this->configSolicitud;
		}


		/**
		* Setea la variable con los datos principales de la configuracion de la
		* solicitud, determinada por la entidad "config-solicitudes".
		* $config, es un modelo de la entidad config-solicitudes.
		* @param $config, variable que contiene el modelo de la entidad "config-solicitudes".
		*/
		public function setConfigSolicitud($config)
		{
			$this->configSolicitud = $config;
		}



		/**
		* identificador de la entidad "config-solicitudes".
		* Es decir de la configuracion de la solicitud.
		*/
		public function getIdConfig()
		{
			return $this->idConfigSolicitud;
		}


		/**
		* Setea la variable $this->idConfigSolicitud con el identificador de la misma.
		*/
		public function setIdConfig($idConfig)
		{
			$this->idConfigSolicitud = $idConfig;
		}



		/**
		* Metodo que retorna un array de valores segun parametros especificos.
		* @param array $arrayParametros, arreglo de campos de la tabla config_solicitudes
		* @return un arreglo donde los indices del arreglo son los campos de la tabla
		* y el valor del elemento corresponde al valor en la tabla del campo.
		*/
		public function getParametroSolicitud($arrayParametros = [])
		{
			$parametros = null;
			if ( count($arrayParametros) > 0 ) {
				$config = $this->getConfigSolicitud();
				foreach ( $arrayParametros as $key => $value ) {
					if ( isset($config[$value]) ) {
						$parametros[$value] = $config[$value];
					}
				}
			}
			return $parametros;
		}




		/**
		* Metodo que realiza una busqueda de los registros realizando un inner join
		* entre config-solicitudes y config-tipos-solicitudes.
		* "tipoSolicitud, es un metodo (getTipoSolicitud) de la clase ConfigurarSolicitud"
		* para realizar la referencia a dicho metodo se utiliza el nombre del metodo
		* sin el "get" y colocando el nombre en miniscula, si el nombre del metodo es
		* la combinacion de dos o más palabras, se coloca la primera letra en minisculas
		* y otras primeras letras en mayuscula.
		* La relacion de ambas tablas se realiza se denomina "tipoSolicitud".
		* @return $config, array con los campos ambas tablas.
		*/
		public function findConfiguracionSolicitudTipo()
		{
			$config = null;

			$config = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
														  'inactivo' => 0
														 ])
			                                     ->with('tipoSolicitud')
			                                     ->asArray()
			                                     ->all();
			return $config;
		}


		/**
		* Se obtiene la descripcion del tipo de solcitud.
		*/
		public function getDescripcionTipoSolicitud()
		{
			$config = $this->findConfiguracionSolicitudTipo();
			return $config[0]['tipoSolicitud']['descripcion'];
		}



		/**
		* Metodo que realiza la busqueda de los registros utilizando inner join entre las entidades
		* "config-solicitudes" e "impuestos". Donde la relacion can la entidad "impuestos" se realiza
		* a traves de la clase ConfigurarSolicitud con el metodo getImpuestoSolicitud.
		*/
		public function findConfiguracionSolicitudImpuesto()
		{
			$configImpuesto = null;

			$configImpuesto = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																  'inactivo' => 0
																 ])
			                        				     ->with('impuestoSolicitud')
			                        				     ->asArray()
			                        				     ->all();
			return $configImpuesto;
		}



		/**
		* Descripcion del impuesto de la configuracion de la socilictud.
		*/
		public function getDescripcionImpuestoSolcitud()
		{
			$configImpuesto = $this->findConfiguracionSolicitudImpuesto();
			return $configImpuesto[0]['impuestoSolicitud']['descripcion'];
		}




		/**
		 * Metodo que busca los registros relacionados entre las entidades "config-solicitudes"
		 * y "config-solic-detalles". la relacion realiza un LEFT JOIN entre las entidades y retorna
		 * un array con todos los campos de ambas tablas.
		 * @return array de campos con todas los campos de ambas entidades.
		 */
		public function findConfiguracionSolicitudDetalle()
		{
			$configDetalle = null;

			$configDetalle = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																 'config_solic_detalles.inactivo' => 0])
			                        				    ->with('detalleSolicitud')
			                        				    ->asArray()
			                        				    ->all();
			return $configDetalle;
		}





		/**
		 * Metodo que busca los registros relacionados entre las entidades "config-solic-detalles"
		 * y "config-solicitud-procesos". La relacion realiza un LEFT JOIN entre ambas entidades,
		 * y los registros resulatante llegan como un arreglo de datos.
		 * @return array de datos con todas las columnas de ambas entidades.
		 */
		public function findConfiguracionDetalleProceso()
		{
			$configDetalleProceso = null;

			$configDetalleProceso = SolicitudDetalle::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																     'config_solic_detalles.inactivo' => 0])
															->joinWith('procesoSolicitud')
															->asArray()
															->all();

			return $configDetalleProceso;
		}



		/**
		 * Metodo que obtiene una lista de los procesos que genera la solicitud.
		 * @return Array de datos con los campos de la entidad "config-solicitud-procesos".
		 */
		public function getProcesoQueGeneraSolicitud()
		{
			$proceso = null;
			// Array de los procesos que genera la solicitud.
			$configProcesos = $this->findConfiguracionDetalleProceso();

			foreach ( $configProcesos as $procesos ) {
				$proceso[] = $procesos['procesoSolicitud'];
			}

			return $proceso;
		}




		/**
		 * Metodo que genera un array con los proceso que genera la solicitud
		 * por eventos. Al momento de configurar la solicitud, se define que
		 * evento se va a relacionar a cada proceso, un evento puede tener
		 * asociado varios procesos.
		 * @param  string $evento los eventos que puede afectar a una solicitud:
		 * - CREAR
		 * - APROBAR
		 * - NEGAR
		 * @return Array donde el indice del arreglo es un evento y los elementos
		 * del arreglo son los procesos asociados al evento.
		 */
		public function getProcesoSegunEvento($evento = '')
		{
			// Array de los procesos que genera la solicitud.
			$configProcesos = self::findConfiguracionDetalleProceso();

			// Los diferentes eventos que puede suceder en una solicitud.
			$eventos = Yii::$app->solicitud->eventos();

			if ( trim($evento) == '' ) {
				foreach ( $eventos as $key => $value ) {
					foreach ( $configProcesos as $procesos ) {
						if ( $procesos['ejecutar_en'] == $value ) {
							$listaProcesos[$procesos['id_proceso']] = $procesos['procesoSolicitud']['descripcion'];
						}
					}
					$lista[$value] = isset($listaProcesos) ? $listaProcesos : null;
					$listaProcesos = null;
				}
			} else {
				foreach ( $configProcesos as $procesos ) {
					if ( $procesos['ejecutar_en'] == $evento ) {
						$listaProcesos[$procesos['id_proceso']] = $procesos['procesoSolicitud']['descripcion'];
					}
				}
				$lista[$evento] = isset($listaProcesos) ? $listaProcesos : null;
				$listaProcesos = null;
			}
			return $lista;
		}



		/**
		 * Metodo que busca los registros relacionados a las entidades "config-solic-documentos"
		 * y "config-documentos-requisitos". En la extension "documentoRequisito" se pueden obtener
		 * los documentos que se deben consignar en la solicitud. A traves de la siguiente nomeclatura
		 * $configSolicitudDoc['documentoRequiisto'].
		 * @return array retorna una arreglo de todos los campos de ambas entidades. Segun
		 * el valor del id-config-solicitud.
		 */
		public function findConfiguracionSolicitudDocumento()
		{
			$configSolicitudDoc = null;

			$configSolicitudDoc = SolicitudDocumento::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																     'config_solic_documentos.inactivo' => 0])
															->joinWith('documentoRequisito')
															->asArray()
															->all();

			return $configSolicitudDoc;
		}




		/**
		 * Metodo que genera un arreglo con los documentos y/o requisitos que son propios
		 * de la solicitud.
		 * @return Array Lista con todos los campos de la entidad "config-documentos-requisitos".
		 */
		public function getDocumentoRequisitoSolicitud()
		{
			$documento = null;

			$configDocumento = $this->findConfiguracionSolicitudDocumento();

			foreach ( $configDocumento as $documentos ) {
				$documento[] = $documentos['documentoRequisito']['descripcion'];
			}

			return $documento;
		}




		/**
		 * Metodo que busca los registros relacionados entre las entidades "config-solicitudes"
		 * y "niveles-aprobacion". La relacion realiza un LEFT JOIN entre ambas entidades,
		 * y los registros resulatante llegan como un arreglo de datos.
		 * @return array de datos con todas las columnas de ambas entidades.
		 */
		public function findConfiguracionNivelAprobacion()
		{
			$configAprobacion = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																  'inactivo' => 0
																 ])
			                        				       ->with('nivelAprobacion')
			                        				       ->asArray()
			                        				       ->all();
			return $configAprobacion;
		}




		/**
		 * Metodo que permite obtener la descripcion del nivel de aprobacion.
		 * @return String Descripcion del nivel de aprobacion.
		 */
		public function getNivelAprobacionSegunSolicitud()
		{
			$nivel = $this->findConfiguracionNivelAprobacion();
			return $nivel[0]['nivelAprobacion']['descripcion'];
		}





		/***/
		public function findDetalleSolicitudTasaMulta($evento)
		{
			$modelDetalleSolicitudTasa = null;

			$modelDetalleSolicitudTasa = TasaMultaSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																         SolicitudDetalle::tableName().'.inactivo' => 0,
																         TasaMultaSolicitud::tableName().'.inactivo' => 0,
																         'ejecutar_en' => $evento,
																	])
																 ->joinWith('detalleSolicitud', false)
																 ->asArray()
																 ->all();
			return $modelDetalleSolicitudTasa;
		}




		/***/
		public function getDetalleSolicitudTasaMulta($evento)
		{
			$tasa = $this->findDetalleSolicitudTasaMulta($evento);
			return $tasa;
		}



	}



 ?>