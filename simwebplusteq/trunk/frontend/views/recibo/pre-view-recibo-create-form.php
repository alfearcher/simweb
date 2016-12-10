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
 *  @file pre-view-recibo-create-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view pre-view-recibo-create-form
 *  @brief vista principal del formulario para la creacion de los recibos de pago.
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
	use yii\widgets\Pjax;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

    $totalSeleccionado = 0;
    $sumaSeleccion = 0;
?>

<div class="pre-view-recibo-pago-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-recibo-create-form',
 			'method' => 'post',
 			'action'=> Url::to(['index-create']),
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'nro_control')->hiddenInput(['value' => $model->nro_control])->label(false);?>
	<?=$form->field($model, 'fecha')->hiddenInput(['value' => $model->fecha])->label(false);?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => ''])->label(false);?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false);?>
	<?=$form->field($model, 'proceso')->hiddenInput(['value' => $model->proceso])->label(false);?>
	<?=$form->field($model, 'observacion')->hiddenInput(['value' => ''])->label(false);?>
	<?=$form->field($model, 'ultima_impresion')->hiddenInput(['value' => $model->ultima_impresion])->label(false);?>
	<?=$form->field($model, 'monto')->hiddenInput(['value' => $model->monto])->label(false);?>
	<?=$form->field($model, 'usuario_creador')->hiddenInput(['value' => $model->usuario_creador])->label(false);?>
	<?=$form->field($model, 'fecha_hora_creacion')->hiddenInput(['value' => $model->fecha_hora_creacion])->label(false);?>
	<?=$form->field($model, 'fecha_hora_proceso')->hiddenInput(['value' => $model->fecha_hora_creacion])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 95%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
 <!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

<!-- Aqui se muestra lo seleccionado por el contribuyente -->

					<div class="row" style="width: 70%;margin-top: 80px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode('Planilla(s) Seleccionadas')?></h4>
						</div>

						<div class="row" class="deuda-seleccionda-pre-view" style="padding-top: 10px;">
							<?= GridView::widget([
								'id' => 'grid-deuda-seleccionada-pre-view',
								'dataProvider' => $dataProvider,
								'summary' => '',
								'columns' => [
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkPlanillaSeleccionadas',
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
			                                  return [
			                                      'onClick' => 'javascript: return false;',
			                                      'checked' => true,
			                                  ];
				                        },
				                        'multiple' => false,
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'planilla'),
				                        'value' => function($data) {
				                                      return $data['planilla'];
				            			                 },
				                        //'visible' => ( $periodoMayorCero ) ? false : true,
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'monto'),
				                        'value' => function($data) {
				                                      return Yii::$app->formatter->asDecimal($data['tmonto'], 2);
				        					                 },
				                        'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'recargo'),
				                        'value' => function($data) {
				                                        return Yii::$app->formatter->asDecimal($data['trecargo'], 2);
				                                  },
				                        'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'interes'),
				                        'value' => function($data) {
				                                      return Yii::$app->formatter->asDecimal($data['tinteres'], 2);
				                                 },
				                       'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'descuento'),
				                        'value' => function($data) {
				                                        return Yii::$app->formatter->asDecimal($data['tdescuento'], 2);
				                                  },
				                        'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'recon./reten.'),
				                        'value' => function($data) {
				                                        return Yii::$app->formatter->asDecimal($data['tmonto_reconocimiento'], 2);
				                                   },
				                        'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'sub-total'),
				                        'value' => function($data) {
				                                        $st = ( $data['tmonto'] + $data['trecargo'] + $data['tinteres'] ) - ( $data['tdescuento'] + $data['tmonto_reconocimiento'] );
				                                        return Yii::$app->formatter->asDecimal($st, 2);
				                                  },
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'concepto'),
				                        'value' => function($data) {
				        					                   return $data['descripcion'];
				        					               },
				                        'visible' => true,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'impuesto'),
				                        'value' => function($data) {
				        					                   return $data['descripcion_impuesto'];
				        					               },
				                        'visible' => true,
				                    ],

					        	]
							]);?>
						</div>
					</div>
<!-- Fin de lo seleccionado -->

					<div class="row" style="padding-bottom: 10px;padding-top: 10px;background-color: #F1F1F1;width: 70%;">
						<div class="col-sm-3" style="width: 45%;text-align: left;">
							<h3><strong><p>Total Seleccionado:</p></strong></h3>
						</div>
						<div class="col-sm-3" style="width: 50%;text-align: right;background-color: #FFFFFF;">
							<h3><strong><p><?= MaskedInput::widget([
						                              'name' => 'total',
						                              'id' => 'id-total',
						                              'value' => $model->totalSeleccionado,
						                              'options' => [
						                                  'class' => 'form-control',
						                                  'style' => 'width:100%;text-align: right;font-size:90%;background-color:#FFFFFF;',
						                                  'readonly' => true,
						                                  'placeholder' => '0.00',

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

						                        ]);?>
						    </p></strong></h3>

							<div class="col-sm-3" style="width: 100%;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Confirmar Crear Recibo'),
																			  [
																				'id' => 'btn-confirm-create',
																				'class' => 'btn btn-success',
																				'value' => 5,
																				'style' => 'width: 100%',
																				'name' => 'btn-confirm-create',
																			  ])
									?>
								</div>
							</div>
						</div>

					</div>


					<div class="row" style="margin-top: 55px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Back'),
																		  [
																			'id' => 'btn-back',
																			'class' => 'btn btn-danger',
																			'value' => 9,
																			'style' => 'width: 80%',
																			'name' => 'btn-back',
																		  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
																		  [
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 80%',
																			'name' => 'btn-quit',
																		  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para salir de la actualizacion -->
					</div>

				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
