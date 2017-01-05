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
 *  @file create-licencia-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view create-licencia-form.php
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


<div class="crear-licencia-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-create-licencia-form',
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
	<?=$form->field($model, 'licencia')->hiddenInput(['value' => $model->licencia])->label(false); ?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => $model->nro_solicitud])->label(false); ?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="col-sm-2" style="width: 10%;">
							<h4><strong>Lapso</strong></h4>
						</div>
						<div class="col-sm-2" style="width: 10%;padding: 0px;">
							<div class="lapso" style="margin-left: 0px;">
								<?= $form->field($model, 'ano_impositivo')->textInput([
																			'id' => 'id-ano-impositivo',
																			'style' => 'width:98%;background-color:white;',
																			'value' => $model->ano_impositivo,
																			'readOnly' => true,

																	])->label(false) ?>
							</div>
						</div>
						<div class="col-sm-2" style="width: 5%;padding: 0px;padding-right: 5px;">
							<div class="lapso" style="margin-left: 0px;">
								<?= $form->field($model, 'periodo')->textInput([
																			'id' => 'id-periodo',
																			'style' => 'width:98%;background-color:white;',
																			'value' => $periodo,
																			'readOnly' => true,
																	])->label(false) ?>
							</div>
						</div>

						<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 15px;">
							<h4><strong>Tipo de Licencia</strong></h4>
						</div>
						<div class="col-sm-2" style="width: 15%;padding: 0px;">
							<div class="tipo-licencia" style="margin-left: 0px;">
								<?= $form->field($model, 'tipo')->textInput([
																			'id' => 'id-tipo',
																			'style' => 'width:98%;background-color:white;',
																			'value' => $tipo,
																			'readOnly' => true,
																	])->label(false) ?>
							</div>
						</div>


						<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 15px;">
							<h4><strong>Nro. de Licencia</strong></h4>
						</div>
						<div class="col-sm-2" style="width: 17%;padding: 0px;">
							<div class="licencia" style="margin-left: 0px;">
								<?= $form->field($model, 'licencia')->textInput([
																			'id' => 'id-licencia',
																			'style' => 'width:100%;background-color:white;',
																			'value' => $model->licencia,
																			'readOnly' => true,
																	])->label(false) ?>
							</div>
						</div>
					</div>


					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', 'Rubros registrados'))?></h4>
						</div>
						<div class="row" id="id-rubro-registrado">
							<?= GridView::widget([
								'id' => 'id-grid-rubro-registrado',
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
				                        'name' => 'chkRubro',
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
			                            	return [
			                                	'value' => $model->id_rubro,
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
				                        'label' => Yii::t('frontend', 'rubro'),
				                        'value' => function($data) {
				                                   		return $data->rubroDetalle->rubro;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'descripcion'),
				                        'value' => function($data) {
				                                   		return $data->rubroDetalle->descripcion;
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Licor'),
				                        'value' => function($data) {
				                        				if ( $data->rubroDetalle->licores == 1 ) {
				                        					return 'SI';
				                        				} else {
				                        					return 'NO';
				                        				}

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

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Crear Solicitud')),
																						  [
																							'id' => 'btn-create',
																							'class' => 'btn btn-success',
																							'value' => 5,
																							'style' => 'width: 100%;',
																							'name' => 'btn-create',

																						  ])
									?>
								</div>
							</div>

							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Back')),
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


							<div class="col-sm-2" style="margin-left: 50px;margin-top:20px;">
								<div class="form-group">
								<!-- '../../common/docs/user/ayuda.pdf'  funciona -->
									<?= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
															'id' => 'btn-help',
															'class' => 'btn btn-default',
															'name' => 'btn-help',
															'target' => '_blank',
															'value' => 1,
															'style' => 'width: 100%;'
														])?>
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


