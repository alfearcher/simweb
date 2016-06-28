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
 *  @file negacion-solicitud-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-06-2016
 *
 *  @view negacion-solicitud-form.php
 *  @brief vista del formulario que se utilizara para capturar la causa y observacion
 * que indique el funcionario cuando quiera negar una solicitud.
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
	use backend\controllers\menu\MenuController;

?>
<div class="negacion-solicitud">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-negacion-solicitud-form',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>

	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => $nroSolicitud])->label(false);?>
	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $idContribuyente])->label(false);?>
	<?=$form->field($model, 'id_config_solicitud')->hiddenInput(['value' => $idConfig])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 80%;">
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
	        						'back' => '/funcionario/solicitud/solicitud-asignada/buscar-solicitud-seleccionada',
	        						'list' => '/funcionario/solicitud/solicitud-asignada/buscar-solicitudes-contribuyente',
	        						'undo' => '/funcionario/solicitud/solicitud-asignada/levantar-form-negacion-solicitud',
	        						'quit' => '/funcionario/solicitud/solicitud-asignada/quit',
	        			])
	        		?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-10">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= $subCaption?></strong></h4>
					</div>

<!-- Inicio Causas -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<strong><?= $model->getAttributeLabel('causa') ?></strong>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								 <?= $form->field($model, 'causa')
								          ->dropDownList($listaCausas, [
                                                               'id'=> 'causa',
                                                               'prompt' => Yii::t('backend', 'Select'),
                                                               'style' => 'width:600px;',
                                                        ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Causas -->

<!-- Inicio de Observacion -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<strong><?= $model->getAttributeLabel('observacion') ?></strong>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row" class="observacion">
								<?= $form->field($model, 'observacion')->textArea([
																				'id' => 'observacion',
																				'rows' => 4,
																				'maxlength' => 255,
																				'style' => 'width: 600px;',
																			])->label(false) ?>
							</div>
						</div>
					</div>
					<div class="row" style="padding-left: 580px; margin-top: -5px;">
						<strong><small>
							<div class="col-sm-2" style="width: 100%;">
								<div id="contador">255</div>
							</div>
							<div class="col-sm-4" style="width: 100%;">
								<p>caracteres</p>
							</div>
						</small></strong>
					</div>
<!-- Fin de Observacion -->

<!-- Inicio de boton -->
					<div class="row" style= "align:left;" >
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Reject Request'),
													  [
														'id' => 'btn-reject-request',
														'class' => 'btn btn-danger',
														'value' => 2,
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

<?php
	$this->registerJs(
		'$(document).ready(function() {
    		var max_chars = 255;
    		$("#max").html(max_chars);
		    $("#observacion").keyup(function() {
		        var chars = $(this).val().length;
		        var diff = max_chars - chars;
		        $("#contador").html(diff);
		    });
		});'
	);
?>
