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
 *  @file IncripcionPropagandaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @class IncripcionPropagandaSearch
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

 	namespace backend\models\propaganda\inscripcionpropaganda;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\propaganda\inscripcionpropaganda\InscripcionPropaganda;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomica;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\propaganda\uso\UsoPropaganda;
	use backend\models\propaganda\clase\ClasePropaganda;
	use backend\models\propaganda\tipo\TipoPropaganda;
	use backend\models\propaganda\mediodifusion\MedioDifusion;
	use backend\models\propaganda\mediotransporte\MedioTransporte;
	use backend\models\utilidad\tarifa\propaganda\TarifaPropaganda;
	use common\models\ordenanza\OrdenanzaBase;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\tiempo\Tiempo;


	/**
	 * Clase
	 */
	class InscripcionPropagandaSearch
	{

		private $_id_contribuyente;

		const IMPUESTO = 4;

		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}


		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return Boolean Retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente()
		{
			$modelFind = null;
			$modelFind = InscripcionPropaganda::find()->where('id_contribuyente =:id_contribuyente',
						 										[':id_contribuyente' => $this->_id_contribuyente])
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
		 * Metodo que permite determinar si un contribuyente es de tipo naturaleza "JURIDICO".
		 * @return boolean retorna un true si es de tipos naturaleza "JURIDICO", false en caso
		 * contrario.
		 */
		public function esUnContribuyenteJuridico()
		{
			$result = false;
			$tipoNaturaleza = '';
			$tipoNaturaleza = ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->_id_contribuyente);
			if ( strtoupper($tipoNaturaleza) == 'JURIDICO' ) {
				$result = true;
			}
			return $result;
		}



		/***/
		public function findSolicitudInscripcionPropaganda($nroSolicitud)
		{
			$findModel = InscripcionPropaganda::find()->where('nro_solicitud =:nro_solicitud',
			 													[':nro_solicitud' => $nroSolicitud])
													  ->andWhere('id_contribuyente =:id_contribuyente',
													  			[':id_contribuyente' => $this->_id_contribuyente])
													  ->joinWith('estatusSolicitud')
													  ->one();
			return isset($findModel) ? $findModel : null;
		}



		/**
		 * Metodo que genera una lista con los usos de la propaganda. Si recibe
		 * un arreglo vacio busca todos los usos de las propagandas.
		 * @param  array  $uso identificadores de la propaganda.
		 * @return array
		 */
		public function getListaUsoPropaganda($uso = [])
		{
			if ( count($uso) > 0 ) {
				$modelUso = UsoPropaganda::find()->where(['IN', 'uso_propaganda', $uso])
			                                     ->all();
			} else {
				$modelUso = UsoPropaganda::find()->all();
			}

			return ArrayHelper::map($modelUso,'uso_propaganda','descripcion');

		}



		/**
		 * Metodo que genera una lista con las clases de las propagandas. Si recibe
		 * un arreglo vacio busca todos las clases de las propagandas.
		 * @param  array  $uso identificadores de la propaganda.
		 * @return array
		 */
		public function getListaClasePropaganda($clase = [])
		{
			if ( count($clase) > 0 ) {
				$modelClase = ClasePropaganda::find()->where(['IN', 'clase_propaganda', $clase])
			                                 ->all();
			} else {
				$modelClase = ClasePropaganda::find()->all();
			}

			return ArrayHelper::map($modelClase,'clase_propaganda','descripcion');

		}




		/**
		 * Metodo que genera una lista con las clases de las propagandas. Si recibe
		 * un arreglo vacio busca todos las clases de las propagandas.
		 * @param  array  $uso identificadores de la propaganda.
		 * @return array
		 */
		public function getListaMewdioDifusion($difusion = [])
		{
			if ( count($difusion) > 0 ) {
				$modelDifusion = MedioDifusion::find()->where(['IN', 'medio_difusion', $difusion])
			                                 ->all();
			} else {
				$modelDifusion = MedioDifusion::find()->all();
			}

			return ArrayHelper::map($modelDifusion,'medio_difusion','descripcion');

		}




		/**
		 * Metodo que genera una lista con las clases de las propagandas. Si recibe
		 * un arreglo vacio busca todos las clases de las propagandas.
		 * @param  array  $uso identificadores de la propaganda.
		 * @return array
		 */
		public function getListaMewdioTransporte($transporte = [])
		{
			if ( count($transporte) > 0 ) {
				$modelTransporte = MedioTransporte::find()->where(['IN', 'medio_transporte', $transporte])
			                                 ->all();
			} else {
				$modelTransporte = MedioTransporte::find()->all();
			}

			return ArrayHelper::map($modelTransporte,'medio_transporte','descripcion');

		}







		/**
		 * Metodo para obtener el identificador de la ordenanza.
		 * @param integer $añoImpositivo año impositivo.
		 * @return integer
		 */
		public function getIdOrdenanza($añoImpositivo)
		{
			$id = 0;
			$añoOrdenanza = 0;
			$añoOrdenanza = OrdenanzaBase::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoImpositivo, self::IMPUESTO);
			if ( $añoOrdenanza > 0 ) {
				$ordenanza = OrdenanzaBase::getIdOrdenanza($añoOrdenanza, self::IMPUESTO);
				if ( count($ordenanza) > 0 ) {
					$id = (int)$ordenanza[0]['id_ordenanza'];
				}
			}

			return $id;
		}






		/**
		 * Metodo que obtiene los identificadores de uso-propaganda, clase-propaganda,
		 * tipos-propagandas.
		 * @param  string $nombreCampo nombre del atributo.
		 * @param  integer $idOrdenanza identificador de la ordenanza.
		 * @return array|null.
		 */
		public function getIdentificadorTarifaPropaganda($nombreCampo, $idOrdenanza)
		{
			if ( $idOrdenanza > 0 ) {

				$model = TarifaPropaganda::find()->select($nombreCampo)
												 ->distinct($nombreCampo)
												 ->where('id_ordenanza =:id_ordenanza',
												 			['id_ordenanza' => $idOrdenanza])
												 ->asArray()
												 ->all();
				return $model;
			}

			return null;
		}




		/***/
		public function getIdentificadorSegunAnoImpositivo($nombreCampo, $añoImpositivo)
		{
			$idOrdenanza = self::getIdOrdenanza($añoImpositivo);
			if ( $idOrdenanza > 0 ) {
				return self::getIdentificadorTarifaPropaganda($nombreCampo, $idOrdenanza);
			}
			return null;
		}




		/***/
		public function generarViewListaTipoPropaganda($uso, $clase, $añoImpositivo)
		{
			$model = [];
	    	if ( $uso > 0 && $clase > 0 && $añoImpositivo > 0 ) {

	    		$idOrdenanza = self::getIdOrdenanza($añoImpositivo);
				if ( $idOrdenanza > 0 ) {

					$model = TarifaPropaganda::find()->alias('A')
													 ->select(['T.tipo_propaganda','descripcion'])
										 			 ->distinct('A.tipo_propaganda')
										 			 ->joinWith('tipoPropaganda T', true, 'INNER JOIN')
						                 			 ->where('uso_propaganda =:uso_propaganda',
																[':uso_propaganda' => $uso])
						                 			 ->andWhere('clase_propaganda =:clase_propaganda',
																[':clase_propaganda' => $clase])
						                 			 ->andWhere('id_ordenanza =:id_ordenanza',
																[':id_ordenanza' => $idOrdenanza])
									     			->all();
				}
			}
	        if ( count($model) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $model as $mod ) {
	                echo "<option value='" . $mod->tipoPropaganda->tipo_propaganda . "'>" .  $mod->tipoPropaganda->tipo_propaganda . ' - ' . $mod->tipoPropaganda->descripcion . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
		}




		/**
		 * Metodo que genera una lista con las registros de la entidad "tiempos"
		 * @param  array  $tiempo identificadores de  la entidad.
		 * @return array
		 */
		public function getListaTiempo($tiempo = [])
		{
			if ( count($tiempo) > 0 ) {
				$modelTiempo = Tiempo::find()->where(['IN', 'id_tiempo', $tiempo])
			                                 ->all();
			} else {
				$modelTiempo = Tiempo::find()->all();
			}

			return ArrayHelper::map($modelTiempo,'id_tiempo','descripcion');

		}




		/***/
		public function getBaseCalculo($tipo)
		{

			$model = TipoPropaganda::findOne($tipo);
			return (string)$model['base_calculo'];

		}




		/***/
		public function getFechaHasta($cantidadTiempo, $idTiempo, $fechaInicio)
		{
			$fecha = '';
			$fechaHasta = '';
			$result = '';
			$findModel = Tiempo::findOne($idTiempo);

			if ( $findModel !== null ) {
				$fechaIn = date('Y-m-d', strtotime($fechaInicio));
				$fecha = date_create($fechaIn);

				$fechaHasta = date_format($fecha, 'Y-m-d');

				if ( $findModel->descripcion == 'Hora(s)' ) {

					$t = $cantidadTiempo . ' hours';
					$result = date_add(date_create($fechaHasta), date_interval_create_from_date_string($t));

				} elseif ( $findModel->descripcion == 'Dia(s)' ) {

					$t = $cantidadTiempo . ' days';
					$result = date_add(date_create($fechaHasta), date_interval_create_from_date_string($t));

				} elseif ( $findModel->descripcion == 'Semana(s)' ) {

					$t = $cantidadTiempo . ' weeks';
					$result = date_add(date_create($fechaHasta), date_interval_create_from_date_string($t));

				} elseif ( $findModel->descripcion == 'Mese(s)' ) {

					$t = $cantidadTiempo . ' months';
					$result = date_add(date_create($fechaHasta), date_interval_create_from_date_string($t));

				} elseif ( $findModel->descripcion == 'Año(s)' ) {

					$t = $cantidadTiempo . ' years';
					$result = date_add(date_create($fechaHasta), date_interval_create_from_date_string($t));
				}
			}

			return date_format($result, 'd-m-Y');
		}


	}
 ?>