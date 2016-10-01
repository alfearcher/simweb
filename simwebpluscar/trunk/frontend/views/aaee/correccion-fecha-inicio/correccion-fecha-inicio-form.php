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
 *  @file correccion-fecha-inicio-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-08-2016
 *
 *  @view correccion-fecha-inicio-form
 *  @brief vista principal del cambio o correccion de la fecha inicio de actividad comercial
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
	use yii\widgets\DetailView;
	use yii\jui\DatePicker;

?>

<div class="correccion-fecha-inicio-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'correccion-fecha-inicio-fiscal-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $datos['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'dni')->hiddenInput([
										'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
								])->label(false);?>
	<?=$form->field($model, 'fecha_inicio_v')->hiddenInput(['value' => $datos['fecha_inicio']])->label(false);?>
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
							    					'label' => $model->getAttributeLabel('domicilio_fiscal'),
							    					'value' => $datos['domicilio_fiscal'],
							    				],
							    				[
							    					'format'=>['date', 'dd-MM-yyyy'],
							    					//'format' => ['raw', 'd-m-Y'],
							    					//'format' =>  ['date', 'php:d-m-Y H:i:s'],
							    					'label' => $model->getAttributeLabel('fecha_inicio_v'),
							    					'value' => $datos['fecha_inicio'],
							    				],
							    			],
										])
									?>
								</div>
					        </div>
					    </div>
					</div>

					<?php if ( trim($errorMensajeFechaInicioSedePrincipal) !== '' ) { ?>
						<div class="row" id="mensaje-error-fecha-inicio-principal" style="padding-left:50px;">
							<div class="well well-sm" style="padding-top:0px;margin-left:0px; width:60%; color: red;">
								<h3><?=Html::encode($errorMensajeFechaInicioSedePrincipal); ?></h3>
							</div>
						</div>
					<?php } ?>

					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($model->getAttributeLabel('fecha_inicio_new')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Fecha Inicio Nueva -->
									<div class="col-sm-3" style="margin-left: 15px;margin-top: 0px">
										<div class="row">
											<div class="fecha-inicio-new">
												<?= $form->field($model, 'fecha_inicio_new')->widget(\yii\jui\DatePicker::classname(),[
																									  'clientOptions' => [
																											'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																											'changeMonth' => true,
																											'changeYear' => true,
																										],
																									  'language' => 'es-ES',
																									  'dateFormat' => 'dd-MM-yyyy',
																									  'options' => [
																									  		'id' => 'fecha-inicio-new',
																											'class' => 'form-control',
																											'readonly' => true,
																											'style' => 'background-color: white;width:55%;',

																										]
																							])->label(false) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Fecha Inicio Nueva -->

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
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>

