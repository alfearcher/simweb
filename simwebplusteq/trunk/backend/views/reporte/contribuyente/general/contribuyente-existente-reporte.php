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
 *  @file contribuyente-existente-reporte.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-06-2017
 *
 *  @view contribuyente-existente-reporte.php
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
<div class="contribuyente-existente-reporte">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-contribuyente-existente-reporte',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
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
								'id' => 'id-grid-contribuyente-existente-reporte',
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
						                'label' => Yii::t('backend', 'ID.'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return Html::a($model->id_contribuyente, '#', [
                																'id' => 'link-id-contribuyente',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-contribuyente-modal',
                																						'id' => $model->id_contribuyente]),
                																'data-pjax' => 0,
                													]);
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'RIF/CEDULA'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
						                				if ( $model->tipo_naturaleza == 0 ) {
															return $model->naturaleza . '-' . $model->cedula;
														} elseif ( $model->tipo_naturaleza == 1 ) {
															return $model->naturaleza . '-' . $model->cedula . '-' . $model->tipo;
														} else {
															return '';
														}
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Contribuyente'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
						                				if ( $model->tipo_naturaleza == 0 ) {
						                					return $model->apellidos . ' ' . $model->nombres;
						                				} elseif ( $model->tipo_naturaleza == 1 ) {
						                					return $model->razon_social;
						                				} else {
						                					return '';
						                				}
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Correo'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
						                				return $model->email;
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Domilicio'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->domicilio_fiscal;
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Condicion'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														if ( $model->inactivo == 0 ) {
															return 'ACTIVO';
														} else {
															return 'INACTIVO';
														}
													},
						            ],

						       //      [
						       //          'label' => Yii::t('backend', 'Usuario'),
						       //          'contentOptions' => [
						       //          	'style' => 'font-size:90%;',
						       //          ],
						       //          'format' => 'raw',
						       //          'value' => function($model) {
													// 	return $model->usuario;
													// },
						       //      ],

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