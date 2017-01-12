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
 *  @date 31-08-2016
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
	// use backend\controllers\utilidad\documento\DocumentoRequisitoController;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="pre-view-anexar-ramo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-anexar-ramo-form',
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
        	<h3><?= Html::encode(Yii::t('frontend', 'Confirm Create. Add New Categories')) ?></h3>
        </div>

	<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false); ?>
	<?= $form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false); ?>
	<?= $form->field($model, 'periodo')->hiddenInput(['value' => $model->periodo])->label(false); ?>
	<?= $form->field($model, 'fecha_desde')->hiddenInput(['value' => $model->fecha_desde])->label(false); ?>
	<?= $form->field($model, 'fecha_hasta')->hiddenInput(['value' => $model->fecha_hasta])->label(false); ?>
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
					        	<b><span><?= Html::encode(Yii::t('backend', 'Summary')) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row" id="rubro-seleccionado" style="padding-left: 15px; width: 100%;">
									<?= GridView::widget([
											'id' => 'grid-lista-rubro-seleccionado',
	    									'dataProvider' => $dataProvider,
	    									'headerRowOptions' => ['class' => 'danger'],
	    									//'filterModel' => $searchModel,
	    									'columns' => [
	    										['class' => 'yii\grid\SerialColumn'],
								            	[
								                    'label' => Yii::t('frontend', 'Category'),
								                    'value' => function($data) {
	                        										return $data->rubro;
	                											},
								                ],
								                [
								                    'label' => Yii::t('frontend', 'Year'),
								                    'value' => function($data) {
	                        										return $data->ano_impositivo;
	                											},
								                ],
								                // [
								                //     'label' => Yii::t('frontend', 'Year End'),
								                //     'format' => 'raw',
								                //     'value' => function($model) {
								                //     	$a = $model->ano_hasta;
								                //     	return $a;
								                //     },
								                // ],
								                [
								                    'label' => Yii::t('frontend', 'Descripcion'),
								                    'value' => function($data) {
	                        										return $data->descripcion;
	                											},
								                ],

								                [
								                	'class' => 'yii\grid\CheckboxColumn',
								                	'name' => 'chkRubroSeleccionado',
								                	'checkboxOptions' => [
                            							'id' => 'chkSucursal',
                            							// Lo siguiente mantiene el checkbox tildado.
                            							'onClick' => 'javascript: return false;',
                            							'checked' => true,
                                					],
								                	'multiple' => false,
								                ],
								        	]
										]);?>
					        	</div>
					        </div>
						</div>
					</div>

					<div class="row">
						<div class="form-group">
							<div class="col-sm-3">
								<?= Html::submitButton(Yii::t('backend', 'Confirm Create'),[
																				'id' => 'btn-confirm-create',
																				'class' => 'btn btn-success',
																				'name' => 'btn-confirm-create',
																				'value' => 5,
																				'style' => 'width: 100%;'
									])?>
							</div>

							<div class="col-sm-3" style="margin-left: 150px;">
								 <?= Html::submitButton(Yii::t('backend', 'Back Form'),[
																				'id' => 'btn-back-form',
																				'class' => 'btn btn-danger',
																				'name' => 'btn-back-form',
																				'value' => 9,
																				'style' => 'width: 100%;'
									])?>
							</div>
						</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
