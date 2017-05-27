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
 *  @file pre-view.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-05-2017
 *
 *  @view pre-view, archivo que permite mostrar una pre-vista de la solicitud que se
 *  desea crear, solicitud de desincorporacion de actividades economicas.
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

 	//use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\widgets\Pjax;
	use yii\widgets\DetailView;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="pre-view-desincorporar-actividad-economica">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-desincorporar-actividad-economica',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>
	 <?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode(Yii::t('frontend', 'Info of Taxpayer')) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
<!-- Datos del Contribuyente -->
									<div class="col-sm-4" style="margin-left: 15px;width: 90%">
										<div class="row">
											<div class="id-contribuyente">
												<?= $this->render('@common/views/contribuyente/datos-contribuyente', [
							        											'model' => $findModel,
							        							])
												?>
											</div>
										</div>
									</div>
<!-- Fin de datos del Contribuyente -->

								</div> 		<!-- Fin de row -->
							</div>
						</div>
					</div>


					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode(Yii::t('frontend', 'Otros datos')) ?></span></b>
					        </div>
					        <div class="panel-body">
<!-- ULTIMA DECLARACION -->
								<div class="row" style="padding: 0px;margin: 0px;padding:0px;">
									<div class="col-sm-2" style="width: 15%;">
										<p><strong><?= $model->getAttributeLabel('ult_declaracion') ?></strong></p>
									</div>
									<div class="col-sm-2" style="width: 25%;padding: 0px;margin: 0px;">
										<?= $form->field($model, 'ult_declaracion')->textInput([
																				'id' => 'id-ult-declaracion',
																				'style' => 'width:100%;',
																 			])->label(false)
										?>
									</div>
								</div>
<!-- FIN DE ULTIMA DECLARACION -->

<!-- ULTIMO PAGO -->
								<div class="row" style="padding: 0px;margin: 0px;padding:0px;">
									<div class="col-sm-2" style="width: 15%;">
										<p><strong><?= $model->getAttributeLabel('ult_pago') ?></strong></p>
									</div>
									<div class="col-sm-2" style="width: 25%;padding: 0px;margin: 0px;">
										<?= $form->field($model, 'ult_pago')->textInput([
																				'id' => 'id-ult-pago',
																				'style' => 'width:100%;',
																 			])->label(false)
										?>
									</div>
								</div>
<!-- FIN DE ULTIMO PAGO -->

							</div>
						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="col-sm-2" style="width: 25%;">
					<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Confirmar Crear Solicitud')),
																		  [
																			'id' => 'btn-create-confirm',
																			'class' => 'btn btn-success',
																			'style' => 'width: 100%;',
																			'name' => 'btn-create-confirm',
																			'value' => 2,
																		  ])
					?>
				</div>

				<div class="col-sm-2" style="width: 18%;margin-left: 25px;">
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

				<div class="col-sm-2" style="width: 18%;margin-left: 25px;">
					<!-- '../../common/docs/user/ayuda.pdf'  funciona -->
					 <?= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
																	'id' => 'btn-help',
																	'class' => 'btn btn-default',
																	'name' => 'btn-help',
																	'target' => '_blank',
																	'value' => 1,
																	'style' => 'width: 100%;'
						])?>
				</div>
			</div>

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


