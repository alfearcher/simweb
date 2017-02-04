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
 *  @file view-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-10-2016
 *
 *  @view view-solicitud.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;
	use yii\grid\GridView;
?>

<div class="row">
	<div class="info-solicitud">
		<div class="row">
			<h3><?= Html::encode($caption) ?></h3>
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

			<?php $result = $model['tipo_declaracion']; ?>

			<?php if ( $result == 1 ) { ?>
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

			<?php if ( $result == 2 ) { ?>
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
	</div>
</div>