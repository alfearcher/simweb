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
 *  @file create-solvencia-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view create-solvencia-form.php
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
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="crear-solvencia-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-create-solvencia-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false); ?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?=$form->field($model, 'impuesto')->hiddenInput(['value' => $model->impuesto])->label(false); ?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false); ?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => $model->nro_solicitud])->label(false); ?>
	<?=$form->field($model, 'ultimo_pago')->hiddenInput(['value' => $model->ultimo_pago])->label(false); ?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="col-sm-2" style="width: 10%;padding: 0px;">
							<div class="lapso" style="margin-left: 0px;">
								<?= $form->field($model, 'ano_impositivo')->textInput([
																			'id' => 'id-ano-impositivo',
																			'style' => 'width:100%;background-color:white;',
																			'value' => $model->ano_impositivo,
																			'readOnly' => true,

																	])->label('Año') ?>
							</div>
						</div>

						<div class="col-sm-2" style="width: 20%;padding: 0px;">
							<div class="ultimo-pago" style="margin-left: 5px;">
								<?= $form->field($model, 'ultimo_pago')->textInput([
																			'id' => 'id-ultimo-pago',
																			'style' => 'width:100%;background-color:white;',
																			'value' => $model->ultimo_pago,
																			'readOnly' => true,

																	])->label('Ultimo lapso pagado') ?>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-sm-4" style="width: 50%;padding: 0px;">
							<div class="observacion" style="margin-left: 0px;">
								<?= $form->field($model, 'observacion')->textArea([
																			'id' => 'id-observacion',
																			'style' => 'width:100%;background-color:white;',
																			'rows' => 4,
																			'readOnly' => true,
																	])->label('Observacion') ?>
							</div>
						</div>

					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
						<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Confirmar Crear Solicitud')),
																					  [
																						'id' => 'btn-confirm-create',
																						'class' => 'btn btn-success',
																						'value' => 5,
																						'style' => 'width: 100%;',
																						'name' => 'btn-confirm-create',

																					  ])
								?>
							</div>
						</div>

						<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Back'),
																			  [
																				'id' => 'btn-back-form',
																				'class' => 'btn btn-danger',
																				'value' => 6,
																				'style' => 'width: 100%;',
																				'name' => 'btn-back-form',

																			  ])
								?>
							</div>
						</div>

						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																					  [
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'value' => 1,
																						'style' => 'width: 100%;',
																						'name' => 'btn-quit',

																					  ])
								?>
							</div>
						</div>

					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


<?php
	$this->registerJs(
		'$(document).ready(function() {
    		var max_chars = 255;
    		$("#max").html(max_chars);
		    $("#id-observacion").keyup(function() {
		        var chars = $(this).val().length;
		        var diff = max_chars - chars;
		        $("#contador").html(diff);
		    });
		});'
	);
?>