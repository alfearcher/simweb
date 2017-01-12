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
 *  @file layout-identidad-pago-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-11-2016
 *
 *  @view layout-identidad-pago-pdf.php
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

 ?>

<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>IDENTIFICACION DEL PAGO</caption>

	<tr>
		<th class="label-identidad-pago" colspan="2"><?=Html::encode('ID. CONTRIBUYENTE'); ?></th>
		<th class="label-identidad-pago" colspan="2"><?=Html::encode('NRO. RECIBO'); ?></th>
		<th class="label-identidad-pago" colspan="4"><?=Html::encode('CODIGO VALIDADOR'); ?></th>
		<th class="label-identidad-pago" colspan="1"><?=Html::encode('FECHA EMISION'); ?></th>
		<th class="label-identidad-pago" colspan="1"><?=Html::encode('FECHA VENCIMIENTO'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-identidad-pago" colspan="2"><?=Html::encode($model->id_contribuyente); ?></td>
		<td class="info-identidad-pago" colspan="2"><?=Html::encode($model->recibo); ?></td>
		<td class="info-identidad-pago" colspan="4"><?=Html::encode($cvb); ?></td>
		<td class="info-identidad-pago" colspan="1"><?=Html::encode(date('d-m-Y', strtotime($model->fecha))); ?></td>
		<td class="info-identidad-pago" colspan="1"><?=Html::encode(date('d-m-Y', strtotime($model->fecha))); ?></td>

	</tr>
</table>


<style type="text/css">

	.info-identidad-pago {
		/*border-bottom: solid 2px #000;*/
	}

	.label-identidad-pago {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 80%;
		border-bottom: solid 2px #175778;

	}

	.info-identidad-pago {
		text-align: center;
		font-weight: bold;
		font-size: 100%;
		font-family: Arial, Helvetica, sans-serif;
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
