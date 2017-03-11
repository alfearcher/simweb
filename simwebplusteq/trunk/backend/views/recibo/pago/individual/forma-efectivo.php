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
 *  @file forma-efectivo.php.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view forma-efectivo.php.php
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
		'id' => 'id-forma-efectivo-form',
		'method' => 'post',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false)?>
	<?=$form->field($model, 'id_forma')->hiddenInput(['value' => $model->id_forma])->label(false)?>
	<?=$form->field($model, 'deposito')->hiddenInput(['value' => $model->deposito])->label(false)?>
	<?=$form->field($model, 'cheque')->hiddenInput(['value' => $model->cheque])->label(false)?>
	<?=$form->field($model, 'cuenta')->hiddenInput(['value' => $model->cuenta])->label(false)?>
	<?=$form->field($model, 'conciliado')->hiddenInput(['value' => $model->conciliado])->label(false)?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false)?>
	<?=$form->field($model, 'codigo_banco')->hiddenInput(['value' => $model->codigo_banco])->label(false)?>
	<?=$form->field($model, 'cuenta_deposito')->hiddenInput(['value' => $model->cuenta_deposito])->label(false)?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false)?>


<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
	<h4><strong><?=Yii::t('backend', 'Datos del ' . $caption)?></strong></h4>
</div>

<!-- FECHA DEL MONTO -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Fecha'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:15%; padding: 0px;margin:0px;">
		<div class="fecha">
			<?=$form->field($model, 'fecha')->textInput([
												'id' => 'id-fecha',
												'style' => 'width: 100%;
												 font-size:120;
												 font-weight:bold;',
												'readOnly' => true,
												'value' => date('d-m-Y'),
											])->label(false)
			?>
		</div>
	</div>
</div>
<!-- FIN DE FECHA DE MONTO -->


<!-- MONTO  -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Monto'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:35%; padding: 0px;margin:0px;">
		<div class="monto">
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
</div>
<!-- FIN DE MONTO -->


<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
</div>

<div class="row" style="width:100%;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Registrar'),
											  [
												'id' => 'btn-add-forma',
												'class' => 'btn btn-primary',
												'value' => $model->id_forma,
												'style' => 'width: 100%',
												'name' => 'btn-add-forma',
											  ])
			?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>