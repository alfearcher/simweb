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
 *  @file reporte-recaudacion-general-por-nivel-presupuestario.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-09-2017
 *
 *  @view reporte-recaudacion-general-por-nivel-presupuestario.php
 *  @brief vista que muestra el resultado de la recaudacion de los codigos
 *  presupuestarios que pertenecen a un nivel presupuestario especifico.
 *  Totalizando el monto de dicho grupo o nivel presupuestario.
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
<div class="reporte-recaudacion-general-por-nivel-presupuestario">
	<div class="row" style="width: 100%;margin-top:15px;">
		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
			<div class="col-sm-2" style="width: 80%;">
				<h4><strong><?=Html::encode($model[0]['descripcion_nivel'])?></strong></h4>
			</div>
		</div>
		<div class="row" style="width:100%;padding:0px;margin:0px;">
			<?= GridView::widget([
				'id' => 'id-grid-reporte-recaudacion-general-por-nivel-presupuestario',
				'dataProvider' => $dataProvider,
				'headerRowOptions' => ['class' => 'success'],
				'showFooter' => true,
				'footerRowOptions' => [
					'style' => 'font-size:120%;font-weight: bold;text-align:right;background-color:#BBDEFB',
					//'class' => 'primary',
				],
				'summary' => '',
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
		            [
		                'label' => Yii::t('backend', 'CODIGO'),
		                'contentOptions' => [
		                	'style' => 'font-size:100%;',
		                ],
		                'value' => function($model) {
		                				return $model['codigo'];
									},
		            ],
		            [
		                'label' => Yii::t('backend', 'DESCRIPCION'),
		                'contentOptions' => [
		                	'style' => 'font-size:100%;',
		                ],
		                'value' => function($model) {
										return $model['impuesto'];
									},
		            ],
		            [
		                'label' => Yii::t('backend', 'MONTO'),
		                'contentOptions' => [
		                	'style' => 'font-size:100%;text-align:right;',
		                ],
		                'value' => function($model) {
										return Yii::$app->formatter->asDecimal($model['monto'], 2);
									},
						'footer' => Yii::t('backend', 'SUB-TOTAL') . ':      ' . Yii::$app->formatter->asDecimal(array_sum(array_column($model, 'monto')), 2),
		            ],
		    	]
			]);?>
		</div>
	</div>
</div>


