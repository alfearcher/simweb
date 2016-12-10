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
 *  @file layout-certificado-declaracion-definitiva-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-08-2016
 *
 *  @view layout-certificado-declaracion-definitiva-pdf.php
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
	$fechaEmision = date('Y-m-d');
?>


<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<tr>
		<td colspan="" rowspan="" headers=""><br></td>
	</tr>
	<tr>
		<td colspan="" rowspan="" headers=""><br></td>
	</tr>
	<tr>
		<td class="texto" style="text-align: justify;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 100%;">
		<p>
			Quien suscribe: <strong><?=Html::encode(Yii::$app->oficina->getDirector())?></strong>, Director(a) de Dirección de Hacienda Municipal de la
			<strong><?=Html::encode(Yii::$app->ente->getAlcaldia())?></strong>, del Municipio <strong><?=Html::encode(Yii::$app->ente->getMunicipio())?></strong> del <strong><?=Html::encode(Yii::$app->ente->getEstado())?></strong>,
			de conformidad con lo dispuesto en el Artículo 138 del Código Orgánico Tributario, certifica
			la recepción de la <strong>Declaración Definitiva de Ingresos Brutos del Impuesto Sobre Actividades
			Económicas de Industria, Comercio, Servicios y Conexos</strong>, ejecicio fiscal <strong><?=Html::encode($periodoFiscal)?></strong>,
			procesada por usted a través del portal web: <strong><?=Html::encode(Yii::$app->ente->getPortalWeb())?></strong>,
			Formulario Electrónico N° <strong><?=Html::encode($historico['serial_control'])?></strong>, en fecha: <strong><?=Html::encode(date('d-m-Y', strtotime($historico['fecha_hora'])))?></strong>
		</p>

		</td>
	</tr>


</table>


<style type="text/css">

	.info-contribuyente {
		border-bottom: solid 2px #000;
	}

	.label-contribuyente,
	.label-representante {
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 90%;

	}

	.info-contribuyente,
	.info-representante {
		text-align: center;
		font-size: 90%;
	}

</style>
