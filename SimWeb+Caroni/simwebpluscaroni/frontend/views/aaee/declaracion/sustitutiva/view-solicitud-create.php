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
 *  @file view-solicitud-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-10-2016
 *
 *  @view view-solicitud-create.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use yii\widgets\DetailView;
	use yii\grid\GridView;
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="view-autorizar-ramo-creada">
	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-4">
	        		<h3><?= Html::encode(Yii::t('frontend', 'Request Nro. ' . $model[0]->nro_solicitud) . '. ' . Yii::t('backend', 'Summary')  ) ?></h3>
	        	</div>
	        	<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
	        		<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #337AB7;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario($opciones); ?>
	        	</div>
        	</div>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" style="width: 99%;padding-left: -5px;">

					<div class="row" style="margin: 0px;padding-left: -5px; width: 100%;">
						<?= GridView::widget([
								'id' => 'grid-sustitutiva',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'danger'],
								//'filterModel' => $searchModel,
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
					                    'label' => Yii::t('frontend', 'Request'),
					                    'value' => function($model) {
                										return $model->nro_solicitud;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Declaracion'),
					                    'value' => function($model) {
                										return $model->tipoDeclaracion->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Año'),
					                    'value' => function($model) {
                										return $model->actEcon->ano_impositivo;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Periodo'),
					                    'value' => function($model) {
                										return $model->exigibilidad_periodo;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Rubro'),
					                    'value' => function($model) {
                										return $model->rubro->rubro;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Rubro/Descrip'),
					                    'value' => function($model) {
                										return $model->rubro->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Monto Anterior'),
					                    'contentOptions' => [
						                    'style' => 'text-align: right',
						                ],
					                    'value' => function($model) {
					                    				if ( $model->tipo_declaracion == 1 ) {
					                    					return Yii::$app->formatter->asDecimal($model->estimado, 2);
					                    				} elseif ( $model->tipo_declaracion == 2 ) {
					                    					return Yii::$app->formatter->asDecimal($model->reales, 2);
					                    				}
        											},
					                ],
					            	[
					                    'label' => Yii::t('frontend', 'Sustitutiva'),
					                    'contentOptions' => [
						                    'style' => 'text-align: right',
						                ],
					                    'value' => function($model) {
                										return Yii::$app->formatter->asDecimal($model->sustitutiva, 2);
        											},
					                ],

					                [
					                    'label' => Yii::t('frontend', 'Condition'),
					                    'value' => function($model) {
                										return $model->estatusSolicitud->descripcion;
        											},
				                	],
					        	]
							]);?>
					</div>

					<?php
						$sumaEstimada = 0;
						$sumaDefinitiva = 0;
						$sumaSustitutiva = 0;

						foreach ( $dataProvider->getModels() as $model ) {
							$sumaEstimada = $sumaEstimada + $model->estimado;
							$sumaDefinitiva = $sumaDefinitiva + $model->reales;
							$sumaSustitutiva = $sumaSustitutiva + $model->sustitutiva;
						}
					 ?>



					<div class="row" style="padding: 0px;width: 100%;">
						<div class="row" style="padding: 0px;width: 100%;">
							<div class="col-sm-2" style="padding: 0px;width: 45%;">
								<?=Html::tag('h3', 'Total de las declaraciones:', [
																'style' => 'width:85%;padding:0px;',
								]);?>
							</div>
						</div>

						<?php if ( $lapso['tipo'] == 1 ) { ?>
							<div class="row" style="padding: 0px;width: 100%;">
								<div class="col-sm-2" style="padding: 0px;width: 35%;">
									<?=Html::tag('h4', 'Total por Estimado:', [
																	'style' => 'width:60%;padding:0px;',
									]);?>
								</div>
								<div class="col-sm-2" style="padding: 0px;width: 25%;">
									<?=Html::textInput('sumaEstimada', Yii::$app->formatter->asDecimal($sumaEstimada, 2), [
																						'class' => 'form-control',
																						'readOnly' => true,
																						'style' => 'text-align: right;font-size:120%;font-weight:bold;',
														]);
									?>
								</div>
							</div>
						<?php } ?>

						<?php if ( $lapso['tipo'] == 2 ) { ?>
							<div class="row" style="padding: 0px;width: 100%;">
								<div class="col-sm-2" style="padding: 0px;width: 35%;">
									<?=Html::tag('h4', 'Total por Definitiva:', [
																	'style' => 'width:60%;padding:0px;',
									]);?>
								</div>
								<div class="col-sm-2" style="padding: 0px;width: 25%;">
									<?=Html::textInput('sumaDefinitiva', Yii::$app->formatter->asDecimal($sumaDefinitiva, 2), [
																						'class' => 'form-control',
																						'readOnly' => true,
																						'style' => 'text-align: right;font-size:120%;font-weight:bold;',
														]);
									?>
								</div>
							</div>
						<?php } ?>


						<div class="row" style="padding: 0px;width: 100%;">
							<div class="col-sm-2" style="padding: 0px;width: 35%;">
								<?=Html::tag('h4', 'Total por Sustitutiva:', [
																'style' => 'width:60%;padding:0px;',
								]);?>
							</div>
							<div class="col-sm-2" style="padding: 0px;width: 25%;">
								<?=Html::textInput('sumaSustitutiva', Yii::$app->formatter->asDecimal($sumaSustitutiva, 2), [
																					'class' => 'form-control',
																					'readOnly' => true,
																					'style' => 'text-align: right;font-size:120%;font-weight:bold;',
													]);
								?>
							</div>
						</div>
					</div>

					<div class="row" style="border-top: 1px solid #ccc; padding-bottom: 10px;"></div>

					<div class="row">
						<div class="form-group">

							<?php if ( count($historico) > 0 ) { ?>
								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Declaracion ' . $historico[0]['serial_control']),
																				['generar-comprobante', 'id' => $historico[0]['id_historico']],
																			  	[
																					'id' => 'btn-declaracion',
																					'class' => 'btn btn-default',
																					'value' => 3,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-declaracion',
																					'target' => '_blank',

																			  	]);
									?>
								</div>


								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Certificado ' . $historico[0]['serial_control']),
																				['generar-certificado', 'id' => $historico[0]['id_historico']],
																			  	[
																					'id' => 'btn-certificado',
																					'class' => 'btn btn-primary',
																					'value' => 2,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-certificado',
																					'target' => '_blank',

																			  	]);
									?>
								</div>

								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Boletin. ' . $lapso['descripcion'] . ' ' . $lapso['a'] . ' - ' . $lapso['p']),
																				[$urlBoletin],
																			  	[
																					'id' => 'btn-boletin',
																					'class' => 'btn btn-primary',
																					'value' => 1,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-boletin',
																					'target' => '_blank',

																			  	]);
									?>
								</div>
							<?php } ?>
						</div>
					</div>

				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->
</div>	 <!-- Fin de inscripcion-sucursal-create -->
