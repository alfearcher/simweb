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
 *  @file layout-piepagina-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @view layout-piepagina-pdf.php
 *
 *
 *  pie de pagina del pdf del comprobante de declaracion.
 *
 *
 *  @inherits
 *
 */

 	//use yii\web\Response;
 	use kartik\icons\Icon;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

 ?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>

		<div class="row" style="width: 100%;padding:0px;padding-top:0px;">
			<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
				<tr>
					<td class="label-pie-pagina-3" colspan="10" style="text-align: center;font-size: 75%;font-family: Verdana, Arial, Helvetica, sans-serif;">
						<div class="nota-pie-1" >
							COLOCAR EN UN LUGAR VISIBLE Y ACCESIBLE AL PUBLICO EN GENERAL, LA IMPRESION DEBE SER LEGIBLE Y DE ALTA CALIDAD.
						</div>
					</td>
				</tr>
				<tr>
					<td class="label-pie-pagina-4" colspan="10" style="text-align: center;font-size: 55%;font-family: Verdana, Arial, Helvetica, sans-serif;">
						<div class="row" style="color: red;">
							LA AUTENTICIDAD Y VIGENCIA DE ESTA NOTIFICACION PUEDE VERIFICARSE A TRAVES DEL CODIGO QR
							QUE SE ENCUENTRA EN LA PARTE INFERIOR DERECHA.
						</div>
						<!-- <div class="row">
							<strong>Este documento ha sido firmado electrónicamente, amparado en el decreto Nro. 1204 con rango
							y fuerza de Ley sobre Mensajes de datos y Firmas Electrónica de fecha 10/02/2001, publicado
							en Gaceta Oficial de la Republica Bolivariana de Venezuela Nro. 37.148 del 28/02/2001.</strong>
						</div> -->
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
