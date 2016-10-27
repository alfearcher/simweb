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

<?php
	$form = ActiveForm::begin([
		'id' => 'id-layout-encabezado-pdf',
		'method' => 'post',
		//'action' => $url,
		'enableClientValidation' => false,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
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
		<div class="row" id="renta-municipal"><?=strtoupper(Html::encode('Direccion de Renta'))?></div>
		<div class="row" id="rif"><?=strtoupper(Html::encode(Yii::$app->ente->getRif()))?></div>
	</div>
	<div class="col-sm-4" id="codigo-barra">
		<?=Html::img('@common/public/imagen/customize/codigo.png');
		?>
	</div>
</div>

<div class="row">
	<div class="col-sm-3" id="titulo">
		<p><?=strtoupper(Html::encode('BOLETIN DE NOTIFICACION'))?></div></p>
	</div>
</div>


<style type="text/css">

	#logo {
		padding-top: -10;
		padding-left: -30px;
		margin: 0;
		border: 0;
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
		font-size: 100%;
		font-weight: bold;
		padding-top: -250px;
		/*width: 60%;*/
	}

</style>









<?php ActiveForm::end(); ?>