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


	/**
	*
	*/
	class SolicitudProcesoEvento extends ParametroSolicitud
	{
		public $idConfigSolicitud;



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
		public function getProcesoQueGeneraSolicitudSegunEvento($evento)
		{
			$listaProcesos = $this->getProcesoSegunEvento($evento);
die(var_dump($listaProcesos));
		}


	}
 ?>