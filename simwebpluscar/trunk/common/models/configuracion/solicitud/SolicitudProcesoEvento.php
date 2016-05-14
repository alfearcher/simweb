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
		public function ejecutarProcesoSolicitudSegunEvento($evento, $conexionLocal = null, $connLocal = null)
		{
			$listaProcesos = $this->getProcesoSegunEvento($evento);
			foreach ( $listaProcesos as $proceso ) {
				foreach ( $proceso as $key => $value ) {
					$miProceso = strtoupper(trim($value));
					$this->accion[$miProceso] = [];
					if ( $miProceso == 'LIQUIDACION DIRECTA' ) {

					} elseif ( $miProceso == 'GENERA TASA' ) {
						
						$result = $this->generaTasa($evento, $conexionLocal, $connLocal);
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
			die();
		}




		/***/
		public function generaTasa($evento, $conexionLocal, $connLocal)
		{
			$result = [];
			$idImpuesto = 0;
			// Se espera los valores de la entidad "config-solic-tasas-multas".
			$tasas = $this->getDetalleSolicitudTasaMulta($evento);
			foreach ( $tasas as $tasa ) {
				foreach ( $tasa as $key => $value ) {
					if ( $key == 'id_impuesto' ) {
						$miTasa = New TasaForm();
						$idImpuesto = $miTasa->determinarTasaParaLiquidar($value);
						if ( $idImpuesto > 0 ) {
die(var_dump(Yii::$app->user->identity));
						}
					}
				}
			}
		}


	}
 ?>