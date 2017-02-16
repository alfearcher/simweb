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
 *  @file historico-licencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view historico-licencia.php
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
	use yii\data\ArrayDataProvider;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

    $datosContribuyente = json_decode($model['fuente_json'], true);
    $datosRubro = json_decode($model['rubro_json'], true);

   // die(var_dump($datosRubro));

 ?>


<div class="historico-licencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-historico-licencia',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode(Yii::t('frontend', 'Historico')) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="padding: 0px;width:100%;">
						<div class="col-sm-5" style="padding: 0px; width: 40%;">
							<div class="row" style="padding-left: 0px; width: 100%;">
				        		<h4><?= Html::encode(Yii::t('frontend', 'Informacion Historico')) ?></h4>
								<?= DetailView::widget([
										'model' => $model,
						    			'attributes' => [

						    				[
						    					'label' => Yii::t('frontend', 'Historico'),
						    					'value' => $model['id_historico'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Nro de Solicitud'),
						    					'value' => $model['nro_solicitud'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Id. Contribuyente'),
						    					'value' => $model['id_contribuyente'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Año'),
						    					'value' => $model['ano_impositivo'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Tipo de Licencia'),
						    					'value' => $model['tipo'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Licencia'),
						    					'value' => $model['licencia'],
						    				],
						    				// [
						    				// 	'label' => Yii::t('frontend', 'condicion'),
						    				// 	'value' => $model['estatusSolicitud']['descripcion'],
						    				// ],
						    			],
									])
								?>
							</div>
						</div>

						<div class="row" style="padding: 0px;margin: 0px;width: 100%;">
							<div class="col-sm-5" style="width:100%;">
								<div class="well well-sm">
									<?=Html::tag('h4', Yii::t('backend', 'IMPORTANTE'),
														[
															'style' => 'font-weight:bold;'
														]);
									?>
									<?=Html::tag('p', Yii::t('backend', 'Las actualizaciones y/o modificaciones de los datos de las licencias solo se verán reflejados cuando se cumplan las condiciones siguientes:'),
														[
															'style' => 'font-size: 120%;'
														]);
									?>
									<?=Html::tag('li', Yii::t('backend', 'Dichas solicitudes de actualizaciones y/o modificaciones hayan sido aprobadas por el ente emisor de la licencia.'),
														[
															'style' => 'font-size: 120%;'
														]);
									?>
									<?=Html::tag('li', Yii::t('backend', 'Una vez que el contribuyente haya realizado una solicitud de emision de licencia y la misma sea aprobada por el ente emisor.'),
														[
															'style' => 'font-size: 120%;'
														]);
									?>
									<?=Html::tag('h5', Yii::t('backend', 'Sugerencia'),
														[
															'style' => 'font-weight:bold;'
														]);
									?>
									<?=Html::tag('li', Yii::t('backend', 'Realice todas las solicitudes de actualización y/o modificación sobre los datos de la licencia que consideré pertinente y espere a que las mismas sean aprobadas para luego solicitar la emision de una licencia.'),
														[
															'style' => 'font-size: 120%;'
														]);
									?>

								</div>
							</div>
						</div>

					</div>

<!-- INFORMACION DEL CONTRIBUYENTE -->

					<div class="row" class="informacion-contribuyente" id="informacion-contribuyente">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Informacion del Contribuyente'))?></strong></h4>
						</div>

						<div class="row">
							<div class="col-sm-2" style="width: 20%;padding: 0px; padding-top: 10px;">
								<div class="rif" style="margin-left: 0px;">
									<?= $form->field($model, 'rif')->textInput([
																				'id' => 'id-rif',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['rif'],
																				'readOnly' => true,

																		])->label('RIF') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 55%;padding: 0px; padding-top: 10px;padding-left: 5px;">
								<div class="razon-social" style="margin-left: 0px;">
									<?= $form->field($model, 'razon_social')->textInput([
																				'id' => 'id-razon-social',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['descripcion'],
																				'readOnly' => true,

																		])->label('Razon Social') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px; padding-top: 10px;padding-left: 5px;">
								<div class="capital" style="margin-left: 0px;">
									<?= $form->field($model, 'capital')->textInput([
																				'id' => 'id-capital',
																				'style' => 'width:100%;
																				            background-color:white;
																				            text-align:right;',
																				'value' => Yii::$app->formatter->asDecimal($datosContribuyente['capital'], 2),
																				'readOnly' => true,

																		])->label('Capital') ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-2" style="width: 75%;padding: 0px;">
								<div class="domicilio" style="margin-left: 0px;">
									<?= $form->field($model, 'domicilio')->textInput([
																				'id' => 'id-rif',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['domicilio'],
																				'readOnly' => true,

																		])->label('Domicilio Fiscal') ?>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-sm-2" style="width: 20%;padding: 0px;">
								<div class="rif" style="margin-left: 0px;">
									<?= $form->field($model, 'catastro')->textInput([
																				'id' => 'id-catastro',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosContribuyente['catastro']) ? $datosContribuyente['catastro'] : 0,
																				'readOnly' => true,

																		])->label('Catastro') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 55%;padding: 0px;padding-left: 5px;">
								<div class="representante" style="margin-left: 0px;">
									<?= $form->field($model, 'representante')->textInput([
																				'id' => 'id-representante',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['representante'],
																				'readOnly' => true,

																		])->label('Representante Legal') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;">
								<div class="cedula-representante" style="margin-left: 0px;">
									<?= $form->field($model, 'cedulaRep')->textInput([
																				'id' => 'id-cedula-representante',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['cedulaRep'],
																				'readOnly' => true,

																		])->label('Cedula Representante') ?>
								</div>
							</div>
						</div>
					</div>


<!-- INFORMACION Y VIGENCIA DE LA LICENCIA -->

					<div class="row" class="informacion-licencia" id="informacion-licencia">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Identificacion y Vigencia de la Licencia'))?></strong></h4>
						</div>

						<div class="row">
							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-top: 10px;">
								<div class="id-contribuyente" style="margin-left: 0px;">
									<?= $form->field($model, 'id_contribuyente')->textInput([
																				'id' => 'id-contribuyente',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosContribuyente['id_contribuyente'],
																				'readOnly' => true,

																		])->label('ID Contribuyente') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="licencia" style="margin-left: 0px;">
									<?= $form->field($model, 'licencia')->textInput([
																				'id' => 'id-licencia',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $model->licencia,
																				'readOnly' => true,

																		])->label('Nro. Licencia') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="fecha-emision" style="margin-left: 0px;">
									<?= $form->field($model, 'fecha_emision')->textInput([
																				'id' => 'id-fecha_emision',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosContribuyente['fechaEmision']) ? $datosContribuyente['fechaEmision'] : '',
																				'readOnly' => true,

																		])->label('Fecha Emisión') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="fecha-vcto" style="margin-left: 0px;">
									<?= $form->field($model, 'fecha_vcto')->textInput([
																				'id' => 'id-fecha_vcto',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosContribuyente['fechaVcto']) ? $datosContribuyente['fechaVcto'] : '',
																				'readOnly' => true,

																		])->label('Fecha Vcto') ?>
								</div>
							</div>

						</div>
					</div>

<!-- INFORMACION DE LOS RUBROS AUTORIZADOS -->

					<div class="row" class="informacion-rubro" id="informacion-rubro">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Rubros Autorizados'))?></strong></h4>
						</div>

						<div class="row" style="width: 100%;">
							<?php
								$provider = New ArrayDataProvider([
									'allModels' => $datosRubro,
									'pagination' => false,
								]);

							?>
							<?= GridView::widget([
								'id' => 'id-grid-rubro-registrado',
								'dataProvider' => $provider,
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
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'rubro'),
				                        'value' => function($data) {
				                                   		return $data['rubro'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'descripcion'),
				                        'value' => function($data) {
				                                   		return $data['descripcion'];
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Alicuota'),
				                        'value' => function($data) {
				                        				return Yii::$app->formatter->asDecimal($data['alicuota'], 2);
				            			           },
				                    ],

				                   	[
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'Minimo'),
				                        'value' => function($data) {
				                        				return Yii::$app->formatter->asDecimal($data['minimo'], 2);
				            			           },
				                    ],
					        	]
							]);?>
						</div>

					</div>



					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<?php
						$disabled = '';
						$target = '_blank';

						if ( $bloquearDescarga ) {
							$disabled = ' disabled';
							$target = '';
						}
					 ?>

					<?php if ( $bloquearDescarga ) { ?>
						<div class="row" style="padding: 0px;margin: 0px;width: 100%;margin-top: 20px;">
							<div class="col-sm-5" style="width:70%;">
								<div class="well well-sm">
									<?=Html::tag('h4', Yii::t('backend', 'AVISO. NO SE PODRA DESCARGAR LA LICENCIA.'),
														[
															'style' => 'color:red;font-weight:bold;'
														]);
									?>
									<p style="font-size: 120%;"><?=Html::encode($mensajeBloqueo); ?></p>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

						<div class="col-sm-3" style="width:30%;padding: 0px;">
							<div class="form-group">
								<?= Html::a(Yii::t('frontend', Yii::t('frontend', 'Descargar Licencia')),
																				['generar-licencia-pdf', 'id' => $model->nro_control],
																			  	[
																					'id' => 'btn-generar-pdf',
																					'class' => 'btn btn-success' . $disabled,
																					'value' => 4,
																					'style' => 'width: 100%;',
																					'name' => 'btn-generar-pdf',
																					'target' => $target,

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


