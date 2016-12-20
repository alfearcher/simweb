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
 *  @file SolvenciaVehiculoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class SolvenciaVehiculoSearch
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

	namespace backend\models\vehiculo\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use backend\models\vehiculo\solvencia\SolvenciaVehiculo;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRif;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocial;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;
	use yii\helpers\ArrayHelper;
	use common\models\deuda\Solvente;
	use common\models\pago\PagoSearch;
	use backend\models\vehiculo\VehiculosForm;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;


	/**
	* Clase donde se controla la politica de negocio para realizar la solicitudes
	* de solvencias de Vehiculos.
	*/
	class SolvenciaVehiculoSearch extends SolvenciaVehiculo
	{
		private $_id_contribuyente;
		private $_id_impuesto;
		private $_licencia;
		private $_tipo_naturaleza;		// Descripcion del tipo de naturaleza
										// tipo = 0 => NATURAL
										// tipo = 1 => JURIDICO.

		const IMPUESTO = 3;

		/**
		 * Metodo constructor de la clase
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente, $idImpuesto = 0)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_id_impuesto = $idImpuesto;
			$this->_tipo_naturaleza = ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($idContribuyente);
		}



		/**
		 * Metodo que permite realizar la consulta por numero de solicitud.
		 * @param  integer $nroSolicitud numero de la solicitud
		 * @return active record.
		 */
		public function findSolicitudSolvencia($nroSolicitud = [])
		{
			$findModel = self::findSolicitudSolvenciaVehiculoModel();
			$model = $findModel->andWhere(['IN', 'nro_solicitud', $nroSolicitud]);

			return $model;
		}



		/**
		 * Metodo que permite obtener el modelo general de ocnsulta de los vehiculos
		 * @return VehiculoForm
		 */
		private function findVehiculoGeneralModel()
		{
			return VehiculosForm::find()->alias('V')
									   ->where('V.id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->_id_contribuyente]);
		}



		/**
		 * Metodo que retorna el modelo de consulta de los vehiculos activos.
		 * En la consulta se obtienen los atributos que se encuentran en el
		 * select.
		 * @return VehiculoForm.
		 */
		public function findVehiculoActivoContribuyente($chkIdImpuesto = [])
		{
			$model = null;

			if ( $this->_id_contribuyente > 0 ) {
				// Se obtiene modelo general de consulta.
				$findModel = self::findVehiculoGeneralModel();
				if ( count($chkIdImpuesto) == 0 ) {

					$model = $findModel->select([
											'id_vehiculo as id_impuesto',
											'V.id_contribuyente',
											'placa',
											'modelo',
											'marca',
											'color',
											'status_vehiculo',

										])
										->andWhere('status_vehiculo =:status_vehiculo',
														[':status_vehiculo' => 0])
										->orderBy([
											'placa' => SORT_ASC,
										])
										->asArray()
										->all();
				} else {

					$model = $findModel->select([
											'id_vehiculo as id_impuesto',
											'V.id_contribuyente',
											'placa',
											'modelo',
											'marca',
											'color',
											'status_vehiculo',

										])
										->andWhere('status_vehiculo =:status_vehiculo',
														[':status_vehiculo' => 0])
										->andWhere(['IN', 'id_vehiculo', $chkIdImpuesto])
										->orderBy([
											'placa' => SORT_ASC,
										])
										->asArray()
										->all();

				}
			}

			return $model;
		}



		/**
		 * Metodo que crea un provider de datos, sobre los vehiculos existentes
		 * del contribuyente. Para el provider se utiliza un ArrayDataProvider.
		 * @return ArrayDataProvider
		 */
		public function getDataProviderVehiculo($chkIdImpuesto = [])
		{
			$provider = null;
			$data = [];

			// Lista de los veihiculos activos que presenta el contribuyente.
			$vehiculos = self::findVehiculoActivoContribuyente($chkIdImpuesto);

			if ( count($vehiculos) > 0 ) {
				foreach ( $vehiculos as $vehiculo ) {
					$condicion = '';
					$this->_id_impuesto = $vehiculo['id_impuesto'];
					$condicion = [];
					$bloquear = 0;
					$m = '';

					// Aqui se debe colocar las condiciones que un objeto no debe presentar.
					$condicion = self::validarCondicionVehiculo(date('Y'));
					if ( count($condicion) > 0 ) {
						$bloquear = 1;
					}

					$ultimoPago = self::getDescripcionUltimoPago();

					$data[$vehiculo['id_impuesto']] = [
						'id_impuesto' => $vehiculo['id_impuesto'],
						'id_contribuyente' => $vehiculo['id_contribuyente'],
						'descripcion' => $vehiculo['placa'],
						'marca' => $vehiculo['marca'],
						'modelo' => $vehiculo['modelo'],
						'color' => $vehiculo['color'],
						'inactivo' => $vehiculo['status_vehiculo'],
						'ultimoPago' => $ultimoPago,
						//'condicion' => $m,
						'condicion' => ( count($condicion) > 0 ) ? $condicion : [],
						'bloquear' => $bloquear,
					];
				}

				$provider = New ArrayDataProvider([
								'key'=>'id_impuesto',
								'allModels' => $data,
								'pagination' => false,
				]);
			}
			return $provider;

		}



		/**
		 * Metodo que devuelve el modelo base de consulta de la clase.
		 * @return active record
		 */
		public function findSolicitudSolvenciaVehiculoModel()
		{
			return SolvenciaVehiculo::find()->alias('S')
									        ->where('S.id_contribuyente =:id_contribuyente',
															[':id_contribuyente' => $this->_id_contribuyente])
											->andWhere('impuesto =:impuesto',
											 				[':impuesto' => self::IMPUESTO]);
		}



		/**
		 * Metodo que permite ejecutar una serie de metodos que controlan la existencia de algunas
		 * solicitudes que puedan chocar con la solicitud de emision de solvencia. Si encuentra una
		 * solicitud pendiente que choque con la de emnision de solvencia creara un mensaje que se
		 * guardara en un arreglo.
		 * @param  integer $añoImpositivo año impositivo donde se desea crear la solvencia.
		 * @return array retorna un arreglo de mensaje, o en su defecto un arreglo vacio.
		 */
		public function validarEvento($añoImpositivo)
		{
			$mensaje = [];
			$result = false;

			// Se determina si tiene numero de licencia asignada. Esto es si el atributo
			// "id-sim" de la entidad "contribuyentes" es diferente a null o si la longitud
			// de dicho valor del atributo es mayor a 1.
			if ( $this->_tipo_naturaleza == 'JURIDICO' ) {
				if ( !self::poseeLicencia() ) {
					$mensaje[] = Yii::t('frontend', 'El contribuyente no posee un Nro. de Licencia');
				}
			}


			if ( $this->_tipo_naturaleza == 'JURIDICO' ) {
				// Se determina si tiene una solicitud de Cambio de Razon Social.
				$findModel = self::findSolicitudCambioRazonSocial();
				if ( count($findModel) > 0 ) {
					$model = $findModel->all();

					foreach ( $model as $mod ) {
						$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
						$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
					}
				}
			}



			if ( $this->_tipo_naturaleza == 'JURIDICO' ) {
				// Se determina si tiene una solicitud de cambio de rif pendiente.
				$findModel = self::findSolicitudCambioRifPendienta();
				if ( count($findModel) > 0 ) {
					$model = $findModel->all();

					foreach ( $model as $mod ) {
						$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
						$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
					}
				}
			}



			// Se determina si tiene una solicitud de cambio de domicilio pendiente.
			$findModel = self::findSolicitudCambioDomicilioPendienta();
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				if ( count($model) > 0 ) {
					foreach ( $model as $mod ) {
						$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
						$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
					}
				}
			}


			if ( $this->_tipo_naturaleza == 'JURIDICO' ) {

				$año = (int)self::determinarInicioActividades();

				if ( $año == 0 ) {
					$mensajes[] = Yii::t('frontend', 'No se pudo determinar el año inicio de actividades del contribuyente');

				} elseif ( $año == (int)date('Y') && (int)$añoImpositivo == (int)date('Y') ) {

					// Para aquellos contribuyentes que soliciten una solvencia por primera vez y que su
					// Actividad Economica haya comenzado el año actual se exige que esten inscripto como
					// contribuyentes de actividad economica.
					$result = self::estaInscritoActividadEconomica();
					if ( !$result ) {
						$mensaje[] = Yii::t('frontend', 'No esta inscripto como contribuyente de Actividad Economica');
					}

				}
			}

			return $mensaje;
		}



		/**
		 * Metodo que realiza una busqueda para determinar si esta inscrito como contribuyente
		 * de Actividad Economica.
		 * @return boolean retorna true si ya esta inscrito, false en caso contrario.
		 */
		public function estaInscritoActividadEconomica() {
			$result      = false;
			$inscripcion = New InscripcionActividadEconomicaSearch($this->_id_contribuyente);
			$result      = $inscripcion->yaEstaInscritoActividadEconomica();
			return $result;
		}




		/***/
		public function validarCondicionVehiculo($añoImpositivo)
		{
			$mensaje = [];

			// Se determina si tiene una solicitud pendiente similar para emision de licencia.
			$result = self::yaPoseeSolicitudSimiliarPendiente($añoImpositivo);
			if ( $result ) {
				$mensaje[] = Yii::t('frontend', 'Ya posee una solicitud similar pendiente');
			}

			return $mensaje;
		}




		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($añoImpositivo)
		{
			$findModel = self::findSolicitudSolvenciaVehiculoModel();
			$model = $findModel->andWhere('ano_impositivo =:ano_impositivo',
												[':ano_impositivo' => $añoImpositivo])
							   ->andWhere('estatus =:estatus',[':estatus' => 0])
							   ->andWhere('id_impuesto =:id_impuesto',
							   					[':id_impuesto' => $this->_id_impuesto])
							   ->count();

			return ( $model > 0 ) ? true : false;
		}



		/**
		 * Metodo que realice una conaulta para determinar si existe una solicitud
		 * pendiente para cambiar el rif.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudCambioRifPendienta()
		{
			$findModel = CorreccionCedulaRif::find()->alias('A')
			                              ->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    								  ->andWhere('estatus =:estatus',
	    											[':estatus' => 0])
	    								  ->orderBy([
	    									  'nro_solicitud' => SORT_ASC,
	    									]);

	    	return ( count($findModel) > 0 ) ? $findModel : [];
		}



		/**
		 * Metodo que realiza una consulta para determinar si existe una solictud pendiente
		 * de Modificacion del Noombre de la Razon Social.
		 * @return [active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudCambioRazonSocial()
		{
			$findModel = CorreccionRazonSocial::find()->alias('A')
			                              ->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    								  ->andWhere('estatus =:estatus',
	    											[':estatus' => 0])
	    								  ->orderBy([
	    									  'nro_solicitud' => SORT_ASC,
	    									]);

	    	return ( count($findModel) > 0 ) ? $findModel : [];
		}




		/**
		 * Metodo que realice una conaulta para determinar si existe una solicitud
		 * pendiente para cambiar el domicilio.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudCambioDomicilioPendienta()
		{
			$findModel = CorreccionDomicilioFiscal::find()->alias('A')
			                              ->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    								  ->andWhere('estatus =:estatus',
	    											[':estatus' => 0])
	    								  ->orderBy([
	    									  'nro_solicitud' => SORT_ASC,
	    									]);

	    	return ( count($findModel) > 0 ) ? $findModel : [];
		}



		/**
		 * Metodo que permite determinar el año de inicio de actividades de un contribuyente
		 * segun los registros que poseea en la entidad "act-econ". Para fortalecer la consulta
		 * se buscara la fecha de inicio que esta registrada en la entidad "contribuyentes".
		 * @return integer retorna el año en que inicio actividad.
		 */
		private function determinarInicioActividades()
		{
			$lista = self::getListaAnoRegistrado();
			$listaAño = array_values($lista);

			if ( count($listaAño) > 0 ) {
				return $listaAño[0];
			}

			return 0;
		}



		/**
	     * Metodo que retorna una lista arreglo donde el indice del arreglo es el
	     * identificador de la entidad "act-econ" y el valor del elemento es el
	     * año impositivo. Esto permitira crear un combo-lista.
	     * @return array retorna una arreglo, con el siguiente esquema:
	     * array {
	     * 		[ano_impositivo] => año
	     * }
	     */
	    public function getListaAnoRegistrado()
	    {
	    	$listaAño = [];
	    	$añoLimite = Yii::$app->lapso->anoLimiteNotificado();

	    	$findModel = ActEcon::find()->distinct('ano_impositivo')
	    								->where('id_contribuyente =:id_contribuyente',
	    	 										['id_contribuyente' => $this->_id_contribuyente])
	    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
	    							    ->andWhere('ano_impositivo >=:ano_impositivo',
	    							    			[':ano_impositivo' => $añoLimite])
	    							    ->joinWith('actividadDetalle', false, 'INNER JOIN')
	    							    ->orderBy([
	    							   		'ano_impositivo' => SORT_ASC,
	    							   	])
	    							    ->all();
	    	if ( count($findModel) > 0 ) {
	    		$listaAño = ArrayHelper::map($findModel, 'ano_impositivo', 'ano_impositivo');
	    	}

	    	return $listaAño;
	    }




	    /**
	     * Metodo que permimte obtener el identificador de la entidad "act-econ", segun el
	     * año impositivo. Solo se busca el registro valido.
	     * @param  integer $añoImpositivo año impositivo.
	     * @return integer retorna el entero que representa el identificador de la entidad
	     * "act-econ"
	     */
	    public function getIdImpuestoSegunAnoImpositivo($añoImpositivo)
	    {
	    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    							    ->andWhere('ano_impositivo =:ano_impositivo',
	    							    			[':ano_impositivo' => $añoImpositivo])
	    							    ->andWhere('estatus =:estatus', [':estatus' => 0])
	    							    ->limit(1)
	    							    ->one();
	    	return ( count($findModel) > 0 ) ? $findModel->id_impuesto : 0;
	    }



	    /**
	     * Metodo que realiza un find del contribuyente. Creando un modelo
	     * de la entidad respectiva.
	     * @return active record retorna un modelo de la entidad "contribuyentes".
	     */
	    public function findContribuyente()
	    {
	    	$findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
			return isset($findModel) ? $findModel : null;
	    }



	    /**
	     * Metodo que determina si un contribuyente tiene asignado un valor en el atributo
	     * "id-sim", el cual se utiliza para guardar el numero de licencia del contribuyente
	     * de Actividad Economica. Dicho atributo debe poseer una longitud mayor a 1 para
	     * considerarse un valor valido. Si es asi el metodo retornara true, de lo contrario
	     * false.
	     * @return boolean retorna true si encuentra un valor valido en el atributo "id-sim"
	     */
	    public function poseeLicencia()
	    {
	    	$result = false;
	    	$this->_licencia = '';
	    	$findModel = self::findContribuyente();
	    	if ( count($findModel) > 0 ) {
	    		if ( strlen($findModel->id_sim) > 1 ) {
	    			$result = true;
	    			$this->_licencia = $findModel->id_sim;
	    		}
	    	}

	    	return $result;
	    }




	    /**
	     * Metodo que permite tener acceso al valor del atributo "_licencia"
	     * @return string retorna un cadena de caracteres, digitos o ambos que indica
	     * el valor del atributo "_licencia"
	     */
	    public function getLicencia()
	    {
	    	return $this->_licencia;
	    }




	     /***/
	    public function getDataProviderSolicitud($nroSolicitud = [])
	    {
	    	$query = self::findSolicitudSolvencia($nroSolicitud);

	    	$dataProvider = new ActiveDataProvider([
            	'query' => $query,
        	]);
	    	$query->all();

        	return $dataProvider;
	    }




	    /**
	     * Metodo que devuelve la fecha de vencimiento
	     * @return string.
	     */
	    public function determinarFechaVctoSolvencia()
	    {
	    	$solvente = New Solvente();
	    	$solvente->setIdContribuyente($this->_id_contribuyente);
	    	$solvente->setImpuesto(self::IMPUESTO);
	    	$solvente->setIdImpuesto($this->_id_impuesto);

	    	$fechaVcto = $solvente->getFechaVctoSolvenciaObjeto();
	    	return $fechaVcto;
	    }



	    /***/
	    public function determinarLapsoVctoSolvencia()
	    {}



	    /***/
	    public function determinarUltimoPago()
	    {
	    	$searchPago = New PagoSearch();
	    	$searchPago->setIdContribuyente($this->_id_contribuyente);

	    	$ultimo = $searchPago->getUltimoLapsoPagoObjeto(self::IMPUESTO, $this->_id_impuesto);
	    	if ( count($ultimo) > 0 ) {
		    	return [
		    		'año' => $ultimo['ano_impositivo'],
		    		'periodo' => $ultimo['trimestre'],
		    		'exigibilidad' => $ultimo['exigibilidad']['unidad'],
		    	];
		    }
		    return null;
	    }



	    /***/
	    public function getDescripcionUltimoPago()
	    {
	    	$result = '';
	    	$ultimo = self::determinarUltimoPago();
	    	if ( $ultimo !== null ) {
	    		$result = $ultimo['año'] . ' - ' . $ultimo['periodo'] . ' - ' . $ultimo['exigibilidad'];
	    	}
	    	return $result;
	    }




	    /***/
	    public function getEstaSolvente()
	    {
	    	$solvente = New Solvente();
	    	//$solvente->setIdContribuyente($this->_id_contribuyente);

	    	$solvente->setImpuesto(self::IMPUESTO);
	    	$solvente->setIdImpuesto($this->_id_impuesto);

	    	return $solvente->determinarSolvencia();
	    }


	}

?>