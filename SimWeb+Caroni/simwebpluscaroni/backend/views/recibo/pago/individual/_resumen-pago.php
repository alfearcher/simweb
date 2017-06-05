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
 *  @file _resumen-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @view _resumen-pago.php
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
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;


 ?>

<?php

	$form = ActiveForm::begin([
		'id' => 'id-resumen-pago',
		'method' => 'post',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
?>

 <div class="row" style="width:100%;">
	<div class="resumen-pago" style="width:100%;">
		<?= $this->render('/recibo/pago/individual/resumen-pago-form', [
												'caption' => $caption,
												'htmlRecibo' => $htmlRecibo,
												'htmlFormaPago' => $htmlFormaPago,
												'htmlCuentaRecaudadora' => $htmlCuentaRecaudadora,

    					]);
    	?>
	</div>
</div>
<div class="row" style="margin-top: 10px;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'GUARDAR PAGO'),
											  [
												'id' => 'btn-guardar-pago',
												'class' => 'btn btn-success',
												'value' => 9,
												'style' => 'width: 100%',
												'name' => 'btn-guardar-pago',
											  ])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 10px;">
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

	<div class="col-sm-2" style="margin-left: 10px;">
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