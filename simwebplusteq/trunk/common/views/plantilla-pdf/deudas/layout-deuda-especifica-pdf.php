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
 *  Resumen de los pagos realizados en el periodo que se tomaran en cuenta en el calculo
 *  de la definitiva.
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

//die(var_dump($datos));
?>

<!-- Especificaciones de los periodos a pagar -->
<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>ESPECIFICACIONES DE LA DEUDA</caption>



	<tr>
		<td class="label-caption" colspan="5"><?=Html::encode('ID del Objeto'); ?></td>
			<td class="info-declaracion-principal" colspan="3"><?=Html::encode($datos[0]['id_impuesto']); ?></td>
	</tr>
  <tr>
    <td class="label-caption" colspan="5"><?=Html::encode('Año Impositivo'); ?></td>
      <td class="info-declaracion-principal" colspan="3"><?=Html::encode($datos[0]['ano_impositivo']); ?></td>
  </tr>
	<tr>
		<td class="label-caption" colspan="5"><?=Html::encode('Informacion del Objeto'); ?></td>
		<td class="info-declaracion-principal" colspan="3"><?=Html::encode($datos[0]['descripcion']); ?></td>
	</tr>
	<tr>
		<td class="label-caption" colspan="5"><?=Html::encode('Nro. Planilla'); ?></td>
		<td class="info-declaracion-principal" colspan="3"><?=Html::encode($datos[0]['planilla']); ?></td>
	</tr>


	<tr>
		<td class="label-total-pago" colspan="6"><?=Html::encode('SUB-TOTAL:'); ?></th>
		<td class="monto-total-pago" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($datos[0]['monto'], 2).' Bs.F'); ?></td>
	</tr>
	<tr>
		<td class="label-causado" colspan="6"><?=Html::encode('MONTO TOTAL:'); ?></th>
		<td class="monto-causado" colspan="4"><?=Html::encode(Yii::$app->formatter->asDecimal($datos[0]['monto_total']).' Bs.F'); ?></td>
	</tr>


</table>


<style type="text/css">

	.label-caption {
		border-bottom: solid 0.5px #000;
		font-size: 80%;
		font-weight: bold;
		font-family: Arial, Helvetica, sans-serif;
	}


	.monto {
		border-bottom: solid 0.5px #000;
		font-size: 80%;
		font-family: Arial, Helvetica, sans-serif;
		text-align: right;
	}

	.label-total-pago,
	.label-causado {
		border-top: solid 2px #000;
		font-size: 100%;
		font-weight: bold;
		font-family: Arial, Helvetica, sans-serif;
		text-align: right;
	}


	.monto-total-pago,
	.monto-causado {
		border-top: solid 2px #000;
		text-align: right;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 100%;
		font-weight: bold;
	}

	.monto-causado {
		border-bottom: solid 1px #000;
	}

	.label-causado,
	.monto-causado {
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
