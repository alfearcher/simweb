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
 *	@file codigo-mensaje.php
 *
 *	@author Jose Rafael Perez Teran
 *	@email jperez320@gmail.com - jperez820@hotmail.com
 *
 *	@date 22-12-2015
 *
 *	@brief Catalogo de mensaje que especifican el tipo de accion sucedido, esto con la
 *	intencion de centralizar la emmision de los mensajes que apareceran en el sistema y
 *	que comunicaran al usuario el resultado de uns accion.
 *
 * 	Se retorna una catalogo de mensajes en formato de arreglo con el par codigo-mensaje.
 *
 */
//fa fa-exclamation-triangle, exclamasion
//fa fa-info-circle, informacion
//fa fa-question-circle, interrogacion

return [
	'999' => [Yii::t('backend', 'User no valid'), 'danger', 'fa fa-times'],
	'900' => [Yii::t('backend', 'Register no valid'), 'danger', 'fa fa-times'],
	'910' => [Yii::t('backend', 'Data for save no detect'), 'danger', 'fa fa-times'],
	'920' => [Yii::t('backend', 'Error. The operation not was executed'), 'danger', 'fa fa-times'],
	'930' => [Yii::t('backend', 'Don`t was defined to onwer type'), 'danger', 'fa fa-times'],
	'990' => [Yii::t('backend', 'Register found not'), 'danger', 'fa fa-times'],
	'404' => [Yii::t('backend', 'The requested page does not exist.'), 'danger', 'fa fa-times'],
	'100' => [Yii::t('backend', 'Register saved'), 'success', 'fa fa-check-circle-o'],
	'200' => [Yii::t('backend', 'Register updated'), 'success', 'fa fa-check-circle-o'],
	'300' => [Yii::t('backend', 'Operation execute'), 'success', 'fa fa-check-circle-o'],
	'400' => [Yii::t('backend', 'Register was inactivated'), 'success', 'fa fa-check-circle-o'],
]


?>