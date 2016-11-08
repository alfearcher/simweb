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
 *  @file lista-impuesto-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view lista-impuesto-solicitud.php
 *  @brief vista del formualario
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

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>

<div class="lista-solicitudes">

	<?php
		$form = ActiveForm::begin([
			'id' => 'lista-impuesto-solicitud',
		    'method' => 'post',
		    //'action' => Url::toRoute(['funcionario/solicitud/funcionario-solicitud/verificar-envio']),
			//'enableClientValidation' => true,
			//'enableAjaxValidation' => true,
			//'enableClientScript' => true,
		]);
	?>

	<?= GridView::widget([
			'id' => 'id-lista-impuesto-solicitud',
			'dataProvider' => $dataProvider,
			//'filterModel' => $model,
			'caption' => $caption,  //Yii::t('backend', 'List of Request'),
			'headerRowOptions' => ['class' => 'info'],
			'rowOptions' => function($data) {
								if ( $data->inactivo == 1 ) {
										return ['class' => 'danger'];
								}
							},
			'summary' => '',
			'columns' => [
				[
					'class' => 'yii\grid\CheckboxColumn',
					'name' => 'chk-solicitud',
					'multiple' => true,
					// 'checkboxOptions' => function($modelSolicitud, $key, $index, $column) {
					// 						if ( $modelSolicitud->inactivo == 1 ) {
					// 								return ['enabled' => false, 'readonly' => true];
					// 							}
					// 						},
				],
				[
					'label' => Yii::t('backend', 'Request'),
					'value' => function($modelSolicitud) {
						return $modelSolicitud->id_tipo_solicitud;
					}
				],
				[
					'label' => Yii::t('backend', 'Description'),
					'value' => function($modelSolicitud) {
						return $modelSolicitud->descripcion;
					}
				],
				[
					'label' => Yii::t('backend', 'Tax'),
					'value' => function($modelSolicitud) {
						return $modelSolicitud->impuestos['descripcion'];
					}
				],
			]
		]);
	?>

	<?php ActiveForm::end(); ?>
</div>

