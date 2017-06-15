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
 *  @file DesincorporarActividadEconomicaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-05-2017
 *
 *  @class DesincorporarActividadEconomicaSearch
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

 	namespace backend\models\aaee\desincorporar;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;

	use backend\models\aaee\desincorporar\DesincorporarActividadEconomica;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;

	use backend\models\aaee\desincorporaramo\DesincorporarRamo;
	use backend\models\aaee\anexoramo\AnexoRamo;
	use backend\models\aaee\autorizarramo\AutorizarRamo;
	use backend\models\aaee\licencia\LicenciaSolicitud;
	use common\models\pago\PagoSearch;
	use common\models\deuda\Solvente;
	use common\models\ordenanza\OrdenanzaBase;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\aaee\Sucursal;



	/**
	 * Clase que controla la consulta basicas de la solicitud para controlar y aplicar
	 * los controles innherentes a la solicitud.
	 */
	class DesincorporarActividadEconomicaSearch extends DesincorporarActividadEconomica
	{

		private $_id_contribuyente;
		private $_datoContribuyente;


		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 * Valor unico dentro de la entidad correspondiente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_datoContribuyente = self::findContribuyente();
		}



		/**
		 * Metodo que realiza una busqueda de la solicitud por concepto de desincorporacion
		 *  de ramo. El $findModel retorna sin valores y solo aplicando el ->all()
		 * se obtienen los registros. Esta solicitud puede contener uno o muchos registros
		 * @param long $nroSolicitud identificador de la solicitud.
		 * @return Active Record.
		 */
		public function findSolicitudDesincorporarActividadEconomica($nroSolicitud)
		{
			return $findModel = DesincorporarActividadEconomica::find()->where('nro_solicitud =:nro_solicitud',
													 						[':nro_solicitud' => $nroSolicitud])
										                        ->andWhere('id_contribuyente =:id_contribuyente',
											   								[':id_contribuyente' => $this->_id_contribuyente]);
		}





		/**
		 * Metodo que realiza la consulta sobre la entidad respectiva.
		 * @param string $nombreClase nombre de la clase.
		 * @return boolean.
		 */
		private function existeSolicitudPendiente($nombreClase)
		{
			return $result = $nombreClase::find()->where('id_contribuyente =:id_contribuyente',
	    														[':id_contribuyente' => $this->_id_contribuyente])
	    								         ->andWhere('estatus =:estatus',
	    														[':estatus' => 0])
			    							     ->exists();
		}




		/**
		 * Metodo que permite obtener un arreglo de las clases que permitiran realizar
		 * la consultas sobre las entidades respectiva.
		 * @return array
		 */
		private function getListaClaseVerificar()
		{
			$label =  Yii::t('backend', 'Existe la solicitud PENDIENTE: ');
			return [
				DesincorporarRamo::className() => $label .  Yii::t('backend', 'Desincorporación de Ramo'),
				AnexoRamo::className() => $label .  Yii::t('backend', 'Anexo de Ramo'),
				AutorizarRamo::className() => $label .  Yii::t('backend', 'Autorización de Ramo'),
				LicenciaSolicitud::className() => $label .  Yii::t('backend', 'Emisión de Licencia'),
			];
		}




		/**
		 * Metodo que permite verificar la existencia de solicitudes pendientes
		 * @return array | null
		 */
		private function verificarSolicitud()
		{
			$results = [];
			$clases = self::getListaClaseVerificar();
			foreach ( $clases as $key => $value ) {
				if ( self::existeSolicitudPendiente($key) ) {
					$results[] = $value;
				}
			}

			return $results;
		}



		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente()
		{
			return $result = DesincorporarActividadEconomica::find()->where('id_contribuyente =:id_contribuyente',
			 																	[':id_contribuyente' => $this->_id_contribuyente])

										                            ->andWhere(['IN', 'estatus', [0, 1]])
										                            ->exists();
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
		 * Metodo que realiza una modelo consulta de las sucursales que posee el mismo rif
		 * y que sean juridicos.
		 * @return Active Record.
		 */
		public function findSucursales()
		{
			$result = null;
			$findModel = null;
			if ( self::getSedePrincipal() ) {
				$datos = $this->_datoContribuyente;
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
	    public function getExigibilidadDeclaracionSegunAnoImpositivo($año)
	    {
	    	$exigibilidad = OrdenanzaBase::getExigibilidadDeclaracion($año, 1);
	    	if ( count($exigibilidad) > 0 ) {
	    		return $exigibilidad['exigibilidad'];
	    	}
	    	return false;
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
	     * Metodo que realiza un find del contribuyente. Creando un modelo
	     * de la entidad respectiva.
	     * @return active record retorna un modelo de la entidad "contribuyentes".
	     */
	    public function findContribuyente()
	    {
	    	return ContribuyenteBase::findOne($this->_id_contribuyente);

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
	     * Metodo que permite validar la logica de negocio que se aplicara para
	     * elaborar una soliciitud de Desincorporacion de Ramo. Cada instancia que
	     * no se cumpla satisfactoriamente generara un mensaje que luego sera incluido
	     * en un arreglo de mensajes.
	     * @return array retorna un arreglo de mensajes, el mismo puede ser vacio.
	     */
	    public function validarEvento()
	    {

	    	// Solicitudes adjuntas que colidan con la creacion de la solicitud actual.
	    	$mensajes = self::verificarSolicitud();

			// Permite determinar si ya posee una solicitud similar.
	    	if ( self::yaPoseeSolicitudSimiliarPendiente() ) {
	    		$mensajes[] = Yii::t('backend', 'Ya posee resgitrada una solicitud similar');
	    	}

	    	// Se verifica si es sede principal
	    	$esSedePrincipal = self::getSedePrincipal();

	    	// Cantidad de contribuyentes asociados al rif.
	    	$cantidadSede = count(self::findSucursales()->all());

	    	if ( $esSedePrincipal && $cantidadSede > 1 ) {
				$mensajes[] = Yii::t('backend', 'La razon social esta registrada como sede principal de un grupo relacionado al mismo rif');
			}

			if ( !self::estaSolvente() ) {
				$mensajes[] = Yii::t('backend', 'El contribuyente no se encuentra solvente') . '. ' . Yii::t('backend', 'Ultimo pago ') . self::armarDescripcionUltimoPago();
			}

			if ( !self::declaracionValida() ) {
				$mensajes[] = Yii::t('backend', 'No se encontro la declaracion del año actual');
			}

			if ( $this->_datoContribuyente['no_declara'] == 1 ) {
				$mensajes[] = Yii::t('backend', 'El contribuyente aparece como DESINCORPORADO para declarar');
			}

	    	return $mensajes;

	    }




	    /**
	     * Metodo que realiza la consulta sobre la entidad "act-econ"
	     * @return array
	     */
	    public function getUltimaPeriodoDeclarado()
	    {
	    	return $registers = ActEcon::find()->alias('A')
	    									   ->joinWith('actividadDetalle D', true, 'INNER JOIN')
	    									   ->joinWith('exigibilidad as E', true)
	    	                                   ->where('id_contribuyente =:id_contribuyente',
	    											[':id_contribuyente' => $this->_id_contribuyente])
	    							 	       ->andWhere('A.estatus =:estatus',
	    							 				[':estatus' => 0])
	    							 	        ->andWhere('inactivo =:inactivo',
	    							 				[':inactivo' => 0])
	    							 	       ->orderBy([
	    							 				'ano_impositivo' => SORT_DESC,
	    							 			])
	    							 		   ->asArray()
	    							 		   ->one();
	    }



	    /**
	     * Metodo que determina si el contribuyente posee una declaracion valida.
	     * Segun la politica si el año de inicio de actividad es igual al año actual,
	     * entonces se debe virificar que el periodo actual sea igual a uno para
	     * considerar la declaracion como valida y asi poder permitir la creacion de la
	     * solicitud.
	     * Si el contribuyente que hace la solicitud no tiene fecha de inicio de actividad
	     * se asumira que es un contribuyente que por migracion u otra causa (succesion)
	     * que actualmente aparece relacionado como contribuyente de actividad economica
	     * debe ser desincorporado como contribuyente de dicho impuesto.
	     * @return boolean
	     */
	    private function declaracionValida()
	    {
	    	$periodoActual = self::determinarPeriodoActual();
	    	$declaracionValida = false;
	    	$datoContribuyente = $this->_datoContribuyente;
	    	$fechaInicio = $datoContribuyente['fecha_inicio'];

	    	if ( trim($fechaInicio) !== '' && $fechaInicio !== null ) {
	    		if ( (int)date('Y', strtotime($fechaInicio)) == (int)date('Y') ) {
	    			if ( $periodoActual['periodo'] == 1 ) {
	    				$declaracionValida = true;
	    			}
	    		} else {
		    		$declaracion = self::getUltimaPeriodoDeclarado();
		    		if ( count($declaracion) > 0 ) {
		    			if ( (int)$declaracion['ano_impositivo'] == (int)date('Y') ) {
		    				$declaracionValida = true;
		    			} elseif ( (int)$declaracion['ano_impositivo'] == (int)date('Y') - 1 ) {
		    				if ( $periodoActual['periodo'] == 1 ) {
		    					$declaracionValida = true;
		    				}
		    			}
		    		}
		    	}
	    	} else {
	    		$declaracionValida = true;
	    	}

	    	return $declaracionValida;

	    }



	    /**
	     * Metodo que arma una cadena a aprtir de un arreglo de datos producto
	     * de la consulta del ultimo periodo declrado valido que posee el contribuyente.
	     * @return string.
	     */
	    public function armarDescripcionUltimoPeriodoDeclarado()
	    {
	    	$ultima = '';
	    	$registers = self::getUltimaPeriodoDeclarado();
	    	if ( count($registers) > 0 ) {
				$ultima = $registers['ano_impositivo'] . '-' . $registers['exigibilidad']['unidad'];
	    	}

	    	return $ultima;
	    }



	    /**
	     * Metodo que busca el ultimo pago registrado sobre el impuesto de
	     * actividad economica (estimada).
	     * @return array
	     */
	    public function getUltimoPago()
	    {
	    	$registers = '';
	    	$searchPago = New PagoSearch();
			$searchPago->setIdContribuyente($this->_id_contribuyente);
			$searchPago->setImpuesto(1);

	    	return $registers = $searchPago->getUltimoLapsoPagoActividadEconomica();
	    }



	    /**
	     * Metodo que arma una cadena con la informacion del ultimo periodo pagado
	     * @return string
	     */
	    public function armarDescripcionUltimoPago()
	    {
	    	$ultimoPago = self::getUltimoPago();
	    	if ( count($ultimoPago) > 0 ) {
	    		$ultimo = $ultimoPago['pagos']['planilla'] . '-' .
	    		          $ultimoPago['ano_impositivo'] . '-' .
	    		          $ultimoPago['trimestre'] . '-' .
	    		          $ultimoPago['exigibilidad']['unidad'];
	    	}

	    	return $ultimo;
	    }




	    /**
	     * Metodo que determina si el contribuyente esta solvente con respecto
	     * al pago de la estimada de actividad economica.
	     * @return boolean
	     */
	    private function solventeSegunEstimadaActividadEconomica()
	    {
	    	$solvente = New Solvente();
	    	$solvente->setIdContribuyente($this->_id_contribuyente);
	    	$solvente->setImpuesto(1);

	    	return $solvente->estaSolventeActividadEconomica();
	    }



	    /**
	     * Metodo que determina el period actual (año - periodo)
	     * @return array
	     */
	    private function determinarPeriodoActual()
	    {
	    	$solvente = New Solvente();
	    	$solvente->setImpuesto(1);

	    	// Retorna un arreglo
	    	// [
	    	//		año => 9999,
	    	//		periodo => 99,
	    	// ]
	    	return $solvente->getPeriodoActualSegunOrdenanza();
	    }




	    /**
	     * Metodo que determina la condicion de solvente del contribuyente segun la siguiente politica:
	     * - Si el año del ultimo pago es igual al año actual, entonces el periodo del ultimo pago debe
	     * ser igual o superior al periodo actual.
	     * - Si el año del ultimo pago es anterior al año actual, entonces el periodo del ultimo pago
	     * debe ser igual a la exigibilidad del pago del año anterior, siempre y cuando el periodo actual
	     * no sea superior a 1.
	     * Esto aplica para el pago de la estimada.
	     * @return boolean
	     */
	    private function estaSolvente()
	    {
	    	$estaSolvente = false;
	    	$lapsoActual = self::determinarPeriodoActual();
	    	if ( count($lapsoActual) > 0 ) {
	    		$ultimoPago = self::getUltimoPago();
	    		if ( count($ultimoPago) > 0 ) {
	    			if ( (int)$lapsoActual['año'] == (int)$ultimoPago['ano_impositivo'] ) {
	    				if ( (int)$lapsoActual['periodo'] <= (int)$ultimoPago['trimestre'] ) {
	    					$estaSolvente = true;
	    				}
	    			} elseif ( ((int)$lapsoActual['año'] - 1) == (int)$ultimoPago['ano_impositivo'] ) {
	    				if ( ( (int)$ultimoPago['exigibilidad_pago'] == (int)$ultimoPago['trimestre'] ) && ( $lapsoActual['periodo'] == 1 ) ) {
	    					$estaSolvente = true;
	    				}
	    			}
	    		}
	    	}

	    	return $estaSolvente;
	    }



	}
 ?>