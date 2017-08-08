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
 *  @file mostrar-archivo-txt.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view mostrar-archivo-txt.php
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
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;


 ?>


<?php

	$form = ActiveForm::begin([
		'id' => 'id-mostrar-archivo-form',
		'method' => 'post',
		//'action' => ['mostrar-archivo-txt'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

<?=Html::hiddenInput('data-path', $ruta);?>
<?=Html::hiddenInput('data-file', $archivo);?>
<?=Html::hiddenInput('data-date', $fecha);?>

<?=$form->field($model, 'id_banco')->hiddenInput(['value' => $model->id_banco])->label(false);?>
<?=$form->field($model, 'fecha_pago')->hiddenInput(['value' => $model->fecha_pago])->label(false);?>
<?=$form->field($model, 'sin_formato')->hiddenInput(['value' => $model->sin_formato])->label(false);?>

<div class="row" style="width:100%;padding: 0px;margin: 0px;">
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;padding-top:10px;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Monto Total:'))?> </strong></h4>
	</div>
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
		<h4><strong><?=Html::textInput('total-txt', Yii::$app->formatter->asDecimal($montoTotal, 2),
				 								[
				 									'class' => 'form-control',
				 									'style' => 'width: 100%;
				 												text-align:right;
				 									            font-size:120%;
				 									            font-weight:bold;',
												]);
				?>
		</strong></h4>
	</div>
</div>

<div class="row" style="width:100%;padding: 0px;margin: 0px;">
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Total Registros:'))?> </strong></h4>
	</div>
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
		<h4><strong><?=Html::textInput('total-registro', $totalRegistro,
						 								[
						 									'class' => 'form-control',
						 									'style' => 'width: 100%;
						 												text-align:right;
						 									            font-size:120%;
						 									            font-weight:bold;',
														]);
				?>
		</strong></h4>
	</div>
</div>


<div class="row">
	<div class="col-sm-2" style="margin-left: 40px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Procesar Pagos'),
												  [
													'id' => 'btn-procesar-pago',
													'class' => 'btn btn-success',
													'value' => 3,
													'style' => 'width: 100%',
													'name' => 'btn-procesar-pago',
												  ])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Analizar Archivo'),
												  [
													'id' => 'btn-analize-file',
													'class' => 'btn btn-primary',
													'value' => 1,
													'style' => 'width: 100%',
													'name' => 'btn-analize-file',
												  ])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 20px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Back'),
												  [
													'id' => 'btn-back',
													'class' => 'btn btn-danger',
													'value' => 1,
													'style' => 'width: 100%',
													'name' => 'btn-back',
												  ])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="margin-left: 20px;">
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
</div>

 <div class="row" style="width:98%;margin-left: 25px;">
	<div class="lista-pago">
		<div class="row" style="width: 100%;margin-top: 5px;">
	    	<?= GridView::widget([
	    		'id' => 'id-grid-mostrar-archivo-formateado-txt',
	    		'dataProvider' => $dataProvider,
	    		'headerRowOptions' => [
	    			'class' => 'success',
	    		],
	    		'rowOptions' => function($data) {
					if ( $data['estatus'] > 0 ) {
						return ['class' => 'danger'];
					}
				},
	    		'tableOptions' => [
	    			'class' => 'table table-hover',
	    		],
	    		//'summary' => true,
	    		'columns' => [
	    			[
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chkRecibo',
                        'multiple' => true,
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                //         	return [
            				// 	'onClick' => 'javascript: return false;',
	               //              'checked' => true,
            				// ];
                        	if ( $model['estatus'] > 0 ) {
                				return [
                					'disabled' => 'disabled',
                				];
                			}
                        }
                    ],

	    			['class' => 'yii\grid\SerialColumn'],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'format' => 'raw',
	                    'label' => Yii::t('backend', 'recibo'),
	                    'value' => function($data, $key) {
                    					//return $data['recibo'];
                    					return Html::a($data['recibo'], '#', [
                    											'title' => $data['recibo'] . ' - ' . $data['observacion'],
																'id' => 'link-recibo',
																'data-toggle' => 'modal',
																'data-target' => '#modal',
																'data-url' => Url::to(['view-recibo-modal',
																					   'nro' => $data['recibo'],
																					]),
																'data-pjax' => 0,
    													]);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                    	'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto recibo'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_recibo'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                    	'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'fecha pago'),
	                    'value' => function($data, $key) {
                    					return date('d-m-Y', strtotime($data['fecha_pago']));
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto efectivo'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_efectivo'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto cheque'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_cheque'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'cuenta cheque'),
	                    'value' => function($data, $key) {
                    					return $data['cuenta_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero cheque'),
	                    'value' => function($data, $key) {
                    					return $data['nro_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'fecha cheque'),
	                    'value' => function($data, $key) {
	                    				if ( $data['fecha_cheque'] !== '0000-00-00' ) {
	                    					return date('d-m-Y', strtotime($data['fecha_cheque']));
	                    				} else {
	                    					return $data['fecha_cheque'];
	                    				}
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto TDD'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_debito'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero TDD'),
	                    'value' => function($data, $key) {
                    					return $data['nro_debito'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto TDC'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_credito'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero TDC'),
	                    'value' => function($data, $key) {
                    					return $data['nro_credito'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right',
	                    ],
	                    'label' => Yii::t('backend', 'monto transferencia'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_transferencia'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero transaccion'),
	                    'value' => function($data, $key) {
                    					return $data['nro_transaccion'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;text-align:right;font-weight:bold',
	                    ],
	                    'label' => Yii::t('backend', 'monto total'),
	                    'value' => function($data, $key) {
                    					return Yii::$app->formatter->asDecimal($data['monto_total'], 2);
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'cuenta recaudadora'),
	                    'value' => function($data, $key) {
                    					return $data['nro_cuenta_recaudadora'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'archivo'),
	                    'value' => function($data, $key) {
                    					return $data['archivo_txt'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'estatus'),
	                    'value' => function($data, $key) {
                    					return $data['estatus'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'Observacion'),
	                    'value' => function($data, $key) {
                    					return $data['observacion'];
	        			           },
	        			'visible' => false,
	                ],

	        	]
	    	]);?>
	    </div>
	</div>
</div>

<div class="row">
	<div class="row" style="width:100%;padding: 0px;margin: 0px;">
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;padding-top:10px;">
			<h4><strong><?=Html::encode(Yii::t('backend', 'Monto Total:'))?> </strong></h4>
		</div>
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
			<h4><strong><?=Html::textInput('total-txt', Yii::$app->formatter->asDecimal($montoTotal, 2),
					 								[
					 									'class' => 'form-control',
					 									'style' => 'width: 100%;
					 												text-align:right;
					 									            font-size:120%;
					 									            font-weight:bold;',
													]);
					?>
			</strong></h4>
		</div>
	</div>

	<div class="row" style="width:100%;padding: 0px;margin: 0px;">
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;">
			<h4><strong><?=Html::encode(Yii::t('backend', 'Total Registros:'))?> </strong></h4>
		</div>
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
			<h4><strong><?=Html::textInput('total-registro', $totalRegistro,
							 								[
							 									'class' => 'form-control',
							 									'style' => 'width: 100%;
							 												text-align:right;
							 									            font-size:120%;
							 									            font-weight:bold;',
															]);
					?>
			</strong></h4>
		</div>
	</div>
</div>


<div class="col-sm-2" style="margin-left: 40px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Procesar Pagos'),
											  [
												'id' => 'btn-procesar-pago',
												'class' => 'btn btn-success',
												'value' => 3,
												'style' => 'width: 100%',
												'name' => 'btn-procesar-pago',
											  ])
		?>
	</div>
</div>


<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Analizar Archivo'),
											  [
												'id' => 'btn-analize-file',
												'class' => 'btn btn-primary',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-analize-file',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Back'),
											  [
												'id' => 'btn-back',
												'class' => 'btn btn-danger',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-back',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
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

<?php ActiveForm::end(); ?>



<?php
$this->registerJs(
    '$(document).on("click", "#link-recibo", (function() {
    	alert("jkka");
        $.get(
            $(this).data("url"),
            function (data) {
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