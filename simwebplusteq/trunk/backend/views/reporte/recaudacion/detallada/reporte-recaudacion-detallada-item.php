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
 *  @file reporte-recaudacion-detallada-item.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view reporte-recaudacion-detallada-item.php
 *  @brief vista que muestra el resultado de la consulta de la recaudacion
 *  detallada de un codigo presupuestario de deuda morosa o deuda actual..
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
	use yii\jui\DatePicker;
	use yii\grid\GridView;


?>
<div class="reporte-recaudacion-detallada-item">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-reporte-recaudacion-detallada-item',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);

		$descripcionLapso = ( $lapso == 1 ) ? Yii::t('backend', 'AÑO ACTUAL') : Yii::t('backend', 'AÑOS ANTERIORES');
		$descripcionTotalCodigo = Yii::t('backend', 'Impuesto - ( Descuento + Recon/Ret. )');
	?>

	<div class="row" style="width: 100%;margin-top:15px;">
		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
			<div class="col-sm-2" style="width: 80%;">
				<h4><strong><?=Html::encode($model[0]['codigo'] . ' ' . $model[0]['nombre_impuesto'] . ' - ' . $descripcionLapso)?></strong></h4>
			</div>
		</div>
		<div class="row" class=<?=Html::encode($model[0]['codigo']);?>></div>
		<div class="row" style="width:100%;padding:0px;margin:0px;">
			<?= GridView::widget([
				'id' => 'id-grid-recaudacion-detallada-item-reporte',
				'dataProvider' => $dataProvider,
				'headerRowOptions' => ['class' => 'success'],
				'showFooter' => true,
				'footerRowOptions' => [
					'style' => 'font-size:100%;font-weight: bold;text-align:right;',
					'class' => 'danger',
				],
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],

		            [
		                'label' => Yii::t('backend', 'Ced/Rif'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'value' => function($model) {
										return $model['id'] ;
									},
		            ],

		            [
		                'label' => Yii::t('backend', 'Id'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'value' => function($model) {
		                				return $model['id_contribuyente'];
									},
		            ],

		            [
		                'label' => Yii::t('backend', 'Planilla'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'value' => function($model) {
		                				return $model['planilla'];
									},
		            ],

		            [
		                'label' => Yii::t('backend', 'Recibo'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'format' => 'raw',
		                'value' => function($model) {
		                				return $model['recibo'];
									},
		            ],

		            [
		                'label' => Yii::t('backend', 'Fecha pago'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'value' => function($model) {
										return date('d-m-Y', strtotime($model['fecha_pago']));
									},
		            ],

		            [
		                'label' => Yii::t('backend', 'Descripción'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;',
		                ],
		                'value' => function($model) {
										return $model['detalle_mov'];
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
						'footer' => Yii::$app->formatter->asDecimal($totalizar['monto'], 2),
		            ],

		            [
		                'label' => Yii::t('backend', 'Recargos'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;text-align:right;',
		                ],
		                'value' => function($model) {
										return Yii::$app->formatter->asDecimal($model['recargo'], 2);
									},
						'footer' => Yii::$app->formatter->asDecimal($totalizar['recargo'], 2),
		            ],

		       		[
		                'label' => Yii::t('backend', 'Interes'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;text-align:right;',
		                ],
		                'value' => function($model) {
										return Yii::$app->formatter->asDecimal($model['interes'], 2);
									},
						'footer' => Yii::$app->formatter->asDecimal($totalizar['interes'], 2),
		            ],

		            [
		                'label' => Yii::t('backend', 'Descuentos'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;text-align:right;',
		                ],
		                'value' => function($model) {
										return Yii::$app->formatter->asDecimal($model['descuento'], 2);
									},
						'footer' => Yii::$app->formatter->asDecimal($totalizar['descuento'], 2),
		            ],

		            [
		                'label' => Yii::t('backend', 'Recon/Ret.'),
		                'contentOptions' => [
		                	'style' => 'font-size:90%;text-align:right;',
		                ],
		                'value' => function($model) {
										return Yii::$app->formatter->asDecimal($model['monto_reconocimiento'], 2);
									},
						'footer' => Yii::$app->formatter->asDecimal($totalizar['monto_reconocimiento'], 2),
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
						'footer' => Yii::$app->formatter->asDecimal($totalizar['monto'] + $totalizar['recargo'] + $totalizar['interes'] - ( $totalizar['descuento'] + $totalizar['monto_reconocimiento'] ), 2),
		            ],

		    	]
			]);?>
		</div>

		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;margin-top: -20px;">
			<div class="col-sm-2" style="width: 60%;">
				<strong><?=Html::encode(Yii::t('backend', 'Total por: ') . $model[0]['codigo'] . ' ' . $model[0]['nombre_impuesto'] . ' - ' . $descripcionLapso)?></strong>
			</div>
			<div class="col-sm-2" style="width: 35%;text-align: right;">
				<strong><?=Html::encode($descripcionTotalCodigo) . ': '. Yii::$app->formatter->asDecimal($totalCodigo, 2);?></strong>
			</div>
		</div>
	</div>

	<?php ActiveForm::end(); ?>
</div>


