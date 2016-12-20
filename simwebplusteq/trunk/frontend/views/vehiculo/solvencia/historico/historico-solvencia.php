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

    $datosSolvencia = json_decode($model['fuente_json'], true);

 ?>


<div class="historico-solvencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-historico-solvencia',
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
        	<h3><?= Html::encode(Yii::t('frontend', 'Historico Solvencia Vehiculo')) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="col-sm-5" style="padding: 0px; width: 65%;">
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
						    					'label' => Yii::t('frontend', 'Observacion'),
						    					'value' => $model['observacion'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Fecha Emision'),
						    					'value' => $model['fecha_emision'],
						    				],
						    				[
						    					'label' => Yii::t('frontend', 'Fecha Vcto'),
						    					'value' => $model['fecha_vcto'],
						    				],
						    				// [
						    				// 	'label' => Yii::t('frontend', 'Ultimo pago cuando se realizo la solicitud'),
						    				// 	'value' => $searchSolicitud->ultimo_pago,
						    				// ],
						    				// [
						    				// 	'label' => Yii::t('frontend', 'condicion'),
						    				// 	'value' => $model['estatusSolicitud']['descripcion'],
						    				// ],
						    			],
									])
								?>
							</div>
						</div>

						<div class="col-sm-3" style="width: 20%;padding: 0px; padding-left: 25px;margin-left:60px;padding-top: 35px;">
							<div class="form-group">
								<?= Html::a(Yii::t('frontend', Yii::t('frontend', 'Descargar Solvencia')),
																				['generar-solvencia-pdf', 'id' => $model->nro_control],
																			  	[
																					'id' => 'btn-generar-pdf',
																					'class' => 'btn btn-success',
																					'value' => 4,
																					'style' => 'width: 100%;',
																					'name' => 'btn-generar-pdf',
																					'target' => '_blink',

																			  	])
								?>
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
																				'value' => $datosSolvencia['rif'],
																				'readOnly' => true,

																		])->label('Cedula o RIF') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 55%;padding: 0px; padding-top: 10px;padding-left: 5px;">
								<div class="razon-social" style="margin-left: 0px;">
									<?= $form->field($model, 'razon_social')->textInput([
																				'id' => 'id-razon-social',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosSolvencia['descripcion'],
																				'readOnly' => true,

																		])->label('Razon Social') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px; padding-top: 10px;padding-left: 5px;">
								<div class="id-contribuyente" style="margin-left: 0px;">
									<?= $form->field($model, 'id_contribuyente')->textInput([
																				'id' => 'id-contribuyente',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $datosSolvencia['id_contribuyente'],
																				'readOnly' => true,

																		])->label('ID Contribuyente') ?>
								</div>
							</div>
						</div>

					</div>


<!-- INFORMACION Y VIGENCIA DE LA LICENCIA -->

					<div class="row" class="informacion-solvencia" id="informacion-solvencia">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Identificacion y Vigencia de la Solvencia'))?></strong></h4>
						</div>

						<div class="row">

							<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="fecha-emision" style="margin-left: 0px;">
									<?= $form->field($model, 'fecha_emision')->textInput([
																				'id' => 'id-fecha_emision',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosSolvencia['fechaEmision']) ? $datosSolvencia['fechaEmision'] : '',
																				'readOnly' => true,

																		])->label('Fecha Emisión') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 15%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="fecha-vcto" style="margin-left: 0px;">
									<?= $form->field($model, 'fecha_vcto')->textInput([
																				'id' => 'id-fecha_vcto',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosSolvencia['fechaVcto']) ? $datosSolvencia['fechaVcto'] : '',
																				'readOnly' => true,

																		])->label('Fecha Vcto') ?>
								</div>
							</div>

						</div>
					</div>

<!-- INFORMACION GENERAL DEL IMPUESTO -->
					<div class="row" class="informacion-solvencia" id="informacion-solvencia">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Información General del Impuesto'))?></strong></h4>
						</div>

						<div class="row">
							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="tipo-impuesto" style="margin-left: 0px;">
									<?= $form->field($model, 'tipo_impuesto')->textInput([
																				'id' => 'id-tipo-impuesto',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosSolvencia['tipoImpuesto']) ? $datosSolvencia['tipoImpuesto'] : '',
																				'readOnly' => true,

																		])->label('Tipo de Impuesto') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="id-impuesto" style="margin-left: 0px;">
									<?= $form->field($model, 'id_impuesto')->textInput([
																				'id' => 'id-id-impuesto',
																				'style' => 'width:100%;background-color:white;',
																				'value' => $model['id_impuesto'],
																				'readOnly' => true,

																		])->label('ID Objeto') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="licencia-placa-catastro" style="margin-left: 0px;">
									<?= $form->field($model, 'placa')->textInput([
																				'id' => 'id-placa',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosSolvencia['placa']) ? $datosSolvencia['placa'] : 0,
																				'readOnly' => true,

																		])->label('Nro. Placa') ?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
								<div class="liquidacion" style="margin-left: 0px;">
									<?= $form->field($model, 'liquidacion')->textInput([
																				'id' => 'id-liquidacion',
																				'style' => 'width:100%;background-color:white;',
																				'value' => isset($datosSolvencia['liquidacion']) ? $datosSolvencia['liquidacion'] : 0,
																				'readOnly' => true,

																		])->label('Nro. Liquidacion') ?>
								</div>
							</div>

						</div>
					</div>

					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

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


