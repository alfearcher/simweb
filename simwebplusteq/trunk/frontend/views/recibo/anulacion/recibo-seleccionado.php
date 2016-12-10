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
 *  @file recibo-seleccionado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-11-2016
 *
 *  @view recibo-seleccionado
 *  @brief vista del recibo de pago seleccionado para su anulacion.
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

<div class="recibo-pago-seleccionado">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-recibo-seleccionado',
 			'method' => 'post',
 			//'action'=> '#',
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false);?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;margin: auto;">
    <div class="panel-heading">
    	<h3><?= Html::encode($caption) ?></h3>
    </div>

<!-- Cuerpo del formulario -->
 <!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width: 100%;padding-left: 0px;">
						<div class="col-sm-4" style="margin-left:0px;padding-left:0; width: 70%;">

							<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
								<h4><?= Html::encode(Yii::t('frontend', 'Resumen de Recibo')) ?></h4>
							</div>

							<div class="row" style="margin-top: 15px;">
								<?= DetailView::widget([
										'model' => $deposito,
						    			'attributes' => [

						    				[
						    					'label' => $deposito->getAttributeLabel('recibo'),
						    					'value' => $deposito->recibo,
						    				],
						    				[
							                    'label' => $deposito->getAttributeLabel('fecha'),
							                    'value' => date('d-m-Y', strtotime($deposito->fecha)),

							                ],
						    				[
						    					'label' => $deposito->getAttributeLabel('monto'),
						    					'value' => Yii::$app->formatter->asDecimal($deposito->monto, 2),
						    				],

						    				// [
						    				// 	'label' => $deposito->getAttributeLabel('usuario'),
						    				// 	'value' => $deposito->usuario,
						    				// ],
						    				// [
						    				// 	'label' => $deposito->getAttributeLabel('fecha_hora'),
						    				// 	'value' => $deposito->fecha_hora,
						    				// ],
						    				[
						    					'label' => $deposito->getAttributeLabel('estatus'),
						    					// 'value' => $deposito->estatus,
						    					'value' => $deposito->condicion->descripcion,
						    				],

						    			],
									])
								?>
							</div>
						</div>

						<div class="col-sm-3" style="width: 30%;padding-left: 50px;padding-top: 30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Solicitar anular este recibo'),
																		[
																			'id' => 'btn-delete-one',
																			'class' => 'btn btn-success',
																			'value' => $model['recibo'],
																			'style' => 'width: 100%',
																			'name' => 'btn-delete-one',
																		])
								?>
							</div>
						</div>

					</div>


<!-- Aqui se muestra lo seleccionado por el contribuyente -->

					<div class="row" style="width: 100%;margin-top:30px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode('Planilla(s) Seleccionadas')?></h4>
						</div>

						<div class="row" class="recibo-seleccioando" style="padding-top: 10px;">
							<?= GridView::widget([
								'id' => 'grid-recibo-seleccionado',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'summary' => '',
								'columns' => [
									[
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'recibo'),
				                        'value' => function($data) {
				                                      return $data->recibo;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'planilla'),
				                        'value' => function($data) {
				                                      return $data->planilla;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'impuesto'),
				                        'value' => function($data) {
		        					                   return $data->impuesto;
		        					               },
				                        'visible' => true,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'contribuyente'),
				                        'value' => function($data) {
		        					                   return $data->descripcion;
		        					               },
				                        'visible' => true,
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'monto'),
				                        'value' => function($data) {
				                                   		return Yii::$app->formatter->asDecimal($data->monto, 2);
	        					                   },
				                        'visible' => true,
				                    ],
				                    [
				                        'contentOptions' => [
				                            'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'condicion'),
				                        'value' => function($data) {
		        					                  return $data->condicion->descripcion;
				        					       },
				                        'visible' => true,
				                    ],

					        	]
							]);?>
						</div>
					</div>
<!-- Fin de lo seleccionado -->

					<div class="row" style="padding-top: 20px;">

						<div class="col-sm-3" style="width: 20%;padding-left: 30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Back'),
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

						<div class="col-sm-3" style="width: 20%;padding-left: 30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Quit'),
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

					</div>

				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
