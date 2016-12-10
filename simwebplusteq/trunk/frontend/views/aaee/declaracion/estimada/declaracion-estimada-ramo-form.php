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
 *  @file declarar-estimada-ramo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-09-2016
 *
 *  @view declarar-estimada-ramo-form.php
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

 	//use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	// use yii\jui\DatePicker;
	use yii\widgets\Pjax;
	use yii\widgets\DetailView;
	//use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use backend\controllers\menu\MenuController;
	use yii\widgets\MaskedInput;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="ramo-registrado-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-ramo-registrado-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false);?>
	<?=$form->field($model, 'exigibilidad_periodo')->hiddenInput(['value' => $model->exigibilidad_periodo])->label(false);?>
	<?=$form->field($model, 'periodo_fiscal_desde')->hiddenInput(['value' => $model->periodo_fiscal_desde])->label(false);?>
	<?=$form->field($model, 'periodo_fiscal_hasta')->hiddenInput(['value' => $model->periodo_fiscal_hasta])->label(false);?>
	<!-- <?//=$form->field($model, 'totalItem')->hiddenInput(['value' => $totalItem])->label(false);?> -->
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 110%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-7" style="padding-top: 10px;">
	    			<h3><?= Html::encode($caption) ?></h3>
	    		</div>
	    		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
	    			<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #337AB7;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario($opciones);?>
	        	</div>
	        </div>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 104%;margin-left: -25px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode(Yii::t('frontend', 'Info of Taxpayer')) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
<!-- Datos del Contribuyente -->
									<div class="col-sm-4" style="margin-left: 15px;width: 90%">
										<div class="row">
											<div class="id-contribuyente">
												<?= $this->render('@common/views/contribuyente/datos-contribuyente', [
							        											'model' => $findModel,
							        							])
												?>
											</div>
										</div>
									</div>
<!-- Fin de datos del Contribuyente -->

								</div> 		<!-- Fin de row -->
							</div>
						</div>
					</div>


					<div class="row">
						<div class="panel panel-success" style="width: 104%;margin-left: -25px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode($subCaption) ?></span></b>
					        </div>
					        <div class="panel-body">
<!-- INICIO RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->
								<div class="row"  style="padding-left: 10px; width: 100%;">
									<div class="rubro-registrado">
										<div class="row" style="margin-left: 5px;">
											<h3><span><?= Html::encode(Yii::t('frontend', $subCaption . ' ' .  $model->ano_impositivo . ' - ' . $model->exigibilidad_periodo)) ?></span></h3>
										</div>
										<?= GridView::widget([
												// Rubros registrados para el año-periodo.
												'id' => 'grid-lista-rubro-registrado',
		    									'dataProvider' => $dataProviderRubro,
		    									'headerRowOptions' => ['class' => 'success'],
		    									'options' => [
		    										'style' => 'width: 102%;',
		    									],

		    									'summary' => '',
		    									'columns' => [
		    										// [
									       //          	'class' => 'yii\grid\CheckboxColumn',
									       //          	'name' => 'chkRubroSeleccionado',
									       //          	'checkboxOptions' => [
	               //              							'id' => 'chkRubroSeleccionado',
	               //              							// Lo siguiente mantiene el checkbox tildado.
	               //              							'onClick' => 'javascript: return false;',
	               //              							'checked' => true,
	               //                  					],
									       //          	'multiple' => false,
									       //          ],
		    										['class' => 'yii\grid\SerialColumn'],
									            	[
									                    'label' => Yii::t('frontend', 'Category'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->rubro;
		                											},
									                ],
									                [
									                    'label' => Yii::t('frontend', 'Year'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->ano_impositivo;
		                											},
									                ],
									                [
									                    'label' => Yii::t('frontend', 'Period'),
									                    'value' => function($data) {
		                        										return $data->exigibilidad_periodo;
		                											},
									                ],
									                // [
									                //     'label' => Yii::t('frontend', 'Begin Date'),
									                //     'value' => function($data) {
		                       //  										return $data->periodo_fiscal_desde;
		                							// 				},
									                // ],
									                // [
									                //     'label' => Yii::t('frontend', 'End Date'),
									                //     'value' => function($data) {
		                       //  										return $data->periodo_fiscal_hasta;
		                							// 				},
									                // ],
									                [
									                    'label' => Yii::t('frontend', 'Descripcion'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->descripcion;
		                											},
									                ],
									                [
                        								'class' => 'yii\grid\DataColumn',
                        								'attribute' => Yii::t('frontend', 'Minimun'),
                        								'format' => 'raw',
                        								'contentOptions' => [
                        									'style'=>'width: 200px;'
                        								],
                        								'value' => function($data) {
                        										$searchDeclaracion = New DeclaracionBaseSearch($data->actividadEconomica->id_contribuyente);
                        										$a = (int)$data->actividadEconomica->ano_impositivo - 1;
                        										$p = (int)$data->exigibilidad_periodo;
                        										$real = $searchDeclaracion->getDefinitivaAnterior($a, $p, $data->id_rubro);

							                                    return Html::textInput('minimo['. $data->id_rubro . ']',
							                                     													$real[$data->rubroDetalle->rubro], [
		                                                                                        							'class' => 'form-control',
		                                                                                        							'id' => 'minimo'. $data->id_rubro,
		                                                                                        							'style' => '',
		                                                                                        							//'value' => $real,
		                                                                                        							'readonly' => true,
									                                                                                    ]);
								                        }
								                    ],
									                [
                        								'class' => 'yii\grid\DataColumn',
                        								'attribute' => Yii::t('frontend', 'Current'),
                        								'format' => 'raw',
                        								'contentOptions' => [
                        									'style'=>'width: 200px;'
                        								],
                        								'value' => function($data) {
                        										// $searchDeclaracion = New DeclaracionBaseSearch($data->actividadEconomica->id_contribuyente);
                        										// $a = (int)$data->actividadEconomica->ano_impositivo - 1;
                        										// $p = (int)$data->exigibilidad_periodo;
                        										// $real = $searchDeclaracion->getDefinitivaAnterior($a, $p, $data->id_rubro);

							                                    return Html::textInput('definitiva['. $data->id_rubro . ']',
							                                     													0, [
		                                                                                        							'class' => 'form-control',
		                                                                                        							'id' => 'definitiva'. $data->id_rubro,
		                                                                                        							'style' => '',
		                                                                                        							//'value' => $real,
		                                                                                        							//'disabled' => 'disabled',
									                                                                                    ]);
								                        }
								                    ],
									        	]
											]);
										?>
									</div>
								</div>
								<?php if ( trim($errorChk) !== '' ) { ?>
									<div class="row">
										<div class="error-chk-documento">
											<div class="well well-sm" style="color: red;width: 60%; margin-left: 10px;"><?=$errorChk; ?></div>
										</div>
									</div>
								<?php } ?>
<!-- FIN DE RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->

							</div>
						</div>
					</div>
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="form-group">
					<div class="col-sm-3" style="width: 20%;margin-left:100px;">
						<?= Html::submitButton(Yii::t('frontend', 'Create Request'),
																			  [
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'value' => 5,
																				'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																				'name' => 'btn-create',
																				//'disabled' => true,
																			  ]);
						?>
					</div>

					<div class="col-sm-3" style="width: 20%;margin-left:100px;">
						<?= Html::submitButton(Yii::t('frontend', 'Back Form'),
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
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


<?php
	$this->registerJs(
		'$( "#btn-create").click(function() {
			$("#btn-create").attr("disabled", true);
		});'
	);
 ?>