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
 *  @file licencia-emitida.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-06-2017
 *
 *  @view licencia-emitida.php
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

 ?>


<div class="licencia-emitida">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-licencia-emitida',
 			//'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
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
																				'value' => $model['licencia'],
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

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


