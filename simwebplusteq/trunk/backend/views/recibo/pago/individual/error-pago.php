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
 *  @file error-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view error-pago.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use yii\widgets\ActiveForm;

 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-error-pago',
		'method' => 'post',
		//'action' => ['registrar-formas-pago'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

 <div class="row" style="width:100%;">
 	<div class="row" style="width:100%;">
		<div class="col-sm-5" style="width:100%;">
			<div class="well" style="color: red;">
	  			<?=Html::encode($errorMensaje); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2" style="width: 20%;margin-left: 10px;">
			<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Back'),
												  [
													'id' => 'btn-back',
													'class' => 'btn btn-danger',
													'value' => 9,
													'style' => 'width: 100%',
													'name' => 'btn-back',
												  ])
				?>
			</div>
		</div>

		<div class="col-sm-2" style="width: 20%;margin-left: 10px;">
			<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Quit'),
												  [
													'id' => 'btn-quit',
													'class' => 'btn btn-danger',
													'value' => 9,
													'style' => 'width: 100%',
													'name' => 'btn-quit',
												  ])
				?>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>