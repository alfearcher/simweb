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
 *  @file correccion-cedula-rif-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 31-07-2016
 *
 *  @view correccion-cedula-rif-form
 *  @brief vista principal del cambio o correccion de la cedula o rif
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

<div class="correccion-cedula-rif-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-correccion-cedula-rif-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'naturaleza_v')->hiddenInput(['value' => $model['naturaleza_v']])->label(false);?>
	<?=$form->field($model, 'cedula_v')->hiddenInput(['value' => $model['cedula_v']])->label(false);?>
	<?=$form->field($model, 'tipo_v')->hiddenInput(['value' => $model['tipo_v']])->label(false);?>
	<?=$form->field($model, 'tipo_naturaleza_v')->hiddenInput(['value' => $model['tipo_naturaleza_v']])->label(false);?>
	<?=$form->field($model, 'naturaleza_new')->hiddenInput(['value' => $model['naturaleza_new']])->label(false);?>
	<?=$form->field($model, 'cedula_new')->hiddenInput(['value' => $model['cedula_new']])->label(false);?>
	<?=$form->field($model, 'tipo_new')->hiddenInput(['value' => $model['tipo_new']])->label(false);?>
	<?=$form->field($model, 'tipo_naturaleza_new')->hiddenInput(['value' => $model['tipo_naturaleza_new']])->label(false);?>
	<?=$form->field($model, 'razon_social')->hiddenInput(['value' => $datosRecibido['razon_social']])->label(false);?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
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
					        	<span><?= Html::encode($subCaption) ?></span>
					        </div>
					        <div class="panel-body">
<!-- DATOS DEL CONTRIBUYENTE PRINCIPAL -->
					        	<div class="row" style="padding-left: 15px; width: 100%;">
					        		<h4><?= Html::encode(Yii::t('frontend', 'Main Taxpayer')) ?></h4>
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

							    				[
							    					'label' => $model->getAttributeLabel('id_contribuyente'),
							    					'value' => $model['id_contribuyente'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('dni_v'),
							    					'value' => $model['naturaleza_v'] . '-' . $model['cedula_v'] . '-' . $model['tipo_v'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $datosRecibido['razon_social'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('id_sim'),
							    					'value' => $datosRecibido['id_sim'],
							    				],
							    			],
										])
									?>
								</div>

								<div class="row" style="padding-left: 15px; width: 100%;">
									<h4><?= Html::encode(Yii::t('frontend', 'Branch Office')) ?></h4>
										<div class="contribuyente-asociado">
	    									<?= GridView::widget([
	    										'id' => 'grid-contribuyente-asociado',
	        									'dataProvider' => $dataProvider,
	        									//'filterModel' => $model,
	        									//'layout'=>"\n{pager}\n{summary}\n{items}",
	        									'columns' => [
	        										//['class' => 'yii\grid\SerialColumn'],

									            	[
									                    'label' => Yii::t('backend', 'ID.'),
									                    'value' => function($model) {
	                            										return $model->id_contribuyente;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Current DNI'),
									                    'value' => function($model) {
	                            										return $model->naturaleza . '-' . $model->cedula . '-' . $model->tipo;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Taxpayer'),
									                    'value' => function($model) {
	                            										return $model->razon_social;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'License No.'),
									                    'value' => function($model) {
	                            										return $model->id_sim;
	                    											},
									                ],
									                [
									                	'class' => 'yii\grid\CheckboxColumn',
									                	'name' => 'chkSucursal',
									                	'checkboxOptions' => [
                                							'id' => 'chk-planilla',
                                							// Lo siguiente mantiene el checkbox tildado.
                                							'onClick' => 'javascript: return false;',
                                							'checked' => true,
                                							//'disabled' => true, funciona.
	                                					],
	                                					'multiple' => false,
									                ],
									        	]
											]);?>
										</div>
								</div>
					        </div>
					    </div>
					</div>

<!-- Cedula / Rif NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($model->getAttributeLabel('dni_new')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Cedula / Rif Nueva -->
									<div class="col-sm-5" style="margin-left: 15px;margin-top: 0px">
										<div class="row">
											<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [

								    				[
								    					'label' => $model->getAttributeLabel('dni_new'),
								    					'value' => $model['naturaleza_new'] . '-' . $model['cedula_new'] . '-' . $model['tipo_new'],
								    				],
								    			],
											])
										?>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
<!-- Fin de Cedula / Rif Nueva -->
<!-- FIN DE Cedula / Rif NUEVO -->


					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Confirm Create')),
																									  [
																										'id' => 'btn-confirm-create',
																										'class' => 'btn btn-success',
																										'value' => 2,
																										'style' => 'width: 100%',
																										'name' => 'btn-confirm-create',
																									  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Back Form')),
																									  [
																										'id' => 'btn-back-form',
																										'class' => 'btn btn-danger',
																										'value' => 3,
																										'style' => 'width: 100%',
																										'name' => 'btn-back-form',
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