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
 *  @file correccion-domicilio-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 31-07-2016
 *
 *  @view correccion-domicilio-form
 *  @brief vista principal del cambio o correccion del domicilio fiscal
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;
	//use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;

?>

<div class="correccion-domicilio-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'correccion-domicilio-fiscal-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $datos['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'dni')->hiddenInput([
										'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
								])->label(false);?>
	<?=$form->field($model, 'domicilio_fiscal_v')->hiddenInput(['value' => $datos['domicilio_fiscal']])->label(false);?>
	<?=$form->field($model, 'razon_social')->hiddenInput(['value' => $datos['razon_social']])->label(false);?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?>
	<?= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($subCaption) ?></span>
					        </div>
					        <div class="panel-body">
<!-- DATOS DEL CONTRIBUYENTE PRINCIPAL -->
					        	<div class="row" style="padding-left: 15px; width: 100%;">
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

							    				[
							    					'label' => $model->getAttributeLabel('id_contribuyente'),
							    					'value' => $datos['id_contribuyente'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('dni'),
							    					'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $datos['razon_social'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('domicilio_fiscal_v'),
							    					'value' =>  $datos['domicilio_fiscal'],
							    				],
							    			],
										])
									?>
								</div>
					        </div>
					    </div>
					</div>

<!-- DOMICILIO FISCAL NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($model->getAttributeLabel('domicilio_fiscal_new')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Domicilio Fiscal Nueva -->
									<div class="col-sm-5" style="margin-left: 15px;margin-top: 0px">
										<div class="row">
											<div class="domicilio-fiscal-new">
												<?= $form->field($model, 'domicilio_fiscal_new')->textArea([
																								'id' => 'domicilio-fiscal-new',
																								'style' => 'width:140%;',
																								'maxlength' => 255,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>

									<div class="row" style="padding-left: 620px; margin-top: -5px;">
										<strong><small>
											<div class="col-sm-2" style="width: 100%;">
												<div id="contador">255</div>
											</div>
											<div class="col-sm-4" style="width: 100%;">
												<p>caracteres</p>
											</div>
										</small></strong>
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Domicilio Fiscal Nueva -->
<!-- FIN DE DOMICILIO FISCAL NUEVO -->


					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Create')),
																									  [
																										'id' => 'btn-create',
																										'class' => 'btn btn-success',
																										'value' => 1,
																										'style' => 'width: 100%',
																										'name' => 'btn-create',
																									  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Quit')),
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
<!-- Fin de Boton para salir de la actualizacion -->

						<div class="col-sm-2" style="margin-left: 50px;">
							<div class="form-group">
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

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>

<?php
	$this->registerJs(
		'$(document).ready(function() {
    		var max_chars = 255;
    		$("#max").html(max_chars);
		    $("#domicilio-fiscal-new").keyup(function() {
		        var chars = $(this).val().length;
		        var diff = max_chars - chars;
		        $("#contador").html(diff);
		    });
		});'
	);
?>