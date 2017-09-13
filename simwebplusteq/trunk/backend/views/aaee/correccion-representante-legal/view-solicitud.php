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
 *  @date 08-08-2016
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

<div class="row" style="width: 100%;">
	<div class="info-solicitud" style="width: 100%;">
		<div class="row" style="width: 100%;">
			<h3><?= Html::encode($caption) ?></h3>
				<?= GridView::widget([
					'id' => 'grid-contribuyente-asociado',
					'dataProvider' => $dataProvider,
					//'filterModel' => $model,
					'headerRowOptions' => [
						'style' => 'font-size: 90%;width:10%;',
					],
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
						[
							'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'Request'),
		                    'value' => function($model) {
    										return $model->nro_solicitud;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'Description'),
		                    'value' => function($model) {
    										return $model->getDescripcionTipoSolicitud($model->nro_solicitud);
										},
	                	],
		            	[
		            		'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'ID'),
		                    'value' => function($model) {
    										return $model->id_contribuyente;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'Ant DNI (Legal Rep.)'),
		                    'value' => function($model) {
    										return $model->naturaleza_rep_v . '-' . $model->cedula_rep_v;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'Ant (Legal Rep.)'),
		                    'value' => function($model) {
    										return $model->representante_v;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'New DNI (Legal Rep.)'),
		                    'value' => function($model) {
    										return $model->naturaleza_rep_new . '-' . $model->cedula_rep_new;
										},
		                ],
		               	[
		               		'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                    'label' => Yii::t('frontend', 'New (Legal Rep.)'),
		                    'value' => function($model) {
    										return $model->representante_new;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                	//'attribute' => 'sucursal.razon_social',
		                   	'label' =>Yii::t('frontend', 'Branch Office'),
		                    'value' => function($model) {
    										return $model->sucursal->razon_social;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
		                	//'attribute' => 'sucursal.id_sim',
		                    'label' => Yii::t('frontend', 'License'),
		                    'value' => function($model) {
    										return $model->sucursal->id_sim;
										},
		                ],
		                [
		                	'contentOptions' => [
	                        	'style' => 'font-size: 90%;width:10%;',
	                        ],
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