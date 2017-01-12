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
 *  @file view_solicitud_seleccionada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view view_solicitud_seleccionada.php
 *  @brief vista del formualario que se utilizara para mostrar los datos principales
 *  de la solicitud seleccionada y los datos basicos del contribuyente.
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

 	//session_start();		// Iniciando session

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use backend\controllers\menu\MenuController;
	use yii\widgets\Pjax;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>
<div class="solicitud-seleccionada">
	<?php
		$form = ActiveForm::begin([
			'id' => 'view-solicitud-seleccionada-form',
		    'method' => 'post',
		    'action' => $url,
			'enableClientValidation' => false,
			'enableAjaxValidation' => false,
			'enableClientScript' => true,
		]);
	?>

	<?=$form->field($model, 'listado')->hiddenInput(['value' => $listado])->label(false);?>
	<?=$form->field($model, 'exigirDocumento')->hiddenInput(['value' => $exigirDocumento])->label(false);?>
	<?=$form->field($model, 'id_config_solicitud')->hiddenInput(['value' => $model->id_config_solicitud])->label(false);?>
	<?=$form->field($model, 'impuesto')->hiddenInput(['value' => $model->impuesto])->label(false);?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #F5F5F5;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario([
	        						'back' => '/funcionario/solicitud/solicitud-asignada/buscar-solicitudes-contribuyente',
	        						'quit' => '/funcionario/solicitud/solicitud-asignada/quit',
	        			])
	        		?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', $subCaption) ?></strong></h4>
					</div>

