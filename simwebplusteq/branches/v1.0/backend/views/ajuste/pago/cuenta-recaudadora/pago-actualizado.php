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
 *  @file listado-pago-encontrado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-01-2017
 *
 *  @view listado-pago-encontrado.php
 *  @brief vista.
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
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\grid\GridView;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\jui\DatePicker;
	use backend\controllers\menu\MenuController;

?>

<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
	<h4><strong><?= Yii::t('backend', 'Listado de Pagos Seleccionados')?></strong></h4>
</div>

<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
	<div class="row" style="width:100%;">
		<?= GridView::widget([
			'id' => 'id-grid-listado-pago-procesado',
			'dataProvider' => $dataProvider,
			'headerRowOptions' => ['class' => 'warning'],
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
	            [
	                'label' => Yii::t('backend', 'Recibo'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;font-size:90%;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return $model->recibo;
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Fecha Pago'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;font-size:90%;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return date('d-m-Y', strtotime($model->depositoRecibo->fecha));
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Forma de Pago'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return $model->formaPago->descripcion;
								},
					'visible' => false,
	            ],

	            [
	                'label' => Yii::t('backend', 'Monto'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model->depositoRecibo->monto, 2);
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Banco'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return isset($model->banco->nombre) ? $model->banco->nombre : '';
								},
	            ],
	            [
	                'label' => Yii::t('frontend', 'Cuenta Recaudadora'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;font-size:90%;',
	                ],
	                'format' => 'raw',
	                'value' => function($model) {
									return $model->cuenta_deposito;
								},
	        	],

	    	]
		]);?>
	</div>
</div>



