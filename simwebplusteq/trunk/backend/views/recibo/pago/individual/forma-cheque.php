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

	<?=$form->field($model, 'linea')->hiddenInput(['value' => $model->linea])->label(false)?>
	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false)?>
	<?=$form->field($model, 'id_forma')->hiddenInput(['value' => $model->id_forma])->label(false)?>
	<?=$form->field($model, 'deposito')->hiddenInput(['value' => $model->deposito])->label(false)?>
	<?=$form->field($model, 'conciliado')->hiddenInput(['value' => $model->conciliado])->label(false)?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false)?>
	<?=$form->field($model, 'codigo_banco')->hiddenInput(['value' => $model->codigo_banco])->label(false)?>
	<?=$form->field($model, 'cuenta_deposito')->hiddenInput(['value' => $model->cuenta_deposito])->label(false)?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false)?>
	<?=$form->field($model, 'id_banco')->hiddenInput(['value' => $model->id_banco])->label(false)?>

<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
	<h4><strong><?=Yii::t('backend', 'Datos del ' . $caption)?></strong></h4>
</div>


<!-- NUMERO DE CUENTA DEL CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Cuenta'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:10%; padding: 0px;margin:0px;padding-right: 5px;">
		<div class="codido-cuenta">
			<?=$form->field($model, 'codigo_cuenta')->textInput([
														'id' => 'id-codigo-cuenta',
														'style' => 'width: 100%;
														 font-size:120;
														 font-weight:bold;',
														'maxLength' => 4,
													])->label(false)
			?>
		</div>
	</div>
	<div class="col-sm-3" style="width:25%; padding: 0px;">
		<div class="cuenta">
			<?=$form->field($model, 'cuenta')->textInput([
														'id' => 'id-cuenta',
														'style' => 'width: 100%;
														 font-size:120;
														 font-weight:bold;',
														'maxLength' => 21,
													])->label(false)
			?>
		</div>
	</div>
</div>
<!-- FIN DEL NUMERO DE CUENTA DEL CHEQUE -->


<!-- NUMERO DE CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Numero'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:35%; padding: 0px;margin:0px;">
		<div class="cheque">
			<?=$form->field($model, 'cheque')->textInput([
												'id' => 'id-cheque',
												'style' => 'width: 100%;
												 font-size:120;
												 font-weight:bold;',
												'maxLength' => 15,
											])->label(false)
			?>
		</div>
	</div>
</div>
<!-- FIN DE NUMERO DE CHEQUE -->

<!-- FECHA DEL CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Fecha'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:25%; padding: 0px;margin:0px;">
		<div class="fecha">
			<?= $form->field($model, 'fecha')->widget(\yii\jui\DatePicker::classname(),[
															  'clientOptions' => [
																	'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																	'changeMonth' => true,
																	'changeYear' => true,
																],
															  'language' => 'es-ES',
															  'dateFormat' => 'dd-MM-yyyy',
															  'options' => [
															  		'id' => 'id-fecha',
																	'class' => 'form-control',
																	'readonly' => true,
																	'style' => 'background-color:white;
																			    width:55%;
																				font-size:120;
												 								font-weight:bold;',
																]
																])->label(false) ?>
		</div>
	</div>
</div>
<!-- FIN DE FECHA DE CHEQUE -->


<!-- MONTO DEL CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Monto'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:35%; padding: 0px;margin:0px;">
		<div class="monto">
			<?= $form->field($model, 'monto')->widget(MaskedInput::className(), [
														//'mask' => '',
														'options' => [
															'id' => 'id-monto',
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
<!-- FIN DE MONTO DEL CHEQUE -->


<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
</div>

<div class="row" style="width:100%;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Guardar'),
											  [
												'id' => 'btn-add-forma',
												'class' => 'btn btn-primary',
												'value' => $model->id_forma,
												'style' => 'width: 120%',
												'name' => 'btn-add-forma',
											  ])
			?>
		</div>
	</div>

	<div class="col-sm-2">
		<?php if ( count($operacion) > 0 ) {
			foreach ( $operacion as $key => $value ) {
				echo Html::tag('li', $value, ['style' => 'color:red;']);
			}

		}?>
	</div>
</div>
<?php ActiveForm::end(); ?>