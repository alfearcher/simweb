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
 *  @file resumen-declaracion-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view resumen-declaracion-pago
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
	use backend\models\utilidad\exigibilidad\ExigibilidadSearch;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="view-resumen-declaracion-pago">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-resumen-declaracion-pago-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=Html::hiddenInput('id_contribuyente', $dataDeclaracion[0]['id_contribuyente'])?>
	<?=$form->field($model, 'id_pago')->hiddenInput(['value' => $model->id_pago])->label(false);?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false);?>
	<?=$form->field($model, 'impuesto')->hiddenInput(['value' => $model->impuesto])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false);?>
	<?=$form->field($model, 'trimestre')->hiddenInput(['value' => $model->trimestre])->label(false);?>
	<?=$form->field($model, 'monto')->hiddenInput(['value' => $model->monto])->label(false);?>
	<?=$form->field($model, 'recargo')->hiddenInput(['value' => $model->recargo])->label(false);?>
	<?=$form->field($model, 'interes')->hiddenInput(['value' => $model->interes])->label(false);?>
	<?=$form->field($model, 'descuento')->hiddenInput(['value' => $model->descuento])->label(false);?>
	<?=$form->field($model, 'fecha_emision')->hiddenInput(['value' => $model->fecha_emision])->label(false);?>
	<?=$form->field($model, 'fecha_vcto')->hiddenInput(['value' => $model->fecha_vcto])->label(false);?>
	<?=$form->field($model, 'pago')->hiddenInput(['value' => $model->pago])->label(false);?>
	<?=$form->field($model, 'fecha_pago')->hiddenInput(['value' => $model->fecha_pago])->label(false);?>
	<?=$form->field($model, 'referencia')->hiddenInput(['value' => $model->referencia])->label(false);?>
	<?=$form->field($model, 'descripcion')->hiddenInput(['value' => $model->descripcion])->label(false);?>
	<?=$form->field($model, 'monto_reconocimiento')->hiddenInput(['value' => $model->monto_reconocimiento])->label(false);?>
	<?=$form->field($model, 'exigibilidad_pago')->hiddenInput(['value' => $model->exigibilidad_pago])->label(false);?>
	<?=$form->field($model, 'fecha_desde')->hiddenInput(['value' => $model->fecha_desde])->label(false);?>
	<?=$form->field($model, 'fecha_hasta')->hiddenInput(['value' => $model->fecha_hasta])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-top: 0px;width:100%;">
							<div class="col-sm-3" style="width: 100%;">
								<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
							</div>
						</div>

						<div class="row" id="id-detalle-resumen-declaracion-pago" style="width: 100%;padding-top: 10px;">
							<?= GridView::widget([
									'id' => 'grid-declaracion',
									'dataProvider' => $dataProvider,
									'headerRowOptions' => ['class' => 'success'],
									'summary' => '',
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Año'),
						                     'contentOptions' => [
						                    	//'style' => 'text-align: center',
						                    ],
						                    'value' => function($data) {
	                										return $data['ano_impositivo'];
	        											},
						                ],
										[
						                    'label' => Yii::t('frontend', 'Rubro'),
						                    'value' => function($data) {
	                										return $data['rubro'];
	        											},
						                ],
						            	[
						                    'label' => Yii::t('frontend', 'Descripcion'),
						                    'value' => function($data) {
	                										return $data['descripcion'];
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Declaracion'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['declaracion'], 2);
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Minimo'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['minimo'], 2);
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Impuesto'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['impuesto'], 2);
	        											},
						                ],

						        	]
								]);?>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-3" style="width: 70%;text-align: right;">
								<h3><strong><?=Html::encode(Yii::t('frontend','Total por Impuesto:'))?></strong></h3>
							</div>
							<div class="col-sm-3" style="width: 28%;padding:0px;padding-top: 12px;padding-right: 15px;">
								<?=Html::textInput('total-impuesto', Yii::$app->formatter->asDecimal($sumaImpuesto, 2),[
																		'class' => 'form-control',
																		'style' => 'width: 100%;
																					background-color: white;
																					text-align:right;
																					font-size:large;font-weight: bold;',
																		'readOnly' => true,
								])?>
							</div>
						</div>


						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-top: 0px;width:100%;">
							<div class="col-sm-3" style="width: 100%;">
								<h4><?=Html::encode(Yii::t('frontend', 'Resumen de pagos'))?></h4>
							</div>
						</div>

						<div class="row" id="id-detalle-resumen-pago" style="width: 100%;padding-top: 10px;">
							<?= GridView::widget([
									'id' => 'grid-pago',
									'dataProvider' => $dataProviderPago,
									'headerRowOptions' => ['class' => 'success'],
									'summary' => '',
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Concepto'),
						                     'contentOptions' => [
						                    	//'style' => 'text-align: center',
						                    ],
						                    'value' => function($data) {
	                										return $data['concepto'];
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Monto'),
						                     'contentOptions' => [
						                    		'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['monto'], 2);
	        											},
						                ],
						        	]
								]);?>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-3" style="width: 70%;text-align: right;">
								<h3><strong><?=Html::encode(Yii::t('frontend','Total Pagos:'))?></strong></h3>
							</div>
							<div class="col-sm-3" style="width: 28%;padding:0px;padding-top: 12px;padding-right: 15px;">
								<?=Html::textInput('total-pago', Yii::$app->formatter->asDecimal($sumaPago, 2),[
																		'class' => 'form-control',
																		'style' => 'width: 100%;
																					background-color: white;
																					text-align:right;
																					font-size:large;font-weight: bold;',
																		'readOnly' => true,
								])?>
							</div>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-3" style="width: 70%;text-align: right;">
								<h3><strong><?=Html::encode(Yii::t('frontend','Total Impuesto - Pagos:'))?></strong></h3>
							</div>
							<div class="col-sm-3" style="width: 28%;padding:0px;padding-top: 12px;padding-right: 15px;">
								<?=Html::textInput('total-diferencia', Yii::$app->formatter->asDecimal($sumaImpuesto - $sumaPago, 2),[
																											'id' => 'id-total-diferencia',
																											'class' => 'form-control',
																											'style' => 'width: 100%;
																														background-color: white;
																														text-align:right;
																														font-size:large;font-weight: bold;',
																											'readOnly' => true,
								])?>
							</div>
						</div>

					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>


					<div class="row">
						<div class="form-group">
							<div class="col-sm-3" style="width: 20%;margin-left:100px;">
								<?= Html::submitButton(Yii::t('frontend', 'Guardar'),
																		  [
																			'id' => 'btn-create',
																			'class' => 'btn btn-success',
																			'value' => 3,
																			'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																			'name' => 'btn-create',
																			//'disabled' => true,
																		  ]);
								?>
							</div>

							<div class="col-sm-3" style="width: 20%;margin-left:100px;">
								<?= Html::submitButton(Yii::t('frontend', 'Back'),
																		  [
																			'id' => 'btn-back-form',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																			'name' => 'btn-back-form',

																		  ]);
								?>
							</div>

							<div class="col-sm-3" style="width: 20%;margin-left:100px;">
								<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																					  [
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'value' => 1,
																						'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																						'name' => 'btn-quit',

																					  ])
								?>
							</div>
						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
