<?php
	namespace common\models\configuracion\solicitud;

	use Yii;
	use backend\models\configuracion\solicitud\ConfigurarSolicitud;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	/**
	*
	*/
	class ParametroSolicitud
	{

		protected $idConfigSolicitud;
		protected $tipoSolicitud;
		protected $impuesto;

		public $configSolicitud;

		public function __construct()
		{}


		/***/
		public  function configurar($idConfig = 0)
		{

			$this->setIdConfig($idConfig);
			$config = ConfigurarSolicitud::findOne($this->getIdConfig());
			$this->setConfigSolicitud($config);

		}



		/***/
		public function getConfigSolicitud()
		{
			return $this->configSolicitud;
		}


		public function setConfigSolicitud($config)
		{
			$this->configSolicitud = $config;
		}



		/***/
		public function getIdConfig()
		{
			return $this->idConfigSolicitud;
		}

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



		public function getTipoSolicitud()
		{
			$config = New ConfigurarSolicitud();
			return $config->getDescripcionTipoSolicitud();
		}



		public function findConfigurarSolicitud()
		{
			$config = ConfigurarSolicitud::find()->where(['id_config_solicitud' => $this->getIdConfig()])
			                                     ->with('tipoSolicitud')->one();
			return $config;
		}
	}



 ?>