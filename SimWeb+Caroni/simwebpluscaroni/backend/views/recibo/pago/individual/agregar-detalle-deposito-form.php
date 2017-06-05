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
 *  @file agregar-detalle-deposito-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view agregar-detalle-deposito-form.php
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
		'id' => 'id-agregar-detalle-deposito-form',
		'method' => 'post',
		'action' => $url,
		'enableClientValidation' => true,
		'enableAjaxValidation' => true,
		'enableClientScript' => true,
	]);
 ?>

	<?=$form->field($modelVauche, 'linea')->hiddenInput(['value' => $modelVauche->linea])->label(false)?>
	<?=$form->field($modelVauche, 'deposito')->hiddenInput(['value' => $modelVauche->deposito])->label(false)?>
	<?=$form->field($modelVauche, 'recibo')->hiddenInput(['value' => $modelVauche->recibo])->label(false)?>
	<?=$form->field($modelVauche, 'usuario')->hiddenInput(['value' => $modelVauche->usuario])->label(false)?>
	<?=$form->field($modelVauche, 'estatus')->hiddenInput(['value' => 0])->label(false)?>
<!--
	<?//=$form->field($model, 'id_forma')->hiddenInput(['value' => $model->id_forma])->label(false)?>
	<?//=$form->field($model, 'cuenta')->hiddenInput(['value' => $model->cuenta])->label(false)?>
	<?//=$form->field($model, 'cheque')->hiddenInput(['value' => $model->cheque])->label(false)?>
	<?//=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false)?>
 -->
<!-- FORMA PARA REGISTRAR LOS DETALLES DEL DEPOSITO -->

<div class="row" style="width:100%;border-bottom: 0.5px solid #ccc;padding:0px;padding-left:5px;margin-bottom: 15px;">
	<h4><strong><?=Yii::t('backend', 'Ingrese el(los) detalle(s) del deposito')?></strong></h4>
</div>



<!-- LISTA DE TIPO DE DEPOSITO -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'tipo'))?></strong></p>
	</div>

	<div class="col-sm-2" style="width:25%;padding: 0px;">
		<div class="banco">
    		<?= $form->field($modelVauche, 'tipo')->dropDownList($listaTipoDeposito,[
												 			'id' => 'id-tipo-deposito',
												 			'style' => 'width: 100%;',
                                 				 			'prompt' => Yii::t('backend', 'Select..'),
                                        			])->label(false)
			?>
		</div>
	</div>
</div>
<!-- FIN DE LISTA TIPO DEPOSITO -->

<!-- NUMERO DE CUENTA DEL CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Cuenta'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:10%; padding: 0px;margin:0px;padding-right: 5px;">
		<div class="codido-cuenta">
			<?=$form->field($modelVauche, 'codigo_cuenta')->textInput([
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
			<?=$form->field($modelVauche, 'cuenta')->textInput([
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
			<?=$form->field($modelVauche, 'cheque')->textInput([
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

<!-- MONTO DEL CHEQUE -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Monto'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:35%; padding: 0px;margin:0px;">
		<div class="monto">
			<?= $form->field($modelVauche, 'monto')->widget(MaskedInput::className(), [
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


<div class="row" style="width:100%;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Guardar'),
											  [
												'id' => 'btn-add-item-deposito',
												'class' => 'btn btn-primary',
												'value' => 9,
												'style' => 'width: 120%',
												'name' => 'btn-add-item-deposito',
											  ])
			?>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>


<script type="text/javascript" charset="utf-8" async defer>
	$('#id-tipo-deposito').change(function() {
		//alert("hola" + $('#id-tipo-deposito').val());
		$('#id-codigo-cuenta').attr('disabled', 'disabled');
		$('#id-cuenta').attr('disabled', 'disabled');
		$('#id-cheque').attr('disabled', 'disabled');
		$('#id-monto').attr('disabled', 'disabled');

		if ( $('#id-tipo-deposito').val() == 1 ) {
			$('#id-monto').removeAttr('disabled');
		} else if ( $('#id-tipo-deposito').val() == 2 ) {
			$('#id-codigo-cuenta').removeAttr('disabled');
			$('#id-cuenta').removeAttr('disabled');
			$('#id-cheque').removeAttr('disabled');
			$('#id-monto').removeAttr('disabled');
		}
	});
</script>