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
	use backend\controllers\menu\MenuController;

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
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>

	<?=
		$form->field($model, 'listado')->hiddenInput(['value' => $listado])->label(false);
		$form->field($model, 'nro_solicitud')->hiddenInput(['value' => $model->nro_solicitud])->label(false);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
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
						<div class="col-sm-3" style="padding-left: 75px;">
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
								<?= $form->field($model, 'impuesto')->textInput([
																					'id' => 'impuesto',
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
							<div class="row">
								<div class="detalle" id="detalle" style="padding-left: 40px;"><?= $viewDetalle?></div>
							</div>
							<div class="row">
								<div class="documento-requisito" id="documento-requisito" style="padding-left: 10px; width: 75%;">
									<?= GridView::widget([
							                'id' => 'grid-lista-documento',
							                'dataProvider' => $dataProvider,
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

							<div class="row">
								<div class="planilla-solicitud" id="planilla-solicitud">
									 <?//= GridView::widget([
							            //    'id' => 'grid-lista-planilla',
							            //    'dataProvider' => $dataProviderPlanilla,
							            //    'summary' => '',
							            //    'columns' => [
						                        // ['class' => 'yii\grid\SerialColumn'],
						                        // [
						                        //     'label' => 'ID.',
						                        //     'value' => 'id_documento',
						                        // ],
						                        // [
						                        //     'label' => 'Descripcion',
						                        //     'value' => 'descripcion',
						                        // ],
							             //   ]
							            //]);
							        ?>
								</div>
							</div>
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
														'data-confirm' => Yii::t('backend', 'Confirm Reject?.'),
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
