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

<?php

	$titulo1 = '';
	// $director = 'José Pérez';
	//$nombreCargo = 'Direccion de Rentas';
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">

			<tr>
				<td class="label-barcode" colspan="10" style="text-align: right;padding-left: 105px;">
					<barcode code=<?=$barcode;?> size="1.6" type="QR" height="1.5" width="0.6" class="barcode" />
				</td>
			</tr>
			<!-- <tr>
				<td class="label-barcode" colspan="10" style="font-weight: bold;text-align: center;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 70%;">
					
				</td>
			</tr> -->

			<tr>
				<td class="label-pie-pagina-1-a" colspan="9" style="font-weight: bold;font-size: 90%;font-family: Verdana, Arial, Helvetica, sans-serif;">
					<?=Html::encode('Firma Autorizada:  ' . $director); ?>
				</td>
				<td class="label-pie-pagina-1-c" colspan="1"></td>

			</tr>
			<tr>
				<td class="label-pie-pagina-2" colspan="10" style="font-weight: bold;font-size: 80%;">
					<?=Html::encode($nombreCargo); ?>
				</td>
			</tr>
			
			<tr>
				<td class="label-pie-pagina-4" colspan="10" style="text-align: center;font-size: 55%;font-family: Verdana, Arial, Helvetica, sans-serif;">
					<div class="row" style="color: red;">
						LA IMPRESION DEBE SER LEGIBLE Y DE ALTA CALIDAD.
					</div>
					<div class="row" style="color: red;">
						LA AUTENTICIDAD Y VIGENCIA DE ESTA NOTIFICACION PUEDE VERIFICARSE A TRAVES DEL CODIGO QR
						QUE SE ENCUENTRA EN LA PARTE INFERIOR DERECHA.
					</div>
					
				</td>
			</tr>
		</table>
	</body>
</html>







<!-- <style type="text/css">

	.label-pie-pagina-1-a,
	.label-pie-pagina-1-b,
	.label-pie-pagina-1-c,
	.label-pie-pagina-2 {
		font-size: 90%;
		font-weight: bold;
		font-family: Verdana, Arial, Helvetica, sans-serif;
	}

	.label-pie-pagina-2 {
		font-size: 80%;
	}


	.label-pie-pagina-1-a {
		width: 20%;
	}


	.label-pie-pagina-3 {
		display: inline-block;
		text-align: justify;
		color: red;
		/*text-align: center;*/
		font-size: 85%;
		border: 1.5px solid #ccc;
		border-radius: 20px;
		background-color: #FFE061;

		padding: 0px;
		font-family: Verdana, Arial, Helvetica, sans-serif;

	}


	.pie-31 .label-pie-pagina-31 {
		border: 2.5px solid #ccc;
		border-radius: 60px;
	}


	.label-pie-pagina-4 {
		/*color: red;*/
		text-align: center;
		font-size: 50%;
		border: 0.5px solid #ccc;
		border-radius: 40px;

		padding: 0px;
		font-family: Verdana, Arial, Helvetica, sans-serif;

	}

</style> -->