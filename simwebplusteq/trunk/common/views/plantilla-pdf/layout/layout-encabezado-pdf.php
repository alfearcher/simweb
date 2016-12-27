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
 *  @file anexo-ramo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view anexo-ramo-form.php
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
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

 ?>

<div class="row">
	<div class="col-sm-3" id="logo">
		<?=Html::img('@common/public/imagen/customize/logo.jpg');
		?>
	</div>
	<div class="col-sm-6">
		<div class="row" id="pais"><p><?=strtoupper(Html::encode(Yii::$app->ente->getPais()))?></div></p>
		<div class="row" id="estado"><p><?=strtoupper(Html::encode(Yii::$app->ente->getEstado()))?></div>
		<div class="row" id="alcaldia"><?=strtoupper(Html::encode(Yii::$app->ente->getAlcaldia()))?></div>
		<div class="row" id="renta-municipal"><?=strtoupper(Html::encode(Yii::$app->oficina->getNombre()))?></div>
		<div class="row" id="rif"><?=strtoupper(Html::encode(Yii::$app->ente->getRif()))?></div>
	</div>
	<div class="col-sm-3" id="codigo-barra">
		<div class="row">
			<!-- <?//=Html::tag('barcode','',[
						// 			'class' => ['barcode'],
						// 			'height' => '0.96',
						// 			'type' => 'C128A',
						// 			'code' => $barcode,
						// 			'size' => '0.8',
						// ])
			 ?> -->
		</div>
		<div class="row" id="code">
			<!-- <?//=Html::encode($barcode)?> -->
		</div>
		<!-- <barcode code=<?//=$barcode;?> type="C128A"  height="0.66" width="1.8" text="1" class="barcode" /> -->
	</div>
</div>

<div class="row">
	<div class="col-sm-3" id="titulo">
		<p><?=strtoupper(Html::encode($caption))?></div></p>
	</div>
</div>


<style type="text/css">

	#logo {
		padding-top: -10;
		padding-left: -30px;
		margin: 0;
		border: 0;
	}

	.barcode {
		padding-left: -90px;
		padding-top: 10px;
	}

	#code {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 60%;
		text-align: justify;
	}

	#pais, #estado, #alcaldia, #renta-municipal, #rif {
		text-align: center;
		padding: 0px;
		margin: 0px;
		padding-top: -100px;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 90%;
		font-weight: bold;
	}

	#estado {
		padding-top: -32px;
	}

	#alcaldia {
		padding-top: -18px;
	}

	#renta-municipal {
		padding-top: -2;
	}

	#rif {
		padding-top: -1;
	}

	#codigo-barra {
		position: absolute;
		top: -185px;
		width: 30%;
		height: 30%;
		padding-left: 520px;
		padding-top: -30px;
		margin: 0;

	}


	#titulo {
		position: absolute;
		text-align: center;
		padding: 0px;
		margin: 0px;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 120%;
		font-weight: bold;
		padding-top: -260px;
		/*width: 60%;*/
	}

</style>
