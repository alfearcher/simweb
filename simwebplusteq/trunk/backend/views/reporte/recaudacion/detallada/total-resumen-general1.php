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
 *  @file total-resumen-general.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view total-resumen-general.php
 *  @brief vista que muestra el resumen general de la recaudacion.
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
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\grid\GridView;


?>
<div class="total-resumen-general">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-total-resumen-general',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<div class="row" style="width:100%;padding:0px;margin:0px;">
		<?= GridView::widget([
			'id' => 'id-grid-total-recaudacion-detallada',
			'dataProvider' => $dataProvider,
			'headerRowOptions' => ['class' => 'default'],
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

	            [
	                'label' => Yii::t('backend', 'Concepto'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;',
	                ],
	                'value' => function($model) {
									return $model['concepto'] ;
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'impuesto'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model['monto'], 2);
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Recargos'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model['recargo'], 2);
								},
	            ],

	       		[
	                'label' => Yii::t('backend', 'Interes'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model['interes'], 2);
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Descuentos'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model['descuento'], 2);
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Recon/Ret.'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model['monto_reconocimiento'], 2);
								},
	            ],

	            [
	                'label' => Yii::t('backend', 'Total'),
	                'contentOptions' => [
	                	'style' => 'font-size:90%;text-align:right;font-weight: bold;',
	                ],
	                'value' => function($model, $subTotal) {
	                				$subTotal = ( $model['monto'] + $model['recargo'] + $model['interes'] ) - ( $model['descuento'] + $model['monto_reconocimiento']);
									return Yii::$app->formatter->asDecimal($subTotal, 2);
								},
	            ],

	    	]
		]);?>
	</div>

	<?php ActiveForm::end(); ?>
</div>


