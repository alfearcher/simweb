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
 *  @file RecaudacionGeneralSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-09-2017
 *
 *  @class RecaudacionGeneralSearch
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

	namespace backend\models\reporte\recaudacion\general;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\fecha\RangoFechaValido;
	use common\conexion\ConexionController;
	use backend\models\recibo\planillacontable\PlanillaContable;
	use backend\models\recibo\deposito\Deposito;
	use common\models\presupuesto\codigopresupuesto\CodigosContables;
	use backend\models\configuracion\cheque\devuelto\ChequeDevueltoSearch;
	use common\models\presupuesto\nivelespresupuesto\NivelesContables;
	use common\models\planilla\PagoDetalle;



	/**
	 * Clase que permite realizar las consultas basicas de los pagos hechos con recibos,
	 * ya sea a traves del banco o realizados en la sede de la Alcaldia, segun un rango
	 * de fecha determinado. Esto permitira generar un reporte con los pagos existentes
	 * para luego mostrarlos por pantalla o a traves de un reporte impreso. Si el arreglo
	 * $this->_error esta vacio indica que todo el proceso fui exitoso. Se deb preguntar
	 * por esta propiedad antes de utilizar el resultado de la clase.
	 * Para obtener el valor de esta propiedad utilizar el metodo publico getError().
	 */
	class RecaudacionGeneralSearch
	{
		private $_fecha_desde;
		private $_fecha_hasta;
		private $_usuario;
		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_error;
		private $_data;



		/**
		 * Metodo constructor de la clase.
		 * @param string $fechaDesde fecha inicio de la consulta de los pagos.
		 * @param string $fechaHasta fecha final de la consulta de los pagos.
		 * @param string $usuario nombre del usuario que solicita la consulta.
		 */
		public function __construct($fechaDesde, $fechaHasta, $usuario)
		{
			$this->_fecha_desde = date('Y-m-d', strtotime($fechaDesde));
			$this->_fecha_hasta = date('Y-m-d', strtotime($fechaHasta));
			$this->_usuario = $usuario;
			$this->_error = [];
			self::iniciarReporteGeneral();
		}


		/**
		 * Metodo que retorna el modelo basico de consulta en Deposito.
		 * @return Deposito
		 */
		public function findDepositoModel()
		{
			return Deposito::find()->alias('D')
							       ->where('D.estatus =:estatus', [':estatus' => 1])
				                   ->andWhere(['BETWEEN', 'fecha', $this->_fecha_desde, $this->_fecha_hasta]);
		}


		/**
		 * Metodo que ejecuta la consulta de los recibos pagados y que estan
		 * en un rango de fecha determinado, agrupado por codigo presupuestario.
		 * Montos mayores o iguales a cero (0).
		 * @return array
		 */
		public function agruparMontoRecaudadoPorCodigo()
		{
			$findModel = self::findDepositoModel();
			return $findModel->select(['codigo',
									   'SUM(C.monto) total',
									   ])
							 ->distinct('codigo')
						     ->joinWith('ingresoPresupuestario C', false, 'INNER JOIN')
						     ->andWhere(['C.estatus' => 0])
						     ->andWhere('C.monto>=0')
						     ->orderBy([
						     	'codigo' => SORT_ASC,
						     ])
						     ->groupBy([
						     	'codigo',
						     ])
						     ->asArray()
						     ->all();
		}




		/**
		 * Metodo que ejecuta la consulta de los recibos pagados y que estan
		 * en un rango de fecha determinado, agrupado por codigo presupuestario.
		 * Los montos a considerar son los menores a cero (0). Notas de debitos
		 * por Cheques devueltos.
		 * @return array
		 */
		public function agruparMontoNegativoRecaudadoPorCodigo()
		{
			$findModel = self::findDepositoModel();
			return $findModel->select(['codigo',
									   'SUM(C.monto) total',
									   ])
							 ->distinct('codigo')
						     ->joinWith('ingresoPresupuestario C', false, 'INNER JOIN')
						     ->andWhere(['C.estatus' => 0])
						     ->andWhere('C.monto<0')
						     ->orderBy([
						     	'codigo' => SORT_ASC,
						     ])
						     ->groupBy([
						     	'codigo',
						     ])
						     ->asArray()
						     ->all();
		}



		/**
		 * Metodo que totaliza los monto positivo
		 * @return double retorna el monto totalizado de los montos positivos.
		 */
		public function totalizarMontoPositivo()
		{
			return (float)array_sum(array_column($this->data, 'monto'));
		}


		/**
		 * Metodo que totaliza los monto positivo
		 * @return double retorna el monto totalizado de los montos positivos.
		 */
		public function totalizarMontoNegativo()
		{
			$data = self::agruparMontoNegativoRecaudadoPorCodigo();
			return (float)array_sum(array_column($data, 'total'));
		}


		/**
		 * Metodo que permite realizar la consulta sobre la entidad "codigos-contables"
		 * y "niveles-contables"
		 * @param string $codigoContable codigo contables
		 * @return array
		 */
		public function getCodigoContable($codigoContable)
		{
			return CodigosContables::find()->alias('A')
										   ->joinWith('nivelPresupuesto B', true, 'INNER JOIN')
										   ->where('codigo =:codigo',
														[':codigo' => $codigoContable])
										   ->asArray()
										   ->one();
		}


		/**
		 * Metodo para obtenet una lista de los niveles contavles.
		 * Implica un find sobre la entidad "niveles-contables".
		 * @return NivelContable
		 */
		public function getListaNivelContable()
		{
			return NivelesContables::find()->all();
		}



		/***/
		private function armarEstructuraDato()
		{
			// Se crea un ciclo de codigos presupuestarios encontrados en la consulta
			// principal.
			$codigos = self::agruparMontoRecaudadoPorCodigo();
			if ( $codigos !== null ) {
				foreach ( $codigos as $key => $codigo ) {
					$nivel = self::getCodigoContable($codigo['codigo']);
					if ( $nivel !== null ) {
						$this->_data[] = [
							'codigo' => $codigo['codigo'],
							'monto' => $codigo['total'],
							'nivel' => $nivel['nivel_contable'],
							'descripcion_nivel' => $nivel['nivelPresupuesto']['descripcion'],
							'impuesto' => $nivel['descripcion'],
							'ingreso_propio' => $nivel['nivelPresupuesto']['ingreso_propio'],
							'fecha_desde' => $this->_fecha_desde,
							'fecha_hasta' => $this->_fecha_hasta,
						];
					} else {
						$this->_data[] = [
							'codigo' => $codigo['codigo'],
							'monto' => $codigo['total'],
							'nivel' => '0',
							'descripcion_nivel' => '',
							'impuesto' => '',
							'ingreso_propio' => '0',
							'fecha_desde' => $this->_fecha_desde,
							'fecha_hasta' => $this->_fecha_hasta,
						];
					}
				}
			}
		}


		/**
		 * Metodo que permite obtener un arreglo filtarndo el arreglo principal
		 * $this->_data, utilizando el parametro del nivel presupuestario, para
		 * asi obtener un arreglo con los codigos presupuestarios que este relacionados
		 * al nivel especifico. El arreglo es similar a $this->_data.
		 * @param integer $nivel nivel presupuestario.
		 * @return array
		 */
		public function filtarDataSegunNivel($nivel)
		{
			$dataFiltrada = [];
			if ( count($this->_data) > 0 ) {
				foreach ( $this->_data as $key => $data ) {
					if ( (int)$data['nivel'] == $nivel ) {
						$dataFiltrada[] = $data;
					}
				}
			}
			return $dataFiltrada;
		}




		/**
		 *
		 * @param array $data arreglo de registros resultado de la consulta sobre
		 * la data recolectada de la recaudacion.
		 * @return array
		 */
		public function getDataProvider($data)
		{
			$provider = New ArrayDataProvider([
				'allModels' => $data,
				'pagination' => false,
			]);
			return $provider;
		}


		/**
		 * Metodo que inicia el proceso para obtener la data de la recaudacion.
		 * @return none
		 */
		public function iniciarReporteGeneral()
		{
			$rangoValido = New RangoFechaValido($this->_fecha_desde, $this->_fecha_hasta);
			if ( $rangoValido->rangoValido() ) {
				self::armarEstructuraDato();
			} else {
				self::setError(Yii::t('backend', 'Rango de fecha no validos'));
			}
		}


		/**
		 * Metodo que permitira localizar las planillas asosiadas a cheques recuparedos
		 * para el rango de fecha especifico y segun se trate de cheques recuperados de
		 * año actual o años anteriores. Para esto se requerirá obtener las caracteristicas
		 * de las planillas (id-impuesto, impuesto), estas caracteristicas
		 * serán obtenida de la configuracion del modulo de cheques devueltos conjuntamente
		 * con la entidad que guarda las tasas. Una vez obtenida los registros que permitan
		 * definir estas caracteristicas se procederá a buscar las planillas complementando
		 * la consulta con el rango de fecha de pago definido. Se retornara una totalizacion
		 * del monto recuperado por cheques devueltos en los conceptos de año actual y año
		 * anterior. El parametro añoImpositivo servira si se quiere realizar la totalizacion
		 * para un año impositivo especifico.
		 * @param integer $añoImpositivo año consulta.
		 * @return array
		 */
		public function getDeterminarMontoPorChequeRecuperado($añoImpositivo = 0)
		{
			$planillas = [];
			// Aqui se totalizara los montos por concepto de cheques recuperados año anterior
			// y año actual.
			$cheque = [
				'año-anterior' => 0,
				'año-actual' => 0
			];
			// Se buscan las caracteristicas deben poseer las planillas de cheques recuperados.
			$chequeDevueltoSearch = New ChequeDevueltoSearch();
			$confCheques = $chequeDevueltoSearch->infoConfigTasaSegunAnoImpositivo($añoImpositivo);
			if ( count($confCheques) > 0 ) {

				foreach ( $confCheques as $conf ) {

					$planillas = self::findPlanilla((int)$conf['tasa']['impuesto'], (int)$conf['tasa']['id_impuesto']);
					if ( count($planillas) > 0 ) {

						foreach ( $planillas as $planilla ) {
							// Si el año impositivo es igual al año de pago se considera un cheque recuperado
							// para el año actual. Si el año impositivo es menor al año de pago se considera como
							// un cheque recuperado de año anteriores.
							if ( $planilla['monto'] >= 0 ) {
								if ( (int)$planilla['ano_impositivo'] == (int)date('Y', strtotime($planilla['fecha_pago'])) ) {
									$cheque['año-actual'] += $planilla['monto'];
								} elseif ( (int)$planilla['ano_impositivo'] < (int)date('Y', strtotime($planilla['fecha_pago'])) ) {
									$cheque['año-anterior'] += $planilla['monto'];
								}
							}
						}
					}

				}

			}

			return $cheque;
		}


		/**
		 * Metodo que realiza la busqueda de las planillas que cumplan la condiciones
		 * indicadas en el where. Se retornan los campos de la entidades relacionadas
		 * con las planillas.
		 * @param integer $impuesto identificador del impuesto.
		 * @param integer $idImpuesto identificador de la tasa.
		 * @return array.
		 */
		public function findPlanilla($impuesto, $idImpuesto)
		{
			// Planillas
			$results = PagoDetalle::find()->alias('D')
										  ->joinWith('pagos P', true, 'INNER JOIN')
										  ->where(['pago' => 1])
										  ->andWhere(['trimestre' => 0])
										  ->andWhere('impuesto =:impuesto',
										  				[':impuesto' => $impuesto])
										  ->andWhere('id_impuesto =:id_impuesto',
										  				[':id_impuesto' => $idImpuesto])
										  ->andWhere(['BETWEEN', 'fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
										  ->andWhere(['>', 'recibo', 0])
										  ->asArray()
										  ->all();
			return $results;
		}


		/**
		 * Metodo que permite el seteo de varios mensaje de error ocurrido en cualquier proceso.
		 * @param array $results arreglo de mensaje de error.
		 * @return none
		 */
		private function check($results)
		{
			if ( count($results) > 0 ) {
				foreach ( $results as $result ) {
					self::setError($result);
				}
			}
		}



		/**
		 * Metodo que permite el seteo de un mensaje de error para uno operacion
		 * especifica.
		 * @param  string $mensajeSecundario mensaje complementario.
		 * @return none
		 */
		private function errorCargarData($mensajeSecundario = '')
		{
			self::setError(Yii::t('backend', 'Error al cargar la data. ') . $mensajeSecundario);
		}



		/**
		 * Metodo setter de los errores.
		 * @param string $mensajeError mensaje de error.
		 */
		private function setError($mensajeError)
		{
			$this->_error[] = $mensajeError;
		}



		/**
		 * Metoso getter de los errores
		 * @return array. Arreglo de Errores.
		 */
		public function getError()
		{
			return $this->_error;
		}


		/**
		 * Metodo getter de _data
		 * @return array
		 */
		public function getData()
		{
			return $this->_data;
		}


	}
?>