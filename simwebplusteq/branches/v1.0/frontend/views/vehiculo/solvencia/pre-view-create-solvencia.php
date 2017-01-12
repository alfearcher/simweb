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
 *  @file pre-view-create-solvencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view pre-view-create-solvencia.php
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

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="pre-view-create">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false); ?>
	<?=$form->field($model, 'impuesto')->hiddenInput(['value' => $model->impuesto])->label(false); ?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => $model->nro_solicitud])->label(false); ?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false); ?>
	<?=$form->field($model, 'ultimo_pago')->hiddenInput(['value' => $model->ultimo_pago])->label(false); ?>
	<?=$form->field($model, 'observacion')->hiddenInput(['value' => $model->observacion])->label(false); ?>
	<?=$form->field($model, 'placa')->hiddenInput(['value' => $model->placa])->label(false); ?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">


					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>
						<div class="row" id="id-rubro-vehiculo" style="padding: 0px;">
							<?= GridView::widget([
								'id' => 'id-grid-rubro-vehiculo',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'tableOptions' => [
                    				'class' => 'table table-hover table-bordered',
              					],
								'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkIdImpuesto',
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
					                            	return [
					                                    'onClick' => 'javascript: return false;',
					                                    'checked' => true,
					                                ];
				                        },
				                        'multiple' => false,
				                    ],
				                    // [
				                    //     'contentOptions' => [
				                    //           'style' => 'font-size: 90%;',
				                    //     ],
				                    //     'label' => Yii::t('frontend', 'id'),
				                    //     'value' => function($data) {
				                    //                		return $data['id_impuesto'];
				            			     //       },
				                    // ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'placa'),
				                        'value' => function($data) {
				                                   		return $data['descripcion'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'modelo'),
				                        'value' => function($data) {
				                                   		return $data['modelo'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'color'),
				                        'value' => function($data) {
				                                   		return $data['color'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'ultimo pago'),
				                        'value' => function($data) {
				                                   		return $data['ultimoPago'];
				            			           },
				                    ],
				                     [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'format' => 'raw',
				                        'label' => Yii::t('frontend', 'bloqueado'),
				                        'value' => function($data) {
				                        				$m = '';
				                        				if ( count($data['condicion']) > 0 ) {
															foreach ( $data['condicion'] as $key => $value ) {
																if ( trim($m) == '' ) {
																	$m = Html::tag('li', $value) . '<br>';
																} else {
																	$m = $m . Html::tag('li', $value) . '<br>';
																}
															}
														}
														return $m;
				            			           },
				                    ],


					        	]
							]);?>
						</div>
					</div>

					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Confirmar Crear Solicitud'),
																						  [
																							'id' => 'btn-confirm-create',
																							'class' => 'btn btn-success',
																							'value' => 5,
																							'style' => 'width: 100%;',
																							'name' => 'btn-confirm-create',

																						  ])
									?>
								</div>
							</div>

							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Back'),
																			  [
																				'id' => 'btn-back-form',
																				'class' => 'btn btn-danger',
																				'value' => 3,
																				'style' => 'width: 100%;',
																				'name' => 'btn-back-form',

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

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


