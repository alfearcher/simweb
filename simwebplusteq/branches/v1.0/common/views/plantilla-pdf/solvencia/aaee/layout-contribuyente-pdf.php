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
 *  @file layout-contribuyente-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-10-2016
 *
 *  @view layout-contribuyente-pdf.php
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
	<caption>INFORMACION GENERAL DEL CONTRIBUYENTE</caption>

	<tr>
		<th class="label-contribuyente" colspan="1"><?=Html::encode('CI o RIF'); ?></th>
		<th class="label-contribuyente" colspan="7"><?=Html::encode('CONTRIBUYENTE'); ?></th>
		<th class="label-contribuyente" colspan="2"><?=Html::encode('ID CONTRIBUYENTE'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-contribuyente" colspan="1"><?=Html::encode($datosContribuyente['rif']); ?></td>
		<td class="info-contribuyente" colspan="7"><?=Html::encode($datosContribuyente['descripcion']); ?></td>
		<td class="info-contribuyente" colspan="2"><?=Html::encode($datosContribuyente['id_contribuyente']); ?></td>
	</tr>

<?php if( $showDireccion ) {?>
	<tr>
		<th class="label-direccion" colspan="10"><?=Html::encode('DIRECCION FISCAL'); ?></td>
	</tr>
	<tr class="cuerpo">
		<td class="info-direccion" colspan="10"><?=Html::encode($datosContribuyente['domicilio']); ?></td>
	</tr>
<?php } ?>

<?php if ( $showRepresentante ) {?>
	<tr>
		<th class="label-representante" colspan="2"><?=Html::encode('NRO. CATASTRO'); ?></th>
		<th class="label-representante" colspan="6"><?=Html::encode('REPRESENTANTE LEGAL'); ?></th>
		<th class="label-representante" colspan="2"><?=Html::encode('CI REP. LEGAL'); ?></th>
	</tr>
	<tr class="cuerpo">
		<td class="info-representante" colspan="2"><?=Html::encode($datosContribuyente['catastro']); ?></td>
		<td class="info-representante" colspan="6"><?=Html::encode($datosContribuyente['representante']); ?></td>
		<td class="info-representante" colspan="2"><?=Html::encode($datosContribuyente['cedulaRep']); ?></td>
	</tr>

<?php } ?>
</table>


<style type="text/css">

	.info-contribuyente {
		/*border-bottom: solid 2px #000;*/
	}

	.label-direccion,
	.label-representante {
		border-top: solid 2px #000;
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 90%;
	}

	.label-contribuyente,
	.label-representante {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 90%;

	}

	.info-contribuyente,
	.info-representante,
	.info-direccion {
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
