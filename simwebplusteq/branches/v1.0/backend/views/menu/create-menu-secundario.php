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
 *  @file create-menu-secundario.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com, jperez820@hotnail.com
 *
 *  @date 29-12-2015
 *
 *  @view create-menu-secundario.php
 *  @brief vista inicial del menu secundario de la aplicacion del lado del cliente. Backend de la aplicacion
 *
 */

 	use yii\helpers\Html;

  	// Se crea una array de valores que controle la visibilidad del icon y las url's que maneje cada icon's
	// La variable opciones, representa un array con la siguiente estructura:
	// ['operacion' => url], la operacion es representada por las siguientes tareas:
	// 1. create.
	// 2. list.
	// 3. update.
	// 4. delete (aun no desarrollado)
	// La url es la direccion a donde se enviara el post.
?>

<?= $this->render('menu-secundario', [
							'opciones' => $opciones,

			])
?>
