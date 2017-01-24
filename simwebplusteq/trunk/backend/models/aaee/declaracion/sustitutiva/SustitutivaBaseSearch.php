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
 *  @file SustitutivaBaseSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class SustitutivaBaseSearch
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

 	namespace backend\models\aaee\declaracion\sustitutiva;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
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
	use backend\models\aaee\declaracion\tipodeclaracion\TipoDeclaracionForm;



	/**
	 * Clase que gestiona el funcionamiento de la solicitud para la declaracion
	 * de los montos. La clase gestiona la declaracion de la sustitutiva.
	 *
	 */
	class SustitutivaBaseSearch extends SustitutivaBase
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



		/***/
		public function getListaTipoDeclaracion($listaTipo)
		{
			$findModel = TipoDeclaracionForm::getListaTipoDeclaracion($listaTipo);
			if ( count($findModel) > 0 ) {
				return ArrayHelper::map($findModel, 'tipo_declaracion', 'descripcion');
			}
			return false;
		}



		/**
		 * Metodo que realiza una busqueda de la solicitud por concepto de declaracion.
		 * El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudSustitutiva($nroSolicitud)
		{
			$findModel = SustitutivaBase::find()->where('nro_solicitud =:nro_solicitud',
														 	[':nro_solicitud' => $nroSolicitud])
										        ->andWhere('id_contribuyente =:id_contribuyente',
											   				[':id_contribuyente' => $this->_id_contribuyente]);

			return isset($findModel) ? $findModel : null;
		}




		/**
		 * Metodo que permite determinar si existe una solicitud similar pendiente
		 * segun los parametros de consulta.
		 * @param  integer $idImpuesto identificador de la entidad maestro de la
		 * declaracion, "ect-econ".
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @return boolean retorna un true si ya existe solicitud similar pendiente, sino
		 * retorna false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente($idImpuesto, $periodo)
		{
			$count = 0;
			$findModel = self::yaPoseeSolicitud($idImpuesto, $periodo, 0);

			if ( count($findModel) > 0 ) {
				$count = $findModel->count();
			}

			return ( $count > 0 ) ? true : false;
		}



		/**
		 * Metodo que permite determinar si existe una solicitud similar aprobada
		 * segun los parametros de consulta.
		 * @param  integer $idImpuesto identificador de la entidad maestro de la
		 * declaracion, "ect-econ".
		 * @param  integer $periodo periodo del lapso a consultar.
		 * @return boolean retorna un true si ya existe solicitud similar aprobada, sino
		 * retorna false.
		 */
		public function yaPoseeSolicitudSimiliarAprobada($idImpuesto, $periodo)
		{
			$count = 0;
			$findModel = self::yaPoseeSolicitud($idImpuesto, $periodo, 1);

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
		private function yaPoseeSolicitud($idImpuesto, $periodo, $estatus)
		{
			$findModel = SustitutivaBase::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $this->_id_contribuyente])
										  	    ->andWhere('id_impuesto =:id_impuesto',
										  	      				[':id_impuesto' => $idImpuesto])
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
	    	$query = self::findSolicitudSustitutiva($nroSolicitud);

	    	$dataProvider = new ActiveDataProvider([
            	'query' => $query,
        	]);
	    	$query->all();

        	return $dataProvider;
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
	   	public function getDefinitiva($añoImpositivo, $periodo, $rubro = 0)
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
	    public function getListaAnoRegistrado($tipoDeclaracion, $consulta = false)
	    {
	    	$listaAño = [];
	    	$añoActual = date('Y');
	    	$añoLimite = Yii::$app->lapso->anoLimiteNotificado();

	    	$findModel = null;
	    	if ( $tipoDeclaracion == 1 ) {		// Estimada

	    		if ( !$consulta ) {

			    	$findModel = ActEcon::find()->distinct('ano_impositivo')
			    								->where('id_contribuyente =:id_contribuyente',
			    	 										['id_contribuyente' => $this->_id_contribuyente])
			    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
			    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
			    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
			    							    ->andWhere(['BETWEEN', 'ano_impositivo', $añoActual, $añoActual])
			    							    ->joinWith('actividadDetalle', false, 'INNER JOIN')
			    							    ->orderBy([
			    							   		'ano_impositivo' => SORT_ASC,
			    							   	])
			    							    ->all();
			    } else {

			    	$findModel = ActEcon::find()->distinct('ano_impositivo')
			    								->where('id_contribuyente =:id_contribuyente',
			    	 										['id_contribuyente' => $this->_id_contribuyente])
			    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
			    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
			    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
			    							    ->andWhere(['BETWEEN', 'ano_impositivo', $añoLimite, $añoActual])
			    							    ->joinWith('actividadDetalle', false, 'INNER JOIN')
			    							    ->orderBy([
			    							   		'ano_impositivo' => SORT_ASC,
			    							   	])
			    							    ->all();

			    }

		    } elseif ( $tipoDeclaracion == 2 ) {	// Definitiva

		    	if ( !$consulta ) {

			    	$findModel = ActEcon::find()->distinct('ano_impositivo')
			    								->where('id_contribuyente =:id_contribuyente',
			    	 										['id_contribuyente' => $this->_id_contribuyente])
			    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
			    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
			    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
			    							    ->andWhere(['BETWEEN', 'ano_impositivo', $añoActual - 1, $añoActual - 1])
			    							    ->joinWith('actividadDetalle', false, 'INNER JOIN')
			    							    ->orderBy([
			    							   		'ano_impositivo' => SORT_ASC,
			    							   	])
			    							    ->all();
			    } else {

			    	$findModel = ActEcon::find()->distinct('ano_impositivo')
			    								->where('id_contribuyente =:id_contribuyente',
			    	 										['id_contribuyente' => $this->_id_contribuyente])
			    			  				    ->andWhere('estatus =:estatus', [':estatus' => 0])
			    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
			    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
			    							    ->andWhere(['BETWEEN', 'ano_impositivo', $añoLimite, $añoActual - 1])
			    							    ->joinWith('actividadDetalle', false, 'INNER JOIN')
			    							    ->orderBy([
			    							   		'ano_impositivo' => SORT_ASC,
			    							   	])
			    							    ->all();

			    }

		    }

	    	if ( count($findModel) > 0 ) {
	    		$listaAño = ArrayHelper::map($findModel, 'ano_impositivo', 'ano_impositivo');
	    	}

	    	return $listaAño;
	    }



	    /**
	     * Metodo que segun el año impositivo determina la exigibilidad de declaracion
	     * (cantidad de veces que se pueden declarar en un año), para generar una lista
	     * combo de numeros enteros.
	     * @param  inetger $añoImpositivo entero que especifica el año.
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
	    	$tablaAct = ActEcon::tableName();
	    	$tablaRubro = Rubro::tableName();
	    	$tablaIngreso = ActEconIngreso::tableName();
	    	$findModel = ActEconIngreso::find()->where('id_contribuyente =:id_contribuyente',
	    													[':id_contribuyente' => $this->_id_contribuyente])
	    	                                   ->andwhere($tablaAct . '.ano_impositivo =:ano_impositivo',
	    													[':ano_impositivo' => $añoImpositivo])
	    	                                   ->andWhere('estatus =:estatus',
	    	                                    			[':estatus' => 0])
	    									   ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
	    									   				[':exigibilidad_periodo' => $periodo])
	    									   ->andWhere($tablaIngreso . '.inactivo =:inactivo',
	    									    			[':inactivo' => 0])
	    									   ->andWhere($tablaRubro . '.inactivo =:inactivo',
	    									    			[':inactivo' => 0])
	    									   ->joinWith('actividadEconomica', true, 'INNER JOIN')
	    									   ->joinWith('rubroDetalle', true, 'INNER JOIN');

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
	    	$idImpuesto = 0;
	    	$aprobada = false;
	    	$pendiente = false;
	    	$tipo = '';

	    	if ( $tipoDeclaracion == 1 ) {
	    		$tipo = 'ESTIMADA';
	    	} elseif ( $tipoDeclaracion == 2 ) {
	    		$tipo = 'DEFINITIVA';
	    	}

	    	$idImpuesto = self::getIdImpuestoSegunAnoImpositivo($añoImpositivo);

	    	// Se determina si existen otras solicitudes que no permitan la creacion
	    	// de la solicitud de declaracion estimada. Lo siguiente debe retornar
	    	// un arreglo de mensajes o un arreglo vacio.
	    	$solicitud = self::getOtraSolicitudPendiente($añoImpositivo, $periodo);

	    	// Lo siguiente controla si existe una solicitud similar pendiente o
	    	// aprobada. Retorna un boolean que indica.

	    	$pendiente = self::yaPoseeSolicitudSimiliarPendiente($idImpuesto, $periodo);
	    	if ( $pendiente ) {
	    		$mensajePendiente = Yii::t('backend', "Existe solicitud similar pendiente para el lapso {$añoImpositivo} - {$periodo}");
	    	}

	    	//no va
	    	//$aprobada = self::yaPoseeSolicitudSimiliarAprobada($idImpuesto, $periodo);
	    	if ( $aprobada ) {
	    		$mensajeAprobada = Yii::t('backend', "Existe solicitud similar aprobada para el lapso {$añoImpositivo} - {$periodo}");
	    	}



	    	// Se arma todo el arreglo de mensajes.
	    	if ( count($solicitud) > 0 ) {
	    		$mensajes[] = $solicitud;
	    	}

	    	if ( !self::declaracionValida($idImpuesto, $periodo, $tipoDeclaracion) ) {
	    		$mensajes[] = Yii::t('backend', 'La declaración ' . $tipo . ' del lapso ' . $añoImpositivo . ' - ' . $periodo . ', NO EXISTE. Cargue los montos de la declaración ' . $tipo . ' de dicho lapso, luego podrá realizar la sustitutiva.' );
	    	}


	    	if ( trim($mensajePendiente) !== "" ) {
	    		$mensajes[] = $mensajePendiente;
	    	}

	    	if ( trim($mensajeAprobada) !== "" ) {
	    		$mensajes[] = $mensajeAprobada;
	    	}

	    	// if ( count($definitiva) > 0 ) {
	    	// 	$mensajes[] = $definitiva;
	    	// }

	    	// if ( count($mensajePago) > 0 ) {
	    	// 	$mensajes[] = $mensajePago;
	    	// }

	    	// if ( count($mensajeReciboPendiente) > 0 ) {
	    	// 	$mensajes[] = $mensajeReciboPendiente;
	    	// }


	    	// if ( count($definitivaExistente) > 0 ) {
	    	// 	$mensajes[] = $definitivaExistente;
	    	// }

	    	return $mensajes;

	    }



	    /**
	     * Metodo que permite determinar si una declaracion de un laspo determinado
	     * posee montos cargadas. Aqui se considera como montos cargados, aquella
	     * declaracion cuya suma de los montos de cada rubro en estatus activos y
	     * desbloqueados es igual o mayor a cero (0).
	     *
	     * @param  integer $idImpuesto identificador de la entidad "act-econ" que es donde
	     * se guarda el maestro de la declaracion.
	     * @param  integer $periodo identifica el periodo dentro del lapso.
	     * @param  integer $tipoDeclaracion indica el tipo de declracion la cual se le cargara
	     * la sustitutiva.
	     * - tipo 1: estimada.
		 * - tipo 2: definitiva.
	     * @return boolean retorna un true o false.
	     */
	    public function declaracionValida($idImpuesto, $periodo, $tipoDeclaracion)
	    {
	    	$result = false;
	    	$suma = 0;
	    	$findModel = self::findActEconIngresos($idImpuesto, $periodo);
	    	$resultados = $findModel->asArray()->all();

	    	if ( count($resultados) > 0 ) {

	    		if ( $tipoDeclaracion == 1 ) {			// Estimada

	    			foreach ( $resultados as $resultado ) {
	    				$suma += $resultado['estimado'];
	    			}

	    		} elseif ( $tipoDeclaracion == 2 ) {	// Definitiva

	    			foreach ( $resultados as $resultado ) {
	    				$suma += $resultado['reales'];
	    			}

	    		}
	    	}

	    	if ( $suma > 0 ) { $result = true; }
	    	return $result;
	    }



	}
 ?>