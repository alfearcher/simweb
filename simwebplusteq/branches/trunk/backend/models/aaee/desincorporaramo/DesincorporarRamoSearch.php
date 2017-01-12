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
 *  @file DesincorporarRamoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-09-2016
 *
 *  @class DesincorporarRamoSearch
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

 	namespace backend\models\aaee\desincorporaramo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use common\models\aaee\Sucursal;
	use backend\models\aaee\desincorporaramo\DesincorporarRamo;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use common\models\ordenanza\OrdenanzaBase;
	use backend\models\aaee\rubro\RubroForm;
	use backend\models\aaee\rubro\Rubro;
	use yii\helpers\ArrayHelper;
	use backend\models\aaee\declaracion\DeclaracionBase;


	/**
	 * Clase que gestiona el funcionamiento de la solicitud para el anexo de
	 * ramos (rubro).
	 */
	class DesincorporarRamoSearch extends DesincorporarRamo
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
		 * Metodo que realiza una busqueda de la solicitud por concepto de desincorporacion
		 *  de ramo. El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudDesincorporarRamo($nroSolicitud)
		{
			$findModel = DesincorporarRamo::find()->where('nro_solicitud =:nro_solicitud',
													 			[':nro_solicitud' => $nroSolicitud])
										          ->andWhere('id_contribuyente =:id_contribuyente',
											   					[':id_contribuyente' => $this->_id_contribuyente]);

			return isset($findModel) ? $findModel : null;
		}



		/**
		 * Metodo que realice una conaulta para determinar si existe una solicitud
		 * pendiente para desincorporar ramo segun el año-periodo indicado.
		 * @param  integer $añoImpositivo año del lapso que se desea consultar.
	     * @param  integer $periodo periodo del lapso que se desea consultar.
		 * @return active record retorna una modelo de la entidad "sl", donde
		 * se guarda la solicitud. En casocontrario un arreglo vacio.
		 */
		public function findSolicitudDesincorporarRamoSegunLapso($añoImpositivo, $periodo)
		{
			$findModel = DesincorporarRamo::find()->where('id_contribuyente =:id_contribuyente',
	    												[':id_contribuyente' => $this->_id_contribuyente])
	    								          ->andWhere('estatus =:estatus',
	    												[':estatus' => 0])
			    								  ->andWhere(DesincorporarRamo::tableName().'.ano_impositivo =:ano_impositivo',
			    									  			[':ano_impositivo' => $añoImpositivo])
			    								  ->andWhere('periodo =:periodo',
			    									  			[':periodo' => $periodo])
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
		public function yaPoseeSolicitudSimiliarPendiente($idRubro)
		{
			$modelFind = null;
			$modelFind = DesincorporarRamo::find()->where('id_contribuyente =:id_contribuyente',
			 													[':id_contribuyente' => $this->_id_contribuyente])
										          ->andWhere('id_rubro =:id_rubro',
										           				[':id_rubro' => $idRubro])
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
	    	$query = self::findSolicitudDesincorporarRamo($nroSolicitud);

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
	    public function getDataProviderAddRubro($arrayRubros)
	    {
	    	return RubroForm::getAddDataProviderRubro($arrayRubros);
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
	     * 		[id-impuesto] => año
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
	    			  				    ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
	    			  				    ->andWhere('bloqueado =:bloqueado', [':bloqueado' => 0])
	    							    ->andWhere('ano_impositivo >=:ano_impositivo',
	    							    			[':ano_impositivo' => $añoLimite])
	    							    ->joinWith('actividadDetalle', 'INNER JOIN', false)
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
	    									   ->andWhere('bloqueado =:bloqueado',
	    									   				[':bloqueado' => 0])
	    									   ->andWhere($tablaRubro . '.inactivo =:inactivo',
	    									    			[':inactivo' => 0])
	    									   ->joinWith('actividadEconomica', 'INNER JOIN', false)
	    									   ->joinWith('rubroDetalle', 'INNER JOIN');
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





	    /***/
	    public function getDataProviderRubroDesincorporar($añoImpositivo, $periodo, $chkSeleccionJson)
	    {
	    	//$query = self::findRubrosRegistrados($añoImpositivo, $periodo);

	    	$query = ActEconIngreso::find();

	    	$chkRubroSeleccionado = [];
	    	$chkIdRubro = [];
	    	$chkIdImpuesto = [];
	    	$chkPeriodo = [];
	    	foreach ( $chkSeleccionJson as $seleccion ) {
	    		// Cada $seleccion es un elemento json, el json_decode genera
	    		// un objeto json. Cada elemento de $chkRubroSeleccionado[] es un
	    		// objeto tipo json.
	    		$chkRubroSeleccionado[] = json_decode($seleccion);
	    	}

	    	if ( count($chkRubroSeleccionado) > 0 ) {
	    		foreach ( $chkRubroSeleccionado as $objRubro ) {
	    			$chkIdImpuesto[] = $objRubro->{'id_impuesto'};
	    			$chkIdRubro[] = $objRubro->{'id_rubro'};
	    			$chkPeriodo[] = $objRubro->{'exigibilidad_periodo'};
	    		}
	    	}

	    	$dataProvider = New ActiveDataProvider([
	    			'query' => $query,
	    	]);

	    	$query->where(['in', 'id_impuesto', $chkIdImpuesto])
	    	 	  ->andFilterWhere(['in', '.id_rubro', $chkIdRubro])
	    		  ->andFilterWhere(['in', 'exigibilidad_periodo', $chkPeriodo])
	    		  ->andWhere('inactivo =:inactivo',[':inactivo' => 0])
	    		  ->andWhere('bloqueado =:bloqueado',[':bloqueado' => 0]);

	    	return $dataProvider;

	    }



	    /**
	     * Metodo que permite determinar si se puede realizar otra solicitud de
	     * desincorporacion, tomando en consideracion la cantidad de rubros registrados
	     * en el año-periodo consultado y la cantidad de solicitudes de desincorporacion
	     * existentes de estos mismo rubros. Ejemplo.
	     *
	     * Sea:
	     * 		i = Cantidad de rubros registrados validos y activos.
	     *   	d = Cantidad de solicitudes pendientes por desincorporacion de dichos rubros.
	     *
	     * Si i == d ó i == d + 1 ó i < d, entonces no se podra permitir otra solicitud de
	     * desincorporacion.
	     * No deberia ocurrir i == d, por eso se considera aqui para evitar otra solicitud.
	     * @param  integer $añoImpositivo año impositivo del lapso
	     * @param  integer $periodo periodo del lapso.
	     * @return boolean retorna true si puede realizar otra solicitud, en caso contrario
	     * retornara false.
	     */
	    public function puedoIniciarOtraSolicitud($añoImpositivo, $periodo)
	    {
	    	$result = false;
	    	$countIdRubro = 0;

	    	// Se busca los identificadores de los rubros registrados, segun el año-periodo
	    	// especificado. Se espera recibir un array de identificadores.
	    	$listaIdRubro = self::getListaIdRubrosRegistrados($añoImpositivo, $periodo);
	    	$countIdRubro = count($listaIdRubro);

	    	if ( count($countIdRubro) > 0 ) {

	    		// Contador de las solicitudes pendientes.
	    		$countSolicitud = 0;


	    		// Una vez encontrado el o los identificadores, se debe verificar cuales de
	    		// estos rubros poseen ya una solicitud de desincorporacion pendiente.
	    		foreach ( $listaIdRubro as $key => $value ) {
	    			if ( self::yaPoseeSolicitudSimiliarPendiente($value) ) {
	    				$countSolicitud = ++$countSolicitud;
	    			}
	    		}

	    		if ( $countIdRubro == $countSolicitud || $countIdRubro == ++$countSolicitud || $countIdRubro < $countSolicitud ) {
	    			$result = false;
	    		} else {
	    			$result = true;
	    		}
	    	}

	    	return $result;
	    }



	    /**
	     * Metodo que genera el modelo para realizar las consultas en la entidad
	     * "sl-declaraciones".
	     * @param  integer $añoImpositivo año del lapso a consultar.
	     * @param  integer $periodo periodo del lapso a consultar
	     * @param  integer $tipoDeclaracion tipo de declaracion:
	     * - tipo 1: estimada.
	     * - tipo 2: definitiva.
	     * @param  integer $estatus condicion del registro.
	     * @return active record retorna un modelo de la entidad "sl-declaraciones".
	     */
	    private function findSolicitudDeclacionSegunLapso($añoImpositivo, $periodo, $tipoDeclaracion, $estatus)
	    {
	    	$findModel = DeclaracionBase::find()->where('id_contribuyente =:id_contribuyente',
	    												['id_contribuyente' => $this->_id_contribuyente])
	    										->andWhere('estatus =:estatus',
	    												[':estatus' => $estatus])
	    										->andWhere('ano_impositivo =:ano_impositivo',
	    												[':ano_impositivo' => $añoImpositivo])
	    										->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
	    												[':exigibilidad_periodo' => $periodo])
	    										->andWhere('tipo_declaracion =:tipo_declaracion',
	    												[':tipo_declaracion' => $tipoDeclaracion]);

	    	return ( count($findModel) > 0 ) ? $findModel : [];
	    }





	    /***/
	    public function yaPoseeSolicitudDeclaracionEstimada($añoImpositivo, $periodo, $estatus)
	    {
	    	$count = 0;
	    	$result = false;
	    	$findModel = self::findSolicitudDeclacionSegunLapso($añoImpositivo, $periodo, 1, $estatus);

	    	$count = (int)$findModel->count();

	    	if ( $count > 0 ) { $result = true; }

	    	return $result;

	    }




	    /**
	     * Metodo que permite validar la logica de negocio que se aplicara para
	     * elaborar una soliciitud de Desincorporacion de Ramo. Cada instancia que
	     * no se cumpla satisfactoriamente generara un mensaje que luego sera incluido
	     * en un arreglo de mensajes.
	     * @param  integer $añoImpositivo año del lapso a consultar.
	     * @param  integer $periodo periodo del lapso a consultar
	     * @return array retorna un arreglo de mensajes, el mismo puede ser vacio.
	     */
	    public function validarEvento($añoImpositivo, $periodo)
	    {
	    	$mensajes = [];
	    	$mensajeEstimada = '';
	    	$mensajeIniciar = '';

	    	// Se verifica si existe una solicitud de declaracion estimada pendiente.
	    	$estimada = self::yaPoseeSolicitudDeclaracionEstimada($añoImpositivo, $periodo, 0);
	    	if ( $estimada ) {
	    		$mensajeEstimada = Yii::t('backend', "Existe una solicitud pendiente para el lapso {$añoImpositivo} - {$periodo}");
	    	}

	    	// Se verifica que el total de rubros registrados no sea igual a la cantidad
	    	// de solicitudes de deincorporacion que desee hacer. No se podra realizar la
	    	// solicitud si solo le queda un rubro por desincorporar. Lo siguinete retorna
	    	// un boolean.
	    	$puedo = self::puedoIniciarOtraSolicitud($añoImpositivo, $periodo);
	    	if ( !$puedo ) {
	    		$mensajeIniciar = Yii::t('frontend', "No es posible realizar solicitudes de este tipo para el lapso {$añoImpositivo} - {$periodo}");
	    	}


	    	// Se arma el arreglo de mensajes.
	    	if ( trim($mensajeEstimada) !== '' ) {
	    		$mensajes[] = $mensajeEstimada;
	    	}

	    	if ( trim($mensajeIniciar) !== '' ) {
	    		$mensajes[] = $mensajeIniciar;
	    	}

	    	return $mensajes;
	    }





	    /**
	     * Metodo que permite varificar si el rubro ya tiene una solicitud elaborada
	     * pendiente, o si la cantidad de rubros que tienen solicitud es menor a la
	     * cantidad de rubros registrados.
	     * @param array $checkItems arreglo de identificadores de rubros. Los key del
	     * arreglo son enteros, mientras el valor de los elementos son los identificadores
	     * de los rubros.
	     * @param integer $totalRubro entero que indica la candidad de rubros existentes
	     * en el grid o puede ser la cantidad de rubros existentes en un lapso determinado.
	     * @param  integer $añoImpositivo año del lapso a consultar.
	     * @param  integer $periodo periodo del lapso a consultar
	     * @return array retorna un arreglo de mensajes, dicho arreglo puede retornar
	     * vacio si todo se valida satisfactoriamente.
	     */
	    public function validarSeleccion($checkItems, $totalRubro, $añoImpositivo, $periodo)
	    {
	    	// foreach ( $checkItems as $jsonItems ) {
	    	// 	$checks[] = json_decode($jsonItems, true);
	    	// }

	    	// Contador de rubros que tenga registrado una solicitud pendiente.
	    	$countItemConSolicitud = 0;
	    	$mensajes = [];

	    	if ( count($checkItems) > 0 ) {
	    		foreach ( $checkItems as $i => $check ) {
	    			$result = false;
	    			$result = self::yaPoseeSolicitudSimiliarPendiente($check);
	    			if ( $result ) {
	    				$countItemConSolicitud = ++$countItemConSolicitud;
	    				$model = Rubro::findOne($check);
	    				$mensajes[] = Yii::t('backend', 'Ya existe una solicitud pendiente para el rubro: ' . $model->descripcion);
	    			}
	    		}
	    	}

	    	// Total de items seleccionados.
	    	$totalCheck = count($checkItems);

	    	// Se controla que el total de rubros seleccionados no sea igual al total de rubros
	    	// que presente declarados.
	    	if ( $totalCheck == $totalRubro ) {
	    		$mensajes[] = Yii::t('backend', 'No es posible realizar la solicitud, ya que ha seleccionado todos los rubros');
	    	}


	    	// Total de items seleccionados mas los items que ya tienen una solicitud pendiente.
	    	$total = $totalCheck + $countItemConSolicitud;
	    	if ( $total >= $totalRubro ) {
	    		$mensajes[] = Yii::t('backend', "No es posible seleccionar {$totalCheck} rubro(s), ya existe(n) {$countItemConSolicitud} rubro(s) con solicitud(es)");
	    	}


	    	// Se controla que la cantidad de rubros con solicitud mas los rubros seleccionados
	    	// no supere el numero de rubros registrados menos 1.
	    	$diferencia = 0;
	    	$diferencia = $countItemConSolicitud + $totalCheck;
	    	if ( ( $totalRubro - 1 ) < $diferencia ) {
	    		$mensajes[] = Yii::t('backend', 'No es posible realizar la solicitud, porque ha superado el limite permitido para este lapso');
	    	}

	    	return $mensajes;

	    }



	    /**
	     * Metodo que recibe un arreglo de identificadores de la entidad "act-econ-ingresos"
	     * y realiza una busqueda de cada identificador para obtener los atributos id-impuesto,
	     * id-rubro y exigibilidad. Con estos atributos se crerar una estructura tipo json que
	     * sera retornanda.
	     * @param  array $chkIdIngresoSeleccion arreglo de identificadores de la entidad
	     * "act-econ-ingresos".
	     * @return array retorna una estructura tipo json.
	     */
	    public function getConvertirJson($chkIdIngresoSeleccion)
	    {
	    	$actIngreso = null;
	    	$jsonRubro = [];
	    	$rubro = [];

	    	if ( count($chkIdIngresoSeleccion) > 0 ) {
	    		foreach ( $chkIdIngresoSeleccion as $key => $value ) {
	    			$actIngreso = ActEconIngreso::findOne((int)$value);
	    			$rubro['id_impuesto'] = $actIngreso->id_impuesto;
	    			$rubro['id_rubro'] = $actIngreso->id_rubro;
	    			$rubro['exigibilidad_periodo'] = $actIngreso->exigibilidad_periodo;

	    			$jsonRubro[] = json_encode($rubro);
	    		}
	    	}

	    	return $jsonRubro;
	    }

	}
 ?>