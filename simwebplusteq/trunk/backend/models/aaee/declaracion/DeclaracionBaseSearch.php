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
 *  @file DeclaracionBaseSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-09-2016
 *
 *  @class DeclaracionBaseSearch
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

 	namespace backend\models\aaee\declaracion;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\DeclaracionBase;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\actecon\ActEconSearch;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use backend\models\aaee\acteconingreso\ActEconIngresoSearch;
	use common\models\ordenanza\OrdenanzaBase;
	use yii\helpers\ArrayHelper;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\aaee\anexoramo\AnexoRamoSearch;
	use backend\models\aaee\desincorporaramo\DesincorporarRamoSearch;
	use backend\models\aaee\correccionfechainicio\CorreccionFechaInicioSearch;
	use common\models\planilla\HistoricoSearch;
	use backend\models\recibo\depositoplanilla\DepositoPlanillaSearch;
	use backend\models\aaee\historico\declaracion\HistoricoDeclaracionSearch;



	/**
	 * Clase que gestiona el funcionamiento de la solicitud para la declaracion
	 * de los montos. La clase gestiona la declaracion de la estimada y definitiva.
	 *
	 */
	class DeclaracionBaseSearch extends DeclaracionBase
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
		 * Metodo que realiza una busqueda de la solicitud por concepto de declaracion.
		 * El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudDeclaracion($nroSolicitud)
		{
			$findModel = DeclaracionBase::find()->where('nro_solicitud =:nro_solicitud',
														 	[':nro_solicitud' => $nroSolicitud])
										        ->andWhere('id_contribuyente =:id_contribuyente',
											   				[':id_contribuyente' => $this->_id_contribuyente]);

			return isset($findModel) ? $findModel : null;
		}




		/**
		 * Metodo que permite determinar si existe una solicitud similar pendiente
		 * segun los parametros de consulta.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
		 * @return boolean retorna un true si ya existe solicitud similar pendiente, sino
		 * retorna false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($añoImpositivo, $periodo, $tipoDeclaracion)
		{
			$count = 0;
			$findModel = self::yaPoseeSolicitud($añoImpositivo, $periodo, $tipoDeclaracion, 0);

			if ( count($findModel) > 0 ) {
				$count = $findModel->count();
			}

			return ( $count > 0 ) ? true : false;
		}



		/**
		 * Metodo que permite determinar si existe una solicitud similar aprobada
		 * segun los parametros de consulta.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
		 * @return boolean retorna un true si ya existe solicitud similar aprobada, sino
		 * retorna false.
		 */
		public function yaPoseeSolicitudSimiliarAprobada($añoImpositivo, $periodo, $tipoDeclaracion)
		{
			$count = 0;
			$findModel = self::yaPoseeSolicitud($añoImpositivo, $periodo, $tipoDeclaracion, 1);

			if ( count($findModel) > 0 ) {
				$count = $findModel->count();
			}

			return ( $count > 0 ) ? true : false;
		}




		/**
		 * Metodo para determinar si existe una solicitud segun los parametros
		 * de consultas.
		 * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
		 * @param  integer $estatus condicion del registro.
		 * @return active record retorna el modelo de la consulta, que esta determinada
		 * por un array, sino retorna un array vacio.
		 */
		private function yaPoseeSolicitud($añoImpositivo, $periodo, $tipoDeclaracion, $estatus)
		{
			$findModel = DeclaracionBase::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $this->_id_contribuyente])
										  	    ->andWhere('ano_impositivo =:ano_impositivo',
										  	      				[':ano_impositivo' => $añoImpositivo])
										  	    ->andWhere('tipo_declaracion =:tipo_declaracion',
										  	      				[':tipo_declaracion' => $tipoDeclaracion])
										  	    ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
										  	      				[':exigibilidad_periodo' => $periodo])
											    ->andWhere('estatus =:estatus',
											     				[':estatus' => $estatus]);

			return ( count($findModel) > 0 ) ? $findModel : [];
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
		 * Metodo que permite determinar si un contribuyente es una sede principal.
		 * Esto solo aplica para los contribuyentes juridicos.
		 * @return boolean true si es verdadero, false en caso contrario.
		 */
		public function getSedePrincipal()
		{
			return ContribuyenteBase::getEsUnaSedePrincipal($this->_id_contribuyente);
		}



		/**
		 * Metodo que retorna la fecha de inicio de actividades del contribuyente
		 * @return string retorna una fecha o en su defecto un null.
		 */
		public function getFechaInicioActividad()
		{
			return ContribuyenteBase::getFechaInicio($this->_id_contribuyente);
		}





		/**
		 * Metodo que realiza una modelo consulta de las sucursales que posee el mismo rif
		 * y que sean juridicos.
		 * @return Active Record.
		 */
		public function findSucursales()
		{
			$result = null;
			$findModel = null;
			if ( self::getSedePrincipal() ) {
				$datos = self::getDatosContribuyente();
				if ( count($datos) > 0 ) {
					$findModel = Sucursal::find()->where('naturaleza =:naturaleza',
															 [':naturaleza' => $datos['naturaleza']])
												 ->andWhere('cedula =:cedula',
												 			 [':cedula' => $datos['cedula']])
												 ->andWhere('tipo =:tipo',
												 			 [':tipo' => $datos['tipo']])
												 ->andWhere('tipo_naturaleza =:tipo_naturaleza',
												  			 [':tipo_naturaleza' => 1])
												 ->andWhere(':inactivo =:inactivo',
												 			 [':inactivo' => 0])
												 ->orderBy([
												 		'id_rif' => SORT_ASC,
												 	]);
				}
			}
			return isset($findModel) ? $findModel : null;
		}



		/**
		 * Metodo que permite obtener un arreglo de los identificadores de los contribuyentes
		 * partiendo de un modelo consulta ejecutado.
		 * @return array retorna un arreglo de identificadores de los contribuyentes, esquema de
		 * retorno:
		 * 		array {
		 *   		[indice] => valor del elemento.
		 *  }
		 */
		public function getIdSucursales()
		{
			$id = null;
			$findModel = self::findSucursales();
			if ( isset($findModel) ) {
				$sucursales = $findModel->asArray()->all();
				foreach ( $sucursales as $sucursal ) {
					$id[] = $sucursal['id_contribuyente'];
				}
			}
			return $id;
		}




		/**
	     * Metodo que retorna un dataProvider, recibiendo como parametro un arreglo
	     * de id contribuyentes.
	     * @param $arrayIdContribuyente, array de id contribuyentes,
	     * @return retorna un dataProvider.
	     */
	    public function getDataProviderSucursal($arrayIdContribuyente = [])
	    {
	    	$query = Sucursal::find();
    		$dataProvider = new ActiveDataProvider([
            	'query' => $query,
        	]);
	    	if ( count($arrayIdContribuyente) > 0 ) {
	        	$query->where(['in', 'id_contribuyente', $arrayIdContribuyente]);
	    	} else {
	    		$query->where('0=1');
	    	}
		    return $dataProvider;
	    }



	    /***/
	    public function getDataProviderSolicitud($nroSolicitud)
	    {
	    	$query = self::findSolicitudDeclaracion($nroSolicitud);

	    	$dataProvider = new ActiveDataProvider([
            	'query' => $query,
        	]);
	    	$query->all();

        	return $dataProvider;
	    }




	    /**
		 * Metodo que permite determinar si un contribuyente tiene registros en
		 * la entidad de las declaraciones.
		 * @param  integer $añoImpositivo año de la consulta.
		 * @return returna una instancia con los datos de la entidad act_econ.
		 * Si retorna false no ss ejecuto la consulta o no encontro nada.
		 */
		public function tieneRecordActEcon($añoImpositivo = 0)
	    {
	    	if ( $añoImpositivo > 0 ) {
		    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
		    	 										[':id_contribuyente' => $this->_id_contribuyente])
		    								->andWhere('ano_impositivo =:ano_impositivo',
		    								 			[':ano_impositivo' => $añoImpositivo])
		    								->andWhere('estatus =:estatus', [':estatus' => 0])
		    								->count();
		    } else {
		    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
		    	 										[':id_contribuyente' => $this->_id_contribuyente])
		    								->andWhere('estatus =:estatus', [':estatus' => 0])
		    								->count();
		    }
	    	return ( $findModel > 0 ) ? true : false;
	    }



	    /**
	     * Metodo que determina si existe registro en la entidad "act-econ" validos
	     * para un rango de fecha especifico.
	     * @param  integer $añoDesde año inicial de la consulta.
	     * @param  integer $añoHasta año final de la consulta.
	     * @return boolean retorna true si existe registro, en caso contrario false.
	     */
	    public function existeDeclaracionParaRangoOrdenanza($añoDesde, $añoHasta)
	    {
	    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
		    	 										[':id_contribuyente' => $this->_id_contribuyente])
		    								->andWhere(['BETWEEN', 'ano_impositivo',$añoDesde, $añoHasta])
		    								->andWhere('estatus =:estatus', [':estatus' => 0])
		    								->count();

		    return ( $findModel > 0 ) ? true : false;
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
	     * impositivo. El identificador retornado corresponde al de la entidad "rubros".
	     * @param  long $idRubro identificador del rubro.
	     * @param  inetger $añoImpositivo año impositivo del catalogo de rubro que quiere consultar
	     * el $idRubro no deberia corresponder al del año impositivo.
	     * @return long retonra un identificador del rubro para el año impositivo $añoimpositivo.
	     * de lo contraro retorna cero (0).
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
	     * Metodo que retorna un [rubro] => definitiva del año anterior
	     * @param  [type]  $añoImpositivo [description]
	     * @param  [type]  $periodo       [description]
	     * @param  integer $rubro         [description]
	     * @return [type]                 [description]
	     */
	   	public function getDefinitivaAnterior($añoImpositivo, $periodo, $rubro = 0)
	    {
	    	$result = null;
	    	$definitiva = null;
	    	$findModel = self::findRubrosRegistrados($añoImpositivo, $periodo);
	    	if ( isset($findModel) ) {
	    		$result = $findModel->all();
	    		foreach ( $result as $ramo ) {
	    			$definitiva[$ramo->rubroDetalle->rubro] = $ramo['reales'];
	    		}
	    	}
	    	return $definitiva;
	    }



	    /**
	     * Metodo que permite determinar si la carga de la declaracion estimada
	     * del año actual se puede realizar tomando en consideracion que si
	     * la fecha de inicio de actividad del contribuyente corresponde al año
	     * actual, no se exigira que exista la declaracion definitiva del año
	     * anterior. Solo si el año de inicio de actividad del contribuyente
	     * corresponde a años anteriores al actual, se exigira la definitiva
	     * correspondiente. Ademas se considerara que una declaracion definitiva
	     * es valida si la sumatoria de los montos de los rubros, es mayor a cero (0).
	     * @param  integer $añoImpositivo año impositivo del lapso en donde se quiere
	     * cargar la declaracion estimada.
	     * @param  integer $periodo periodo del lapso.
	     * @return string retorna un string con un mensaje o un string vacio sino
	     * sucede nada excepcional.
	     */
		public function puedoDeclararEstimada($añoImpositivo, $periodo)
		{
			$result = '';

			$mensaje1 = "Para continuar con la Declaracion Estimada {$añoImpositivo}, debe realizar primero la cargar de la definitiva indicada.";
			// Año actual
			$añoActual = date('Y');

			$añoAnterior = $añoImpositivo - 1;

			$fechaInicio = null;

			// Se obtiene la fecha de inicio de actividad economica
			$fechaInicio = self::getFechaInicioActividad();

			if ( $fechaInicio !== null ) {
				$añoInicio = date('Y', strtotime($fechaInicio));
				if ( $añoInicio == $añoActual ) {
					$result = '';

				} else {
					$sumaDefinitiva = 0;
					// Como la fecha de inicio no corresponde con el año actual, se
					// debe verificar que exista la declaracion definitiva del año
					// anterior, lo que implica que la suma de los rubros de dicha
					// declaracion, sumen un monto mayor a cero (0).
					$definitivas = self::getDefinitivaAnterior($añoAnterior, $periodo);

					if ( $definitivas !== null ) {
						foreach ( $definitivas as $key => $value ) {
							$sumaDefinitiva = $sumaDefinitiva + $value;
						}
						if ( $sumaDefinitiva > 0 ) {
							$result = '';

						} else {
							$result = Yii::t('frontend', "Los montos de la declaracion definitiva del año {$añoAnterior}, suman cero (0). " . $mensaje1);

						}
					} else {
						$result = Yii::t('frontend', "La declaracion definitiva del año {$añoAnterior}, no esta definida. " . $mensaje1);

					}
				}
			} else {
				$result = Yii::t('frontend', "La fecha de inicio de actividad de la empresa no esta definida.");
			}
			return $result;
		}






	    /**
	     * Metodo que retorna una lista arreglo donde el indice del arreglo es el
	     * año impositivo de la entidad "act-econ" y el valor del elemento es el
	     * año impositivo. Esto permitira crear un combo-lista.
	     * @param integer $tipoDeclaracion entero que identifica el tipo de declaracion
	     * que se dese realizar.
	     * @return array retorna una arreglo, con el siguiente esquema:
	     * array {
	     * 		[año] => año
	     * }
	     */
	    public function getListaAnoRegistrado($tipoDeclaracion)
	    {
	    	$listaAño = [];
	    	$añoActual = date('Y');
	    	$añoLimite = Yii::$app->lapso->anoLimiteNotificado();

	    	if ( $tipoDeclaracion == 1 ) {		// Estimada

		    	$findModel = ActEcon::find()->distinct('ano_impositivo')
		    								->where('id_contribuyente =:id_contribuyente',
		    	 										['id_contribuyente' => $this->_id_contribuyente])
		    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
		    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
		    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
		    							    ->andWhere('ano_impositivo =:ano_impositivo',
		    							    			[':ano_impositivo' => $añoActual])
		    							    ->joinWith('actividadDetalle', 'INNER JOIN', false)
		    							    ->orderBy([
		    							   		'ano_impositivo' => SORT_ASC,
		    							   	])
		    							    ->all();

		    } elseif ( $tipoDeclaracion == 2 ) {	// Definitiva

		    	$findModel = ActEcon::find()->distinct('ano_impositivo')
		    								->where('id_contribuyente =:id_contribuyente',
		    	 										['id_contribuyente' => $this->_id_contribuyente])
		    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
		    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
		    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
		    							    ->andWhere(['BETWEEN', 'ano_impositivo', $añoLimite, $añoActual - 1])
		    							    ->joinWith('actividadDetalle', 'INNER JOIN', false)
		    							    ->orderBy([
		    							   		'ano_impositivo' => SORT_ASC,
		    							   	])
		    							    ->all();

		    } elseif ( $tipoDeclaracion == 3 ) {


		    }

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



	    /***/
	    public function findActEconIngresos($idImpuesto, $periodo)
	    {
	    	$findModel = ActEconIngreso::find()->where('id_impuesto =:id_impuesto',
	    													[':id_impuesto' => $idImpuesto])
	    									   ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
	    										 			[':exigibilidad_periodo' => $periodo])
	    									   ->andWhere('inactivo =:inactivo',
	    										 			[':inactivo' => 0])
	    									   ->andWhere('bloqueado =:bloqueado',
	    										 			[':bloqueado' => 0]);

	    	return isset($findModel) ? $findModel : null;
	    }




	    /**
	     * Metodo que realiza una busqueda del identificador de año declarado. El identificador
	     * corresponde a la entidad "act-econ".
	     * @param  integer $añoImpositivo año fiscal de consulta
	     * @return long retorna un identificador si encuentra, en caso contario null.
	     */
	    public function getIdImpuestoSegunAnoImpositivo($añoImpositivo)
	    {
	    	$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
	    												[':id_contribuyente' => $this->_id_contribuyente])
	    								->andwhere('ano_impositivo =:ano_impositivo',
	    												[':ano_impositivo' => $añoImpositivo])
	    								->andwhere('estatus =:estatus',
	    												[':estatus' => 0])
	    								->one();
	    	return isset($findModel['id_impuesto']) ? (int)$findModel['id_impuesto'] : null;
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
	    	// $tablaAct = ActEcon::tableName();
	    	// $tablaRubro = Rubro::tableName();
	    	// $tablaIngreso = ActEconIngreso::tableName();
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
	    									   ->joinWith('actividadEconomica A', true, 'INNER JOIN')
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



	    /**
	     * Metodo que permite determinar si existe una solicitud pendiente del tipo:
	     * 1- Desincorporacion de Ramo.
	     * 2- Anexo de Ramo.
	     * Si existe algunas de estas solicitudes pendientes, no se podra permitir
	     * la creacion de una solicitud para declarar. La solicitud debe ser del año
	     * periodo considerado.
	     * @param  integer $añoImpositivo año del lapso que se desea declrar.
	     * @param  integer $periodo periodo del lapso que se desea declarar.
	     * @return array retorna un arreglo, donde el indice es un integer y el valor
	     * del elemento es un string. El string sera un mensaje indicando la solicitud
	     * pendiente. Sino encuentra nada el arreglo sera vacio, es decir count() = 0.
	     */
	    public function getOtraSolicitudPendiente($añoImpositivo, $periodo)
	    {
	    	$arregloMsjs = [];

	    	// Se buscan las solicitudes por anexo de ramos que esten pendiente.
	    	$anexoSearch = New AnexoRamoSearch($this->_id_contribuyente);
	    	$anexoSolicitud = $anexoSearch->findSolicitudAnexoRamoSegunLapso($añoImpositivo, $periodo);

	    	$modelAnexo = $anexoSolicitud->joinWith('rubro', 'INNER JOIN')
	    	                             ->all();

	    	if ( count($modelAnexo) > 0 ) {
	    		$arregloMsjs[] = self::getMensaje($modelAnexo);
	    	}

	    	// Se buscan las solicitudes por desincorporacion de ramos que esten pendiente.
	    	$desincorporarSearch = New DesincorporarRamoSearch($this->_id_contribuyente);
	    	$desincorporarSolicitud = $desincorporarSearch->findSolicitudDesincorporarRamoSegunLapso($añoImpositivo, $periodo);

	    	$modelDesincorporar = $desincorporarSolicitud->joinWith('rubro', 'INNER JOIN')
	    	                                             ->all();

	    	if ( count($modelDesincorporar) > 0 ) {
	    		$arregloMsjs[] = self::getMensaje($modelDesincorporar);
	    	}



	    	// Se busca las solicitudes por correccion de fecha de inicio.
	    	$fechaInicioSearch = New CorreccionFechaInicioSearch($this->_id_contribuyente);
	    	$fechaInicioSolicitud = $fechaInicioSearch->findSolicitudFechaInicio(0);

	    	$modelFechaInicio = $fechaInicioSolicitud->joinWith('estatusSolicitud')
	    											 ->all();

	    	if ( count($modelFechaInicio) > 0 ) {
	    		$arregloMsjs[] = self::getMensaje($modelFechaInicio);
	    	}




	    	return $arregloMsjs;

	    }



	    /**
	     * Metodo que permite realizar un resumen de la solicitud y su detalles.
	     * @param model $model modelo de la entidad "sl" que posee el detalle
	     * de la solicitud.
	     * @return array retorna un arreglo que indica en su key un integer y el
	     * valor del elemento es otro arraglo que posee tres elementos:
	     * - Numero de Solicitud.
	     * - Descripcion del tipo de solicitud.
	     * - Descripcion del detalle del tipo de solicitud. En este caso muestra es
	     * la descripcion del ramo.
	     */
	    private function getMensaje($model)
	    {
	    	$mensaje = '';
	    	$result = [];
	    	if ( count($model) > 0 ) {
	    		foreach ( $model as $mod ) {
	    			$mensaje = '';
	    			$mensaje = isset($mod['nro_solicitud']) ? 'Solicitud Pendiente: ' . $mod['nro_solicitud'] : '';
	    			$mensaje = $mensaje . ' - ' . $mod->getDescripcionTipoSolicitud($mod['nro_solicitud']);
	    			//$mensaje = isset($mod['rubro']['descripcion']) ? $mensaje . ' - ' . $mod['rubro']['descripcion'] : $mensaje;
		    		$result[] = $mensaje;
		    	}
	    	}
	    	return $result;
	    }




	    /**
	     * Metodo que permite validar la logica del negocio que se aplicara para
	     * la solicitud de declaracion estimada. Si no se cumple algunas de las
	     * consideraciones que se tienen para esta solicitud, el metodo asigna un
	     * mensaje en un arreglo. Cada consideracion generara un mensaje sino se
	     * cumple.
	     * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
	     * @return array retorna un arreglo de mensajes o en su defecto un arreglo
	     * vacio si todo resulta satisfactoriamente.
	     */
	    public function validarEvento($añoImpositivo, $periodo, $tipoDeclaracion)
	    {
	    	$mensajes = [];

	    	$solicitud = '';
	    	$declaracion = '';
	    	$definitiva = [];
	    	$definitivaExistente = [];
	    	$mensajePendiente = '';
	    	$mensajeAprobada = '';
	    	$mensajePago = [];
	    	$mensajeReciboPendiente = [];

	    	// Se determina si existen otras solicitudes que no permitan la creacion
	    	// de la solicitud de declaracion estimada. Lo siguiente debe retornar
	    	// un arreglo de mensajes o un arreglo vacio.
	    	$solicitud = self::getOtraSolicitudPendiente($añoImpositivo, $periodo);

	    	if ( $tipoDeclaracion == 1 ) {			// Estimada

		    	// Se controla si existe la definitiva del periodo anterior, solo aplica
		    	// para los contribuyentes que su año de inicio es anterior al actual.
		    	// Lo siguiente debe retornar un mensaje o un string vacio.
		    	$declaracion = self::puedoDeclararEstimada($añoImpositivo, $periodo);

		    } elseif ( $tipoDeclaracion == 2 ) {	// Definitiva
		    	// Lo siguiente controla la existencia de las declaraciones definitivas anteriores
		    	// a la que se desea realizar.
		    	$definitiva = self::controlDefinitivasAnteriores($añoImpositivo, $periodo);

		    	// Lo sguiente controla si los montos de la definitiva estan todos en cero.
		    	// Sino es asi se considera como valida dicha declaracion definitiva y no se
		    	// podra cagar otra. Solo a traves dle modulo de sustitutiva.
		    	$definitivaExistente = self::controlDefinitivaExistente($añoImpositivo, $periodo);

		    } elseif ( $tipoDeclaracion == 3 ) {

		    }
	    	// Lo siguiente controla si existe una solicitud similar pendiente o
	    	// aprobada. Retorna un boolean que indica.
	    	$pendiente = self::yaPoseeSolicitudSimiliarPendiente($añoImpositivo, $periodo, $tipoDeclaracion);

	    	if ( $pendiente ) {
	    		$mensajePendiente = Yii::t('backend', "Existe solicitud similar pendiente para el lapso {$añoImpositivo} - {$periodo}");
	    	}

	    	$aprobada = self::yaPoseeSolicitudSimiliarAprobada($añoImpositivo, $periodo, $tipoDeclaracion);
	    	if ( $aprobada ) {
	    		$mensajeAprobada = Yii::t('backend', "Existe solicitud similar aprobada para el lapso {$añoImpositivo} - {$periodo}");
	    	}

	    	if ( $tipoDeclaracion == 1 || $tipoDeclaracion == 2 ) {

		    	// Lo siguiente controla los pagos existentes para un lapso y tipo de declaracion
		    	// especifica.
		    	$mensajePago = self::controlPagoExistenteDelLapso($añoImpositivo, $periodo, $tipoDeclaracion);


		    	// Lo siguiente controla que el lapso que se desea declararno este relacionado a ninguna
		    	// planilla que este en un recibo pendiente.
		    	$mensajeReciboPendiente = self::controlReciboPendiente($añoImpositivo, $periodo, $tipoDeclaracion);
		    }


	    	// Se arma todo el arreglo de mensajes.
	    	if ( count($solicitud) > 0 ) {
	    		$mensajes[] = $solicitud;
	    	}

	    	if ( trim($declaracion) !== "" ) {
	    		$mensajes[] = $declaracion;
	    	}

	    	if ( trim($mensajePendiente) !== "" ) {
	    		$mensajes[] = $mensajePendiente;
	    	}

	    	if ( trim($mensajeAprobada) !== "" ) {
	    		$mensajes[] = $mensajeAprobada;
	    	}

	    	if ( count($definitiva) > 0 ) {
	    		$mensajes[] = $definitiva;
	    	}

	    	if ( count($mensajePago) > 0 ) {
	    		$mensajes[] = $mensajePago;
	    	}

	    	if ( count($mensajeReciboPendiente) > 0 ) {
	    		$mensajes[] = $mensajeReciboPendiente;
	    	}


	    	if ( count($definitivaExistente) > 0 ) {
	    		$mensajes[] = $definitivaExistente;
	    	}

	    	return $mensajes;

	    }



	    /**
	     * Metodo que controla la existencia de las declaraciones definitivas anteriores
	     * al lapso que se pretende declarar. Se determina la fecha de inicio de actividades
	     * para saber desde donde se debe controlar la definitiva. Si el $añoInicio es igual
	     * al año actual no sera necesario realizar al control.
	     * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
	     * @return array retorna un arreglo de mensjaes, en caso de que todo salga satisfactoriamente
	     * retorna un arreglo vacio.
	     */
	    public function controlDefinitivasAnteriores($añoImpositivo, $periodo)
	    {
	    	$mensajes = [];
	    	$result = '';
	    	$añoActual = (int)date('Y');
	    	$añoLimite = Yii::$app->lapso->anoLimiteNotificado();
	    	$mensaje1 = Yii::t('frontend', "Para continuar con la Declaracion Definitiva {$añoImpositivo} - {$periodo}, debe realizar primero la cargar de la declaracion definitiva");

	    	// Se determina la fecha de inicio de actividades para saber desde donde
	    	// se debe controlar la definitiva. Si el $añoInicio es igual al año actual
	    	// no sera necesario realizar al control.
	    	$fechaInicio = ContribuyenteBase::getFechaInicio($this->_id_contribuyente);
	    	$añoInicio = (int)date('Y', strtotime($fechaInicio));

	    	if ( $añoInicio == $añoActual && $añoInicio == $añoImpositivo ) {
	    		$mensajes = [];
	    	} else {
	    		if ( $añoInicio < $añoLimite ) {
	    			$añoBegin = $añoLimite;
	    		} else {
	    			$añoBegin = $añoInicio;
	    		}

	    		for ( $i = $añoBegin; $i <= $añoImpositivo - 1; $i++ ) {
	    			$result = null;
	    			$result = self::getDefinitivaAnterior($i, $periodo);

	    			if ( count($result) == 0 || $result == null ) {
	    				$mensajes[] = $mensaje1 . ' ' . "{$i} - {$periodo}";

	    			} else {
	    				$suma = 0;
	    				foreach ( $result as $key => $value ) {
	    					$suma = $value + $suma;
	    				}
	    				if ( $suma == 0 ) {
	    					$mensajes[] = $mensaje1 . ' ' . "{$i} - {$periodo}";
	    				}
	    			}
	    		}

	    	}
	    	return $mensajes;
	    }



	    /**
	     * Metodo que permite determinar si existe un pago existente en un lapso determinado
	     * y para un tipo de declaracion especifica. Si existe un pago se generara un mensaje
	     * indicando la situacion.
	     * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
	     * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
	     * @return array retorna un arreglo de mensajes.
	     */
	    private function controlPagoExistenteDelLapso($añoImpositivo, $periodo, $tipoDeclaracion)
	    {
	    	$result = false;
	    	$mensajes = [];
	    	$mensaje2 = Yii::t('backend', "La DECLARACION DEFINITIVA que desea realizar ya posee un pago realizado");
	    	$mensaje1 = Yii::t('backend', "La DECLARACION ESTIMADA que desea realizar ya posee un pago realizado");

	    	if ( $tipoDeclaracion == 1 ) {

	    	} elseif ( $tipoDeclaracion == 2 ) {
	    		$historico = New HistoricoSearch();
	    		$result = $historico->condicionDefinitiva($añoImpositivo, $periodo, $this->_id_contribuyente);
	    		if ( is_integer($result) ) {
	    			if ( $result == 1 ) {
	    				$mensajes[] = $mensaje2;
	    			}
	    		}
	    	}

	    	return $mensajes;
	    }




	    /**
	     * Metodo que determina si el lapso al cual se quiere declarar esta asociado
	     * a una planilla que este relacionada a un recibo pndiente por pagar.
	     * Se realiza una consulta para determinar primero las planillas asociadas
	     * al lapso y luego se determina si dichas planillas estan relacionadas a un
	     * recibo, si dicha relacion esta en un estatus pendiente, esto generara un
	     * mensaje que se colocara en un arreglo de mensajes.
	     * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
	     * @param  integer $tipoDeclaracion tipo de declaracion que se desea
		 * consultar.
		 * - tipo 1: estimada.
		 * - tipo 2: definitiva.
	     * @return array retorna un arreglo de mensajes.
	     */
	    private function controlReciboPendiente($añoImpositivo, $periodo, $tipoDeclaracion)
	    {
	    	$planillas = [];
	    	$result = '';
	    	$mensajes = [];

	    	if ( $tipoDeclaracion == 1 ) {

	    	} elseif ( $tipoDeclaracion == 2 ) {
	    		$historico = New HistoricoSearch();
	    		// Lo siguinete retorna un arreglo de planillas.
	    		$planillas = $historico->planillaDefinitivaRelacionadaLapso($añoImpositivo, $periodo, $this->_id_contribuyente);

	    		if ( count($planillas) > 0 ) {
	    			// Se determina si algunas de las planillass encontradas esta en un recibo de pago,
	    			// y si las planillas aun estan pendiente por pagar en dicho recibo.
	    			$deposito = New DepositoPlanillaSearch();
	    			foreach ( $planillas as $planilla ) {
	    				$result = '';
	    				// Lo siguiente retorna un arreglo donde el indice del arreglo es el numero del
	    				// identificador de la entidad "depositos-planillas" y el valor del elemneto
	    				// es otro arreglo donde se obtienen el numero de recibo, planilla y la condicion
	    				// del regsitro(estatus).
	    				$result = $deposito->planillaRelacionadaReciboPendiente($planilla);
	    				if ( count($result) > 0 ) {
	    					foreach ( $result as $datos ) {
	    						if ( $datos['estatus'] == 0 ) {
	    							$mensajes[] = Yii::t('backend', "La planilla numero " . $datos['planilla'] . " esta relacionada al lapso que desea declarar. Dicha planilla esta relacionada al recibo " . $datos['recibo'] . " que aun aparece pendiente por pagar");
	    						}
	    					}
	    				}
	    			}
	    		}
	    	}

	    	return $mensajes;
	    }




	    /**
	     * Metodo que realiza una busqueda de los registros pertenecientes al lapso
	     * que se desea consultar, para sumar los montos existentes en el atributo
	     * "reales". Si la suma de dichos atributos es mayor a cero (0), se considera
	     * que este lapso ya fue declarado (declaracion definitiva).
	     * @param  integer $añoImpositivo año del lapso a consultar.
		 * @param  integer $periodo periodo del lapso a consultar.
	     * @return array retorna un arreglo de mensaje. Si todo resulta satisfactorio
	     * retorna un arreglo vacio.
	     */
	    private function controlDefinitivaExistente($añoImpositivo, $periodo)
	    {
	    	$result = false;
	    	$mensajes = [];
	    	$suma = 0;

	    	$findModel = self::findRubrosRegistrados($añoImpositivo, $periodo);
	    	if ( count($findModel) > 0 ) {
	    		$result = $findModel->asArray()->all();
	    		if ( count($result) > 0 ) {
	    			foreach ( $result as $dato ) {
	    				$suma = $suma + $dato['reales'];
	    			}
	    			if ( $suma > 0 ) {
	    				$mensajes[] = Yii::t('backend', "La Declaracion Definitiva {$añoImpositivo} - {$periodo}, ya se encuentra registrada. Utilice el modulo de DECLARACION SUSTITUTIVA.");
	    			}
	    		}
	    	}

	    	return $mensajes;

	    }



	    /**
	     * Metodo que se encarga de crear y guardar la declaracion estimada del lapso
	     * siguiente al de la declaracion definitiva. Con la variable $modelDefinitiva
	     * que debe ser de la instancia "DeclaracionBaseForm", cuando crea la solicitud
	     * y la instancia "DeclaracionBase" cuando se aprueba la solicitud.
	     * @param  model $modelDeclaracion modelo que contiene los datos dela entidad
	     * "sl-declaraciones" donde se guarda el identificador del rubro, año impositivo,
	     * periodo, monto-new (monto de la declaracion), id-contribuyente, identificador
	     * del maestro de la declaracion (id-impuesto), entre otros.
	     * @param  [type] $conexion        [description]
	     * @param  [type] $conn            [description]
	     * @return [type]                  [description]
	     */
	    public function cargarEstimadaPorOficio($modelDeclaracion, $conexion, $conn)
	    {
	    	$result = false;
	    	$mensajes = [];
	    	$añoActual = (int)date('Y');
    		$añoImpositivo = (int)$modelDeclaracion[0]['ano_impositivo'];
    		$usuario = '';
			$usuario = Yii::$app->identidad->getUsuario();

    		// Año de la estimada a cargar.
	    	$añoEstimada = $añoImpositivo + 1;

	    	if ( $añoEstimada > $añoActual + 1 ) {

				$mensajes[] = Yii::t('backend', 'Año de declracion fuera de rango');

			} else {

				// Se determina si ambos años, el de la definitiva y el de la estimada
		    	// corresponden a la misma ordenaza.
				$ordenanzaValida = OrdenanzaBase::anoMismaOrdenanza($añoImpositivo, $añoEstimada, 1);
				if ( $ordenanzaValida ) {

					$actSearch = New ActEconSearch($this->_id_contribuyente);

					$exigibilidadDecla = self::getExigibilidadSegunAnoImpositivo($añoEstimada);
					$arregloDatos = [
							'ente' => Yii::$app->ente->getEnte(),
							'id_contribuyente' => $this->_id_contribuyente,
							'ano_impositivo' => $añoEstimada,
							'exigibilidad_declaracion' => $exigibilidadDecla['exigibilidad'],

					];

					// Se determina el identificador de la entidad maestro donde se guarda la declaraciones.

					// Debe retornar un arreglo con la informacion de la operacion, este arreglo
					// tiene dos parametros, 'r' => indica si el identificador ya exite o se genero
					// en la operacion e 'id' => el identificador de la entidad.
					$idActEcon = $actSearch->guardar($arregloDatos, $conexion, $conn);

					$periodo = $modelDeclaracion[0]['exigibilidad_periodo'];
					if ( $idActEcon['id'] > 0 ) {

						// Si se llega aqui significa que se pudo obtener el identificador de la
						// entidad "act-econ" para incluir la estimada del lapso.
						// -----
						// Ahora se buscan los registros de la estimada para determinar si existen
						$findModel = self::findActEconIngresos($idActEcon['id'], $periodo);
						$modelIngresos = $findModel->asArray()->all();
						if ( count($modelIngresos) > 0 ) {

							// Existe registros en la estimada.
							$searchIngreso = New ActEconIngresoSearch($this->_id_contribuyente);

							// Se inactiva lo registros existentes.
							// ----
							// Se determina la suma de los montos exitentes en las estimadas.
							$suma = 0;
							foreach ( $modelIngresos as $ingreso ) {
								$suma = $ingreso['estimado'] + $suma;
							}

							// Se inactiva los registros existentes.
							foreach ( $modelIngresos as $ingreso ) {

								$result = $searchIngreso->inactivarItem($ingreso['id_impuesto'],
																	 	$ingreso['id_rubro'],
																	 	$ingreso['exigibilidad_periodo'],
																	 	$conexion,
																	 	$conn);

								if ( !$result ) {
									$mensajes[] = Yii::t('backend', 'Operacion cancelada no de pudo cargar la estimada del lapso continuo');
									break;
								}

							}

							if ( $result ) {

								$result = false;
								$modelActEconIngreso = New ActEconIngreso();

								// Se anulo las registros existentes ahora se cargan los nuevos.
								foreach ( $modelDeclaracion as $declaracion ) {

									// Se crea el arreglo de datos para guardar el registro
									$arreglo = $modelActEconIngreso->attributes;

									$idRubro = (int)$searchIngreso->getIdRubro($declaracion['id_rubro'], $añoEstimada);
									$rangoFecha = self::getRangoFechaDeclaracion($añoEstimada);

									$arreglo['id_impuesto'] = (int)$idActEcon['id'];
									//$arreglo['id_rubro'] = $declaracion['id_rubro'];
									$arreglo['id_rubro'] = $idRubro;
									$arreglo['exigibilidad_periodo'] = (int)$declaracion['exigibilidad_periodo'];
									$arreglo['periodo_fiscal_desde'] = $rangoFecha['fechaDesde'];
									$arreglo['periodo_fiscal_hasta'] = $rangoFecha['fechaHasta'];

									$arreglo['estimado'] = $declaracion['monto_new'];
									$arreglo['usuario'] = $usuario;
									$arreglo['fecha_hora'] = date('Y-m-d H:i:s');
									$arreglo['inactivo'] = 0;
									$arreglo['condicion'] = 3;		// Rubro cargado por oficio o codigo

									$result = $searchIngreso->guardar($arreglo, $conexion, $conn);
									if ( !$result ) {
										$mensajes[] = Yii::t('backend', 'No se guardo la estimada');
										break;
									}
								}
							}

						} else {

							// No Existe registros en la estimada.
							$searchIngreso = New ActEconIngresoSearch($this->_id_contribuyente);

							$result = false;
							$modelActEconIngreso = New ActEconIngreso();

							// Se anulo las registros existentes ahora se cargan los nuevos.
							foreach ( $modelDeclaracion as $declaracion ) {

								// Se crea el arreglo de datos para guardar el registro
								$arreglo = $modelActEconIngreso->attributes;

								$idRubro = (int)$searchIngreso->getIdRubro($declaracion['id_rubro'], $añoEstimada);
								$rangoFecha = self::getRangoFechaDeclaracion($añoEstimada);

								$arreglo['id_impuesto'] = (int)$idActEcon['id'];
								$arreglo['id_rubro'] = $idRubro;
								$arreglo['exigibilidad_periodo'] = (int)$declaracion['exigibilidad_periodo'];
								$arreglo['periodo_fiscal_desde'] = $rangoFecha['fechaDesde'];
								$arreglo['periodo_fiscal_hasta'] = $rangoFecha['fechaHasta'];

								$arreglo['estimado'] = $declaracion['monto_new'];
								$arreglo['usuario'] = $usuario;
								$arreglo['fecha_hora'] = date('Y-m-d H:i:s');
								$arreglo['inactivo'] = 0;
								$arreglo['condicion'] = 3;

								$result = $searchIngreso->guardar($arreglo, $conexion, $conn);
								if ( !$result ) {
									$mensajes[] = Yii::t('backend', 'No se guardo la estimada');
									break;
								}
							}
						}
					}
	    		}
	    	}

			return $mensajes;
	    }




	    /**
	     * Metodo que retorna la consulta del historico de declaraciones por el
	     * lapso declarado, estructura del lapso:
	     * 9999 - 9.
	     * @param  integer $añoImpositivo año del lapso consultado.
		 * @param  integer $periodo periodo del lapso consultado.
		 * @return array retorna un arreglo con la informacion de la consulta
		 * sobre la entidad "HistoricoDeclaracion".
	     */
	    public function getHistoricoDeclaracionSegunLapso($añoImpositivo, $periodo)
	    {
	    	$historico = New HistoricoDeclaracionSearch($this->_id_contribuyente);
	    	return $historico->findHistoricoDeclaracionSegunLapso($añoImpositivo, $periodo);
	    }



	    /***/
	    public function getDataProviderHistoricoDeclaracionSegunLapso($añoImpositivo, $periodo)
	    {
	    	$query = self::getHistoricoDeclaracionSegunLapso($añoImpositivo, $periodo);

	    	$dataProvider = New ActiveDataProvider([
	    						'query' => $query,
	    		]);
	    	$query->all();
	    	return $dataProvider;
	    }





	    /**
	     * Metodo que permite controlarar el monto declarado den la definitiva.
	     * A partir del año es 2017 se debe controlar que la suma de los montos declarados
	     * en la estimada no sea no sea inferior a la definitiva anterior.
	     * @param  integer $añoImpositivo año de la declaracion.
	     * @param  integer $periodo periodo de la declaracion.
	     * @param  array $postEnviado post enviaado.
	     * @return string retorna mensaje si encuentra una situacion irregular, en caso contrario
	     * debe enviar mensaje.
	     */
	    public function controlDeclaracionEstimada($añoImpositivo, $periodo, $postEnviado)
	    {
	    	$mensajeDeclaracion = '';

	    	if ( $añoImpositivo >= 2017 ) {
	    		$montoMinimo = self::montoMinimoEstimada($añoImpositivo, $periodo);


		    	$sumaDeclarado = 0;
		    	foreach ( $postEnviado as $post ) {
		    		$sumaDeclarado = $sumaDeclarado + $post['monto_new'];
		    	}

		    	if ( $sumaDeclarado < $montoMinimo ) {
		    		$descreto = 'De conformidad con el DECRETO Nro. FGDS-I-048-2016, de fecha 29-12-2016, LA SUMA DE LO DECLARADO NO DEBE SER INFERIOR A ' . number_format($montoMinimo, 2);
		    		$mensajeDeclaracion = $descreto;
		    	}
		    }

	    	return $mensajeDeclaracion;
	    }





	    /***/
	    public function montoMinimoEstimada($añoImpositivo, $periodo)
	    {
	    	$minimo = 0;
	    	$porcentajeIncremento = 1.65;
	    	if ( $añoImpositivo >= 2017 ) {
	    		$findModel = self::findRubrosRegistrados($añoImpositivo - 1, $periodo);
	    		$resultados = $findModel->all();

	    		$suma = 0;
	    		if ( count($resultados) > 0 ) {
	    			foreach ( $resultados as $resultado ) {
	    				$suma = $resultado['reales'] + $suma;
	    			}
	    		}

	    		$suma =  $suma * $porcentajeIncremento;
	    	}

	    	return $suma;
	    }



	    /**
	     * Metodo que a traves del año impositivo, define su año anterior y con estos valores
	     * determina si ambos pertenecen a la misma ordenanza.
	     * @param integer $añoImpositivo año impositivo de la declaracion.
	     * @return boolean retorna true si ambos coinciden en la misma ordenanza o false caso
	     * contrario.
	     */
	    public function esLaMismaOrdenaza($añoImpositivo)
	    {
	    	$añoInterior = $añoImpositivo - 1;
	    	$result = false;
	    	return $result = OrdenanzaBase::anoMismaOrdenanza($añoImpositivo, $añoInterior, 1);

	    }



	}
 ?>