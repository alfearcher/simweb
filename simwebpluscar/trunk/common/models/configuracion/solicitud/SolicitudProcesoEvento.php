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
 *  @file SolicitudProcesoEvento.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13/05/2016
 *
 *  @class SolicitudProcesoEvento
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *
 *  @inherits
 *
 */
	namespace common\models\configuracion\solicitud;

	use Yii;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use backend\models\tasa\TasaForm;
	use common\models\planilla\PlanillaTasa;


	/**
	*
	*/
	class SolicitudProcesoEvento extends ParametroSolicitud
	{
		public $idConfigSolicitud;
		public $acciones = [];



		/**
		 * Metodo constructor.
		 * @param Long $idConfig identificador de configuracion de la entidad "config-solicitudes".
		 * Autoincremental.
		 */
		public function __construct($idConfig)
		{
			$this->idConfigSolicitud = $idConfig;
			parent::__construct($idConfig);
		}



		/***/
		public function ejecutarProcesoSolicitudSegunEvento($idContribuyente, $evento, $conexionLocal = null, $connLocal = null)
		{
			$result = null;
			$listaProcesos = $this->getProcesoSegunEvento($evento);
			if ( count($listaProcesos) ) {
				foreach ( $listaProcesos as $proceso ) {
					foreach ( $proceso as $key => $value ) {
						$miProceso = strtoupper(trim($value));
						$this->accion[$miProceso] = [];
						if ( $miProceso == 'LIQUIDACION DIRECTA' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA TASA' ) {

							$result = $this->generaTasa($idContribuyente, $evento, $conexionLocal, $connLocal);
							$this->acciones[$miProceso] = $result;

						} elseif ( $miProceso == 'SOLICITA DOCUMENTOS' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA CITA' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA NOTIFICACION' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA MULTA' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA FISCALIZACION' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA AUDITORIA' ) {

							$this->acciones[$miProceso] = false;

						} elseif ( $miProceso == 'GENERA CIERRE' ) {

							$this->acciones[$miProceso] = false;

						}
					}
				}
			} else {
				$this->acciones = $result;
			}
		}




		/**
		 * Metodo que permite obtener un arreglo de los procesos generados por
		 * la solicitud, segun el evento de la solicitud.
		 * @return Array Retorna un arreglo donde el key principal corresponde
		 * al nonbre del proceso y el valor del elemneto a los resultados de la
		 * ejecucion de dicho proceso.
		 */
		public function getAccion()
		{
			return $this->acciones;
		}



		/**
		 * Metodo que liquida la tasa correspondiente, segun el evento.
		 * @param  Long $idContribuyente [description]
		 * @param  String $evento          [description]
		 * @param  ConexionController $conexionLocal  instancia de conexion, Clase de tipo Conexioncontroller.
		 * @param  [type] $connLocal
		 * @return Array Retorna un arreglo con los resultado de la liquidacion de las tasas.
		 * el esquema de este arreglo es:
		 * [1] => array {
		 * 			[planilla] => numero de planilla
		 * 			[resultado] => true o false
		 * }
		 * [n] => array {
		 * 			[planilla n] => numero de planilla
		 * 			[resultado n] => true o false
		 * }
		 */
		protected function generaTasa($idContribuyente, $evento, $conexionLocal, $connLocal)
		{
			$result = null;
			$idImpuesto = 0;
			// Se espera los valores de la entidad "config-solic-tasas-multas".
			$tasas = $this->getDetalleSolicitudTasaMulta($evento);
			foreach ( $tasas as $tasa ) {

				$miTasa = New TasaForm();
				// Se determinara la tasa correspondiente al año actual.
				// Con el id_impuesto se determina si corresponde al año actual, sino
				// es la del año actual se busca el id_impuesto que corresponda, segun
				// los parametros existentes del id_impuesto que se mande.
				$idImpuesto = $miTasa->determinarTasaParaLiquidar($tasa['id_impuesto']);

				if ( $idImpuesto > 0 ) {
					for ( $i = 1; $i <= $tasa['nro_veces_liquidar']; $i++ ) {
						$planillaTasa = New PlanillaTasa($idContribuyente, $idImpuesto, $conexionLocal, $connLocal);
						$planillaTasa->liquidarTasa();
						$result[$idImpuesto][$i] = $planillaTasa->getResultado();
					}
				} else {
					$result[$tasa['id_impuesto']] = null;
				}
			}

			return $result;
		}



		/***/
		protected function analizarGeneraTasa($accionesLocal)
		{
			$result = true;
			if ( count($accionesLocal) > 0 ) {
				foreach ( $accionesLocal as $accion ) {
					if ( isset($accionesLocal['GENERA TASA']) ) {
//die(var_dump($accion));
						// $accion tiene el identificador de la tasa y las veces que se liquido.
						foreach ( $accion as $items ) {
							// $items contiene el numero de veces que se liquido la tasa.
							if ( $items == null ) {
								$result = false;
								break;
							} else {
								foreach ( $items as $item ) {
									// $item contiene la liquidacion de una tasa, numero de planilla
									// y resultado de la liquidacion.
									if ( $item['resultado'] == false ) {
										$result = false;
										break;
									}
								}
							}
						}
					}
				}
			}

			return $result;
		}



		/***/
		public function resultadoEjecutarProcesos()
		{
			$result = false;
			if ( count($this->acciones) ) {
				foreach ( $this->acciones as $key => $value ) {
					if ( $key == 'LIQUIDACION DIRECTA' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA TASA' ) {

						$result[$key] = $this->analizarGeneraTasa($this->acciones);

					} elseif ( $key == 'SOLICITA DOCUMENTOS' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA CITA' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA NOTIFICACION' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA MULTA' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA FISCALIZACION' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA AUDITORIA' ) {

						$result[$key] = false;

					} elseif ( $key == 'GENERA CIERRE' ) {

						$result[$key] = false;

					}
				}
			}

			return $result;
		}




	}
 ?>