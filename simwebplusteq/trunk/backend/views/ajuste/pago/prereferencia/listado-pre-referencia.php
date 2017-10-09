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
 *  @file listado-pre-referencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-10-2017
 *
 *  @view listado-pre-referencia
 *  @brief vista que muestra un listado de pre-referencia bancarias que fueron
 *  obtenida despues de la consulta. Este listado se utilizara para ajustar los
 *  registros.
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

<div class="listado-pre-referencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-pre-referencia',
 			'method' => 'post',
 			//'action'=> '#',
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<!-- <?//=$form->field($model, 'fecha_desde')->hiddenInput(['value' => $model->fecha_desde])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 105%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" style="width:100%;">

					<div class="row" style="width: 100%;margin-top: 10px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode($subCaption)?></h4>
						</div>

						<div class="row" style="width: 100%;padding: 0px;marging:0px;">
							<div class="col-sm-4" style="width: 75%;">
<!-- Lista combo de tipos de ajuste -->
								<div class="row" style="width: 100%;padding:0px;margin-top:10px;margin-bottom:0px;">
									<div class="col-sm-2" style="width: 20%;margin-top:5px;">
										<p><strong><?=Html::label(Yii::t('backend', 'Tipo de Ajuste'))?></strong></p>
									</div>
									<div class="col-sm-2" style="width:75%;padding:0px;margin: 0px;">
										<?= $form->field($modelAjusteForm, 'tipo_ajuste')
									              ->dropDownList($listaTipoAjuste, [
		                                                             'id'=> 'id-tipo-ajuste',
		                                                             'prompt' => Yii::t('backend', 'Select'),
		                                                             'style' => 'width:100%;',
		                                                             'onChange' => '$.post( "' . Yii::$app->urlManager
		                                                                                   		           ->createUrl('/ajuste/pago/prereferencia/ajuste-pre-referencia-bancaria/find-nota-tipo-ajuste') . '&tipo=' . '" + $(this).val() + "' . '",
		                                                                                   		           			 function( data ) {
		                                                                                   		           			 	$( "#id-ajuste-nota-explicativa" ).html( "" );
		                                                                                                             	$( "#id-ajuste-nota-explicativa" ).html( data );
		                                                                                                       		}
		                                                                                    );'
		                                        ])->label(false);
		                        		?>
									</div>
								</div>
<!-- fin Lista combo de tipos de ajuste -->

<!-- nota explicativa de tipos de ajuste -->
								<div class="row" style="width: 100%;">
									<div class="col-sm-2" style="width: 20%;margin-top:5px;">
										<p><strong><?=Html::label(Yii::t('backend', 'Nota-Descripcion'))?></strong></p>
									</div>
									<div class="col-sm-4" style="width:75%;padding:0px;margin:0px;">
										<?= $form->field($modelAjusteForm, 'nota_explicativa')->textArea([
																				'id' => 'id-ajuste-nota-explicativa',
																				'rows' => 6,
																				'style' => 'width:100%;background-color:white;',
																				'readOnly' => true,
																				'onChange' => 'var str = $(this).text;
																							   if ( str.length > 0 ) {
		                                                        									$( "#btn-ajustar-pre-referencia" ).removeAttr("disabled");
		                                                    								   } else {
		                                                        									$( "#btn-ajustar-pre-referencia" ).attr("disabled", true);
		                                                    								   }',
																		 	])->label(false) ?>
									</div>
								</div>
