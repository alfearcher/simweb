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
 *  @file layout-info-general-impuesto-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-11-2016
 *
 *  @view layout-info-general-impuesto-pdf de inmueble
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
	<caption>INFORMACION GENERAL DEL IMPUESTO</caption>

	<tr>
		<th class="label-contribuyente" colspan="1"><?=Html::encode('TIPO DE IMPUESTO'); ?></th>
		<th class="label-contribuyente" colspan="1"><?=Html::encode('ID OBJETO'); ?></th>
		<th class="label-contribuyente" colspan="7"><?=Html::encode('Nro. CATASTRO'); ?></th>
		<th class="label-contribuyente" colspan="1"><?=Html::encode('Nro. LIQUIDACION'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-contribuyente" colspan="1"><?=Html::encode($datosContribuyente['tipoImpuesto']); ?></td>
		<td class="info-contribuyente" colspan="1"><?=Html::encode($model['id_impuesto']); ?></td>
		<td class="info-contribuyente" colspan="7"><?=Html::encode($datosContribuyente['catastro']); ?></td>
		<td class="info-contribuyente" colspan="1"><?=Html::encode($datosContribuyente['liquidacion']); ?></td>
	</tr>

	<tr>
		<th class="label-ubicacion" colspan="10"><?=Html::encode('UBICACION'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-ubicacion" colspan="10"><?=Html::encode($datosContribuyente['domicilio']); ?></td>
	</tr>

</table>


<style type="text/css">

	.info-contribuyente {
		/*border-bottom: solid 2px #000;*/
	}




	.label-contribuyente,
	.label-ubicacion {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 90%;
	}

	.info-contribuyente {
		border-bottom: solid 2px #000;
	}


	.info-contribuyente,
	.info-ubicacion {
		text-align: center;
		font-size: 90%;
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
