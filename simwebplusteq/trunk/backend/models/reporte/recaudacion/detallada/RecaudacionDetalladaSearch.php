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
 *  @file RecaudacionDetallada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-06-2017
 *
 *  @class RecaudacionDetallada
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

	namespace backend\models\reporte\recaudacion\detallada;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\fecha\RangoFechaValido;
	use common\conexion\ConexionController;
	use backend\models\reporte\recaudacion\detallada\RecaudacionDetallada;
	use yii\db\Query;
	use common\models\presupuesto\codigopresupuesto\CodigosContables;


	/**
	 * Clase que permite realizar las consultas basicas de los pagos hechos con recibos,
	 * ya sea a tarves del banco o realizados en la sede de la Alcaldia, segun un rango
	 * de fecha determinado. Esto permitira generar un reporte con los pagos existentes
	 * para luego mostrarlos por pantalla o a traves de un reporte impreso. Si el arreglo
	 * $this->_error esta vacio indica que todo el proceso fui exitoso. Se deb preguntar
	 * por esta propiedad antes de utilizar el resultado de la clase.
	 * Para obtener el valor de esta propiedad utilizar el metodo publico getError().
	 */
	class RecaudacionDetalladaSearch extends RecaudacionDetallada
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
			self::inicializarRecaudacion();
		}




		/**
		 * Metodo que retorna le modelo basico de consulta de los registros recolectados.
		 * @return RecaudacionDetallada
		 */
		public function findDetalleRecaudacionModel()
		{
			return RecaudacionDetallada::find()->alias('R')
											   ->where('login =:login',
																[':login' => $this->_usuario])
			    				               ->andWhere(['BETWEEN', 'fecha_pago', $this->_fecha_desde, $this->_fecha_hasta]);
		}



		/**
		 * Metodo que ejecuta la consulta sobre el modelo basico de los registros
		 * recolectados.
		 * @return array
		 */
		public function findDetalleRecolectado()
		{
			$findModel = self::findDetalleRecaudacionModel();
			return $findModel->orderBy([
									'lapso' => SORT_ASC,
									'fecha_pago' => SORT_ASC,
									'codigo' => SORT_ASC,
								])
							 ->asArray()
				   		     ->all();
		}



		/**
		 * Metodo que realiza una consulta de los detalles de recaudacion pero
		 * agrupando por codigo de ingresos, lo que solo traera un grupo reducido
		 * de registros. Solo se retorna el codigo de ingresos.
		 * @return array
		 */
		public function findDetalleAgrupadoByCodigo()
		{
			$findModel = self::findDetalleRecaudacionModel();
			return $findModel->select(['codigo'])->groupBy([
									'codigo',
								])
							 ->orderBy([
									'lapso' => SORT_ASC,
									'codigo' => SORT_ASC,
								])
							 ->asArray()
				   		     ->all();
		}



		/**
		 * Metodo que realiza la consulta sobre la recaudacion de ingresos, utilizando
		 * los parametros indicados.
		 * @param integer $codigo codigo de ingreso presupuestario. Ejemplo 301020700
		 * Actividad Economica.
		 * @param integer $lapso entero que indica el tipo de deuda que se quiere
		 * consultar.
		 * 1 => Deuda Actual.
		 * 2 => Deuda Morosa.
		 * @return array
		 */
		public function findDetalleByCodigoLapso($codigo, $lapso)
		{
			$findModel = self::findDetalleRecaudacionModel();
			return $findModel->andWhere('codigo =:codigo',[':codigo' => $codigo])
							 ->andWhere('lapso =:lapso', [':lapso' => $lapso])
							 ->orderBy([
							 		'fecha_pago' => SORT_ASC,
									'lapso' => SORT_ASC,
									'planilla' => SORT_ASC,
									'codigo' => SORT_ASC,
								])
							 ->asArray()
				   		     ->all();
		}




		/**
		 * Metodo que agrupa la deuda por concepto de codigo presupuestario y si esta es resultado
		 * de una deuda actual o una deuda morosa.
		 * @param integer $codigo codigo de ingreso presupuestario. Ejemplo 301020700
		 * Actividad Economica.
		 * @param integer $lapso entero que indica el tipo de deuda que se quiere
		 * consultar.
		 * 1 => Deuda Actual.
		 * 2 => Deuda Morosa.
		 * @return array
		 */
		public function findSumatoriaDetalleByPlanillaCodigoLapso($codigo = 0, $lapso)
		{
			$findModel = self::findDetalleRecaudacionModel();
			if ( $codigo == 0 && $lapso > 0 ) {

				return $findModel->select([
									'id_linea',
									'lapso',
									'impuesto',
									'codigo',
									'nombre_impuesto',
									'id_contribuyente',
									'id',
									'contribuyente',
									'planilla',
									'ano_impositivo',
									'trimestre',
									'fecha_pago',
									'SUM(monto) as monto',
									'SUM(recargo) as recargo',
									'SUM(interes) as interes',
									'detalle_mov',
									'id_impuesto',
									'login',
									'SUM(descuento) as descuento',
									'SUM(monto_reconocimiento) as monto_reconocimiento',
									'recibo'
									])
								 ->andWhere('lapso =:lapso', [':lapso' => $lapso])
								 ->groupBy([
								 		'planilla',
								 		'codigo',
								 	])
								 ->orderBy([
								 		'fecha_pago' => SORT_ASC,
										'lapso' => SORT_ASC,
										'planilla' => SORT_ASC,
										'codigo' => SORT_ASC,
									])
								 ->asArray()
					   		     ->all();

			} elseif ( $codigo > 0 && $lapso > 0 ) {

				return $findModel->select([
									'id_linea',
									'lapso',
									'impuesto',
									'codigo',
									'nombre_impuesto',
									'id_contribuyente',
									'id',
									'contribuyente',
									'planilla',
									'ano_impositivo',
									'trimestre',
									'fecha_pago',
									'SUM(monto) as monto',
									'SUM(recargo) as recargo',
									'SUM(interes) as interes',
									'detalle_mov',
									'id_impuesto',
									'login',
									'SUM(descuento) as descuento',
									'SUM(monto_reconocimiento) as monto_reconocimiento',
									'recibo'
									])
				  				 ->andWhere('codigo =:codigo',[':codigo' => $codigo])
								 ->andWhere('lapso =:lapso', [':lapso' => $lapso])
								 ->groupBy([
								 		'planilla',
								 		'codigo',
								 	])
								 ->orderBy([
								 		'fecha_pago' => SORT_ASC,
										'lapso' => SORT_ASC,
										'planilla' => SORT_ASC,
										'codigo' => SORT_ASC,
									])
								 ->asArray()
					   		     ->all();

			} else {
				return [];
			}
		}






		/**
		 * Metodo que totaliza los montos por los atributos:
		 * - monto
		 * - recargo
		 * - interes
		 * - descuento
		 * - monto-reconocimiento
		 * Y entrega un arreglo donde el indice es el nombre del atributo y
		 * el valor del elemnto sera el monto totalizado del atributo.
		 * @param arreglo $results arreglo con el resultado de la consulta.
		 * @return array.
		 */
		public function totalizarResultado($results)
		{
			return $totalizado = [
						'monto' => array_sum(array_column($results, 'monto')),
						'recargo' => array_sum(array_column($results, 'recargo')),
						'interes' => array_sum(array_column($results, 'interes')),
						'descuento' => array_sum(array_column($results, 'descuento')),
						'monto_reconocimiento' => array_sum(array_column($results, 'monto_reconocimiento')),
					];
		}



		/**
		 * Metodo que totaliza el monto por el concepto de Cheques Recuperados.
		 * @param  array $results arreglo con la data de la consulta
		 * @param  integer $lapso entero que indica deuda actual o morosa (1, 2).
		 * @return double retorna monto con la totalizacion.
		 */
		public function totalizarChequeRecuperado($results, $lapso = 0)
		{
			$total = (float)0;
			if ( count($results) > 0 ) {
				foreach ( $results as $result ) {
					if ( trim($result['codigo']) == '101020200' && (float)$result['monto'] > 0 ) {
						$total += (float)$result['monto'] - ((float)$result['descuento'] + (float)$result['monto_reconocimiento']);
					}
				}
			}
			return $total;
		}



		/**
		 * Metodo que totaliza el monto por el concepto de Notas de Debitos.
		 * @param  array $results arreglo con la data de la consulta
		 * @param  integer $lapso entero que indica deuda actual o morosa (1, 2).
		 * @return double retorna monto con la totalizacion.
		 */
		public function totalizarNotaDebito($results, $lapso = 1)
		{
			$total = (float)0;
			if ( count($results) > 0 ) {
				foreach ( $results as $result ) {
					if ( (int)$result['lapso'] == $lapso && (float)$result['monto'] < 0 ) {
						$total += (float)$result['monto'];
					}
				}
			}
			return $total;
		}




		/**
		 * Metodo que permite totalizar las columnas existentes en le results de la
		 * consulta realizada sobre la entidad "recaudacion-detallada". El metodo
		 * retorna un arreglo donde los indices (key) del arreglo son los atributos
		 * totalizados y el valor de cada elemento es la suma totalizada de ese atributo.
		 * @param array $results arreglo de registros resultado de la consulta sobre
		 * la data recolectada de la recaudacion.
		 * @return array
		 */
		public function getDataProvider($results)
		{
			$provider = New ArrayDataProvider([
				'allModels' => $results,
				'pagination' => false,
			]);
			return $provider;
		}



		/***/
		public function armarData($results)
		{
			$data = [];
			$conceptos = [
				'TOTAL DEPOSITOS + CHQ. RECUPERADOS',
				'TOTAL DEPOSITOS - CHQ. RECUPERADOS',
				'TOTAL GENERAL - ND'
			];
			foreach ( $conceptos as $key => $value ) {
				$data[] = [
					'concepto' => $value,
					'monto' => $results['monto'],
					'recargo' => $results['recargo'],
					'interes' => $results['interes'],
					'descuento' => $results['descuento'],
					'monto_reconocimiento' => $results['monto_reconocimiento'],
				];
			}

			return $data;
		}


		/**
		 * Metodo getter de los data recolectada.
		 * @return array
		 */
		public function getDataRecolectada()
		{
			return $this->_data = self::findDetalleRecolectado();
		}




		/**
		 * Metodo que inicia el proceso para obtener la data de la recaudacion.
		 * @return [type] [description]
		 */
		public function iniciarReporteDetalle()
		{
			$rangoValido = New RangoFechaValido($this->_fecha_desde, $this->_fecha_hasta);
			if ( $rangoValido->rangoValido() ) {
				if ( self::iniciarRecolectarData() ) {
					// Realizo un select completo con la informacion de la recoleccion de los datos.
					//return $this->_data;
				} else {
					self::setError(Yii::t('backend', 'Error al intentar recolectar la data para el reporte'));
				}
			} else {
				self::setError(Yii::t('backend', 'Rango de fecha no validos'));
			}
		}



		/**
		 * Metodo que inicia la recoleccion de la data.
		 * @return none
		 */
		private function iniciarRecolectarData()
		{
			self::findPagoDetalleActividadEconomica();
			self::findPagoDetalleInmuebleUrbano();
			self::findPagoDetalleVehiculo();
			self::findPagoDetalleAseo();
			self::findPagoDetallePropaganda();
			self::findPagoDetalleEspectaculo();
			self::findPagoDetalleApuesta();
			self::findPagoDetalleVario();
			self::findPagoDetalleMontoNegativo();
			self::ajustarDataReporte();
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
		 * Metodo que setea las variables para las operaciones CRUD.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



		/**
		 * Metodo que inicializa la entidad que se utilizará para guardar el resultado
		 * de la consulta, se borran los registros de dicha entidad que tenga como
		 * valor en el atributo "login" al usuario actual que realiza la consulta.
		 * @return boolean
		 */
		private function inicializarRecaudacion()
		{
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$tabla = $this->tableName();
			$arregloCondicion = [
				'login' => $this->_usuario,
			];
			$result = $this->_conexion->eliminarRegistro($this->_conn, $tabla, $arregloCondicion);
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();
			return $result;
		}



		/**
		 * Metodo que realiza la insercion por lote de las consulta realizada, sobre
		 * la entidad "recaudacion-detallada". Si guarda retorna true, de lo contrario false.
		 * @param array $registers arreglo producto de la consulta, este arreglo esta
		 * basado en el modelo de la entidad "recaudacion-detallada"
		 * @return boolean
		 */
		private function guardarConsultaRecaudacion($registers)
		{
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			// Lo siguiente retorna un arreglo de los taributos de la entidad "recaudacion-detallada"
			//  [
			// 		[0] => attribute0,
			// 		[1] => attribute1,
			// 		.
			// 		.
			// 		[N] => attributeN,
			// 	]
			$attributes = $this->attributes();
			$tabla = $this->tableName();
			$result = $this->_conexion->guardarLoteRegistros($this->_conn, $tabla, $attributes, $registers);
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();
			return $result;
		}



		/**
		 * Metodo que ejecuta un ajuste en la data resultado de la consulta.
		 * Esto se aplico para el proyecto de pto Ordaz. Si esto no aplica para el resto
		 * de los proyecto, no aplicar.
		 * @return boolean
		 */
		private function ajustarDataReporte()
		{
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$tabla = $this->tableName();

			//lapso=2 and ano_impositivo<year(fecha_pago) and impuesto=9 and fecha_pago>='2014-06-01
			$sql = "UPDATE {$tabla} SET codigo=301035900, nombre_impuesto='Deuda Morosa Por Tasas'
			        WHERE lapso=2 AND ano_impositivo<year(fecha_pago) AND impuesto=9 AND fecha_pago>='2014-06-01'";

			$result = $this->_conn->createCommand($sql)->execute();
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();
			return $result;
		}




		/**
		 * Metodo que ejecuta una consulta para localizar los registros de un codigo presupuestario
		 * a partir del mismo codigo presupuestario.
		 * @param string $codigoPresupuesto codigo presupuestario.
		 * @return CodigosContables
		 */
		private function getCodigoPresupuestarioByCodigo($codigoPresupuesto)
		{
			return $codigo = CodigosContables::find()->alias('A')
			                 				  		 ->where('codigo =:codigo',
			                 				   					[':codigo' => $codigoPresupuesto])
			                 				  		 ->one();
		}


		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Actividad Economica"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioActividadEconomica()
		{
			$codigoPresupuesto['codigo'] = '301020700';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}


		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Inmuebles Urvanos"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioInmuebleUrbano()
		{
			$codigoPresupuesto['codigo'] = '301020500';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}


		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Patente de Vehiculos"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioVehiculo()
		{
			$codigoPresupuesto['codigo'] = '301020800';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}



		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Aseo"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioAseo()
		{
			$codigoPresupuesto['codigo'] = '301035400';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}




		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Propaganda Comercial"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioPropagandaComercial()
		{
			$codigoPresupuesto['codigo'] = '301020900';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}



		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Espectaculo Publico"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioEspectaculoPublico()
		{
			$codigoPresupuesto['codigo'] = '301021000';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}



		/**
		 * Metodo para obtener la informacion del codigo presupuestario del renglon "Apuesta Licitas"
		 * @return CodigosPresupuestario
		 */
		private function getCodigoPresupuestarioApuestaLicita()
		{
			$codigoPresupuesto['codigo'] = '301021100';
			return $codigo = self::getCodigoPresupuestarioByCodigo($codigoPresupuesto['codigo']);
		}



		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Actividad Econimica.
		 * @return none
		 */
		public function findPagoDetalleActividadEconomica()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioActividadEconomica();

			$registers = [];
			// Pagos con periodos mayores a cero
			$registers = self::findPagoDetallePeriodoMayorCero($codigoPresupuestario, [1]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Actividad Economica periodo mayores a cero'));
			}

			$registers = [];
			// Pagos con periodos iguales a cero
			$registers = self::findPagoDetallePeriodoIgualCero($codigoPresupuestario, [1]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Actividad Economica periodo iguales a cero'));
			}

		}




		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Patente de Vehiculos.
		 * @return none
		 */
		public function findPagoDetalleVehiculo()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioVehiculo();

			$registers = [];
			// Pagos con periodos mayores a cero
			$registers = self::findPagoDetallePeriodoMayorCero($codigoPresupuestario, [3]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Vehiculo periodo mayores a cero'));
			}

			$registers = [];
			// Pagos con periodos iguales a cero
			$registers = self::findPagoDetallePeriodoIgualCero($codigoPresupuestario, [3]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Vehiculo periodo iguales a cero'));
			}
		}



		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Inmuebles Urbanos.
		 * @return none
		 */
		public function findPagoDetalleInmuebleUrbano()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioInmuebleUrbano();

			$registers = [];
			// Pagos con periodos mayores a cero
			$registers = self::findPagoDetallePeriodoMayorCeroInmueble($codigoPresupuestario, [2]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Inmuebles Urbanos periodo mayores a cero'));
			}

			// $registers = [];
			// // Pagos con periodos iguales a cero
			// $registers = self::findPagoDetallePeriodoIgualCeroInmueble($codigoPresupuestario, [2]);
			// if ( !self::guardarConsultaRecaudacion($registers) ) {
			// 	self::errorCargarData(Yii::t('backend', 'Inmuebles Urbanos periodo iguales a cero'));
			// }


			$registers = [];
			$registers = self::findPagoDetalleVarioImpuestoModel([2]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Inmuebles Urbanos por varios'));
			}

		}



		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Inmuebles Urbanos.
		 * @return none
		 */
		public function findPagoDetalleAseo()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioAseo();

			$registers = [];
			// Pagos con periodos mayores a cero
			$registers = self::findPagoDetallePeriodoMayorCeroInmueble($codigoPresupuestario, [12]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Aseo periodo mayores a cero'));
			}

			//$registers = [];
			// Pagos con periodos iguales a cero
			// $registers = self::findPagoDetallePeriodoIgualCeroInmueble($codigoPresupuestario, [12]);
			// if ( !self::guardarConsultaRecaudacion($registers) ) {
			// 	self::errorCargarData(Yii::t('backend', 'Aseo periodo iguales a cero'));
			// }


			$registers = [];
			$registers = self::findPagoDetalleVarioImpuestoModel([12]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Aseo por varios'));
			}

		}




		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Propaganda Comercial.
		 * @return none
		 */
		public function findPagoDetallePropaganda()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioPropagandaComercial();

			$registers = [];
			// Pagos con periodos iguales a cero
			$registers = self::findPagoDetallePeriodoIgualCero($codigoPresupuestario, [4]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Propaganda comercial'));
			}
		}




		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Espectaculo Publico.
		 * @return none
		 */
		public function findPagoDetalleEspectaculo()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioEspectaculoPublico();

			$registers = [];
			// Pagos con periodos iguales a cero
			$registers = self::findPagoDetallePeriodoIgualCero($codigoPresupuestario, [6]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Espectaculo Publico'));
			}
		}



		/**
		 * Metodo que permite obtener los detalles de los pagos sobre el
		 * impuesto de Apuesta Licitas.
		 * @return none
		 */
		public function findPagoDetalleApuesta()
		{
			$codigoPresupuestario = self::getCodigoPresupuestarioApuestaLicita();

			$registers = [];
			// Pagos con periodos iguales a cero
			$registers = self::findPagoDetallePeriodoIgualCero($codigoPresupuestario, [7]);
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Apuesta Licitas'));
			}
		}




		/**
		 * Metodo que realiza la consulta de la:
		 * - Deuda Morosa Por tasa
		 * - Certificacion y Solvencia
		 * - Otros impuesto que no son Deuda morosa por tasa ni certificacion de solvencia
		 * @return none
		 */
		public function findPagoDetalleVario()
		{
			$codigoPresupuestario = [
				'301034900',
				'301035900',
			];

			$impuesto = [
				5, 8, 9, 10, 11
			];

			$registers = self::findPagoDetalleVarioModel($impuesto, $codigoPresupuestario, 'NOT IN');
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Otros conceptos'));
			}


			// Deuda Morora por Tasa.
			if ( date('Y-m-d') > date('Y-m-d', strtotime('2014-03-01')) ) {
				$codigoPresupuestario = ['301035900'];
				$impuesto = [9];

				$registers = self::findPagoDetalleVarioModel($impuesto, $codigoPresupuestario, 'IN');
				if ( !self::guardarConsultaRecaudacion($registers) ) {
					self::errorCargarData(Yii::t('backend', 'Deuda Morosa por Tasa'));
				}
			}


			// Certificacion y solvencia.
			$codigoPresupuestario = ['301034900'];
			$impuesto = [4, 5, 6, 7, 8, 9, 10, 11, 12];	// impuesto > 3

			$registers = self::findPagoDetalleVarioModel($impuesto, $codigoPresupuestario, 'IN');
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Certificacion y Solvencia'));
			}

		}



		/**
		 * Metodo que consulta los monto negativos (<0) y envia el resultado de la consulta
		 * para se que guarde en la entidad temporal.
		 * @return none
		 */
		public function findPagoDetalleMontoNegativo()
		{
			$registers = self::findPagoDetalleMontoNegativoModel();
			if ( !self::guardarConsultaRecaudacion($registers) ) {
				self::errorCargarData(Yii::t('backend', 'Montos Negativos'));
			}
		}




		/**
		 * Metodo para buscar los registros con las caracteristicas enviadas. Y retorna el arreglo
		 * con los atributos y los valores de los atributos. El atributo fijo en este metodo es que
		 * los registros poseen el trimestre mayores a cero (0).
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetallePeriodoMayorCero($codigoPresupuesto, $impuesto)
		{
			return $registers = self::findPagoDetalleModel($codigoPresupuesto, '>', $impuesto);
		}



		/**
		 * Metodo para buscar los registros con las caracteristicas enviadas. Y retorna el arreglo
		 * con los atributos y los valores de los atributos.El atributo fijo en este metodo es que
		 * los registros poseen el trimestre igual a cero (0).
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetallePeriodoIgualCero($codigoPresupuesto, $impuesto)
		{
			return $registers = self::findPagoDetalleModel($codigoPresupuesto, '=', $impuesto);
		}



		/**
		 * Metodo para buscar los registros con las caracteristicas enviadas. Y retorna el arreglo
		 * con los atributos y los valores de los atributos. El atributo fijo en este metodo es que
		 * los registros poseen el trimestre mayores a cero (0).
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetallePeriodoMayorCeroInmueble($codigoPresupuesto, $impuesto)
		{
			return $registers = self::findPagoDetalleInmuebleModel($codigoPresupuesto, '>', $impuesto);
		}



		/**
		 * Metodo para buscar los registros con las caracteristicas enviadas. Y retorna el arreglo
		 * con los atributos y los valores de los atributos.El atributo fijo en este metodo es que
		 * los registros poseen el trimestre igual a cero (0).
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetallePeriodoIgualCeroInmueble($codigoPresupuesto, $impuesto)
		{
			return $registers = self::findPagoDetalleInmuebleModel($codigoPresupuesto, '=', $impuesto);
		}




		/**
		 * Metodo que genera el modelo basico de consulta sobre los detalles de pago
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param string $operador condicion que complementa el wher condition de la consulta,
		 * estos pueden ser:
		 * - >
		 * - >=
		 * - <
		 * - <=
		 * - =
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetalleModel($codigoPresupuesto, $operador, $impuesto)
		{
			$query = New Query();
			return $registers = $query->select(['D.id_detalle',
					    				         'if(D.ano_impositivo<year(D.fecha_pago),2,1) as lapso',
					    				         'D.impuesto',
					    				         'CONCAT("' . $codigoPresupuesto['codigo'] . '") as codigo',
					    				         'CONCAT("' . $codigoPresupuesto['descripcion'] . '") as nombre_impuesto',
					    				         'P.id_contribuyente',
					    				         'CONCAT(C.naturaleza,"-",C.cedula,"-",C.tipo) as id',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as contribuyente',
					    				         'P.planilla',
					    				         'D.ano_impositivo',
					    				         'D.trimestre',
					    				         'D.fecha_pago',
					    				         'D.monto',
					    				         'D.recargo',
					    				         'D.interes',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as detalle_mov',
					    				         'D.id_impuesto',
					    				         'CONCAT("' . $this->_usuario . '") as login',
					    				         'D.descuento',
					    				         'D.monto_reconocimiento',
					    				         'P.recibo'
					    				         ])
									   ->from('contribuyentes C')
									   ->join('INNER JOIN', 'pagos P', 'C.id_contribuyente = P.id_contribuyente')
									   ->join('INNER JOIN', 'pagos_detalle D', 'P.id_pago = D.id_pago')
									   ->where('P.recibo >:recibo', [':recibo' => 0])
									   ->andWhere('D.trimestre ' . $operador . ':trimestre', [':trimestre' => 0])
									   ->andWhere('D.pago =:pago', [':pago' => 1])
									   ->andWhere('D.monto >=:monto', [':monto' => 0])
									   ->andWhere(['IN', 'D.impuesto', $impuesto])
									   ->andWhere(['BETWEEN', 'D.fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
									   ->orderBy([
									   		'lapso' => SORT_ASC,
									   		'codigo' => SORT_ASC,
									   		'P.planilla' => SORT_ASC,
									   		'id' => SORT_ASC,
									    ])
									   ->all();
		}




		/**
		 * Metodo que genera el modelo basico de consulta sobre los detalles de pago
		 * @param CodigosContables $codigoPresupuesto arreglo del modelo CodigosContables
		 * @param string $operador condicion que complementa el wher condition de la consulta,
		 * estos pueden ser:
		 * - >
		 * - >=
		 * - <
		 * - <=
		 * - =
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @return array
		 */
		private function findPagoDetalleInmuebleModel($codigoPresupuesto, $operador, $impuesto)
		{
			$query = New Query();
			return $registers = $query->select(['D.id_detalle',
					    				         'if(D.ano_impositivo<year(D.fecha_pago),2,1) as lapso',
					    				         'D.impuesto',
					    				         'CONCAT("' . $codigoPresupuesto['codigo'] . '") as codigo',
					    				         'CONCAT("' . $codigoPresupuesto['descripcion'] . '") as nombre_impuesto',
					    				         'P.id_contribuyente',
					    				         'CONCAT(C.naturaleza,"-",C.cedula,"-",C.tipo) as id',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as contribuyente',
					    				         'P.planilla',
					    				         'D.ano_impositivo',
					    				         'D.trimestre',
					    				         'D.fecha_pago',
					    				         'D.monto',
					    				         'D.recargo',
					    				         'D.interes',
					    				         'CONCAT(I.id_impuesto," ",I.direccion) as detalle_mov',
					    				         'D.id_impuesto',
					    				         'CONCAT("' . $this->_usuario . '") as login',
					    				         'D.descuento',
					    				         'D.monto_reconocimiento',
					    				         'P.recibo'
					    				         ])
									   ->from('contribuyentes C')
									   ->join('INNER JOIN', 'pagos P', 'C.id_contribuyente = P.id_contribuyente')
									   ->join('INNER JOIN', 'pagos_detalle D', 'P.id_pago = D.id_pago')
									   ->join('INNER JOIN', 'inmuebles I', 'D.id_impuesto = I.id_impuesto')
									   ->where('P.recibo >:recibo', [':recibo' => 0])
									   ->andWhere('D.trimestre ' . $operador . ':trimestre', [':trimestre' => 0])
									   ->andWhere('D.pago =:pago', [':pago' => 1])
									   ->andWhere('D.monto >=:monto', [':monto' => 0])
									   ->andWhere(['IN', 'D.impuesto', $impuesto])
									   ->andWhere(['BETWEEN', 'D.fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
									   ->orderBy([
									   		'lapso' => SORT_ASC,
									   		'codigo' => SORT_ASC,
									   		'P.planilla' => SORT_ASC,
									   		'id' => SORT_ASC,
									    ])
									   ->all();
		}



		/**
		 * Metodo que genera el modelo basico de consulta sobre los detalles de pago, pero para aquellos
		 * registros relacionados con la entidad "varios" y cuyos periodos sean iguales a cero.
		 * @param array $impuesto arreglo de identificadores de los impuestos [1, 2, 3,. ,. ,. ]
		 * @param array $codigo arreglo de codigos presupuestarios
		 * @return array
		 */
		private function findPagoDetalleVarioModel($impuesto, $codigo, $operadorIn)
		{
			$query = New Query();
			return $registers = $query->select(['D.id_detalle',
					    				         'if(D.ano_impositivo<year(D.fecha_pago),2,1) as lapso',
					    				         'D.impuesto',
					    				         'A.codigo as codigo',
					    				         'A.descripcion as nombre_impuesto',
					    				         'P.id_contribuyente',
					    				         'CONCAT(C.naturaleza,"-",C.cedula,"-",C.tipo) as id',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as contribuyente',
					    				         'P.planilla',
					    				         'D.ano_impositivo',
					    				         'D.trimestre',
					    				         'D.fecha_pago',
					    				         'D.monto',
					    				         'D.recargo',
					    				         'D.interes',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as detalle_mov',
					    				         'D.id_impuesto',
					    				         'CONCAT("' . $this->_usuario . '") as login',
					    				         'D.descuento',
					    				         'D.monto_reconocimiento',
					    				         'P.recibo'
					    				         ])
									   ->from('contribuyentes C')
									   ->join('INNER JOIN', 'pagos P', 'C.id_contribuyente = P.id_contribuyente')
									   ->join('INNER JOIN', 'pagos_detalle D', 'P.id_pago = D.id_pago')
									   ->join('INNER JOIN', 'varios V', 'D.id_impuesto = V.id_impuesto')
									   ->join('INNER JOIN', 'codigos_contables A', 'V.id_codigo = A.id_codigo')
									   ->where('P.recibo >:recibo', [':recibo' => 0])
									   ->andWhere('D.trimestre =:trimestre', [':trimestre' => 0])
									   ->andWhere('D.pago =:pago', [':pago' => 1])
									   ->andWhere('D.monto >=:monto', [':monto' => 0])
									   ->andWhere(['IN', 'D.impuesto', $impuesto])
									   ->andWhere([$operadorIn, 'A.codigo', $codigo])
									   ->andWhere(['BETWEEN', 'D.fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
									   ->orderBy([
									   		'lapso' => SORT_ASC,
									   		'A.codigo' => SORT_ASC,
									   		'P.planilla' => SORT_ASC,
									   		'id' => SORT_ASC,
									    ])
									   ->all();
		}



		/**
		 * Metodo que permite consultar los detalles de pago, que tienen como caracyeristicas
		 * que fueron liquidados por el impuesto de varios, relacionandolo al impuesto
		 * principal
		 * @param array $impuesto arreglo de identificadores de los impuesto.
		 * @return array
		 */
		private function findPagoDetalleVarioImpuestoModel($impuesto)
		{
			$query = New Query();
			return $registers = $query->select(['D.id_detalle',
					    				         'if(D.ano_impositivo<year(D.fecha_pago),2,1) as lapso',
					    				         'D.impuesto',
					    				         'A.codigo as codigo',
					    				         'A.descripcion as nombre_impuesto',
					    				         'P.id_contribuyente',
					    				         'CONCAT(C.naturaleza,"-",C.cedula,"-",C.tipo) as id',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as contribuyente',
					    				         'P.planilla',
					    				         'D.ano_impositivo',
					    				         'D.trimestre',
					    				         'D.fecha_pago',
					    				         'D.monto',
					    				         'D.recargo',
					    				         'D.interes',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as detalle_mov',
					    				         'D.id_impuesto',
					    				         'CONCAT("' . $this->_usuario . '") as login',
					    				         'D.descuento',
					    				         'D.monto_reconocimiento',
					    				         'P.recibo'
					    				         ])
									   ->from('contribuyentes C')
									   ->join('INNER JOIN', 'pagos P', 'C.id_contribuyente = P.id_contribuyente')
									   ->join('INNER JOIN', 'pagos_detalle D', 'P.id_pago = D.id_pago')
									   ->join('INNER JOIN', 'varios V', 'D.id_impuesto = V.id_impuesto')
									   ->join('INNER JOIN', 'codigos_contables A', 'V.id_codigo = A.id_codigo')
									   ->where('P.recibo >:recibo', [':recibo' => 0])
									   ->andWhere('D.trimestre =:trimestre', [':trimestre' => 0])
									   ->andWhere('D.pago =:pago', [':pago' => 1])
									   ->andWhere('D.monto >=:monto', [':monto' => 0])
									   ->andWhere(['IN', 'D.impuesto', $impuesto])
									   ->andWhere(['BETWEEN', 'D.fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
									   ->orderBy([
									   		'lapso' => SORT_ASC,
									   		'A.codigo' => SORT_ASC,
									   		'P.planilla' => SORT_ASC,
									   		'id' => SORT_ASC,
									    ])
									   ->all();
		}



		/**
		 * Metodo que consulta los detalles de pagos con montos negativos.
		 * Cheques Devueltos
		 * @return array
		 */
		private function findPagoDetalleMontoNegativoModel()
		{
			$query = New Query();
			return $registers = $query->select(['D.id_detalle',
					    				         'if(D.ano_impositivo<year(D.fecha_pago),3,3) as lapso',
					    				         'D.impuesto',
					    				         'CONCAT("000000000") as codigo',
					    				         'CONCAT("") as nombre_impuesto',
					    				         'P.id_contribuyente',
					    				         'CONCAT(C.naturaleza,"-",C.cedula,"-",C.tipo) as id',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as contribuyente',
					    				         'P.planilla',
					    				         'D.ano_impositivo',
					    				         'D.trimestre',
					    				         'D.fecha_pago',
					    				         'D.monto',
					    				         'D.recargo',
					    				         'D.interes',
					    				         'CONCAT(C.apellidos,"",C.nombres,"",C.razon_social) as detalle_mov',
					    				         'D.id_impuesto',
					    				         'CONCAT("' . $this->_usuario . '") as login',
					    				         'D.descuento',
					    				         'D.monto_reconocimiento',
					    				         'P.recibo'
					    				         ])
									   ->from('contribuyentes C')
									   ->join('INNER JOIN', 'pagos P', 'C.id_contribuyente = P.id_contribuyente')
									   ->join('INNER JOIN', 'pagos_detalle D', 'P.id_pago = D.id_pago')
									   ->where('P.recibo >:recibo', [':recibo' => 0])
									   ->andWhere('D.pago =:pago', [':pago' => 1])
									   ->andWhere('D.monto <:monto', [':monto' => 0])
									   ->andWhere(['BETWEEN', 'D.fecha_pago', $this->_fecha_desde, $this->_fecha_hasta])
									   ->orderBy([
									   		'lapso' => SORT_ASC,
									   		'codigo' => SORT_ASC,
									   		'P.planilla' => SORT_ASC,
									   		'id' => SORT_ASC,
									    ])
									   ->all();
		}

	}
?>