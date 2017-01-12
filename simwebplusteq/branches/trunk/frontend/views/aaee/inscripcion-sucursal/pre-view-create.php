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
 *  @file pre-view-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-07-2016
 *
 *  @view pre-view-create.php
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
	use yii\jui\DatePicker;
	use yii\widgets\DetailView;
	//use backend\models\registromaestro\TipoNaturaleza;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	//use backend\models\TelefonoCodigo;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="pre-view-inscripcion-sucursal-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-inscripcion-sucursal-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode(Yii::t('frontend', 'Confirm Create. Inscription of Branch Office')) ?></h3>
        </div>

	<?= $form->field($model, 'id_sede_principal')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'naturaleza')->hiddenInput(['value' => $model['naturaleza']])->label(false); ?>
	<?= $form->field($model, 'cedula')->hiddenInput(['value' => $model['cedula']])->label(false); ?>
	<?= $form->field($model, 'tipo')->hiddenInput(['value' => $model['tipo']])->label(false); ?>
	<?= $form->field($model, 'razon_social')->hiddenInput(['value' => $model['razon_social']])->label(false); ?>
	<?= $form->field($model, 'id_sim')->hiddenInput(['value' => $model['id_sim']])->label(false); ?>
	<?= $form->field($model, 'fecha_inicio')->hiddenInput(['value' => $model['fecha_inicio']])->label(false); ?>
	<?= $form->field($model, 'domicilio_fiscal')->hiddenInput(['value' => $model['domicilio_fiscal']])->label(false); ?>
	<?= $form->field($model, 'email')->hiddenInput(['value' => $model['email']])->label(false); ?>
	<?= $form->field($model, 'tlf_ofic')->hiddenInput(['value' => $model['tlf_ofic']])->label(false); ?>
	<?= $form->field($model, 'tlf_ofic_otro')->hiddenInput(['value' => $model['tlf_ofic_otro']])->label(false); ?>
	<?= $form->field($model, 'tlf_celular')->hiddenInput(['value' => $model['tlf_celular']])->label(false); ?>
	<?= $form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?= $form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?= $form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?= $form->field($model, 'id_sim')->hiddenInput(['value' => 0])->label(false); ?>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row">
						<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Info of Headquarters Main')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row" id="datos-principal-primario" style="padding-left: 15px; width: 100%;">
									<!-- <h4><?//= Html::encode(Yii::t('backend', 'Info of Headquarters Main')) ?></h3> -->
										<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [

								    				[
								    					'label' => $model->getAttributeLabel('id_sede_principal'),
								    					'value' => $datosRecibido['id_sede_principal'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('dni_principal'),
								    					'value' => $datosRecibido['naturaleza'] . '-' . $datosRecibido['cedula'] . '-' . $datosRecibido['tipo'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('representante'),
								    					'value' => $datosRecibido['representante'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('dni_representante'),
								    					'value' =>  $datosRecibido['naturaleza_rep'] . '-' . $datosRecibido['cedula_rep'],
								    				],
								    			],
											])
										?>
					        	</div>
					        	<div class="row" id="datos-principal-reg-mercantil" style="padding-left: 15px; width: 100%;">
					        		<h4><?= Html::encode(Yii::t('backend', 'Info of Commercial Register')) ?></h3>
					        		<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [

								    				[
								    					'label' => $model->getAttributeLabel('reg_mercantil'),
								    					'value' => $datosRecibido['reg_mercantil'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('num_reg'),
								    					'value' => $datosRecibido['num_reg'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('fecha'),
								    					'value' => date('d-M-Y', strtotime($datosRecibido['fecha'])),
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('tomo'),
								    					'value' => $datosRecibido['tomo'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('folio'),
								    					'value' => $datosRecibido['folio'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('capital'),
								    					'value' => $datosRecibido['capital'],
								    				],
								    			],
											])
										?>
					        	</div>
					        </div>
						</div>
					</div>

<!-- DATOS DE LA SUCURSAL -->
					<div class="row">
						<h4><?= Html::encode(Yii::t('backend', 'Branch Office')) ?></h3>
		        		<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Summary')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row" style="padding-left: 15px; width: 100%;">
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $model->razon_social,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('id_sim'),
							    					'value' => $model->id_sim,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('fecha_inicio'),
							    					'value' => $model->fecha_inicio,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('domicilio_fiscal'),
							    					'value' => $model->domicilio_fiscal,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('email'),
							    					'value' => $model->email,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('tlf_ofic'),
							    					'value' => $model->tlf_ofic,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('tlf_ofic_otro'),
							    					'value' => $model->tlf_ofic_otro,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('tlf_celular'),
							    					'value' => $model->tlf_celular,
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('usuario'),
							    					'value' => $model->usuario,
							    				],
							    				// [
							    				// 	'label' => $model->getAttributeLabel('fecha_hora'),
							    				// 	'value' => $model->fecha_hora,
							    				// ],
							    				[
							    					'label' => $model->getAttributeLabel('orugen'),
							    					'value' => $model->origen,
							    				],


							    			],
										])
									?>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Confirm Create'),[
																				'id' => 'btn-confirm-create',
																				'class' => 'btn btn-success',
																				'name' => 'btn-confirm-create',
																				'value' => 2,
																				'style' => 'width: 100%;'
									])?>
							</div>
						</div>
						<div class="col-sm-2" style="margin-left: 150px;">
							<div class="form-group">
								 <?= Html::submitButton(Yii::t('backend', 'Back Form'),[
																				'id' => 'btn-back-form',
																				'class' => 'btn btn-danger',
																				'name' => 'btn-back-form',
																				'value' => 3,
																				'style' => 'width: 100%;'
									])?>
							</div>
						</div>
					</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
