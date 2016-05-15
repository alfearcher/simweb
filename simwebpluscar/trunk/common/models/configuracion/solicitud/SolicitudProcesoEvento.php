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
		public $accion = [];



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

						} elseif ( $miProceso == 'GENERA TASA' ) {

							$result = $this->generaTasa($idContribuyente, $evento, $conexionLocal, $connLocal);
							$this->accion[$miProceso] = $result;

						} elseif ( $miProceso == 'SOLICITA DOCUMENTOS' ) {

						} elseif ( $miProceso == 'GENERA CITA' ) {

						} elseif ( $miProceso == 'GENERA NOTIFICACION' ) {

						} elseif ( $miProceso == 'GENERA MULTA' ) {

						} elseif ( $miProceso == 'GENERA FISCALIZACION' ) {

						} elseif ( $miProceso == 'GENERA AUDITORIA' ) {

						} elseif ( $miProceso == 'GENERA CIERRE' ) {

						} else {
						}
					}
				}
			} else {
				$this->accion = $result;
			}
		}




		/***/
		public function getAccion()
		{
			return $this->accion;
		}



		/***/
		public function generaTasa($idContribuyente, $evento, $conexionLocal, $connLocal)
		{
			$result = null;
			$idImpuesto = 0;
			// Se espera los valores de la entidad "config-solic-tasas-multas".
			$tasas = $this->getDetalleSolicitudTasaMulta($evento);
			foreach ( $tasas as $tasa ) {
				$miTasa = New TasaForm();
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
		public function analizarGeneraTasa($acciones)
		{
			$result = true;
			if ( count($acciones) > 0 ) {
				foreach ( $acciones as $accion ) {
					if ( isset($acciones['GENERA TASA']) ) {
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



	}
 ?>