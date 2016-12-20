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
 *  @file LicenciaSolicitudSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @class LicenciaSolicitudSearch
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

 	namespace backend\models\aaee\licencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\aaee\Sucursal;

	use backend\models\aaee\anexoramo\AnexoRamo;
	use backend\models\aaee\desincorporaramo\DesincorporarRamo;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;
	use backend\models\aaee\correccioncapital\CorreccionCapital;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRif;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocial;
	use backend\models\aaee\correccionreplegal\CorreccionRepresentanteLegal;

	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\aaee\rubro\RubroForm;
	use backend\models\aaee\rubro\Rubro;
	use yii\helpers\ArrayHelper;

	/**
	 * Clase que gestiona el funcionamiento de la solicitud para la emision de
	 * las licencias de Actividades Economicas.
	 */
	class LicenciaSolicitudSearch extends LicenciaSolicitud
	{

		private $_id_contribuyente;


		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 * Valor unico dentro de la entidad correspondiente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}



		/**
		 * Metodo que realiza una busqueda de la solicitud por concepto de emision de licencia.
		 * El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudLicencia($nroSolicitud)
		{
			$findModel = LicenciaSolicitud::find()->where('nro_solicitud =:nro_solicitud',
													 		[':nro_solicitud' => $nroSolicitud])
										           ->andWhere('id_contribuyente =:id_contribuyente',
											   				[':id_contribuyente' => $this->_id_contribuyente]);

			return isset($findModel) ? $findModel : null;
		}



		/**
		 * Metodo que realice una conaulta para determinar si existe una solicitud
		 * pendiente para anexar un ramo segun el año-periodo indicado.
		 * @param  integer $añoImpositivo año del lapso que se desea consultar.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudAnexoRamoPendienta($añoImpositivo)
		{
			$findModel = AnexoRamo::find()->alias('A')
			                              ->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    								  ->andWhere('estatus =:estatus',
	    											[':estatus' => 0])
	    								  ->andWhere('A.ano_impositivo =:ano_impositivo',
	    									  		[':ano_impositivo' => $añoImpositivo])
	    								  ->orderBy([
	    									  'nro_solicitud' => SORT_ASC,
	    									]);

	    	return ( count($findModel) > 0 ) ? $findModel : [];
		}



		/**
		 * Metodo que realice una conaulta para determinar si existe una solicitud
		 * pendiente para desincorporar un ramo segun el año-periodo indicado.
		 * @param  integer $añoImpositivo año del lapso que se desea consultar.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudDesincorporacionRamoPendienta($añoImpositivo)
		{
			$findModel = DesincorporarRamo::find()->alias('A')
			                              ->where('id_contribuyente =:id_contribuyente',
	    												[':id_contribuyente' => $this->_id_contribuyente])
	    								  ->andWhere('estatus =:estatus',
	    												[':estatus' => 0])
	    								  ->andWhere('A.ano_impositivo =:ano_impositivo',
	    									  			[':ano_impositivo' => $añoImpositivo])
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
		 * Metodo que realice una consulta para determinar si existe una solicitud
		 * pendiente para cambiar el representante legal.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En caso contrario un arreglo vacio.
		 */
		public function findSolicitudCambioRepresentantePendienta()
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
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($añoImpositivo)
		{
			$modelFind = null;
			$modelFind = LicenciaSolicitud::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $this->_id_contribuyente])
										  ->andWhere('ano_impositivo =:ano_impositivo',
										  						[':ano_impositivo' => $añoImpositivo])
										  ->andWhere(['IN', 'estatus', [0]])
										  ->count();
			return ( $modelFind > 0 ) ? true : false;
		}




		/**
		 * Metodoq ue permite determinar si existe rubros para el año indicado.
		 * @param  integer $añoImpositivo año impositivo
		 * @return boolean retorna true si encuentra rubro, de lo contrario false.
		 */
		public function existeRubro($añoImpositivo)
		{
			$findModel = ActEconIngreso::find()->alias('I')
											   ->where('id_contribuyente =:id_contribuyente',
											   					[':id_contribuyente' => $this->_id_contribuyente])
											   ->andWhere('inactivo =:inactivo',[':inactivo' => 0])
											   ->andWhere('estatus =:estatus',[':estatus' => 0])
											   ->andWhere('ano_impositivo =:ano_impositivo',
											   					[':ano_impositivo' => $añoImpositivo])
											   ->joinWith('actividadEconomica', true, 'INNER JOIN')
											   ->count();

			if ( $findModel > 0 ) {
				return true;
			} else {
				return false;
			}

		}




		/**
		 * Metodo que permite ejecutar una serie de metodos que controlan la existencia de algunas
		 * solicitudes que puedan chocar con la solicitud de emision de licencia. Si encuentra una
		 * solicitud pendiente que choque con la de emnision de licencia creara un mensaje que se
		 * guardara en un arreglo.
		 * @param  integer $añoImpositivo año impositivo donde se desea crear la licencia.
		 * @return array retorna un arreglo de mensaje, o en su defecto un arreglo vacio.
		 */
		public function validarEvento($añoImpositivo, $tipoLicencia)
		{
			$mensaje = [];
			$result = false;


			// Se determina si existen rubros registrados para el año.
			$existe = self::existeRubro($añoImpositivo);
			if ( !$existe ) {
				$mensaje[] = Yii::t('frontend', 'No posee rubros registros para el año ' . $añoImpositivo);
			}


			// Se determina si tiene una solicitud pendiente similar para emision de licencia.
			$result = self::yaPoseeSolicitudSimiliarPendiente($añoImpositivo);
			if ( $result ) {
				$mensaje[] = Yii::t('frontend', 'Ya posee una solicitud similar pendiente');
			}


			// Se determina si tiene una solicitud de anexo de ramo pendiente.
			$findModel = self::findSolicitudAnexoRamoPendienta($añoImpositivo);
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion . ', rubro: ' . $mod->rubro->rubro . ' ' . $mod->rubro->descripcion;
				}
			}


			// Se determina si tiene una solicitud de desincorporacion de ramo pendiente.
			$findModel = self::findSolicitudDesincorporacionRamoPendienta($añoImpositivo);
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion . ', rubro: ' . $mod->rubro->rubro . ' ' . $mod->rubro->descripcion;
				}
			}


			// Se determina si tiene una solicitud de Cambio de Razon Social.
			$findModel = self::findSolicitudCambioRazonSocial();
			if ( count($findModel) > 0 ) {
				$model = $findModel->all();

				foreach ( $model as $mod ) {
					$tipo = $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
					$mensaje[] = 'La Solicitud Nro. ' . $mod['nro_solicitud'] . ' ('. $tipo .'), se encuentra ' . $mod->estatusSolicitud->descripcion . ', rubro: ' . $mod->rubro->rubro . ' ' . $mod->rubro->descripcion;
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


			// Se determina si tiene una solicitud de cambio de representante legal pendiente.
			$findModel = self::findSolicitudCambioRepresentantePendienta();
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


			$año = (int)self::determinarInicioActividades();

			if ( $año == 0 ) {
				$mensajes[] = Yii::t('frontend', 'No se pudo determinar el año inicio de actividades del contribuyente');

			} elseif ( $año == (int)date('Y') && $tipoLicencia == 'NUEVA' &&  (int)$añoImpositivo == (int)date('Y') ) {

				// Para aquellos contribuyentes que soliciten una licencia por primera vez y que su
				// Actividad Economica haya comenzado el año actual se exige que esten inscripto como
				// contribuyentes de actividad economica.
				$result = self::estaInscritoActividadEconomica();
				if ( !$result ) {
					$mensaje[] = Yii::t('frontend', 'No esta inscripto como contribuyente de Actividad Economica');
				}

			} elseif ( $año < (int)date('Y') && $tipoLicencia == 'NUEVA' &&  (int)$añoImpositivo == (int)date('Y') ) {
				$mensaje[] = Yii::t('frontend', 'El contribuyente no aplica para este tipo de Licencia. Año inicio ' . $año);

			} elseif ( $año == (int)date('Y') && $tipoLicencia == 'RENOVACION' &&  (int)$añoImpositivo == (int)date('Y') ) {
				$mensaje[] = Yii::t('frontend', 'El contribuyente no aplica para este tipo de Licencia. Año inicio ' . $año);

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
		 * Metodo que retorna el modelo general de consulta de la entidad "act-econ"
		 * @return active record.
		 */
		private function getActEconModel()
		{
			return $findModel = ActEcon::find()->alias('A')
											   ->where('id_contribuyente =:id_contribuyente',
											   				[':id_contribuyente' => $this->_id_contribuyente])
											   ->andWhere('estatus =:estatus',['estatus' => 0]);
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
		 * Metodo que permite determinar si un contribuyente es una sede principal.
		 * Esto solo aplica para los contribuyentes juridicos.
		 * @return boolean true si es verdadero, false en caso contrario.
		 */
		public function getSedePrincipal()
		{
			return ContribuyenteBase::getEsUnaSedePrincipal($this->_id_contribuyente);
		}




	    /***/
	    public function getDataProviderSolicitud($nroSolicitud)
	    {
	    	$query = self::findSolicitudLicencia($nroSolicitud);

	    	$dataProvider = new ActiveDataProvider([
            	'query' => $query,
        	]);
	    	$query->all();

        	return $dataProvider;
	    }





	    /**
	     * Metodo que permite obtener un dataProvider que permite generar un catalogo de los
	     * rubros según un año y paramatros adicionales.
	     * @param  integer $anoImpositivo [description]
	     * @param  string $params        [description]
	     * @return returna un a instancia de tipo dataProvider.
	     */
	    public function getDataProvider($anoImpositivo, $params = '', $exceptoIdRubro = [])
	    {
	    	return RubroForm::getDataProviderRubro($anoImpositivo, $params, $exceptoIdRubro);
	    }



	    /***/
	    public function getAnoSegunFecha($fecha)
	    {
	    	if ( isset($fecha) ) {
	    		if ( $fecha == '0000-00-00' || $fecha == null ) {
	    			return 0;
	    		} else {
	    			return date('Y', strtotime($fecha));
	    		}
	    	}
	    	return 0;
	    }



	    /***/
	    public function getRangoFechaDeclaracion($añoCatalogo)
	    {
	    	$rangoFecha = [];
	    	if ( strlen($añoCatalogo) == 4 ) {
	    		// if (is_integer($añoCatalogo) ) {
	    			$rangoFecha = [
	    				'fechaDesde' => $añoCatalogo . '-01-01',
	    				'fechaHasta' => $añoCatalogo . '-12-31'
	    			];
	    		// }
	    	}
	    	return $rangoFecha;
	    }



	    /***/
	    public function getExigibilidadDeclaracionSegunAnoImpositivo($añoCatalogo)
	    {
	    	$exigibilidad = OrdenanzaBase::getExigibilidadDeclaracion($añoCatalogo, 1);
	    	if ( count($exigibilidad) > 0 ) {
	    		return $exigibilidad['exigibilidad'];
	    	}
	    	return false;
	    }


	    /**
	     * Metodo que determina el identificador que corresponde segun el año impositivo
	     * para el rubro. Se utilizar el $idRubro para buscar los valores de ese registro
	     * y obtener el valor del rubro, este valor (rubro) se convina con el $añoImpositivo
	     * para realizar una busqueda añoImpositivo-rubro. Esta busqueda debe resultar en un
	     * registro donde el identificador del mismo es el valor buscado como idRubro del año
	     * impositivo.
	     * @param  long $idRubro identificador del rubro.
	     * @param  inetger $añoImpositivo año impositivo del catalogo de rubro que quiere consultar
	     * el $idRubro no deberia corresponder al del año impositivo.
	     * @return long retonra un identificador del rubro para el año impositivo $añoimpositivo.
	     */
	    public function getIdRubro($idRubro, $añoImpositivo)
	    {
	    	$idRubroEncontrado = 0;
	    	$findModelRubro = Rubro::findOne($idRubro);
	    	if ( isset($findModelRubro) ) {
	    		$rubro = $findModelRubro->rubro;
	    		$findModelNew = Rubro::find()->where('ano_impositivo =:ano_impositivo',
	    													[':ano_impositivo' => $añoImpositivo])
	    									 ->andWhere('rubro =:rubro', [':rubro' => $rubro])
	    									 ->andWhere('inactivo =:inactivo',['inactivo' => 0])
	    									 ->one();
	    		if ( isset($findModelNew) ) {
	    			$idRubroEncontrado = $findModelNew->id_rubro;
	    		}
	    	}
	    	return $idRubroEncontrado;
	    }




	    /**
	     * [getListaRubro description]
	     * @param  array $chkSeleccion arreglo de identificadores de la entidad rubros.
	     * @param  integer $añoImpositivo año fiscal al cual se desea obtener los identificadores.
	     * @return array retorna un arreglo de identificadores, en caso contrario una arreglo vacio.
	     */
	    public function getListaRubro($chkSeleccion, $añoImpositivo)
	    {
	    	$listaIdRubro = [];
	    	$id = 0;
	    	foreach ( $chkSeleccion as $key => $value ) {
	    		$id = self::getIdRubro($value, $añoImpositivo);
	    		if ( $id > 0 ) {
	    			$listaIdRubro[] = $id;
	    		} else {
	    			$listaIdRubro = null;
	    			break;
	    		}
	    	}
	    	return $listaIdRubro;
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
	    			  				   // ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
	    			  				   // ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
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
	     * Metodo que segun el año impositivo determina la exigibilidad de declaracion
	     * (cantidad de veces que se pueden declarar en un año), para generar una lista
	     * combo de numeros enteros que representan a cada periodo que se pueden solicitar
	     * un anexo de ramo.
	     * @param  inetger $añoImpositivo entero que especifica el año al cual se le anexara
	     * el ramo.
	     * @return array retorna una arreglo, con el siguiente esquema:
	     * array {
	     * 		[periodo] => periodo
	     * }
	     */
	    public function getListaPeriodo($añoImpositivo)
	    {
	    	$listaPeriodo = [];
	    	// Se refiere a la exigibilidad de la declaracion.
	    	$arreglo = OrdenanzaBase::getExigibilidadDeclaracion($añoImpositivo, 1);
	    	if ( count($arreglo) ) {
	    		$exigibilidad = $arreglo['exigibilidad'];
	    		for ( $i = 1; $i <= $exigibilidad ; $i++ ) {
	    			$listaPeriodo[$i] = $i;
	    		}
	    	}
	    	return $listaPeriodo;
	    }



	    /**
	     * Metodo que realiza una find sobre la entidad "ect-econ",
	     * utilizando el identificador de la entidad.
	     * @param  long $idImpuesto identificador de la entidad "act-econ".
	     * @return active record retorna un modelo de la entidad "ect-econ"
	     * con los datos de la consulta. Sino encuentra nada retorna null.
	     */
	    public function findActEcon($idImpuesto)
	    {
	    	$findModel = ActEcon::findOne($idImpuesto);
	    	return isset($findModel) ? $findModel : null;
	    }



	    /**
	     * Metodo que permite obtener los parametros de la exigibilidad, segun
	     * el id-impuesto enviado. El id-impuesto corresponde al año-periodo
	     * del al declaracion.
	     * @param  long $idImpuesto identificador de la entidad "act-econ".
	     * @return array retorna un arreglo con los atributos de la entidad
	     * "exigibilidades", sino el arreglo sera vacio.
	     */
	    public function getExigibilidadSegunIdImpuesto($idImpuesto)
	    {
	    	$exigibilidad = [];
	    	$findModel = self::findActEcon($idImpuesto);
	    	if ( isset($findModel) ) {
	    		$exigibilidad = OrdenanzaBase::getExigibilidadDeclaracion($findModel['ano_impositivo'], 1);
	    	}
	    	return $exigibilidad;
	    }



	    /**
	     * Metodo que permite obtener los parametros de la exigibilidad, segun el
	     * año impositivo enviado.
	     * @param  integer $añoImpositivo año fiscal en cuestion.
	     * @return array retorna un arreglo con los atributos de la entidad
	     * "exigibilidades", sino el arreglo sera vacio.
	     */
	    public function getExigibilidadSegunAnoImpositivo($añoImpositivo)
	    {
	    	$exigibilidad = [];
	    	if ( $añoImpositivo > 0 ) {
	    		$exigibilidad = OrdenanzaBase::getExigibilidadDeclaracion($añoImpositivo, 1);
	    	}
	    	return $exigibilidad;
	    }



	    /**
	     * Metodo que crea un vista tipo lista-combo para ser mostrada.
	     * @param  array $exigibilidad arreglo que contiene los atributos de la entidad
	     * respectiva.
	     * @return view retorna una lista de elementos peridod - descripcion.
	     */
	    public function getViewListaExigibilidad($exigibilidad)
	    {
	    	echo "<option> - </option>";
	    	if ( count($exigibilidad) > 0 ) {
	    		for ( $i = 1; $i <= (int)$exigibilidad['exigibilidad']; $i++) {
	    			 echo "<option value='" . $i . "'>" . $i . " - " . $exigibilidad['unidad'] . "</option>";
	    		}
	    	} else {
	    		echo "<option> - </option>";
	    	}
			return;
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
	     * Metodo que realiza una consulta para encontrar los ramos (rubros) registrados
	     * del contribuyente para un año-periodo especifico.
	     * @param  integer $añoImpositivo año impositivo del lapso
	     * @param  integer $periodo periodo del lapso.
	     * @return active record retorna un modelo de dicha consulta.
	     */
	    public function findRubrosRegistrados($añoImpositivo, $periodo)
	    {
	    	$findModel = ActEconIngreso::find()->alias('I')
	    	                                   ->where('id_contribuyente =:id_contribuyente',
	    													[':id_contribuyente' => $this->_id_contribuyente])
	    	                                   ->andwhere('A.ano_impositivo =:ano_impositivo',
	    													[':ano_impositivo' => $añoImpositivo])
	    	                                   ->andWhere('estatus =:estatus',
	    	                                    			[':estatus' => 0])
	    									   ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
	    									   				[':exigibilidad_periodo' => $periodo])
	    									   ->andWhere('I.inactivo =:inactivo',
	    									    			[':inactivo' => 0])
	    									   ->andWhere('R.inactivo =:inactivo',
	    									    			[':inactivo' => 0])
	    									   ->joinWith('actividadEconomica A', false, 'INNER JOIN')
	    									   ->joinWith('rubroDetalle R', true, 'INNER JOIN');

	    	return isset($findModel) ? $findModel : null;
	    }




	    /**
	     * Metodo que crea el data provider para los grid.
	     * @param  integer $añoImpositivo año impositivo del lapso
	     * @param  integer $periodo periodo del lapso.
	     * @return active data provider.
	     */
	    public function getDataProviderRubrosRegistrados($añoImpositivo, $periodo)
	    {
	    	$query = self::findRubrosRegistrados($añoImpositivo, $periodo);
	    	$dataProvider = New ActiveDataProvider([
	    			'query' => $query,
	    		]);
	    	$query->all();
	    	return $dataProvider;
	    }





	    /**
	     * Metodo que permite iniciar el un data provider. Esto se utiliza para iniciar
	     * el grid donde se muestran los rubros registrados de un contribuyente para un
	     * lapso determinado.
	     * @param  model $model modelo respectivo que se refiere a ActEconIngreso.
	     * @return active data provider sin valores.
	     */
	    public function inicializarDataProvider($model)
	    {
	    	$query = $model;

	    	$dataProvider = New ActiveDataProvider([
	    			'query' => $query,
	    		]);
	    	$query->where('0=1');

	    	return $dataProvider;
	    }



	    /**
	     * Metodo que realiza una busqueda de los rubros registrados de un contribuyente
	     * para un año-periodo especifico, luego se extrae los identificadores para generar
	     * un array de identificadores de rubros.
	     * @param  integer $añoImpositivo año impositivo del lapso
	     * @param  integer $periodo periodo del lapso.
	     * @return array retorna un arreglo de identificadores de rubros, sino el arreglo
	     * retornado estara vacio.
	     */
	    public function getListaIdRubrosRegistrados($añoImpositivo, $periodo)
	    {
	    	$listaIdRubro = [];
	    	$findModel = self::findRubrosRegistrados($añoImpositivo, $periodo);
	    	if ( isset($findModel) ) {
	    		$arreglo = $findModel->asArray()->all();
	    		foreach ( $arreglo as $a ) {
	    			if ( isset($a['rubroDetalle']) ) {
	    				$listaIdRubro[] = $a['rubroDetalle']['id_rubro'];
	    			}
	    		}
	    	}
	    	return $listaIdRubro;
	    }




	    /**
	     * Metodo que realiza un find sobre la entidad "act-econ". Segun los
	     * parametros enviados.
	     * @param  integer $añoImpositivo año fiscal consultado.
	     * @param  integer $estatus condicion del registro.
	     * @return active record en caso contrario null.
	     */
	    private function findLapsoDeclaracion($añoImpositivo, $estatus)
	    {
	    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
	    												[':id_contribuyente' => $this->_id_contribuyente])
	    								->andWhere('ente =:ente',
	    												[':ente' => Yii::$app->ente->getEnte()])
	    								->andWhere('ano_impositivo =:ano_impositivo',
	    												[':ano_impositivo' => $añoImpositivo])
	    								->andWhere('estatus =:estatus',
	    												[':estatus' => $estatus]);
	    	return isset($findModel) ? $findModel : null;
	    }




	    /**
	     * Metodo que permite obtener el identificador del lapso declarado, recibido
	     * la consulta realizada filtra y solo retorna el identificador, en caso contrario
	     * retorna null.
	     * @param  integer $añoImpositivo año fiscal consultado.
	     * @param  integer $estatus condicion del registro.
	     * @return long retorna el identificador de la entidad segun la consulta realizada.
	     * En caso contrario retorna null.
	     */
	    public function getIdentificadorLapsoValido($añoImpositivo)
	    {
	    	$findModel = self::findLapsoDeclaracion($añoImpositivo, 0);
	    	if ( $findModel == null ) {
	    		return null;
	    	} else {
	    		$result = $findModel->one();
	    		return isset($result->id_impuesto) ? $result->id_impuesto : null;
	    	}
	    }
	}
 ?>