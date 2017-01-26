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
 *  @file liquidar-tasa-intro-parametro.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-01-2017
 *
 *  @view liquidar-tasa-intro-parametro.php
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
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\MaskedInput;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="liquidar-tasa-intro-parametro">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-liquidar-tasa-intro-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false);?>
	<?=$form->field($model, 'impuesto')->hiddenInput(['value' => $model->impuesto])->label(false);?>
	<?=$form->field($model, 'id_codigo')->hiddenInput(['value' => $model->id_codigo])->label(false);?>
	<?=$form->field($model, 'grupo_subnivel')->hiddenInput(['value' => $model->grupo_subnivel])->label(false);?>
	<?=$form->field($model, 'codigo')->hiddenInput(['value' => $model->codigo])->label(false);?>
	<?=$form->field($model, 'descripcion')->hiddenInput(['value' => $model->descripcion])->label(false);?>
	<?=$form->field($model, 'id_pago')->hiddenInput(['id' => 'id_pago'])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode('Liquidar') ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="col-sm-2" style="width: 10%;padding:0px;">
							<h4><strong>Impuesto</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
							<?= Html::textInput('impuesto-descripcion', $tasa['impuestos']['descripcion'],
															[
																'id' => 'impuestos',
																'class' => 'form-control',
																'style' => 'width:340px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>


					<div class="row" id="lista-ano-impositivo">
						<div class="col-sm-2" style="width: 10%;padding:0px;">
							<h4><strong>Año</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
							<?= Html::textInput('ano_impositivo-descripcion', $tasa['ano_impositivo'],
															[
																'id' => 'id-ano_impositivo-descripcion',
																'class' => 'form-control',
																'style' => 'width:120px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>



					<div class="row" id="codigo-presupuestario">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Codigo Preupuestario</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= Html::textInput('codigo-presupuesto-descripcion', $tasa['codigoContable']['codigo'] . ' - ' . $tasa['codigoContable']['descripcion'],
															[
																'id' => 'id-codigo-presupuesto-descripcion',
																'class' => 'form-control',
																'style' => 'width:600px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>



					<div class="row" id="grupo-sub-nivel">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Grupo SubNivel</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= Html::textInput('grupo-subnivel-descripcion', $tasa['grupo_subnivel'] . ' - ' . $tasa['grupoSubNivel']['descripcion'],
															[
																'id' => 'id-grupo-subnivel-descripcion',
																'class' => 'form-control',
																'style' => 'width:600px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>




					<div class="row" id="codigo-sub-nivel">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Codigo SubNivel</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= Html::textInput('codigo-subnivel-descripcion', $tasa['codigo'],
															[
																'id' => 'id-codigo-subnivel-descripcion',
																'class' => 'form-control',
																'style' => 'width:140px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>


					<div class="row" id="codigo-descripcion">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Descripcion</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							<?= Html::textArea('codigo-descripcion', $tasa['codigo'] . ' - ' . $tasa['descripcion'],
															[
																'id' => 'id-codigo-descripcion',
																'class' => 'form-control',
																'rows' => 6,
																'style' => 'width:600px;
																		   background-color:white;',
																'readOnly' => true
															]);
							?>
						</div>
					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;padding-top:20px;width: 103%;margin-left: -30px;">
					</div>


					<div class="row" style="padding:0px; padding-top: 20px;margin-left:10px;">

						<div class="row" id="monto">
							<div class="col-sm-2" style="width: 18%;padding:0px;">
								<h4><strong>Monto</strong></h4>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
								 <?= Html::textInput('monto', Yii::$app->formatter->asDecimal($tasa['monto'], 4),
																[
																	'id' => 'id-monto',
																	'class' => 'form-control',
																	'style' => 'width:140px;
																			   background-color:white;',
																	'readOnly' => true
																]);
								?>
							</div>
						</div>


						<div class="row" id="tipo-monto">
							<div class="col-sm-2" style="width: 18%;padding:0px;">
								<h4><strong>Tipo Monto</strong></h4>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
								 <?= Html::textInput('tipo-monto', $tasa['tipoRango']['descripcion'],
																[
																	'id' => 'id-tipo-monto',
																	'class' => 'form-control',
																	'style' => 'width:200px;
																			   background-color:white;',
																	'readOnly' => true
																]);
								?>
							</div>
						</div>


						<div class="row" id="ut-del-ano-impositivo">
							<div class="col-sm-2" style="width: 18%;padding:0px;">
								<h4><strong>UT del Año <?= $tasa['ano_impositivo']?></strong></h4>
							</div>
							<div class="col-sm-4" style="width:20%;padding:0px;margin-left:16px;">
								 <?= Html::textInput('ut-del-ano-impositivo', Yii::$app->formatter->asDecimal($utDelAño, 2),
																[
																	'id' => 'id-ut-del-ano-impositivo',
																	'class' => 'form-control',
																	'style' => 'width:200px;
																			   background-color:white;',
																	'readOnly' => true
																]);
								?>
							</div>

							<div class="col-sm-2" style="width: 10%;padding:0px;">
								<h4><strong>En Bs.</strong></h4>
							</div>
							<div class="col-sm-4" style="width:20%;padding:0px;">
								 <?= Html::textInput('monto-en-moneda', ($tasa['tipoRango']['descripcion'] == "BOLIVARES") ? round($tasa['monto'],4) : $utDelAño * $tasa['monto'],
																[
																	'id' => 'id-monto-en-moneda',
																	'class' => 'form-control',
																	'style' => 'width:200px;
																			   background-color:white;',
																	'readOnly' => true
																]);
								?>
							</div>
						</div>

					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;padding-top:20px;width: 103%;margin-left: -30px;">
					</div>


					<div class="row" style="width: 100%;padding: 0px;padding-top: 20px;">
						<div class="col-sm-2" style="width: 20%;">
							<h4><strong>Multiplicar por:</strong></h4>
						</div>
						<div class="col-sm-4" style="width:20%;padding:0px;margin-left:16px;">
							<?= $form->field($model, 'multiplicar_por')->widget(MaskedInput::className(), [
																							'id' => 'id-multiplicar-por',
																							//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																							'options' => [
																								'class' => 'form-control',
																								'style' => 'width: 100%;
																								            font-size: 160%;
																								            font-weight:bold;',
																								'placeholder' => '0.00',
																							],
																							'clientOptions' => [
																								'alias' =>  'decimal',
																								'digits' => 2,
																								'digitsOptional' => false,
																								//'groupSeparator' => '.',
																								'removeMaskOnSubmit' => true,
																								// 'allowMinus'=>false,
																								//'groupSize' => 3,
																								//'radixPoint'=> ".",
																								'autoGroup' => true,
																								'decimalSeparator' => ',',
																							],

																		  				  ])->label(false) ?>
						</div>

						<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
							<div class="form-group">
								<?= Html::button(Yii::t('frontend', 'Calcular'),
																		  [
																			'id' => 'btn-calcular',
																			'class' => 'btn btn-primary',
																			'value' => 1,
																			'style' => 'width: 100%;',
																			'name' => 'btn-calcular',
																			'onClick' => '
																						  	var enMoneda = $( "#id-monto-en-moneda" ).val();
																						  	var multiplicarPor = $( "#liquidartasaform-multiplicar_por" ).val();
																						  	var total = parseFloat(enMoneda) * parseFloat(multiplicarPor);
																						  	$( "#liquidartasaform-resultado" ).val(total);

																						  ',
																		  ])
								?>
							</div>
						</div>
					</div>


					<div class="row" style="width: 100%;padding: 0px;">
						<div class="col-sm-2" style="width: 20%;">
							<h4><strong>Resultado:</strong></h4>
						</div>
						<div class="col-sm-4" style="width:20%;padding:0px;margin-left:16px;">
							<?= $form->field($model, 'resultado')->widget(MaskedInput::className(), [
																					'id' => 'resultado',
																					//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																					'options' => [
																						'class' => 'form-control',
																						'style' => 'width: 100%;
																								    font-size: 160%;
																								    font-weight:bold;',
																						'placeholder' => '0.00',
																						'readOnly' => true,
																					],
																					'clientOptions' => [
																						'alias' =>  'decimal',
																						'digits' => 2,
																						'digitsOptional' => false,
																						'groupSeparator' => '.',
																						'removeMaskOnSubmit' => true,
																						// 'allowMinus'=>false,
																						//'groupSize' => 3,
																						//'radixPoint'=> ",",
																						'autoGroup' => true,
																						'decimalSeparator' => ',',
																					],

																  				  ])->label(false) ?>
						</div>
					</div>


					<div class="row" id="observacion-planilla" style="width: 100%;padding:0px;padding-left: 16px;">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Observacion (Opcional):</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:25px;">
							<?=$form->field($model, 'observacion')->textArea([
																'id' => 'id-observacion',
																'class' => 'form-control',
																'rows' => 4,
																'style' => 'width:600px;
																		   background-color:white;',

															]);
							?>
						</div>
					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;padding-top:20px;width: 103%;margin-left: -30px;">
					</div>


					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Liquidar'),
																			  [
																				'id' => 'btn-liquidar',
																				'class' => 'btn btn-success',
																				'value' => 5,
																				'style' => 'width: 100%;',
																				'name' => 'btn-liquidar',
																			  ])
									?>
								</div>
							</div>

							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Back'),
																		  [
																			'id' => 'btn-back',
																			'class' => 'btn btn-danger',
																			'value' => 3,
																			'style' => 'width: 100%;',
																			'name' => 'btn-back',

																		  ])
									?>
								</div>
							</div>

							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Quit'),
																			  [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%;',
																				'name' => 'btn-quit',

																			  ])
									?>
								</div>
							</div>


							<!-- <div class="col-sm-2" style="margin-left: 50px;">
								<div class="form-group">-->
								<!-- '../../common/docs/user/ayuda.pdf'  funciona -->
									<!-- <?//= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
														// 	'id' => 'btn-help',
														// 	'class' => 'btn btn-default',
														// 	'name' => 'btn-help',
														// 	'target' => '_blank',
														// 	'value' => 1,
														// 	'style' => 'width: 100%;'
														// ])?> -->
								<!-- </div>
							</div>  -->

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