<!-- Inicio de Nro de Solicitud -->
					<div class="row"  style="padding-left: 15px; padding-top: 10px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('nro_solicitud')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-3" style="padding-left: 90px;">
							<div class="row" class="nro-solicitud">
								<?= $form->field($model, 'nro_solicitud')->textInput([
																					'id' => 'nro-solicitud',
																					'readonly' => true,
																					'style' => 'width: 110%; background-color: white;',
																					'value' => $model->nro_solicitud,
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Nro de Solicitud -->


<!-- Inicio de Fecha y Hora de Creacion -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('fecha_hora_creacion')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2" style="padding-left: 0px;">
							<div class="row" class="fecha-hora-creacion">
								<?= $form->field($model, 'fecha_hora_creacion')->textInput([
																					'id' => 'fecha-hora-creacion',
																					'readonly' => true,
																					'style' => 'width: 110%; background-color: white;',
																					//'value' => $model->nro_solicitud,
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Fecha y Hora de Creacion -->


<!-- Inicio de Impuestos -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('impuesto')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5" style="padding-left: 0px;">
							<div class="row" class="impuesto">
								<?= $form->field($model, 'impuesto_descripcion')->textInput([
																					'id' => 'impuesto_descripcion',
																					'readonly' => true,
																					'style' => 'width: 100%; background-color: white;',
																					'value' =>$model->impuestos['descripcion'],
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Impuestos -->

<!-- Inicio de Tipo de Solicitud -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('tipo_solicitud')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-7" style="padding-left: 0px;">
							<div class="row" class="tipo-solicitud">
								<?= $form->field($model, 'tipo_solicitud')->textInput([
																					'id' => 'tipo-solicitud',
																					'readonly' => true,
																					'style' => 'width: 100%; background-color: white;',
																					'value' =>$model->tipoSolicitud['descripcion'],
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Tipo de Solicitud -->


<!-- Inicio de Nivel de Aprobacion -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('nivel_aprobacion')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5" style="padding-left: 0px;">
							<div class="row" class="nivel-aprobacion">
								<?= $form->field($model, 'nivel_aprobacion')->textInput([
																					'id' => 'nivel-aprobacion',
																					'readonly' => true,
																					'style' => 'width: 100%; background-color: white;',
																					'value' =>$model->nivelAprobacion['descripcion'],
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Nivel de Aprobacion -->

<!-- Inicio de Estatus Solicitud -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', $model->getAttributeLabel('estatus')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5" style="padding-left: 0px;">
							<div class="row" class="estatus-solicitud">
								<?= $form->field($model, 'estatus')->textInput([
																					'id' => 'estatus-solicitud',
																					'readonly' => true,
																					'style' => 'width: 100%; background-color: white;',
																					'value' =>$model->estatusSolicitud['descripcion'],
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Estatus Solicitud -->


<!-- Inicio de Datos del CONTRIBUYENTE -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Basic Data of Taxpayer') ?></strong></h4>
					</div>

<!-- Inicio de Id Contribuyente -->
					<div class="row" style="padding-left: 15px; padding-top: 10px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', Yii::t('backend','ID')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2" style="padding-left: 0px;">
							<div class="row" class="id-contribuyente">
								<?= $form->field($model, 'id_contribuyente')->textInput([
																					'id' => 'id-contribuyente',
																					'readonly' => true,
																					'style' => 'width: 100%; background-color: white;',
																					'value' => $model->id_contribuyente,
																				])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Id Contribuyente -->


<!-- Inicio de DNI del Contribuyente -->
					<div class="row" style="padding-left: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', Yii::t('backend', 'DNI')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2" style="padding-left: 0px;">
							<div class="row" class="dni">
								<?= Html::textInput('dni', $contribuyente['dni'], [
																'class' => 'form-control',
																'readonly' => true,
																'style' => 'background-color: white; width: 100%;'
								]) ?>
							</div>
						</div>
					</div>
<!-- Fin de DNI del Contribuyente -->

<!-- Inicio de Descripcion del Contribuyente -->
					<div class="row" style="padding-left: 15px; padding-top: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', Yii::t('backend', 'Taxpayer')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-7" style="padding-left: 0px;">
							<div class="row" class="contribuyente">
								<?= Html::textInput('contribuyente', $contribuyente['contribuyente'], [
																'class' => 'form-control',
																'readonly' => true,
																'style' => 'background-color: white; width: 100%;'
								]) ?>
							</div>
						</div>
					</div>
<!-- Fin de Descripcion del Contribuyente -->

<!-- Inicio de Domicilio del Contribuyente -->
					<div class="row" style="padding-left: 15px; padding-top: 15px;">
						<div class="col-sm-3">
							<div class="row">
								<p><strong><?= Yii::t('backend', Yii::t('backend', 'Address')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-7" style="padding-left: 0px;">
							<div class="row" class="domicilio">
								<?= Html::textarea('domicilio', $contribuyente['domicilio'], [
																'class' => 'form-control',
																'readonly' => true,
																'style' => 'background-color: white; width: 100%;'
								]) ?>
							</div>
						</div>
					</div>
<!-- Fin de Domicilio del Contribuyente -->

<!-- Fin de Datos del CONTRIBUYENTE -->

<!-- Inicio de Detalle de la Solicitud -->
					<div class="row" style="padding-top: 15px;">
						<div class="detalle-solicitud">
							<div class="row" style="border-bottom: 0.5px solid #ccc;">
								<h4><strong><?= Yii::t('backend', 'Details of Request') ?></strong></h4>
							</div>

							<div class="row" style="width: 90%;">
								<div class="detalle" id="detalle" style="padding-left: 40px;"><?= $viewDetalle?></div>
							</div>

							<div class="row" style="padding-left: 10px;">
								<small><strong><?= Yii::t('backend', 'Indique los Documentos y Requisitos consignados.') ?></strong></small>
							</div>
							<div class="row" style="padding-top: 5px;">
								<div class="documento-requisito" id="documento-requisito" style="padding-left: 10px; width: 90%;">
									<?= GridView::widget([
							                'id' => 'grid-lista-documento',
							                'dataProvider' => $dataProvider,
							                'headerRowOptions' => ['class' => 'success'],
							                'summary' => '',
							                'columns' => [
						                        ['class' => 'yii\grid\SerialColumn'],
						                        [
						                            'label' => 'ID.',
						                            'value' => 'id_documento',
						                        ],
						                        [
						                            'label' => 'Descripcion',
						                            'value' => 'descripcion',
						                        ],
						                        [
						                            'class' => 'yii\grid\CheckboxColumn',
						                            'name' => 'chk-documento-requisito',
						                        ],
							                ]
							            ]);
							        ?>
								</div>
							</div>
							<?php if ( trim($errorChk) !== '' ) { ?>
								<div class="row">
									<div class="error-chk-documento">
										<div class="well well-sm" style="color: red;"><?=$errorChk; ?></div>
									</div>
								</div>
							<?php } ?>

							<div class="row" style="padding-left: 10px;">
								<small><strong><?= Yii::t('backend', 'Planilla(s)') ?></strong></small>
							</div>

							<div class="row" style="padding-top: 5px;padding-left: 10px;">
								<div class="planilla-solicitud" id="planilla-solicitud">

									 <?= GridView::widget([
							         		'id' => 'grid-lista-planilla',
							               	'dataProvider' => $dataProviderPlanilla,
							               	'headerRowOptions' => ['class' => 'primary'],
            								'rowOptions' => function($data) {
                									if ( $data['pago'] == 0 ) {
                    									return ['class' => 'danger'];
                									} elseif ( $data['pago'] == 1 ) {
                										return ['class' => 'success'];
                									}
            								},
							               'summary' => '',
							               'columns' => [
							               		[
                        							'class' => 'yii\grid\CheckboxColumn',
                        							'name' => 'chk-planilla',
                        							'checkboxOptions' => [
                                							'id' => 'chk-planilla',
                                							// Lo siguiente mantiene el checkbox tildado.
                                							'onClick' => 'javascript: return false;',
                                							'checked' => true,
                                							//'disabled' => true, funciona.
                                					],
                                					'multiple' => false,

                        						],
						                        ['class' => 'yii\grid\SerialColumn'],
						                        [
						                            'label' => 'Planilla',
						                            'format' => 'raw',
						                            'value' => function($data) {
						                            	return Html::a($data['planilla'], '#', [
            																		'id' => 'link-view-planilla',
																		            //'class' => 'btn btn-success',
																		            'data-toggle' => 'modal',
																		            'data-target' => '#modal',
																		            'data-url' => Url::to(['view-planilla', 'p' => $data['planilla']]),
																		            'data-planilla' => $data['planilla'],
																		            'data-pjax' => '0',
																		        ]);
						                            	//return Html::a($data['planilla'], ['view-planilla', 'p' => $data['planilla']]);
						                            },
						                        ],
						                        [
						                            'label' => 'Impuesto',
						                            'value' => function($data) {
						                            	return $data['descripcion_impuesto'];
						                            },
						                        ],
						                        [
						                            'label' => 'Total',
						                            'value' => function($data) {
						                            	return ($data['sum_monto'] + $data['sum_recargo'] + $data['sum_interes']) - ($data['sum_descuento'] + $data['sum_monto_reconocimiento']);
						                            },
						                        ],
						                        [
						                            'label' => 'Observacion',
						                            'value' => function($data) {
						                            	return $data['descripcion'];
						                            },
						                        ],
						                        [
						                            'label' => 'Pago',
						                            'format'=>'raw',
						                            // afecta solo a la celda
						                            'contentOptions' => function($data) {
						                            		if ( $data['pago'] == 0 ) {
						                            			return ['style' => 'display: block;color: red;'];
						                            		} elseif ( $data['pago'] == 1 ) {
						                            			return ['style' => 'display: block;color: blue;'];
						                            		}
				                            		},
						                            //
						                            'value' => function($data) {
						                            	if ( $data['pago'] == 0 ) {
						                            		return Html::tag('strong', Html::tag('h3',
						                            								   			$data['estatus'],
						                            								   			['class' => 'label label-danger',
						                            								   			 'id' => 'pago',
						                            								   			 'name' => 'pago',
						                            								   			]));
						                            	} elseif ( $data['pago'] == 1 ) {
						                            		return Html::tag('strong', Html::tag('h4',
						                            			                     			 $data['estatus'],
						                            			                     			 ['class' => 'label label-primary',
						                            								   			  'id' => 'pago',
						                            								   			  'name' => 'pago',
						                            								   			]));

						                            	} elseif ( $data['pago'] == 9 ) {
						                            		return Html::tag('strong', Html::tag('h4',
						                            			                     			 $data['estatus'],						                            			                     			 ['class' => 'label label-warning',
						                            								   			  'id' => 'pago',
						                            								   			  'name' => 'pago',
						                            								   			]));
						                            	} else {
						                            		return Html::tag('strong', Html::tag('h4',
						                            			                     			 $data['estatus'],
						                            			                     			 ['class' => 'label label-warning',
						                            								   			  'id' => 'pago',
						                            								   			  'name' => 'pago',
						                            								   			]));
						                            	}
						                            },
						                        ],

							               ]
							            ]);
							        ?>
								</div>
							</div>

							<?php if ( trim($planillaNoSolvente) !== '' ) { ?>
								<div class="row">
									<div class="planilla-no-solvente" id="planilla-no-solvente">
										<div class="well well-sm" style="color: red;"><?=$planillaNoSolvente; ?></div>
									</div>
								</div>
							<?php } ?>

						</div>
					</div>
<!-- Fin de Detalle de la Solicitud -->

					<div class="row" style="padding-top: 55px;">
						<div class="separador">
							<div class="row" style="border-bottom: 0.5px solid #ccc;">

							</div>
						</div>
					</div>

<!-- Inicio de boton -->
					<div class="row" style="padding-top: 55px;">
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Approve Request'),
													  [
														'id' => 'btn-approve-request',
														'class' => 'btn btn-success',
														'value' => 1,
														'name' => 'btn-approve-request',
														'style' => 'width: 100%;',
														'data-confirm' => Yii::t('backend', 'Confirm Approve?.'),
													  ])
								?>
							</div>
						</div>

						<div class="col-sm-3"></div>

						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Reject Request'),
													  [
														'id' => 'btn-reject-request',
														'class' => 'btn btn-danger',
														'value' => 1,
														'name' => 'btn-reject-request',
														'style' => 'width: 100%;',
														//'data-confirm' => Yii::t('backend', 'Confirm Reject?.'),
													  ])
								?>
							</div>
						</div>

					</div>
<!--  -->
				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
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
    }));


	$(document).ready(function() {
		var solvente = $( "#id-solvente" ).val();
		var n = $( "#planilla-no-solvente" ).length;

		if ( ( n > 0 ) || ( solvente == "NO SOLVENTE" )  || ( solvente == "" ) ) {
			$("#btn-approve-request").attr("disabled", true);
		} else {
			$( "#btn-approve-request" ).removeAttr("disabled");
		}
	});


    '
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