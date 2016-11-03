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
 *  @file layout-declaracion-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view layout-declaracion-pdf.php
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
	use common\models\calculo\liquidacion\aaee\CalculoRubro;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

 ?>

<?php
	$fechaEmision = date('d-m-Y');
	$sumaImpuesto = 0;
?>

<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>CODIGO CATASTRAL</caption>

	<tr>
		<th class="label-declaracion-principal" colspan="3"><?=Html::encode('TIPO DECLARACION'); ?></th>
		<th class="label-declaracion-principal" colspan="4"><?=Html::encode('PERIODO FISCAL'); ?></th>
		<th class="label-declaracion-principal" colspan="3"><?=Html::encode('FECHA EMISION Y NOTIFICACION'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-declaracion-principal" colspan="3"><?=Html::encode($tipoDeclaracion); ?></td>
		<td class="info-declaracion-principal" colspan="4"><?=Html::encode($periodoFiscal); ?></td>
		<td class="info-declaracion-principal" colspan="3"><?=Html::encode($fechaEmision); ?></td>
	</tr>

<!-- Datos especificos de la declaracion -->
	<tr>
		<th class="label-declaracion" colspan="1"><?=Html::encode('EDO'); ?></th>
		<th class="label-declaracion" colspan="3"><?=Html::encode('MUN'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('PARR'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('AMBITO'); ?></th>
		<th class="label-declaracion" colspan="2"><?=Html::encode('SECTOR'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('MANZ'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('PARCELA'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('SUB-P'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('NIVEL'); ?></th>
		<th class="label-declaracion" colspan="1"><?=Html::encode('UNIDAD'); ?></th>
	</tr>  

	<?php foreach ( $resumen as $i => $r ) {?>
		<?php
			$sumaImpuesto = $sumaImpuesto + $r['impuesto'];
		?>
		<tr class="cuerpo">
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['rubro']); ?></td>
			<td class="info-declaracion-x" colspan="3"><?=Html::encode($resumen['descripcion']); ?></td>
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['alicuota']); ?></td>
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['minimo_ut']); ?></td>
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['rubro']); ?></td>
			<td class="info-declaracion-x" colspan="3"><?=Html::encode($resumen['descripcion']); ?></td>
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['alicuota']); ?></td>
			<td class="info-declaracion" colspan="1"><?=Html::encode($resumen['minimo_ut']); ?></td>
			<?php if ( $tipoDeclaracion == 'ESTIMADA' ) {?>
				<td class="info-declaracion" colspan="2" style="text-align: right;"><?=Html::encode(Yii::$app->formatter->asDecimal($r['estimado'], 2)); ?></td>
			<?php } elseif ( $tipoDeclaracion == 'DEFINITIVA' ) {?>
				<td class="info-declaracion" colspan="2" style="text-align: right;"><?=Html::encode(Yii::$app->formatter->asDecimal($r['reales'], 2)); ?></td>
			<?php } ?>
			<td class="info-declaracion" colspan="1" style="text-align: right;"><?=Html::encode(Yii::$app->formatter->asDecimal($r['minimo'], 2)); ?></td>
			<td class="info-declaracion" colspan="1" style="text-align: right;"><?=Html::encode(Yii::$app->formatter->asDecimal($r['impuesto'], 2)); ?></td>
		</tr>
	<?php } ?>

<!-- Totalizacion -->
	<tr>
		<td class="label-total" colspan="7"><?=Html::encode('MONTO TOTAL ANUAL A PAGAR:'); ?></th>
		<td class="info-total" colspan="3"><?=Html::encode(Yii::$app->formatter->asDecimal($sumaImpuesto, 2)); ?></td>
	</tr>
</table>

<style type="text/css">

	.label-total, .info-total {
		border-top: solid 2px #000;
	}


	.info-declaracion-principal,
	.label-declaracion {
		border-bottom: solid 2px #000;
	}

	.info-declaracion,
	.info-declaracion-x {
		border-bottom: solid 1px #ccc;
	}

	.label-declaracion-principal {
		text-align: center;
		font-size: 70%;
	}

	.info-declaracion-principal {
		text-align: center;
		font-size: 70%;
	}


	.label-declaracion {
		text-align: center;
		font-size: 60%;
	}

	.info-declaracion,
	.info-declaracion-x {
		text-align: center;
		font-size: 65%;
	}

	.info-declaracion-x {
		text-align: normal;
	}


	.label-total, .info-total {
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

	.cuerpo {
		border-bottom: solid 1px #ccc;
	}
</style>