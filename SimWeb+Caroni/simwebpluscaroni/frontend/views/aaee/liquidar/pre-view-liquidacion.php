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


<div class="pre-view-liquidacion-aaee-resultante">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-liquidacion-aaee-resultante-form',
 			'method' => 'post',
 			// 'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


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
					                	'class' => 'yii\grid\CheckboxColumn',
					                	'name' => 'chkLapso',
					                	'checkboxOptions' => [
                							'id' => 'chkLapso',
                							// Lo siguiente mantiene el checkbox tildado.
                							'onClick' => 'javascript: return false;',
                							'checked' => true,
                							//'disabled' => true, funciona.
                    					],
                    					'multiple' => false,
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
				            			//'visible' => false,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align: center;',
				                        ],
				                        'label' => Yii::t('frontend', 'Periodo'),
				                        'value' => function($data) {
				                                   		return $data['trimestre'];
				            			           },
				            			//'visible' => false,
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


					<div class="row" style="width:100%;padding: 0px;margin-top: 20px;">
						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Confirmar'),
																	  [
																		'id' => 'btn-confirm-create',
																		'class' => 'btn btn-success',
																		'value' => 3,
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
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'value' => 1,
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

					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
