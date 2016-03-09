<?php
	namespace common\models\configuracion\solicitud;

	use Yii;
	use backend\models\configuracion\solicitud\ConfigurarSolicitud;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\Impuesto;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumento;
	use backend\models\configuracion\detallesolicitud\SolicitudDetalle;

	/**
	*
	*/
	class ParametroSolicitud
	{

		protected $idConfigSolicitud;
		protected $tipoSolicitud;
		protected $impuesto;

		public $configSolicitud;





		public function __construct($idConfig = 0)
		{
			$this->configurar($idConfig);
		}


		/**
		* Busca y configura la variable $config, con los datos de la entidad
		* "config-solicitudes", creando un modelo de dicha entidad.
		* @param $idConfig, long que identifica al registro de la entidad
		* "config-solicitudes".
		*/
		private function configurar($idConfig = 0)
		{
			$this->setIdConfig($idConfig);
			$config = ConfigurarSolicitud::findOne($this->getIdConfig());
			$this->setConfigSolicitud($config);
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
			$config = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
														  'inactivo' => 0
														 ])
			                                     ->with('tipoSolicitud')->asArray()->all();
			return $config;
		}


		/**
		* Se obtiene la descripcion del tipo de solcitud.
		*/
		public function getDescripcionTipoSolicitud()
		{
			$config = $this->findConfigurarSolicitud();
			return $config[0]['tipoSolicitud']['descripcion'];
		}



		/**
		* Metodo que realiza la busqueda de los registros utilizando inner join entre las entidades
		* "config-solicitudes" e "impuestos". Donde la relacion can la entidad "impuestos" se realiza
		* a traves de la clase ConfigurarSolicitud con el metodo getImpuestoSolicitud.
		*/
		public function findConfiguracionSolicitudImpuesto()
		{
			$configImpuesto = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																  'inactivo' => 0
																 ])
			                        				     ->with('impuestoSolicitud')->asArray()->all();
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




		/***/
		public function findConfiguracionSolicitudDetalle()
		{
			$configDetalle = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig(),
																  'inactivo' => 0
																 ])
			                        				     ->with('detalleSolicitud')->asArray()->all();
			return $configDetalle;
		}




		/***/
		public function findConfiguracionDetalleProceso()
		{
			$config = SolicitudDetalle::find()->where([])
			return $config;
		}


	}



 ?>