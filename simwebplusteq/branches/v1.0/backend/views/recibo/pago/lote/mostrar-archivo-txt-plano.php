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
 *  @file mostrar-archivo-txt-plano.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-08-2017
 *
 *  @view mostrar-archivo-txt-plano.php
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

 	use yii\web\Response;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;


 ?>

<?php

	$form = ActiveForm::begin([
		'id' => 'id-mostrar-archivo-plano',
		'method' => 'post',
		//'action' => ['mostrar-archivo-txt'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

<div class="row" style="width: 100%;padding:0px;padding-left: 25px;padding-top: 15px;">
	<?=$htmlIdentidadBanco;?>
</div>

<div class="row" style="width: 100%;padding-top:20px;">
	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Back'),
												  [
													'id' => 'btn-back',
													'class' => 'btn btn-danger',
													'value' => 1,
													'style' => 'width: 100%',
													'name' => 'btn-back',
												  ])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Quit'),
												  [
													'id' => 'btn-quit',
													'class' => 'btn btn-danger',
													'value' => 1,
													'style' => 'width: 100%',
													'name' => 'btn-quit',
												  ])
			?>
		</div>
	</div>
</div>

<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-top: 0px;padding-left: 25px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Archivo: ') . $archivo)?></strong></h4>
</div>
<div class="row" style="width:105%;padding-left: 20px;">
	<?=$contenidoPlano; ?>
</div>
<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-top: 0px;padding-left: 25px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Archivo: ') . $archivo . ' ------ ' . Yii::t('backend', 'final'))?></strong></h4>
</div>

<div class="row" style="width: 100%;padding-top: 20px;">
	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Back'),
													  [
														'id' => 'btn-back',
														'class' => 'btn btn-danger',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-back',
													  ])
				?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Quit'),
												  [
													'id' => 'btn-quit',
													'class' => 'btn btn-danger',
													'value' => 1,
													'style' => 'width: 100%',
													'name' => 'btn-quit',
												  ])
			?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>