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
 *  @file pre-seleccion-vehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view pre-seleccion-vehiculo.php
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


<div class="pre-seleccion-vehiculo-activo">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-vehiculo-form',
 			'method' => 'post',
 			'action' => $url,
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


					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left:10px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>
						<div class="row" id="id-lista-vehiculo" style="padding: 0px;">
							<?= GridView::widget([
								'id' => 'id-grid-lista-vehiculo',
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
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Id'),
				                        'value' => function($data) {
				                                   		return $data['id_impuesto'];
				            			           },
				                    ],
				                    // [
				                    //     'contentOptions' => [
				                    //           'style' => 'font-size: 90%;',
				                    //     ],
				                    //     'format' => 'raw',
				                    //     'label' => Yii::t('frontend', 'id'),
				                    //     'value' => function($data) {
				                    //                		return Html::textInput('id-impuesto[' . $data['id_impuesto'] . ']',
				                    //                											$data['id_impuesto'],
				                    //                											[
				                    //                												'readOnly' => true,
				                    //                												'class' => 'form-control',
				                    //                												'style' => 'width: 45%;
				                    //                														    background-color:white;',
				                    //                											]);
				            			     //       },
				                    // ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'placa'),
				                        'value' => function($data) {
				                                   		return $data['placa'];
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'marca'),
				                        'value' => function($data) {
				                                   		return $data['marca'];
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
				                        'label' => Yii::t('frontend', 'ultimo lapso'),
				                        'value' => function($data, $ultimo) {
				                        				$ultimo = $data['añoImpositivo'] . ' - ' . $data['periodo'] . ' - ' . $data['unidad'];
				                                   		return $ultimo;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'planilla / condicion'),
				                        'value' => function($data, $ultimo) {
				                        				$ultimo = $data['planilla'] . ' / ' . $data['condicion'];
				                                   		return $ultimo;
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
									<?= Html::submitButton(Yii::t('frontend', 'Confirmar Seleccion'),
																					  [
																						'id' => 'btn-confirm-seleccion',
																						'class' => 'btn btn-success',
																						'value' => 5,
																						'style' => 'width: 100%;',
																						'name' => 'btn-confirm-seleccion',

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
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


