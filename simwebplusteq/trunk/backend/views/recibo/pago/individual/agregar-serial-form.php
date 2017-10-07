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
 *  @file agregar-serial-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view agregar-serial-form
 *  @brief vista principal
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
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>

<div class="agregar-serial-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-agregar-serial-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($modelSerial, 'recibo')->hiddenInput(['value' => $modelSerial->recibo])->label(false)?>
	<?=$form->field($modelSerial, 'usuario')->hiddenInput(['value' => $modelSerial->usuario])->label(false)?>

	<div class="col-sm-3" id="id-serial-referencia-form" style="width: 100%;padding:0px;margin:0px;">
		<div class="row" style="width:100%;padding:0px;margin:0px;">
			<div class="col-sm-2" style="width: 95%;border-bottom: 1px solid;padding:0px;padding-top: 0px;">
				<h4><strong><?=Html::encode(Yii::t('backend', 'Ingrese lo siguiente:'))?></strong></h4>
			</div>
		</div>

		<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 20px;">
			<div class="col-sm-2" style="width: 35%;padding:0px;margin:0px;margin-top: 5px;">
				<p><strong><?=Html::encode($modelSerial->getAttributeLabel('serial'))?></strong></p>
			</div>
			<div class="col-sm-4" style="width:50%;padding:0px;margin:0px;margin-left: 15px;">
				<?=$form->field($modelSerial, 'serial')->textInput([
															'id' => 'id-serial',
															'style' => 'width: 100%;
															 font-size:100;
															 font-weight:bold;',
														])->label(false)
				?>
			</div>
		</div>

		<div class="row" style="width:100%;padding:0px;margin:0px;">
			<div class="col-sm-2" style="width: 35%;padding:0px;margin:0px;margin-top: 5px;">
				<p><strong><?=Html::encode($modelSerial->getAttributeLabel('monto_edocueta'))?></strong></p>
			</div>
			<div class="col-sm-4" style="width:50%;padding:0px;margin:0px;margin-left: 15px;">
				<?= $form->field($modelSerial, 'monto_edocuenta')->widget(MaskedInput::className(), [
																//'mask' => '',
																'options' => [
																	'id' => 'id-monto-edocuenta',
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


		<div class="row" style="width:100%;padding:0px;margin:0px;">
			<div class="col-sm-2" style="width: 35%;padding:0px;margin:0px;margin-top: 5px;">
				<p><strong><?=Html::encode($modelSerial->getAttributeLabel('fecha_edocueta'))?></strong></p>
			</div>
			<div class="col-sm-4" style="width:32%;padding:0px;margin:0px;margin-left: 15px;">
				<?= $form->field($modelSerial, 'fecha_edocuenta')->widget(\yii\jui\DatePicker::classname(),[
														  'clientOptions' => [
																'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																'changeMonth' => true,
																'changeYear' => true,
															],
														  'language' => 'es-ES',
														  'dateFormat' => 'dd-MM-yyyy',
														  'options' => [
														  		'id' => 'id-fecha-edocuenta',
																'class' => 'form-control',
																'readonly' => true,
																'style' => 'background-color:white;
																		    width:100%;
																			font-size:100%;
											 								font-weight:bold;',
															]
															])->label(false) ?>
			</div>
		</div>
	</div>

	<div class="row" style="width:100%;">
		<div class="col-sm-2" style="width:30%;padding:0px;margin:0px;margin-left: 15px;">
			<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Guardar'),
												  [
													'id' => 'btn-add-serial',
													'class' => 'btn btn-primary',
													'value' => 5,
													'style' => 'width: 100%',
													'name' => 'btn-add-serial',
												  ])
				?>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>



