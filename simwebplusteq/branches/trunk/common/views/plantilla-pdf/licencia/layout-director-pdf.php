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
 *  @file layout-director-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @view layout-director-pdf.php
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
		<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
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
		</table>
	</body>
</html>
