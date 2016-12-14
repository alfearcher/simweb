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
 *  @file SolvenciaActividadEconomicaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class SolvenciaActividadEconomicaSearch
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

	namespace backend\models\aaee\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\solvencia\SolvenciaActividadEconomica;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRif;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocial;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;
	use backend\models\aaee\actecon\ActEcon;
	use yii\helpers\ArrayHelper;
	use common\models\deuda\Solvente;
	use common\models\pago\PagoSearch;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;


	/**
	* Clase donde se controla la politica de negocio para realizar la solicitudes
	* de solvencias de Actividad Economicas.
	*/
	class SolvenciaActividadEconomicaSearch extends SolvenciaActividadEconomica
	{
		private $_id_contribuyente;
		private $_licencia;



		/**
		 * Metodo constructor de la clase
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_licencia = '';
		}



		/**
		 * Metodo que permite realizar la consulta por numero de solicitud.
		 * @param  integer $nroSolicitud numero de la solicitud
		 * @return active record.
		 */
		public function findSolicitudSolvencia($nroSolicitud)
		{
			$findModel = self::findSolicitudSolvenciaActEconomicaModel();
			$model = $findModel->andWhere('nro_solicitud =:nro_solicitud',
												[':nro_solicitud' => $nroSolicitud]);

			return $model;
		}



		/**
		 * Metodo que devuelve el modelo base de consulta de la clase.
		 * @return active record
		 */
		public function findSolicitudSolvenciaActEconomicaModel()
		{
			return SolvenciaActividadEconomica::find()->where('id_contribuyente =:id_contribuyente',
																	[':id_contribuyente' => $this->_id_contribuyente])
													  ->andWhere('impuesto =:impuesto', [':impuesto' => 1]);
		}



		/**
		 * Metodo que permite ejecutar una serie de metodos que controlan la existencia de algunas
		 * solicitudes que puedan chocar con la solicitud de emision de licencia. Si encuentra una
		 * solicitud pendiente que choque con la de emnision de licencia creara un mensaje que se
		 * guardara en un arreglo.
		 * @param  integer $añoImpositivo año impositivo donde se desea crear la licencia.
		 * @return array retorna un arreglo de mensaje, o en su defecto un arreglo vacio.
		 */
		public function validarEvento($añoImpositivo)
		{
			$mensaje = [];
			$result = false;

			// Se determina si tiene una solicitud pendiente similar para emision de licencia.
			$result = self::yaPoseeSolicitudSimiliarPendiente($añoImpositivo);
			if ( $result ) {
				$mensaje[] = Yii::t('frontend', 'Ya posee una solicitud similar pendiente');
			}

			// Se determina si tiene numero de licencia asignada. Esto es si el atributo
			// "id-sim" de la entidad "contribuyentes" es diferente a null o si la longitud
			// de dicho valor del atributo es mayor a 1.
			if ( !self::poseeLicencia() ) {
				$mensaje[] = Yii::t('frontend', 'El contribuyente no posee un Nro. de Licencia');
			}


			// Se determina si tiene una solicitud de Cambio de Razon Social.
			$findModel = self::findSolicitudCambioRazonSocial();
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
				}
			}


			// Se determina si tiene una solicitud de cambio de rif pendiente.
			$findModel = self::findSolicitudCambioRifPendienta();
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
				}
			}


			// Se determina si tiene una solicitud de cambio de domicilio pendiente.
			$findModel = self::findSolicitudCambioDomicilioPendienta();
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();
				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion;
				}
			}


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

			return $mensaje;
		}



		/**
		 * Metodo que realiza una busqueda para determinar si esta inscrito como contribuyente
		 * de Actividad Economica.
		 * @return boolean retorna true si ya esta inscrito, false en caso contrario.
		 */
		public function estaInscritoActividadEconomica()
		{
			$result = false;
			$inscripcion = New InscripcionActividadEconomicaSearch($this->_id_contribuyente);
			$result = $inscripcion->yaEstaInscritoActividadEconomica();
			return $result;
		}





		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($añoImpositivo)
		{
			$findModel = self::findSolicitudSolvenciaActEconomicaModel();
			$model = $findModel->andWhere('ano_impositivo =:ano_impositivo',
												[':ano_impositivo' => $añoImpositivo])
							   ->andWhere('estatus =:estatus',[':estatus' => 0])
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
	    public function getDataProviderSolicitud($nroSolicitud)
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
	    	$fechaVcto = $solvente->getFechaVctoSolvenciaActividadEconomica();
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
	    	$ultimo = $searchPago->getUltimoLapsoPagoActividadEconomica();
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
	    	$solvente->setIdContribuyente($this->_id_contribuyente);

	    	return $solvente->estaSolventeActividadEconomica();
	    }


	}

?>