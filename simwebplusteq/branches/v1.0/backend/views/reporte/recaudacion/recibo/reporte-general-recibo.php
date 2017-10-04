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
 *  @file reporte-general-recibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-10-2017
 *
 *  @view reporte-general-recibo
 *  @brief vista que muestra la consulta realizada sobre los recibos de pago,
 *  se muestra en formato de lista.
 *
 */

 	use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

?>

<div class="reporte-general-recibo">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-reporte-general-recibo',
 			'method' => 'post',
 			//'action'=> '#',
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 95%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

					<div class="row" style="width: 100%;margin-top: 10px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode($caption)?></h4>
						</div>

						<div class="row" id="id-reporte-general-recibo">
							<?= GridView::widget([
								'id' => 'grid-reporte-general-recibo',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'primary',
								],
								'rowOptions' => function($model) {
													if ( $model['estatus'] == 9 ) {
														return ['class' => 'danger'];
													} elseif ( $model['estatus'] == 1 ) {
														return ['class' => 'success'];
													}
												},
								'tableOptions' => [
                    				'class' => 'table table-hover',
              					],
								//'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
				                    [
					                	'contentOptions' => [
					                    	'style' => 'font-size: 90%;text-align:center;width:12%;',
					                	],
					                	'format' => 'raw',
					                	'label' => Yii::t('backend', 'Recibo'),
				                    	'value' => function($model) {
				                                   		//return $model['recibo'];
				                                   		return Html::a($model['recibo'], '#', [
                    											'title' => $model['recibo'] . ' - ' . $model['observacion'],
																'id' => 'link-recibo',
																'data-toggle' => 'modal',
																'data-target' => '#modal',
																'data-url' => Url::to(['view-recibo-modal',
																					   'nro' => $model['recibo'],
																					]),
																'data-pjax' => 0,
    													]);
				            			           },
				                	],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;width:10%;',
				                        ],
				                        'label' => Yii::t('backend', 'Fecha'),
				                        'value' => function($model) {
				                                   		return date('d-m-Y', strtotime($model['fecha']));
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('backend', 'Monto'),
				                        'value' => function($model) {
				                                    	return Yii::$app->formatter->asDecimal($model['monto'], 2);
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => $model->getAttributeLabel('usuario_creador'),
				                        'value' => function($model) {
				                                    	return $model['usuario_creador'];
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => $model->getAttributeLabel('usuario'),
				                        'value' => function($model) {
				                                    	return $model['usuario'];
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => $model->getAttributeLabel('fecha_hora_proceso'),
				                        'value' => function($model) {
				                                    	return date('d-m-Y h:i:s', strtotime($model['fecha_hora_proceso']));
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;;',
				                        ],
				                        'label' => Yii::t('backend', 'ID'),
				                        'format' => 'raw',
				                        'value' => function($model) {
				                                   		//return $model['id_contribuyente'];
				                                   		return Html::a($model['id_contribuyente'], '#', [
                																'id' => 'link-id-contribuyente',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-contribuyente-modal',
                																						'id' => $model['id_contribuyente']]),
                																'data-pjax' => 0,
                													]);
													},
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;',
				                        ],
				                        'label' => Yii::t('backend', 'condicion'),
				                        'value' => function($model) {
				                                   		return $model->condicion->descripcion;
				            			           },
				                    ],

					        	]
							]);?>

						</div>
					</div>

					<?php
						foreach ( $listaEstatus as $key => $value ) {
							$totalRecibo[$value] = 0;
						}

						$models = $dataProvider->getModels();
						foreach ( $models as $deposito ) {
							$register = $deposito->toArray();
							foreach ( $listaEstatus as $key => $value ) {
								if ( (int)$register['estatus'] == (int)$key ) {
									$totalRecibo[$value] += (float)$register['monto'];
								}
							}
						}
					?>

					<div class="row" style="width: 100%;">
						<div class="col-sm-2" style="width: 45%;">
							<h4><strong><?=Html::label(Yii::t('backend', 'TOTAL POR RECIBO PAGADO'))?></strong></h4>
						</div>
						<div class="col-sm-2" style="width: 30%;">
							<?=Html::textInput('total-recibo-pagado',
												Yii::$app->formatter->asDecimal($totalRecibo['PAGADO'], 2),
												[
													'class' => 'form-control',
													'style' => 'width:100%;
																font-size:140%;
																font-weight: bold;
																text-align:right;',
													'readOnly' => true,
												])
							?>
						</div>
					</div>

					<div class="row" style="width: 100%;">
						<div class="col-sm-2" style="width: 45%;">
							<h4><strong><?=Html::label(Yii::t('backend', 'TOTAL POR RECIBO PENDIENTE'))?></strong></h4>
						</div>
						<div class="col-sm-2" style="width: 30%;">
							<?=Html::textInput('total-recibo-pendiente',
												Yii::$app->formatter->asDecimal($totalRecibo['PENDIENTE'], 2),
												[
													'class' => 'form-control',
													'style' => 'width:100%;
																font-size:140%;
																font-weight: bold;
																text-align:right;',
													'readOnly' => true,
												])
							?>
						</div>
					</div>

					<div class="row" style="width: 100%;">
						<div class="col-sm-2" style="width: 45%;">
							<h4><strong><?=Html::label(Yii::t('backend', 'TOTAL POR RECIBO ANULADO'))?></strong></h4>
						</div>
						<div class="col-sm-2" style="width: 30%;">
							<?=Html::textInput('total-recibo-anulado',
												Yii::$app->formatter->asDecimal($totalRecibo['ANULADO'], 2),
												[
													'class' => 'form-control',
													'style' => 'width:100%;
																font-size:140%;
																font-weight: bold;
																text-align:right;',
													'readOnly' => true,
												])
							?>
						</div>
					</div>


					<div class="row">
						<div class="col-sm-3" style="width:20%;padding-top: 15px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
																		[
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 100%',
																			'name' => 'btn-quit',
																		])
								?>
							</div>
						</div>

						<div class="col-sm-3" style="width:20%;padding-top: 15px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Back'),
																		[
																			'id' => 'btn-back',
																			'class' => 'btn btn-danger',
																			'value' => 2,
																			'style' => 'width: 100%',
																			'name' => 'btn-back',
																		])
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>



<?php
$this->registerJs(
    '$(document).on("click", "#link-recibo", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                $(".detalle").html(data);
                $("#modal").modal();
            }
        );
    }));
    '
);

$this->registerJs(
    '$(document).on("click", "#link-id-contribuyente", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle").html(data);
                $("#modal").modal();
            }
        );
    }));
    '
); ?>


<style type="text/css">
	.modal-content	{
			margin-top: 110px;
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
echo "<div class='detalle'></div>";
Pjax::end();
Modal::end();
?>