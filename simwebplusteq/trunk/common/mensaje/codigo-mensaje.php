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
	'921' => [Yii::t('backend', 'El inmueble ya posee avaluo para el año actual'), 'danger', 'fa fa-times'],
	'922' => [Yii::t('backend', 'El inmueble no posee datos de propiedad. Realice la solicitud DATOS DE REGISTRO DE PROPIEDAD'), 'danger', 'fa fa-times'],

	'930' => [Yii::t('backend', 'Taxpayer type was not defined'), 'danger', 'fa fa-times'],
	'932' => [Yii::t('backend', 'Taxpayer not defined'), 'danger', 'fa fa-times'],
	'934' => [Yii::t('backend', 'Type of taxpayer incorrect'), 'danger', 'fa fa-times'],
	'936' => [Yii::t('backend', 'Contribuyente no es sede principal'), 'danger', 'fa fa-times'],
	'938' => [Yii::t('backend', 'Error. Info taxpayer not found'), 'danger', 'fa fa-times'],
	'940' => [Yii::t('backend', 'Request was not defined'), 'danger', 'fa fa-times'],
	'941' => [Yii::t('backend', 'Status of request incorrect '), 'danger', 'fa fa-times'],
	'945' => [Yii::t('backend', 'Request exists'), 'warning', 'fa fa-info-circle'],
	'946' => [Yii::t('backend', 'Request details not exists'), 'danger', 'fa fa-times'],
	'947' => [Yii::t('backend', 'El contribuyente ya esta registrado como contribuyente de Actividad Economica'), 'warning', 'fa fa-info-circle'],

	'950' => [Yii::t('backend', 'Invoice uncreditworthy'), 'danger', 'fa fa-times'],
	'955' => [Yii::t('backend', 'Setup parameter was not defined'), 'danger', 'fa fa-times'],
	'960' => [Yii::t('backend', 'El contribuyente posee una solicitud para crear sucursal. Hasta que no se procese dicha solicitud no podra crear una para modificacion del rif.'), 'danger', 'fa fa-times'],
	'961' => [Yii::t('backend', 'Al menos una de las propagandas seleccionadas ya posee un patrocinador activo'), 'danger', 'fa fa-times'],
	'962' => [Yii::t('backend', 'El contribuyente ya posee una solicitud por inscripcion de actividad economica, dicha solicitud no permite crear la actual'), 'danger', 'fa fa-times'],
	'964' => [Yii::t('backend', 'Taxpayer doesnt has start date activity'), 'danger', 'fa fa-times'],
	'970' => [Yii::t('backend', 'No tiene rango ordenazas pendientes.'), 'warning', 'fa fa-info-circle'],
	'971' => [Yii::t('backend', 'El inmueble ya posee una solicitud pendiente de cambio numero catastral, dicha solicitud no permite crear la actual'), 'danger', 'fa fa-times'],
    '972' => [Yii::t('backend', 'El inmueble ya posee una solicitud pendiente de Desintegracion, dicha solicitud no permite crear la actual'), 'danger', 'fa fa-times'],
    '973' => [Yii::t('backend', 'La recuperacion de contraseña a fallado'), 'success', 'fa fa-check-circle-o'],
    '974' => [Yii::t('backend', 'La recuperacion de contraseña a fallado por no tener correo electronico asignado como contribuyente'), 'danger', 'fa fa-check-circle-o'],
    '975' => [Yii::t('backend', 'Anteriormente ingreso validamente al sistema, seleccione la opcion CAMBIAR CONTRASEÑA'), 'danger', 'fa fa-check-circle-o'],
    '976' => [Yii::t('backend', 'El contribuyente no posee afiliacion al sistema'), 'danger', 'fa fa-check-circle-o'],


	'990' => [Yii::t('backend', 'Register not found'), 'danger', 'fa fa-times'],
	'991' => [Yii::t('backend', 'You can aquire a property you already own '), 'danger', 'fa fa-exclamation-triangle'],
	'992' => [Yii::t('backend', 'This car plate doesnt exists, please go to your city hall  '), 'danger', 'fa fa-exclamation-triangle'],

	'993' => [Yii::t('backend', 'The buyer doesnt exists, please go to your city hall  '), 'danger', 'fa fa-exclamation-triangle'],
	'994' => [Yii::t('backend', 'This oficcer is already enabled'), 'danger', 'fa fa-exclamation-triangle'],
	'995' => [Yii::t('backend', 'This Stickers lot already exists'), 'danger', 'fa fa-exclamation-triangle'],
	'996' => [Yii::t('backend', 'This Stickers lot has already been asigned'), 'danger', 'fa fa-exclamation-triangle'],
	'997' => [Yii::t('backend', 'This Car already has a sticker asigned'), 'danger', 'fa fa-exclamation-triangle'],
	'998' => [Yii::t('backend', 'The year you selected is not available'), 'danger', 'fa fa-exclamation-triangle'],
	'899' => [Yii::t('backend', 'At least 1 officer still have a sticker lot asigned'), 'danger', 'fa fa-exclamation-triangle'],

	'404' => [Yii::t('backend', 'The requested page does not exist.'), 'danger', 'fa fa-times'],
	'100' => [Yii::t('backend', 'Register saved'), 'success', 'fa fa-check-circle-o'],
	'101' => [Yii::t('backend', 'Request was apporved successfully'), 'success', 'fa fa-check-circle-o'],
	'102' => [Yii::t('backend', 'Request was rejected successfully'), 'success', 'fa fa-check-circle-o'],
	'103' => [Yii::t('backend', 'Proceso exitoso, el usuario y clave han sido enviado a su correo electronico'), 'success', 'fa fa-check-circle-o'],
	'104' => [Yii::t('frontend', 'Proceso exitoso, su nueva clave ha sido enviada a su correo electronico'), 'success', 'fa fa-check-circle-o'],
	'200' => [Yii::t('backend', 'Register updated'), 'success', 'fa fa-check-circle-o'],
	'300' => [Yii::t('backend', 'Operation executed'), 'success', 'fa fa-check-circle-o'],
	'400' => [Yii::t('backend', 'Register was inactivated'), 'success', 'fa fa-check-circle-o'],
	'401' => [Yii::t('backend', 'Security Answers not Created'), 'warning', 'fa fa-exclamation-triangle'],
	'402' => [Yii::t('backend', 'We have sent you an email with your user and Password'), 'success', 'fa fa-exclamation-triangle'],
	'403' => [Yii::t('backend', 'You already have an open request for this '), 'success', 'fa fa-exclamation-triangle'],

	'410' => [Yii::t('backend', 'Recibo no encontrado'), 'danger', 'fa fa-exclamation-triangle'],

	'501' => [Yii::t('frontend', 'No presenta deudas pendientes'), 'success', 'fa fa-info-circle'],
	'505' => [Yii::t('frontend', 'No presenta vehiculos activos registrados'), 'warning', 'fa fa-info-circle'],
	'507' => [Yii::t('frontend', 'El vehiculo no presenta historico de solvencias'), 'warning', 'fa fa-info-circle'],
	'509' => [Yii::t('frontend', 'No presenta inmuebles activos registrados'), 'warning', 'fa fa-info-circle'],
	'510' => [Yii::t('frontend', 'El inmueble no presenta historico de solvencias'), 'warning', 'fa fa-info-circle'],

	'520' => [Yii::t('frontend', 'No presenta historico de licencias'), 'warning', 'fa fa-info-circle'],
]


?>