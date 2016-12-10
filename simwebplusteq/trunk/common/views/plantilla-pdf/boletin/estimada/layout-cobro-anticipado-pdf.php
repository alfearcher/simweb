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
 *  @file layout-cobro-anticipado-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view layout-cobro-anticipado-pdf.php
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
	$sumaImpuesto = 0;
	$subTotal = 0;
	$sumaDescuento = 0;
?>

<!-- Especificaciones de los periodos a pagar -->
<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>ESPECIFICACIONES DE LOS COBROS ANTICIPADOS</caption>

	<tr>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('PERIODO'); ?></th>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('DESCRIPCION'); ?></th>
		<th class="label-liquidacion" colspan="2"><?=Html::encode('MONTO A PAGAR'); ?></th>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('PERIODO PAGO'); ?></th>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('RECARGO EN'); ?></th>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('INTERES EN'); ?></th>
		<th class="label-liquidacion" colspan="1"><?=Html::encode('DESCUENTO'); ?></th>
		<th class="label-liquidacion" colspan="2"><?=Html::encode('SUBTOTAL'); ?></th>
	</tr>



	<?php foreach ( $resumen as $i => $r ) { ?>
		<?php
			$sumaImpuesto = $sumaImpuesto + $r['monto'];
			$subTotal = $r['monto'];
			$sumaDescuento = $sumaDescuento + $r['descuento'];
		?>
		<tr class="cuerpo-liquidacion">
			<td class="info-liquidacion" colspan="1"><?=Html::encode($r['periodo']); ?></td>
			<td class="info-liquidacion" colspan="1"><?=Html::encode($r['descripcion']); ?></td>
			<td class="info-liquidacion" colspan="2" style="width: 70%;"><?=Html::encode(Yii::$app->formatter->asDecimal($r['monto'])); ?></td>
			<td class="info-liquidacion" colspan="1"><?=Html::encode($r['pagarEn']); ?></td>
			<td class="info-liquidacion" colspan="1"><?=Html::encode($r['recargoEn']); ?></td>
			<td class="info-liquidacion" colspan="1"><?=Html::encode($r['interesEn']); ?></td>
			<td class="info-liquidacion" colspan="1"><?=Html::encode(Yii::$app->formatter->asDecimal(0)); ?></td>
			<td class="info-liquidacion" colspan="2" style="text-align: right;"><?=Html::encode(Yii::$app->formatter->asDecimal($subTotal)); ?></td>
		</tr>
	<?php }?>
	<tr>
		<td class="label-total-liquidacion" colspan="6"><?=Html::encode('MONTO A PAGAR EN EL AÑO:'); ?></th>
		<td class="info-total-liquidacion" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($sumaImpuesto, 2)); ?></td>
	</tr>
	<tr>
		<td class="label-total-descuento" colspan="6"><?=Html::encode('DESCUENTO SI PAGA TODO EL AÑO ANTES DEL ' . $resumen[1]['fechaHasta'] . ':'); ?></th>
		<td class="info-total-descuento" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($sumaDescuento, 2)); ?></td>
	</tr>

	<tr>
		<td class="label-total" colspan="6"><?=Html::encode('TOTAL A PAGAR:'); ?></th>
		<td class="info-total" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($sumaImpuesto-$sumaDescuento, 2)); ?></td>
	</tr>

</table>


<style type="text/css">

	.label-total-liquidacion,
	.info-total-liquidacion,
	.label-total-descuento,
	.info-total-descuento,
	.label-total,
	.info-total {
		border-top: solid 2px #000;
	}


	.label-liquidacion {
		border-bottom: solid 2px #000;
		font-size: 68%;
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
	}

	.info-liquidacion {
		border-bottom: solid 1px #ccc;
	}

	.label-liquidacion-principal {
		text-align: center;
		font-size: 70%;
	}

	.info-liquidacion {
		text-align: center;
		font-size: 70%;
	}


	.label-total-liquidacion,
	.info-total-liquidacion,
	.label-total-descuento,
	.info-total-descuento {
		text-align: right;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 85%;
		font-weight: bold;
	}


	.label-total,
	.info-total {
		text-align: right;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 100%;
		font-weight: bold;
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

	.cuerpo-liquidacion {
		border-bottom: solid 1px #ccc;
	}
</style>
