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
	use common\models\configuracion\solicitudplanilla\SolicitudPlanillaForm;


	/**
	 * Clase que permite determinar los procesos asociados a una configuracion
	 * de una solicitud, indicando el identificador de configuracion de la solicitud
	 * y el evento en que se encuentra dicha solicitud. Permite ejecutar los procesos
	 * asociados a la configuracion de la solicitud segun el evento, arrojando un resultado
	 * de dicha ejecucion.
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



		 /**
		 * [ejecutarProcesoSolicitudSegunEvento description]
		 * @param  Active Record $model modelo que debe contener el identificador del contribuyente (id_contribuyente)
		 * y el identificador de la solicitud (nro_solicitud).
		 * @param  String $evento descripcion del evento relacionado a la solicitud.
		 * @param  Class $conexionLocal instancia de tipo ConexionController.
		 * @param  [type] $connLocal     [description]
		 * @return [type]                [description]
		 */
		public function ejecutarProcesoSolicitudSegunEvento($model, $evento, $conexionLocal = null, $connLocal = null)
		{
			$result = null;
			$listaProcesos = $this->getProcesoSegunEvento($evento);
			if ( count($listaProcesos) > 0 ) {
				foreach ( $listaProcesos as $proceso ) {
					if ( $proceso !== null ) {
						foreach ( $proceso as $key => $value ) {
							$miProceso = strtoupper(trim($value));
							$this->accion[$miProceso] = [];
							if ( $miProceso == 'LIQUIDACION DIRECTA' ) {

								$this->acciones[$miProceso] = false;

							} elseif ( $miProceso == 'GENERA TASA' ) {

								$result = $this->generaTasa($model, $evento, $conexionLocal, $connLocal);
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
		 * @param  Active Record $model [description]
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
		public function generaTasa($model, $evento, $conexionLocal, $connLocal)
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
die('kaka');
				if ( $idImpuesto > 0 ) {
					for ( $i = 1; $i <= $tasa['nro_veces_liquidar']; $i++ ) {
						$planillaTasa = New PlanillaTasa($model->id_contribuyente, $idImpuesto, $conexionLocal, $connLocal);
						$planillaTasa->liquidarTasa();
						$result[$idImpuesto][$i] = $planillaTasa->getResultado();

						self::guardarSolicitudPlanilla($model->nro_solicitud, $result[$idImpuesto][$i]['planilla'], $conexionLocal, $connLocal, $evento);
					}
				} else {
					$result[$tasa['id_impuesto']] = null;
				}
			}

			return $result;
		}



		/***/
		public function analizarGeneraTasa($accionesLocal)
		{
			$result = true;
			if ( count($accionesLocal) > 0 ) {
				foreach ( $accionesLocal as $key => $value ) {
					if ( isset($accionesLocal['GENERA TASA']) ) {

						$accion = $accionesLocal['GENERA TASA'];

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
        public function guardarSolicitudPlanilla($nroSolicitud, $planilla, $conexionLocal, $connLocal, $evento)
        {
            $result = true;
            if ( isset(Yii::$app->user->identity->login) ) {
            	$origen = 'WEB';
            	$usuario = Yii::$app->user->identity->login;
            } else {
            	$origen = 'LAN';
            	$usuario = Yii::$app->user->identity->email;
            }
            //$usuario = isset(Yii::$app->user->identity->login) ? Yii::$app->user->identity->login : Yii::$app->user->identity->email;
            $modelSolicitudPlanilla = New SolicitudPlanillaForm();
            $tabla = $modelSolicitudPlanilla->tableName();

            $modelSolicitudPlanilla->nro_solicitud = $nroSolicitud;
            $modelSolicitudPlanilla->id_config_solicitud = 0;
            $modelSolicitudPlanilla->id_config_solic_tasa_multa = 0;
            $modelSolicitudPlanilla->inactivo = 0;
            $modelSolicitudPlanilla->usuario = $usuario;
            $modelSolicitudPlanilla->fecha_hora = date('Y-m-d H:i:s');
            $modelSolicitudPlanilla->planilla = $planilla;
            $modelSolicitudPlanilla->origen = $origen;
            $modelSolicitudPlanilla->evento = $evento;

            $arregloDatos = $modelSolicitudPlanilla->attributes;

            $result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);

            return $result;
        }





		/**
		 * Metodo que permite determinar el resultado de cada accion ejecutado (procesos ejecutados)
		 * para enviar un resumen que contiene un array donde el key del array es el nombre del proceso
		 * y el valor del elemento es el resultado de la ejecucion del proceso segun la logica de validacion
		 * de cada proceso.
		 * @return Array Retorna un array donde el key representa el nombre del proceso y el valor del elemento
		 * es boolean.
		 */
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