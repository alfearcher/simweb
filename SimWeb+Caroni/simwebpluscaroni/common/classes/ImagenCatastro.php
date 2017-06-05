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
	class ImagenCatastro
	{


		
		/**
		 * Metodo que permite armar la ruta de la imagen adverso del catastro.
		 * @param  integer $tipo tipo de solicitud
		 * @param  string $escenario "backend" o "frontend"
		 * @return string retorna una url donde se especifica la ruta de la ayuda.
		 */
		public function getRutaImagenAdverso($idImpuesto)
		{
			$rutaBase = '@web/imagen/imagencatastro/adverso/A';
			
			if ( $rutaBase == '' ) {
				return '';
			} else {
			 	return $rutaBase .'-'. $idImpuesto . '.jpg';
			}
		}

		/**
		 * Metodo que permite armar la ruta de la imagen del reverso del catastro.
		 * @param  integer $tipo tipo de solicitud
		 * @param  string $escenario "backend" o "frontend"
		 * @return string retorna una url donde se especifica la ruta de la ayuda.
		 */
		public function getRutaImagenReverso($idImpuesto)
		{
			$rutaBase = '@web/imagen/imagencatastro/reverso/R';
			if ( $rutaBase == '' ) {
				return '';
			} else {
			 	return $rutaBase .'-'. $idImpuesto . '.jpg';
			}
		}

//$rutaImagen = Yii::$app->catastro->getRutaImagenAdverso($idImpuesto);
	}



 ?>
