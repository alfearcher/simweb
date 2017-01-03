<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file GestorAyuda.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 13-05-2015
 *
 *  @class GestorAyuda
 *
 *	@method
 *
 *	@inherits
 *
 */

	namespace common\classes;

	use Yii;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;

	/**
	*
	*/
	class GestorAyuda
	{


		/***/
		private function getRutaBase()
		{
			return $ruta = require(dirname(__DIR__)) . '/docs/user/index-ruta.php';
		}


		/***/
		public function getRutaAyuda($tipo)
		{
			return $rutaBase = self::getRutaBase()[$tipo] . self::findTipoSolicitud($tipo);
		}




		/***/
		private function findTipoSolicitud($tipo)
		{
			$descripcion = '';
			$result = TipoSolicitud::findOne($tipo);
			if ( $result !== null ) {
				$descripcion = $result['descripcion'] . '.pdf';
			}
			return $descripcion;
		}


	}



 ?>
