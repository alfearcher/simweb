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
 *  @file datos-recibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view datos-recibo
 *  @brief vista principal para mostrar los datos del recibo y las planillas
 *  contenidas en el mismo.
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>


<div class="row" style="width:100%;padding:0px;">
	<div class="row" style="width:100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;">
		<h4><strong><?=Html::encode(Yii::t('frontend', 'Datos Básicos del Recibo'))?></strong></h4>
	</div>

	<div class="row" style="width:100%;">
		<?= GridView::widget([
			'id' => 'id-grid-recibo',
			'dataProvider' => $dataProviderRecibo,
			'headerRowOptions' => ['class' => 'success'],
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				[
	                'label' => Yii::t('backend', 'Nro. Recibo'),
	                'value' => function($model) {
									return $model->recibo;
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Fecha'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;',
	                ],
	                'value' => function($model) {
									return date('d-m-Y',strtotime($model->fecha));
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Contribuyente'),
	                'contentOptions' => [
	                	'style' => 'text-align:right;',
	                ],
	                'value' => function($model) {
									return $model->id_contribuyente;
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Monto'),
	                'contentOptions' => [
	                	'style' => 'text-align:right;font-weight:bold;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model->monto, 2);
								},
	            ],
	            [
	                'label' => Yii::t('frontend', 'Condition'),
	                'value' => function($model) {
									return $model->condicion->descripcion;
								},
	        	],
	        	[
	                'label' => Yii::t('frontend', 'Creador'),
	                'value' => function($model) {
									return $model->usuario_creador;
								},
	        	],
	    	]
		]);?>
	</div>

	<div class="row" style="width: 100%;">
		<div class="col-sm-4" style="width: 65%;font-size: 120%;font-weight: bold;">
			<?=Html::encode(Yii::t('backend', 'Total por Recibo: '));?>
		</div>
		<div class="col-sm-3" style="width: 30%;float: right;">
			<?=Html::textInput('total-recibo',
							   (isset($totales[0]) ? Yii::$app->formatter->asDecimal($totales[0],2) : 0),
							   [
							   		'class' => 'form-control',
							   		'style' => 'font-size:120%;
							   			        font-weight:bold;
							   			        text-align:right;
							   			        background-color:white;',
							   	    'readOnly' => true,
							   ])
			;?>
		</div>
	</div>

	<div class="row" style="width:100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;margin-top: 15px;">
		<h4><strong><?=Html::encode(Yii::t('frontend', 'Planillas Relacionadas al Recibo'))?></strong></h4>
	</div>

	<div class="row" style="width:100%;">
		<?= GridView::widget([
			'id' => 'id-grid-recibo-planilla',
			'dataProvider' => $dataProviderReciboPlanilla,
			'headerRowOptions' => ['class' => 'warning'],
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				[
	                'label' => Yii::t('backend', 'Nro. Recibo'),
	                'value' => function($model) {
									return $model->recibo;
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Planilla'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;',
	                ],
	                'format' => 'raw',
	                'value' => function($data) {
									//return $model->planilla;
									return Html::a($data['planilla'], '#', [
															'id' => 'link-view-planilla',
												            //'class' => 'btn btn-success',
												            'data-toggle' => 'modal',
												            'data-target' => '#modal',
												            'data-url' => Url::to(['view-planilla', 'p' => $data['planilla']]),
												            'data-planilla' => $data['planilla'],
												            'data-pjax' => '0',
												        ]);
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Impuesto'),
	                'contentOptions' => [
	                	'style' => 'text-align:center;',
	                ],
	                'value' => function($model) {
									return $model->impuesto;
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Contribuyente'),
	                'contentOptions' => [
	                	//'style' => 'text-align:right;',
	                ],
	                'value' => function($model) {
									return $model->descripcion;
								},
	            ],
	            [
	                'label' => Yii::t('backend', 'Monto'),
	                'contentOptions' => [
	                	'style' => 'text-align:right;font-weight:bold;',
	                ],
	                'value' => function($model) {
									return Yii::$app->formatter->asDecimal($model->monto, 2);
								},
	            ],
	            [
	                'label' => Yii::t('frontend', 'Condition'),
	                'value' => function($model) {
									return $model->condicion->descripcion;
								},
	        	],

	    	]
		]);?>
	</div>

	<div class="row" style="width: 100%;">
		<div class="col-sm-4" style="width: 65%;font-size: 120%;font-weight: bold;">
			<?=Html::encode(Yii::t('backend', 'Total por Planilla: '));?>
		</div>
		<div class="col-sm-3" style="width: 30%;float: right;">
			<?=Html::textInput('total-recibo',
							   (isset($totales[1]) ? Yii::$app->formatter->asDecimal($totales[1],2) : 0),
							   [
							   		'class' => 'form-control',
							   		'style' => 'font-size:120%;
							   			        font-weight:bold;
							   			        text-align:right;
							   			        background-color:white;',
							   	    'readOnly' => true,
							   ])
			;?>
		</div>
	</div>

	<div class="row" style="width:100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
	</div>
</div>


<?php
$this->registerJs(
    '$(document).on("click", "#link-view-planilla", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".planilla").html(data);
                $("#modal").modal();
            }
        );
    }));'
); ?>

<style type="text/css">
	.modal-content	{
			margin-top: 150px;
			margin-left: -180px;
			width: 150%;
	}
</style>

<?php
Modal::begin([
    'id' => 'modal',
    //'header' => '<h4 class="modal-title">Complete</h4>',
    'size' => 'modal-lg',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Cerrar</a>',
]);

//echo "<div class='well'></div>";
Pjax::begin();
echo "<div class='planilla'></div>";
Pjax::end();
Modal::end();
?>