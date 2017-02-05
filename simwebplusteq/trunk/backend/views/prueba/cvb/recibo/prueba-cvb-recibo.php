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
 *  @file prueba-cvb-recibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-02-2017
 *
 *  @view prueba-cvb-recibo
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
	use yii\widgets\MaskedInput;
	use yii\jui\DatePicker;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);


 ?>


<div class="prueba-cvb-recibo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-prueba-cvb-recibo-form',
 			'method' => 'post',
 			//'action' => ['index'],
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => false,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode('PRUEBA DE CODIGO VALIDADOR BANCARIO DE 3 DIGITOS') ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="border-bottom: 1px solid #ccc;margin-top: 20px;;margin-bottom: 20px;">
							<h4><strong><?=Html::encode(Yii::t('backend', 'Ingrese los siguientes datos '))?></strong></h4>
						</div>

					<div class="row" style="width: 100%;padding: 0px;">
<!-- Numero de recibo -->
						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding: 0px;">
								<div class="recibo">
			                		<strong><h5><?=Html::encode('Numero de Recibo')?></h5></strong>
								</div>
							</div>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding:0px;">
								<div class="recibo">
									<?= $form->field($model, 'recibo')->textInput([
																		'id' => 'id-recibo',
																		'style' => 'width: 100%;',
																  ])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Numero de recibo -->

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding: 0px;">
								<div class="fecha">
		                			<strong><h5><?=Html::encode('Fecha Vcto')?></h5></strong>
								</div>
							</div>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding:0px;">
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
																					  		'id' => '-id-fecha',
																							'class' => 'form-control',
																							'readonly' => true,
																							'style' => 'background-color: white;width:55%;',

																						]
																						])->label(false) ?>
								</div>
							</div>
						</div>


						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding: 0px;">
								<div class="monto">
			                		<strong><h5><?=Html::encode('Monto a Pagar del Recibo')?></h5></strong>
								</div>
							</div>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:20%;padding:0px;">
								<div class="monto">
									<?= $form->field($model, 'monto')->widget(MaskedInput::className(), [
																					'options' => [
																						'class' => 'form-control',
																						'style' => 'width: 100%;',
																						'placeholder' => '0.00',
																						'id' => 'id-monto',

																					],
																					'clientOptions' => [
																						'alias' =>  'decimal',
																						'digits' => 2,
																						'digitsOptional' => false,
																						'groupSeparator' => ',',
																						'removeMaskOnSubmit' => true,
																						// 'allowMinus'=>false,
																						//'groupSize' => 3,
																						'radixPoint'=> ".",
																						'autoGroup' => true,
																						//'decimalSeparator' => ',',
																					],
																  				])->label(false);
									?>
								</div>
							</div>
						</div>

					</div>



					<div class="row">
						<?=Html::encode('CODIGO:') ?>
						<?=Html::tag('h3', $cvb);?>
					</div>



					<div class="row" style="border-bottom: 1px solid #ccc;margin-top: 20px;;margin-bottom: 20px;">
					</div>


					<div class="row" style="width: 100%;">
						<div class="col-sm-3" style="width: 30%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Determinar CVB'),
																		  [
																			'id' => 'btn-cvb',
																			'class' => 'btn btn-primary',
																			'value' => 1,
																			'name' => 'btn-cvb',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>


						<div class="col-sm-3" style="width: 30%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Salir'),
																		  [
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'name' => 'btn-quit',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
