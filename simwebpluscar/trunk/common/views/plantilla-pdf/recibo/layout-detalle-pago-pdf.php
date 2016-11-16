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
 *  @file layout-detalle-pago-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-11-2016
 *
 *  @view layout-detalle-pago-pdf.php
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *  @inherits
 *
 */

 	//use yii\web\Response;
 	//use kartik\icons\Icon;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\web\View;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

	$total = 0;

 ?>

<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>DETALLE GENERAL DE PAGO</caption>

	<tr>
		<th class="label-detalle-pago" colspan="4"><?=Html::encode('IMPUESTO Y/O TASA'); ?></th>
		<th class="label-detalle-pago" colspan="2"><?=Html::encode('AÑOS ANTERIORES'); ?></th>
		<th class="label-detalle-pago" colspan="2"><?=Html::encode('AÑO ACTUAL'); ?></th>
		<th class="label-detalle-pago" colspan="2"><?=Html::encode('SUBTOTAL'); ?></th>
	</tr>

	<?php foreach ( $deudas as $deuda ) {
		$total = ($deuda['morosa'] + $deuda['actual']) + $total;
	?>
		<tr class="cuerpo">
			<td class="info-detalle-pago" colspan="4"><?=Html::encode($deuda['descripcion']); ?></td>
			<td class="info-detalle-monto" colspan="2"><?=Html::encode(Yii::$app->formatter->asDecimal($deuda['morosa'], 2)); ?></td>
			<td class="info-detalle-monto" colspan="2"><?=Html::encode(Yii::$app->formatter->asDecimal($deuda['actual'], 2)); ?></td>
			<td class="info-detalle-monto-t" colspan="2"><?=Html::encode(Yii::$app->formatter->asDecimal($deuda['morosa'] + $deuda['actual'], 2)); ?></td>

		</tr>
	<?php } ?>
	<tr>
		<td class="label-total" colspan="6"><?=Html::encode('TOTAL A PAGAR'); ?></td>
		<td class="info-total" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($total, 2)); ?></td>
	</tr>
</table>


<style type="text/css">

	.info-identidad-pago {
		/*border-bottom: solid 2px #000;*/
	}


	.label-detalle-pago,
	.label-detalle-pago-a {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 80%;
		border-bottom: solid 0.25px #175778;

	}


	.label-detalle-pago {
		border-bottom: solid 2px #175778;

	}


	.info-detalle-pago {
		text-align: left;
		font-weight: bold;
		font-size: 80%;
		font-family: Arial, Helvetica, sans-serif;
		border-bottom: solid 0.25px #175778;
	}


	.info-detalle-monto,
	.info-detalle-monto-t,
	.info-total,
	.label-total {
		text-align: right;
		/*font-weight: bold;*/
		font-size: 70%;
		font-family: Arial, Helvetica, sans-serif;
		border-bottom: solid 0.25px #175778;
	}


	.info-total,
	.label-total {
		font-weight: bold;
		font-size: 120%;
		border-bottom: solid 2.5px #175778;
		border-top: solid 2px #175778;
	}




	caption {
		color: white;
		text-align: center;
		font-size: 100%;
		border: 0.5px solid #175778;
		background-color: #175778;
		border-radius: 50px;
		padding: 0px;
		font-family: Arial, Helvetica, sans-serif;
		height: 2.2%;
		width: 180%;
		padding-top: 2px;
		/*margin-top: 35px;*/
	}
</style>
