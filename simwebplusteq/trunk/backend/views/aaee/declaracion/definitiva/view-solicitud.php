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
					'id' => 'grid-declaracion-definitiva',
					'dataProvider' => $dataProvider,
					//'filterModel' => $model,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						[
		                    'label' => Yii::t('frontend', 'Request'),
		                    'value' => function($model) {
    										return $model->nro_solicitud;
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Request Description'),
		                    'value' => function($model) {
    										return $model->getDescripcionTipoSolicitud($model->nro_solicitud);
										},
	                	],
		            	[
		                    'label' => Yii::t('frontend', 'Year'),
		                    'value' => function($model) {
    										return $model->ano_impositivo;
										},
		                ],
		            	[
		                    'label' => Yii::t('frontend', 'Category'),
		                    'value' => function($model) {
    										return $model->rubro->rubro;
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Descripcion'),
		                    'value' => function($model) {
    										return $model->rubro->descripcion;
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Monto Declaracion'),
		                    'contentOptions' => [
		                    	'style' => 'text-align: right',
		                    ],
		                    'value' => function($model) {
    										return Yii::$app->formatter->asDecimal($model->monto_new, 2);
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Monto Anterior'),
		                    'contentOptions' => [
		                    	'style' => 'text-align: right',
		                    ],
		                    'value' => function($model) {
    										return Yii::$app->formatter->asDecimal($model->monto_v, 2);
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Monto Minimo'),
		                    'contentOptions' => [
		                    	'style' => 'text-align: right',
		                    ],
		                    'value' => function($model) {
    										return Yii::$app->formatter->asDecimal($model->monto_minimo, 2);
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Condition'),
		                    'value' => function($model) {
    										return $model->estatusSolicitud->descripcion;
										},
	                	],
		        	]
				]);
			?>
		</div>

		<!-- Se obtiene una linea de la consulta general, primera linea -->
		<?php $model = $model->one();?>

		<div class="row" style="width: 80%;">
			<div class="col-sm-6" style="width: 40%;">
				<div class="row">
					<strong><h3><?=Html::encode('Declaracion IVA') ?></h3></strong>
				</div>
				<div class="row">
					<?= DetailView::widget([
							'model' => $model,
							'attributes' => [
								[
									'label' => Yii::t('backend', 'enero'),
									'value' => Yii::$app->formatter->asDecimal($model->iva_enero, 2),
								],
								[
									'label' => Yii::t('backend','febrero'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_febrero, 2),
								],
								[
									'label' => Yii::t('backend', 'marzo'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_marzo, 2),
								],
								[
									'label' => Yii::t('backend', 'abril'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_abril, 2),
								],
								[
									'label' => Yii::t('backend', 'mayo'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_mayo, 2),
								],
								[
									'label' => Yii::t('backend', 'junio'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_junio, 2),
								],
								[
									'label' => Yii::t('backend', 'julio'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_julio, 2),
								],
								[
									'label' => Yii::t('backend', 'agosto'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_agosto, 2),
								],
								[
									'label' => Yii::t('backend', 'septiembre'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_septiembre, 2),
								],
								[
									'label' => Yii::t('backend', 'octubre'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_octubre, 2),
								],
								[
									'label' => Yii::t('backend', 'noviembre'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_noviembre, 2),
								],
								[
									'label' => Yii::t('backend', 'diciembre'),
									'value' =>  Yii::$app->formatter->asDecimal($model->iva_diciembre, 2),
								],
								[
									'label' => Yii::t('backend', 'Total'),
									'value' => Yii::$app->formatter->asDecimal($model->iva_enero + $model->iva_febrero +
																			   $model->iva_marzo + $model->iva_abril +
																			   $model->iva_mayo + $model->iva_junio +
																			   $model->iva_julio + $model->iva_agosto +
																			   $model->iva_septiembre + $model->iva_octubre +
																			   $model->iva_noviembre + $model->iva_diciembre, 2),
								],

							],
						])
					?>
				</div>
			</div>

			<div class="col-sm-2" style="width: 2%;"> </div>

			<div class="col-sm-6" style="width: 45%;">
				<div class="row">
					<strong><h3><?=Html::encode('Declaracion ISLR y Otros') ?></h3></strong>
				</div>
				<div class="row">
					<?= DetailView::widget([
							'model' => $model,
							'attributes' => [
								[
									'label' => Yii::t('backend', 'islr'),
									'value' => Yii::$app->formatter->asDecimal($model->islr, 2),
								],
								[
									'label' => Yii::t('backend','Pagos por Industria'),
									'value' => Yii::$app->formatter->asDecimal($model->pp_industria, 2),
								],
								[
									'label' => Yii::t('backend', 'Pagos Retencion'),
									'value' => Yii::$app->formatter->asDecimal($model->pagos_retencion, 2),
								],
								[
									'label' => Yii::t('backend', 'Total'),
									'value' => Yii::$app->formatter->asDecimal($model->islr +
																			   $model->pp_industria +
																			   $model->pagos_retencion, 2),
								]
							],
						])
					?>
				</div>

			</div>
		</div>
	</div>
</div>