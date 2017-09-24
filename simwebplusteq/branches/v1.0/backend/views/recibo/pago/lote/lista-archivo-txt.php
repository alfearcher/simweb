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
 *  @file lista-archivo-txt.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-04-2017
 *
 *  @view lista-archivo-txt
 *  @brief vista.
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


?>

<div class="lista-archivo-txt">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-archivo',
 			'method' => 'post',
 			//'action' => ['mostrar-archivo-txt'],
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_banco')->hiddenInput(['value' => $model->id_banco])->label(false);?>
	<?=$form->field($model, 'fecha_desde')->hiddenInput(['value' => $model->fecha_desde])->label(false);?>
	<?=$form->field($model, 'fecha_hasta')->hiddenInput(['value' => $model->fecha_hasta])->label(false);?>
	<?=$form->field($model, 'sin_formato')->hiddenInput(['value' => $model->sin_formato])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

		        	<div class="row" style="width:80%;margin-bottom: 20px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-top: 0px;">
							<h4><strong><?=Html::encode($subCaption)?></strong></h4>
						</div>

						<div class="row" style="padding:0px;padding-top: 15px;">
							<p><strong><?=$labelBanco?></strong></p>
						</div>

						<div class="row" style="padding:0px;padding-top: 0px;">
							<p><strong><?=$labelRango?></strong></p>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;padding:0px;padding-top: 0px;">
						</div>

						<div class="row" style="width: 100%;padding:0px;margin:0px;margin-top: 20px;">
							<div class="row" style="width: 100%;">
								<?= Html::activeCheckbox($model, 'sin_formato', [
																				'label' => $model->getAttributeLabel('sin_formato'),
																				'labelOptions' => [
																					'style' => 'width:100%;',
																				],
																			]);
								?>
							</div>
							<div class="row" style="width: 100%;margin-top: 5px;">
						    	<?= GridView::widget([
						    		'id' => 'id-grid-lista-archivo-txt',
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
						                          'style' => 'font-size: 100%;font-weight:bold;width:45%;padding-right:0px;',
						                    ],
						                    'label' => Yii::t('backend', 'Archivos'),
						                    'format' => 'raw',
						                    'value' => function($data, $key) {
					                    					return Html::submitButton($data['file'],
		                            													[
																							'id' => 'btn-file',
														            						'class' => 'btn btn-primary',
														            						'style' => 'width:60%;',
														            						'data' => [
														            							'method' => 'post',
														            							'params' => [
														            								'data-file' => $data['file'],
														            								'data-path' => $data['path'],
														            								'data-date' => $data['fecha'],
														            								'data-file-type' => 'file',
														            								'data-key' => $key,
														            							],
														            						],
														            					]);
						        			           },
						                ],

						                [
						                    'contentOptions' => [
						                          'style' => 'font-size: 100%;font-weight:bold;width:45%;',
						                    ],
						                    'label' => Yii::t('backend', 'Archivo plano'),
						                    'format' => 'raw',
						                    'value' => function($data, $key) {
					                    					return Html::submitButton($data['file'],
		                            													[
																							'id' => 'btn-file-flat',
																							'name' => 'btn-file-flat',
														            						'class' => 'btn btn-default',
														            						'style' => 'width:60%;',
														            						'data' => [
														            							'method' => 'post',
														            							'params' => [
														            								'data-file' => $data['file'],
														            								'data-path' => $data['path'],
														            								'data-date' => $data['fecha'],
														            								'data-file-type' => 'file-flat',
														            								'data-key' => $key,
														            							],
														            						],
														            					]);
						        			           },
						                ],
						        	]
						    	]);?>
						    </div>

						</div>
					</div>

					<div class="row" style="margin-top: 30px;">
						<div class="col-sm-2" style="margin-left: 40px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Back'),
																  [
																	'id' => 'btn-back',
																	'class' => 'btn btn-danger',
																	'value' => 1,
																	'style' => 'width: 100%',
																	'name' => 'btn-back',
																  ])
								?>
							</div>
						</div>

						<div class="col-sm-2" style="margin-left: 20px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
																  [
																	'id' => 'btn-quit',
																	'class' => 'btn btn-danger',
																	'value' => 1,
																	'style' => 'width: 100%',
																	'name' => 'btn-quit',
																  ])
								?>
							</div>
						</div>

						<!-- <div class="col-sm-2" style="margin-left: 50px;">
							 <div class="form-group">
							 '../../common/docs/user/ayuda.pdf'  funciona
								<?//= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
													// 	'id' => 'btn-help',
													// 	'class' => 'btn btn-default',
													// 	'name' => 'btn-help',
													// 	'target' => '_blank',
													// 	'value' => 1,
													// 	'style' => 'width: 100%;'
													// ])?>

							</div>
						</div> -->
<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>