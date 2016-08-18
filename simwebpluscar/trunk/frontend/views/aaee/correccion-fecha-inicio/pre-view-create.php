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
	use yii\widgets\DetailView;
	// use backend\controllers\utilidad\documento\DocumentoRequisitoController;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="pre-view-correccion-domicilio-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-correccion-domicilio-form',
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
        	<h3><?= Html::encode(Yii::t('frontend', 'Confirm Create. Update of Start Date Activity')) ?></h3>
        </div>

	<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'fecha_inicio_v')->hiddenInput(['value' => $model['fecha_inicio_v']])->label(false); ?>
	<?= $form->field($model, 'fecha_inicio_new')->hiddenInput(['value' => $model['fecha_inicio_new']])->label(false); ?>
	<?= $form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?= $form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?= $form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row">
						<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Summary')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row" id="datos-principal-primario" style="padding-left: 15px; width: 100%;">
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

							    				[
							    					'label' => $model->getAttributeLabel('id_contribuyente'),
							    					'value' => $model['id_contribuyente'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('dni'),
							    					'value' => $datosRecibido['dni'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $datosRecibido['razon_social'],
							    				],
							    				[
							    					'format'=>['date', 'dd-MM-yyyy'],
							    					'label' => $model->getAttributeLabel('fecha_inicio_v'),
							    					'value' => $model['fecha_inicio_v'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('fecha_inicio_new'),
							    					'value' => $model['fecha_inicio_new'],
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
