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
 *  @file AutorizarRamoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-08-2016
 *
 *  @class AutorizarRamoSearch
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

 	namespace backend\models\aaee\autorizarramo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\aaee\Sucursal;
	use backend\models\aaee\autorizarramo\AutorizarRamo;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\aaee\rubro\RubroForm;
	use backend\models\aaee\rubro\Rubro;

	/**
	 * Clase que gestiona el funcionamiento de la solicitud para la autorizacion de
	 * ramos (rubro).
	 */
	class AutorizarRamoSearch extends AutorizarRamo
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
		 * Metodo que realiza una busqueda de la solicitud por concepto de autorizacion de ramo.
		 * El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudAutorizarRamo($nroSolicitud)
		{
			$findModel = AutorizarRamo::find()->where('nro_solicitud =:nro_solicitud',
													 			[':nro_solicitud' => $nroSolicitud])
											  ->andWhere('id_contribuyente =:id_contribuyente',
											   					[':id_contribuyente' => $this->_id_contribuyente]);

			return isset($findModel) ? $findModel : null;
		}



		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($añoDesde, $añoHasta)
		{
			$modelFind = null;
			$modelFind = AutorizarRamo::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $this->_id_contribuyente])
											  ->andWhere(['BETWEEN', 'ano_impositivo', $añoDesde, $añoHasta])
											  ->andWhere(['IN', 'estatus', [0]])
											  ->count();
			return ( $modelFind > 0 ) ? true : false;
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
		 * Metodo que retorna los datos del contribuyente, segun el identificador
		 * del contribuyente, lo recibido en este metodo es un arreglo donde el
		 * indice del arregloes un entero que comienza en cero (0), y el valor del
		 * arreglo es otro arrelo con los atributos del contribuyente.
		 * @return array retorna un arreglo con la estructura:
		 * array {
		 * 		[0] => array {
		 *   				atributo0 => valor0
		 *       			atributo1 => valor1
		 *
		 *            		atributon => valorn
		 *   			}
		 * }
		 */
		public function getDatosContribuyente()
		{
			$datos = ContribuyenteBase::getDatosContribuyenteSegunID($this->_id_contribuyente);
			return (isset($datos)) ? $datos[0] : null;
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
	    	$query = self::findSolicitudAutorizarRamo($nroSolicitud);

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




	    /**
	     * [determinarPrimerAnoCatalogoRubro description]
	     * @return retorna un integer con 4 digitos.
	     */
	    public function determinarPrimerAnoCatalogoRubro()
	    {
	    	return $anoInicioCatalogo = RubroForm::getPrimerAnoCatalogoRubro();
	    }




	    /**
	     * [determinarUltimoAnoCatalogoRubro description]
	     * @return @return retorna un integer con 4 digitos.
	     */
	    public function determinarUltimoAnoCatalogoRubro()
	    {
	    	return $anoFinalCatalogo = RubroForm::getUltimoAnoCatalogoRubro();
	    }




	    /**
	     * Metodo que permite determinar el año del catalogo de rubro que le corresponde
	     * según el año de inicio de actividades del contribuyente juridico, la intencion
	     * es determinar el año del catalogo de rubros para listarlos.
	     * @param $añoInicio, integer que define el año de inicio de actividades, esto deriva
	     * de la fecha de inicio del contribuyente.
	     * @return returna integer, año de 4 digitos o cero (0) si no logra determinar el año.
	     */
	    public function determinarAnoCatalogoSegunAnoInicio($añoInicio)
	    {
	    	if ( $añoInicio > 0 ) {
	    		$primerAnoCatalogo = self::determinarPrimerAnoCatalogoRubro();
	    		$ultimoAnoCatalogo = self::determinarUltimoAnoCatalogoRubro();
	    		if ( $primerAnoCatalogo > 0 && $ultimoAnoCatalogo > 0 ) {
	    			if ( $añoInicio == $primerAnoCatalogo ) {
	    				return $primerAnoCatalogo;

	    			} elseif ( $añoInicio < $primerAnoCatalogo ) {
	    				return $primerAnoCatalogo;

	    			} elseif ( $añoInicio > $primerAnoCatalogo ) {
	    				for ($i = $primerAnoCatalogo; $i <= $ultimoAnoCatalogo; $i++ ) {
	    					if ( $i == $añoInicio ) {
	    						return $añoInicio;
	    					}
	    				}
	    			}
	    		}
	    	}
	    	return 0;
	    }



	    /**
	     * Metodo que permite obtener un dataProvider que permite generar un catalogo de los
	     * rubros según un año y paramatros adicionales.
	     * @param  [type] $anoImpositivo [description]
	     * @param  string $params        [description]
	     * @return returna un a instancia de tipo dataProvider.
	     */
	    public function getDataProvider($anoImpositivo, $params = '')
	    {
	    	return RubroForm::getDataProviderRubro($anoImpositivo, $params);
	    }



	    /***/
	    public function getDataProviderAddRubro($arrayRubros)
	    {
	    	return RubroForm::getAddDataProviderRubro($arrayRubros);
	    }


	    /***/
	    public function getAnoSegunFecha($fecha)
	    {
	    	if ( isset($fecha) ) {
	    		if ( $fecha == '0000-00-00' ) {
	    			return 0;
	    		} else {
	    			return date('Y', strtotime($fecha));
	    		}
	    	}
	    	return 0;
	    }



	    /***/
	    public function getVencimientoOrdenanza($añoCatalogo)
	    {
	    	return OrdenanzaBase::getAnoVencimientoOrdenanzaSegunAnoImpositivo($añoCatalogo, 1);
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




	    /***/
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
	     * Metodo que determina que rango de ordenanza estan sin registros en
	     * la entidad "act-econ", segun el año de inicio de actividades y de
	     * considerar si el mismo esta notificado de su deuda (por defecto se
	     * asume que si), se realiza una busqueda de todas las ordenanzas y se
	     * filtra en aquellas que ya existan registros en la entidad "act-econ".
	     * Se considera el limite para tomar como rango declarable, utilizando
	     * el parametro $añoLimite.
	     * @param  integer  $añoInicioActividad año en que inicio la actividad
	     * economica. Valor que de determina con la fecha inicio del contribuyente.
	     * @param  boolean $notificado indica si al contribuyente se le ha notificado
	     * su deuda. Se asume por defecto true.
	     * @return array retorna un arreglo si encuentra ordenanzas por declarar,
	     * este arreglo contiene en su indice el año de creacion de la ordenanza y
	     * el valor del elemento el año de vencimiento de la ordenanza.
	     */
	    public function getRangoOrdenanza($añoInicioActividad, $notificado = true)
	    {
	    	$rangoOrdenanza = [];
	    	$añoInicial = self::getAnoLimiteSegunAnoInicio($añoInicioActividad, $notificado = true);

	    	if ( $añoInicial > 0 ) {
		    	// Se recibe un array donde el indice del arreglo es al año de creacion de la
		    	// ordenanza y el valor del elemento es el año de vencimiento de la ordenanza
		    	// esquema:
		    	// arreglo[indice] => elemento
		    	$rango = OrdenanzaBase::getRangoAnoOrdenanzaSegunImpuesto(1, $añoInicial);

		    	if ( count($rango) > 0 ) {
		    		foreach ( $rango as $key => $value ) {
		    			if ( !self::existeDeclaracionParaRangoOrdenanza($key, $value) ) {
		    				$rangoOrdenanza[$key] = $value;
		    			}
		    		}
		    	}
		    }

	    	return $rangoOrdenanza;
	    }




	    /**
	     * Metodo que determina el año de limite de actividades que se puede tomar
	     * en cuenta para los proceso de declaracion y calculo, segun el año de inicio
	     * de actividades del contribuyente y los lapso que se deban de considerar, si
	     * el mismo esta notificado o no.
	     * @param  integer  $añoInicioActividad año en que inicio la actividad
	     * economica. Valor que de determina con la fecha inicio del contribuyente.
	     * @param  boolean $notificado indica si al contribuyente se le ha notificado
	     * su deuda. Se asume por defecto true.
	     * @return integer retorna año limite de inicio de actividades para iniciar los
	     * procesos relacionados a las declaraciones y claculos.
	     */
	    public function getAnoLimiteSegunAnoInicio($añoInicioActividad, $notificado = true)
	    {
	    	$añoInicial = 0;
	    	if ( $notificado ) {
	    		$añoLimite = Yii::$app->lapso->anoLimiteNotificado();
	    	} else {
	    		$añoLimite = Yii::$app->lapso->anoLimiteSinNotificar();
	    	}
	    	if ( $añoInicioActividad < $añoLimite ) {
	    		$añoInicial = $añoLimite;
	    	} else {
	    		$añoInicial = $añoInicioActividad;
	    	}
	    	return $añoInicial;
	    }



	    /***/
	    public function getArrayDataProviderOrdenanza($rangoOrdenanza = [])
	    {
	    	$provider = null;
	    	$rango = [];

	    	// if ( count($rangoOrdenanza) == 0 ) {
	    	// 	$rangoOrdenanza = self::getRangoOrdenanza($añoInicioActividad, $notificado);
	    	// }

	    	foreach ( $rangoOrdenanza as $key => $value ) {
    			$rango[$key] = [
    				'desde' => $key,
    				'hasta' => $value,
    			];
    		}

	    	if ( count($rango) > 0 ) {
				$provider = New ArrayDataProvider([
							'allModels' => $rango,
							'pagination' => [
								'pageSize' => 10,
							],
							'sort' => [
								'attributes' => ['desde', 'hasta'],
							],
					]);
    		}
	    	return $provider;
	    }


	}
 ?>