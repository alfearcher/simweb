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
	'999' => [Yii::t('backend', 'User not valid'), 'danger', 'fa fa-times'],
	'900' => [Yii::t('backend', 'Register not valid'), 'danger', 'fa fa-times'],
	'910' => [Yii::t('backend', 'Saving data not detected'), 'danger', 'fa fa-times'],
	'920' => [Yii::t('backend', 'Error. The operation was not executed'), 'danger', 'fa fa-times'],
	'930' => [Yii::t('backend', 'Taxpayer type was not defined'), 'danger', 'fa fa-times'],
	'940' => [Yii::t('backend', 'Request was not defined'), 'danger', 'fa fa-times'],
	'945' => [Yii::t('backend', 'Request exists'), 'danger', 'fa fa-times'],
	'946' => [Yii::t('backend', 'Request details not exists'), 'danger', 'fa fa-times'],
	'990' => [Yii::t('backend', 'Register not found'), 'danger', 'fa fa-times'],
	'991' => [Yii::t('backend', 'You can aquire a property you already own '), 'danger', 'fa fa-exclamation-triangle'],
	'992' => [Yii::t('backend', 'This car plate doesnt exists, please go to your city hall  '), 'danger', 'fa fa-exclamation-triangle'],
	'993' => [Yii::t('backend', 'The buyer doesnt exists, please go to your city hall  '), 'danger', 'fa fa-exclamation-triangle'],
	'994' => [Yii::t('backend', 'This oficcer is already enabled'), 'danger', 'fa fa-exclamation-triangle'],
	'995' => [Yii::t('backend', 'This Stickers lot already exists'), 'danger', 'fa fa-exclamation-triangle'],
	'996' => [Yii::t('backend', 'This Stickers lot has already been asigned'), 'danger', 'fa fa-exclamation-triangle'],
	'997' => [Yii::t('backend', 'This Car already has a sticker asigned'), 'danger', 'fa fa-exclamation-triangle'],


	'404' => [Yii::t('backend', 'The requested page does not exist.'), 'danger', 'fa fa-times'],
	'100' => [Yii::t('backend', 'Register saved'), 'success', 'fa fa-check-circle-o'],
	'200' => [Yii::t('backend', 'Register updated'), 'success', 'fa fa-check-circle-o'],
	'300' => [Yii::t('backend', 'Operation executed'), 'success', 'fa fa-check-circle-o'],
	'400' => [Yii::t('backend', 'Register was inactivated'), 'success', 'fa fa-check-circle-o'],
	'401' => [Yii::t('backend', 'Security Answers not Created'), 'warning', 'fa fa-exclamation-triangle'],
	'402' => [Yii::t('backend', 'We have sent you an email with your user and Password'), 'success', 'fa fa-exclamation-triangle'],
	'403' => [Yii::t('backend', 'You already have an open request for this '), 'success', 'fa fa-exclamation-triangle'],

]


?>