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
 *  @file layout-rubro-autorizado-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 23-11-2016
 *
 *  @view layout-rubro-autorizado-pdf.php
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

<?php

?>


<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>RUBROS AUTORIZADOS</caption>

	<tr>
		<th class="label-rubro" colspan="1"><?=Html::encode('CODIGO'); ?></th>
		<th class="label-rubro" colspan="7"><?=Html::encode('DESCRIPCION'); ?></th>
		<th class="label-rubro" colspan="1"><?=Html::encode('ALICUOTA'); ?></th>
		<th class="label-rubro" colspan="1"><?=Html::encode('MINIMO TRIBUTABLE'); ?></th>
	</tr>

	<?php foreach ( $datosRubro as $rubro ) { ?>
		<tr class="cuerpo">
			<td class="info-rubro-codigo" colspan="1"><?=Html::encode($rubro['rubro']); ?></td>
			<td class="info-rubro" colspan="7"><?=Html::encode($rubro['descripcion']); ?></td>
			<td class="info-rubro-alicuota" colspan="1"><?=Html::encode(Yii::$app->formatter->asDecimal($rubro['alicuota'],2)); ?></td>
			<td class="info-rubro-minimo" colspan="1"><?=Html::encode(Yii::$app->formatter->asDecimal($rubro['minimo'],2)); ?></td>
		</tr>
	<?php } ?>
		<tr class="cuerpo">
			<td class="linea" colspan="10"><?=Html::encode(''); ?></td>
		</tr>
</table>


<style type="text/css">

	.info-rubro {
		/*border-bottom: solid 2px #000;*/
	}

	.linea {
		border-bottom: solid 2px #000;
		width: 100%;
	}

	.label-rubro {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 70%;
		border-bottom: solid 2px #000;

	}

	.info-rubro {
		text-align: justify;
		font-size: 80%;
		border-bottom: solid 0.5px #000;
		font-weight: bold;
	}

	.info-rubro-codigo,
	.info-rubro-alicuota,
	.info-rubro-minimo {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 80%;
		border-bottom: solid 0.25px #000;
		font-weight: bold;
	}


	.info-rubro-alicuota,
	.info-rubro-minimo {
		text-align: right;
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
