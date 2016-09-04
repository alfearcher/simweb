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
 *  @date 09-08-2016
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
					'id' => 'grid-contribuyente-asociado',
					'dataProvider' => $dataProvider,
					//'filterModel' => $model,
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
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
		                    'label' => Yii::t('frontend', 'Id. Taxpayer'),
		                    'value' => function($model) {
    										return $model->id_contribuyente;
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'Ant Company Name'),
		                    'value' => function($model) {
    										return $model->razon_social_v;
										},
		                ],
		                [
		                    'label' => Yii::t('frontend', 'New Company Name'),
		                    'value' => function($model) {
    										return $model->razon_social_new;
										},
		                ],
		                [
		                	//'attribute' => 'sucursal.razon_social',
		                   	'label' =>Yii::t('frontend', 'Addrres Office'),
		                    'value' => function($model) {
    										return $model->sucursal->domicilio_fiscal;
										},
		                ],
		                [
		                	//'attribute' => 'sucursal.id_sim',
		                    'label' => Yii::t('frontend', 'License'),
		                    'value' => function($model) {
    										return $model->sucursal->id_sim;
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