<!-- fin nota explicativa de tipos de ajuste -->
							</div>  <!-- fin de col-sm-4 -->

							<div class="col-sm-4" style="width: 20%;margin-top: 15px;">
								<div class="col-sm-2" style="width: 100%;padding:0px;">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('backend', 'Realizar Ajuste<br/>en las Pre-Referencias'),
																			[
																				'id' => 'btn-ajustar-pre-referencia',
																				'class' => 'btn btn-primary',
																				'value' => 3,
																				'style' => 'width: 100%;',
																				'name' => 'btn-ajustar-pre-referencia',
																			])
										?>
									</div>
								</div>
							</div>
						</div>

						<?php if ( trim($mensajeSeleccion) !== '' ) { ?>
							<div class="row" style="width: 100%;">
								<div class="well well-sm" style="width: 30%;color: red;">
									<?=Html::encode($mensajeSeleccion);?>
								</div>
							</div>
						<?php } ?>

						<div class="row" id="id-pre-referencia-bancaria">
							<?= GridView::widget([
								'id' => 'grid-pre-referencia',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'warning',
									'style' => 'font-size: 90%;',
								],
								'rowOptions' => function($model) {
													if ( (float)round($model['credito'], 2) !== (float)round($model['monto_planilla'], 2) ) {
			                							return [
			                								'class' => 'danger',
			                							];
			                						}
												},
								'tableOptions' => [
                    				'class' => 'table table-hover',
              					],
								'layout' => '{summary}{items}',	// Total elemento n
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkIdReferencia',
				                        'multiple' => true,
				                        'checkboxOptions' => function ($model, $key, $index, $column) {

				                        }
				                    ],
				                    [
					                	'contentOptions' => [
					                    	'style' => 'font-size: 90%;text-align:center;width:10%;',
					                	],
					                	'attribute' => 'recibo',
					                	'format' => 'raw',
					                	'label' => Yii::t('backend', 'Recibo'),
				                    	'value' => function($model) {
				                                   		//return $model['recibo'];
				                                   		return Html::a($model['recibo'], '#', [
                    											'title' => Yii::t('backend', 'recibo') . $model['recibo'] . ' - ' . $model['observacion'],
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
				                              'style' => 'font-size: 90%;text-align:center;width:8%;',
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
				                        'attribute' => 'monto_recibo',
				                        'label' => Yii::t('backend', 'Mto. Recibo'),
				                        'value' => function($model) {
				                                    	return Yii::$app->formatter->asDecimal($model['monto_recibo'], 2);
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'format' => 'raw',
				                        'label' => $model->getAttributeLabel('planilla'),
				                        'value' => function($model) {
				                                    	//return $model['planilla'];
				                                    	return Html::a($model['planilla'], '#', [
                    											'title' => $model['planilla'],
																'id' => 'link-planilla',
																'data-toggle' => 'modal',
																'data-target' => '#modal',
																'data-url' => Url::to(['view-planilla-modal',
																					   'p' => $model['planilla'],
																					]),
																'data-pjax' => 0,
    													]);
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('backend', 'Mto. Planilla'),
				                        'value' => function($model) {
				                                    	return Yii::$app->formatter->asDecimal($model['monto_planilla'], 2);
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('backend', 'ID'),
				                        'format' => 'raw',
				                        'value' => function($model) {
				                                   		return $model['id_contribuyente'];
				                         //           		return Html::a($model['id_contribuyente'], '#', [
                													// 			'id' => 'link-id-contribuyente',
                													// 			'data-toggle' => 'modal',
                													// 			'data-target' => '#modal',
                													// 			'data-url' => Url::to(['view-contribuyente-modal',
                													// 									'id' => $model['id_contribuyente']]),
                													// 			'data-pjax' => 0,
                													// ]);
													},
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;',
				                        ],
				                        'label' => Yii::t('backend', 'F. EdoCuenta'),
				                        'value' => function($model) {
				                                    	return date('d-m-Y', strtotime($model['fecha_edocuenta']));
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('backend', 'Serial'),
				                        'value' => function($model) {
				                                    	return $model['serial_edocuenta'];
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('backend', 'Mto Dedito'),
				                        'value' => function($model) {
				                                   		return Yii::$app->formatter->asDecimal($model['debito'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('backend', 'Mto Credito'),
				                        'value' => function($model) {
				                                   		return Yii::$app->formatter->asDecimal($model['credito'], 2);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('backend', 'C. Recaudadora'),
				                        'value' => function($model) {
				                                   		return $model->depositoDetalle->cuenta_deposito;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('backend', 'Editar'),
				                        'format' => 'raw',
				                        'value' => function($model, $key) {
				                                   		return Html::a('<center><span class= "fa fa-pencil"></span></center>', '#', [
                																'id' => 'link-id-referencia',
                																'title' => Yii::t('backend', 'recibo') . ' ' . $model['recibo'] . ' ' . Yii::t('backend', 'serial') . ' ' . $model['serial_edocuenta'],
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-referencia-modal',
                																						'id-referencia' => $model['id_referencia'],
                																						'recibo' => $model['recibo'],
                																						'planilla' => $model['planilla'],
                																						'monto_planilla' => $model['monto_planilla'],
                																						'serial_edocuenta' => $model['serial_edocuenta'],
                																						'fecha_edocuenta' => $model['fecha_edocuenta'],
                																						'debito' => $model['debito'],
                																						'credito' => $model['credito'],
                																					]),
                																'data-pjax' => 0,
                													]);
													},
										'visible' => false,
				                    ],
					        	]
							]);?>

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
);

$this->registerJs(
    '$(document).on("click", "#link-id-referencia", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle").html(data);
                $("#modal").modal();
            }
        );
    }));'
);

$this->registerJs(
    '$(document).on("click", "#link-planilla", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle").html(data);
                $("#modal").modal();
            }
        );
    }));'
);?>


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