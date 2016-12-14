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
 *  @file detalle-liquidacion-aaee.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view detalle-liquidacion-aaee
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


<div class="detalle-liquidacion-aaee-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-detalle-liquidacion-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?> -->


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

						<div class="row" style="padding-top: 15px;">
							<div class="col-sm-2" style="width: 10%;padding: 0px;">
								<strong><h5><?=Html::encode(Yii::t('frontend', 'Fecha Inicio:'))?></h5></strong>
							</div>
							<div class="col-sm-2" style="width: 14%;padding: 0px;padding-left: 55px;">
								<div class="fecha-inicio" style="margin-left: 0px;">
									<?= Html::textInput('fecha_inicio', date('d-m-Y', strtotime($fechaInicio)),
																		[
																			'id' => 'id-fecha-inicio',
																			'style' => 'width:100%;background-color:white;',
																			'readOnly' => true,
																			'class' => 'form-control',

																		])?>
								</div>
							</div>
						</div>

						<div class="row" style="padding-top:5px;">
							<div class="col-sm-2" style="width: 15%;padding: 0px;">
								<strong><h5><?=Html::encode(Yii::t('frontend', 'Ultimo Lapso Liquidado:'))?></h5></strong>
							</div>
							<div class="col-sm-2" style="width: 9%;padding: 0px;">
								<div class="ano-impositivo" style="margin-left: 0px;">
									<?= Html::textInput('ultimo_ano_impositivo', $ultimoLapsoLiquidado['ano_impositivo'],
																		[
																			'id' => 'id-ano-impositivo',
																			'style' => 'width:100%;background-color:white;',
																			'readOnly' => true,
																			'class' => 'form-control',

																		])?>
								</div>
							</div>
							<div class="col-sm-2" style="width: 5%;padding: 0px;">
								<div class="trimestre" style="margin-left: 5px;">
									<?= Html::textInput('trimestre', $ultimoLapsoLiquidado['trimestre'],
																		[
																			'id' => 'id-trimestre',
																			'style' => 'width:100%;background-color:white;',
																			'readOnly' => true,
																			'class' => 'form-control',

																		])?>
								</div>
							</div>
							<div class="col-sm-2" style="width: 15%;padding: 0px;">
								<div class="exigibilidad" style="margin-left: 5px;">
									<?= Html::textInput('exigibilidad', $ultimoLapsoLiquidado['exigibilidad']['unidad'],
																		[
																			'id' => 'id-exigibilidad',
																			'style' => 'width:100%;background-color:white;',
																			'readOnly' => true,
																			'class' => 'form-control',

																		])?>
								</div>
							</div>
							<div class="col-sm-2" style="width: 15%;padding: 0px;">
								<div class="estatus" style="margin-left: 5px;">
									<?= Html::textInput('estatus', $ultimoLapsoLiquidado['estatus']['descripcion'],
																		[
																			'id' => 'id-estatus',
																			'style' => 'width:100%;background-color:white;',
																			'readOnly' => true,
																			'class' => 'form-control',

																		])?>
								</div>
							</div>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-top: 0px;margin-top: 10px;width:100%;">
							<div class="col-sm-3" style="width: 100%;">
								<h4><?=Html::encode(Yii::t('frontend', 'Lapsos por liquidar'))?></h4>
							</div>
						</div>


						<div class="row" id="id-detalle-liquidacion" style="width: 100%;">
							<?= GridView::widget([
								'id' => 'id-grid-detalle-liquidacion',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'tableOptions' => [
                    				'class' => 'table table-hover',
              					],
								'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

									[
			               		 		'contentOptions' => [
				                              'style' => 'font-size: 100%;',
				                        ],
			                           	'label' => 'Seleccionar lapso',
			                            'format' => 'raw',
			                            'value' => function($data, $key) {
			                            		return Html::submitButton($data['ano_impositivo'] . ' - ' . $data['trimestre'],
		                            													[
																							'id' => 'btn-lapso',
																							//'name' => 'btn-lapso[' . $data['ano_impositivo'] . ' - ' . $data['trimestre'] . ']',
														            						'class' => 'btn btn-primary',
														            						'data' => [
														            							'method' => 'post',
														            							'params' => [
														            								'data-ano-impositivo' => $data['ano_impositivo'],
														            								'data-periodo' => $data['trimestre'],
														            								'data-key' => $key,
														            								'data-id-pago' => $data['id_pago'],
														            							],
														            						],
														            					]);
			                            },
			                        ],



				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'id-pago'),
				                        'value' => function($data) {
				                                   		return $data['id_pago'];
				            			           },
				            			'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'id-impuesto'),
				                        'value' => function($data) {
				                                   		return $data['id_impuesto'];
				            			           },
				            			'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'impuesto'),
				                        'value' => function($data) {
				                                   		return $data['impuesto'];
				            			           },
				            			'visible' => false,
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: center;',
				                        ],
				                        'label' => Yii::t('frontend', 'Año'),
				                        'value' => function($data) {
				                                   		return $data['ano_impositivo'];
				            			           },
				            			'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: center;',
				                        ],
				                        'label' => Yii::t('frontend', 'Periodo'),
				                        'value' => function($data) {
				                                   		return $data['trimestre'];
				            			           },
				            			'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Tipo'),
				                        'value' => function($data) {
				                                   		return ExigibilidadSearch::descripcion($data['exigibilidad_pago']);
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Monto'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data['monto'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Recargo'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data['recargo'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Interes'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data['interes'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Descuento'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data['descuento'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Rec/Ret'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data['monto_reconocimiento'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: center;',
				                        ],
				                        'label' => Yii::t('frontend', 'Fecha Vcto'),
				                        'value' => function($data) {
				                                   		return $data['fecha_vcto'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: center;',
				                        ],
				                        'label' => Yii::t('frontend', 'Observacion'),
				                        'value' => function($data) {
				                                   		return $data['descripcion'];
				            			           },
				                    ],
				                    // [
				                    //     'contentOptions' => [
				                    //           'style' => 'font-size: 90%;',
				                    //     ],
				                    //     'label' => Yii::t('frontend', 'Licor'),
				                    //     'value' => function($data) {
				                    //     				if ( $data->rubroDetalle->licores == 1 ) {
				                    //     					return 'SI';
				                    //     				} else {
				                    //     					return 'NO';
				                    //     				}

				            			     //       },
				                    // ],

				                   /* [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;width:10%;',
				                        ],
				                        'label' => Yii::t('frontend', 'fecha'),
				                        'value' => function($data) {
				                                   		return date('d-m-Y', strtotime($data['fecha']));
				            			           },
				                    ],*/

					        	]
							]);?>
						</div>

					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
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

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
