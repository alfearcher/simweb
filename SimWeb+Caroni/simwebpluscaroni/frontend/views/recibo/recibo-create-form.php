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
 *  @file recibo-create-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view recibo-create-form
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
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

    $totalSeleccionado = 0;
    $sumaSeleccion = 0;
?>

<div class="recibo-pago-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-recibo-create-form',
 			'method' => 'post',
 			'action'=> Url::to(['index-create']),
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>

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

					<div class="row" style="width: 105%;padding-left: 10px;">
						<div class="col-sm-4" style="margin-left:0px;padding-left:0; width: 30%;">

							<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
								<h4><?= Html::encode(Yii::t('frontend', 'Deuda por impuestos')) ?></h4>
							</div>

							<div class="row" class="deuda-por-impuesto" style="padding-top: 10px;">

								<?= GridView::widget([
									'id' => 'grid-deuda-general-contribuyente',
									'dataProvider' => $dataProvider,
									//'filterModel' => $model,
									'summary' => '',
									'columns' => [

						            	[
						            		'contentOptions' => [
						                    	'style' => 'font-size: 90%;width:20%;text-align:center;',
						                	],
						                    'label' => Yii::t('frontend', 'Impuesto'),
						                    'value' => function($data) {
	                										return $data['impuesto'];
	        											},
						                ],
						                [
						                	'contentOptions' => [
						                    	'style' => 'font-size: 90%;',
						                	],
						                    'label' => Yii::t('frontend', 'Descripcion'),
						                    'value' => function($data) {
	                										return $data['descripcion'];
	        											},
						                ],

						                [
						                	'contentOptions' => [
						                    	'style' => 'font-size: 90%;text-align:right;',
						                	],
						                    'class' => 'yii\grid\ActionColumn',
				                    		'header'=> Yii::t('backend','Deuda'),
				                    		'template' => '{view}',
				                    		'buttons' => [
				                        		'view' => function ($url, $model, $key) {
				                        				// $url =  Url::to(['buscar-deuda']);
				                        				$u = Yii::$app->urlManager
							                                          ->createUrl('recibo/recibo/buscar-deuda-detalle') . '&view=1' . '&i=' . $model['impuesto'] . '&idC='.$model['id_contribuyente'];
				                           				return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. Yii::$app->formatter->asDecimal($model['deuda'], 2) .'</center></div>',
					                        							[
					                        								'id' => 'id-deuda',
							                        						'name' => 'id',
							                        						'class' => 'btn btn-default',
							                        						'title' => 'deuda '. $model['deuda'],
							                        						'style' => 'text-align:right;',
							                        						//'onClick' => 'alert("' . $u. '")',
							                        						'onClick' => '
							                        										$.post("' . $u . '", function( data ) {
							                        																$( "#deuda-por-objeto" ).html("");
							                        																$( "#deuda-detalle" ).html("");
							                        																$( "#id-suma" ).val("0");
																													$( "#deuda-en-periodo" ).html( data );
							                        														   }
							                        											);return false;',
										                        		]
									                        		);
				                        				},
						                	],
						                ],
						        	]
								]);?>
							</div>

							<div class="row" style="padding-top: 0px;margin-top: -10px;background-color: #F1F1F1;">
								<div class="col-sm-3" style="width: 45%;text-align: right;">
									<h3><strong><p>Total:</p></strong></h3>
								</div>
								<div class="col-sm-3" style="width: 55%;text-align: right;">
									<h3><strong><p><?=Html::encode(Yii::$app->formatter->asDecimal($total, 2))?></p></strong></h3>
								</div>
							</div>

						</div>


						<div class="col-sm-4" style="margin-left:40px;margin-top:0px;padding-left:0; width: 60%;">
							<?php Pjax::begin() ?>
								<div class="deuda-en-periodo" id="deuda-en-periodo">
								</div>
							<?php Pjax::end() ?>
						</div>


						<div class="col-sm-4" style="margin-left:40px;margin-top:0px;padding-left:0; width: 60%;">
							<?php Pjax::begin(['enablePushState' => false]) ?>
								<div class="deuda-por-objeto" id="deuda-por-objeto">
								</div>
							<?php Pjax::end() ?>
						</div>

					</div>

					<div class="row" style="padding-top: 50px;">
						<div class="col-sm-10" style="margin-left:10px;margin-top:0px;padding-left:0; width: 95%;">
							<?php Pjax::begin(['enablePushState' => false]) ?>
								<div class="deuda-detalle" id="deuda-detalle">
								</div>
							<?php Pjax::end() ?>
						</div>
					</div>


<!-- Aqui se muestra lo seleccionado por el contribuyente -->

					<div class="row" style="width: 70%;margin-top: 80px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode('Planilla(s) Seleccionadas')?></h4>
						</div>

						<div class="row" class="deuda-seleccionda" style="padding-top: 10px;">
							<?= GridView::widget([
								'id' => 'grid-deuda-seleccionada',
								'dataProvider' => $providerPlanillaSeleccionada,
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
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'class' => 'yii\grid\ActionColumn',
				                        'header'=> Yii::t('frontend', 'Quitar'),
				                        'template' => '{view}',
				                        'buttons' => [
				                            'view' => function ($url, $model, $key) {

			                                      return Html::submitButton(Yii::t('frontend', 'Quitar'),
							                                                              [
							                                                                  'id' => 'id-quitar',
							                                                                  'name' => 'quitar',
							                                                                  'class' => 'btn btn-warning',
							                                                                  'title' => 'Quitar',
							                                                              ]
							                                    );

				                            },

				                        ],
				                        'visible' => false,
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
						                              'value' => Yii::$app->formatter->asDecimal($model->totalSeleccionado, 2),
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
									<?= Html::submitButton(Yii::t('frontend', 'Crear Recibo'),
																			  [
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'value' => 1,
																				'style' => 'width: 100%',
																				'name' => 'btn-create',
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
								<?= Html::submitButton(Yii::t('frontend', 'Reset'),
																		  [
																			'id' => 'btn-reset',
																			'class' => 'btn btn-danger',
																			'value' => 9,
																			'style' => 'width: 80%',
																			'name' => 'btn-reset',
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
