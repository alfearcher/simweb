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
					'id' => 'grid-declaracion-estimada',
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
	</div>
</div>