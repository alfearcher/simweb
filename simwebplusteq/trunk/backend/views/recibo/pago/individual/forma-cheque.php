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
 *  @file forma-cheque.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view forma-cheque.php
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
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-forma-cheque-form',
		'method' => 'post',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false)?>
	<?=$form->field($model, 'fecha')->hiddenInput(['value' => $model->fecha])->label(false)?>
	<?=$form->field($model, 'id_forma')->hiddenInput(['value' => $model->id_forma])->label(false)?>
	<?=$form->field($model, 'deposito')->hiddenInput(['value' => $model->deposito])->label(false)?>
	<?=$form->field($model, 'cheque')->hiddenInput(['value' => $model->cheque])->label(false)?>
	<?=$form->field($model, 'cuenta')->hiddenInput(['value' => $model->cuenta])->label(false)?>
	<?=$form->field($model, 'conciliado')->hiddenInput(['value' => $model->conciliado])->label(false)?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false)?>
	<?=$form->field($model, 'codigo_banco')->hiddenInput(['value' => $model->codigo_banco])->label(false)?>
	<?=$form->field($model, 'cuenta_deposito')->hiddenInput(['value' => $model->cuenta_deposito])->label(false)?>


<div class="row">
	<h4><strong><?=Html::encode(Yii::t('frontend', 'Ingrese Monto Efectivo:'))?></strong></h4>
</div>
<div class="row" style="width:30%;">
	<div class="monto" style="padding:0px;">
		<?= $form->field($model, 'monto')->widget(MaskedInput::className(), [
														'id' => 'id-monto',
														//'mask' => '',
														'options' => [
															'class' => 'form-control',
															'style' => 'width: 100%;
															 font-weight:bold;
															 font-size:120;',
															'placeholder' => '0.00',
														],
														'clientOptions' => [
															'alias' =>  'decimal',
															'digits' => 2,
															'digitsOptional' => false,
															'groupSeparator' => '.',
															'removeMaskOnSubmit' => true,
															// 'allowMinus'=>false,
															//'groupSize' => 3,
															'radixPoint'=> ",",
															'autoGroup' => true,
															'decimalSeparator' => ',',
														],
									  				  ])->label(false);
		?>
	</div>
</div>
<div class="row" style="width:100%;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Registrar'),
											  [
												'id' => 'btn-add-forma',
												'class' => 'btn btn-primary',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-add-forma',
											  ])
			?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>