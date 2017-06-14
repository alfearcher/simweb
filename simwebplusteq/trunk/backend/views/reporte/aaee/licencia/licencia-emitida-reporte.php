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
 *  @file licencia-emitida-reporte.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-06-2017
 *
 *  @view licencia-emitida-reporte.php
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
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="licencia-emitida-reporte">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-licencia-emitida-reporte',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => true,
		]);
	?>


	<!-- <?//=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?=Html::encode($caption)?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?=Html::encode($subCaption)?></strong></h4>
					</div>

					<div class="row" style="width: 103%;padding: 0px;margin: 0px;">
						<div class="row" style="width:100%;">
							<?= GridView::widget([
								'id' => 'id-grid-licencia-emitida-reporte',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'warning'],
								'rowOptions' => function($data) {
									if ( $data['inactivo'] == 1 ) {
										return ['class' => 'danger'];
									}
								},
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									// [
				     //                    'class' => 'yii\grid\CheckboxColumn',
				     //                    'name' => 'chkPago',
				     //                    'multiple' => true,
				     //                    'checkboxOptions' => function ($model, $key, $index, $column) {
				     //            				return [
				     //            					//'onClick' => 'javascript: return false;',
			      //                       			//'checked' => true,
				     //            				];

				     //                    }
				     //                ],
						            [
						                'label' => Yii::t('backend', 'Nro. Solicitud'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														//return $model->nro_solicitud;
														return Html::a($model->nro_solicitud, '#', [
                																'id' => 'link-id-historico',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-pre-licencia-modal',
                																						'nro' => $model->nro_solicitud,
                																						'id' => $model->id_contribuyente,
                																						'historico' => $model->id_historico]),
                																'data-pjax' => 0,
                													]);
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Tipo'),
						                'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->tipo;
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Licencia Generada'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->licencia;
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Emision'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														$fuente = json_decode($model->fuente_json, true);
														return date('d-m-Y', strtotime($fuente['fechaEmision']));
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Vcto'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														$fuente = json_decode($model->fuente_json, true);
														return date('d-m-Y', strtotime($fuente['fechaVcto']));
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Condicion'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														if ( $model->inactivo == 1 ) {
															return 'INACTIVO';
														} else {
															return 'ACTIVO';
														}
													},
						            ],

						       //      [
						       //          'label' => Yii::t('backend', 'Fecha'),
						       //          'contentOptions' => [
						       //          	'style' => 'font-size:90%;text-align:center;',
						       //          ],
						       //          'format' => 'raw',
						       //          'value' => function($model) {
													// 	return date('d-m-Y', strtotime($model->fecha_hora));
													// },
						       //      ],

						            [
						                'label' => Yii::t('backend', 'Id. Contribuyente'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														$fuente = json_decode($model->fuente_json, true);
														return $fuente['id_contribuyente'];
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'RIF'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														$fuente = json_decode($model->fuente_json, true);
														return $fuente['rif'];
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Contribuyente'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														$fuente = json_decode($model->fuente_json, true);
														return $fuente['descripcion'];
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Usuario'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->usuario;
													},
						            ],

						       //      [
						       //          'label' => Yii::t('frontend', 'Observacion'),
						       //          'contentOptions' => [
						       //          	'style' => 'font-size:90%;',
						       //          ],
						       //          'format' => 'raw',
						       //          'value' => function($model) {
													// 	return $model->observacion;
													// },
						       //  	],

						    	]
							]);?>
						</div>
					</div>


					<div class="row" style="width: 100%;padding: 0px;margin: 0px;">

						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Back'),
																	  [
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-back',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>


						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Quit'),
																	  [
																		'id' => 'btn-quit',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-quit',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs(
    '$(document).on("click", "#link-id-historico", (function() {
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