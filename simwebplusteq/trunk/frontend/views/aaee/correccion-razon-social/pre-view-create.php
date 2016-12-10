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
 *  @date 06-08-2016
 *
 *  @view
 *  @brief vista principal del cambio o correccion
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
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;

?>

<div class="correccion-razon-social-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-correccion-razon-social-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?>
	<?=$form->field($model, 'razon_social_v')->hiddenInput(['value' => $model->razon_social_v])->label(false);?>
	<?=$form->field($model, 'razon_social_new')->hiddenInput(['value' => $model->razon_social_new])->label(false);?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 95%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 100%;margin-left: -15px;">
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
							    					'label' => $model->getAttributeLabel('dni'),
							    					'value' => $datosRecibido['dni'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $datosRecibido['razon_social'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('id_sim'),
							    					'value' => $datosRecibido['id_sim'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('domicilio_fiscal'),
							    					'value' => $datosRecibido['domicilio_fiscal'],
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
									                    'label' => Yii::t('backend', 'Current Company Name'),
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
                                							'id' => 'chkSucursal',
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

<!-- RAZON SOCIAL NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 100%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($model->getAttributeLabel('razon_social_new')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
									<div class="col-sm-5" style="width: 70%; margin-left: 15px;margin-top: 0px">
										<div class="row">
											<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [
								    				[
								    					'label' => $model->getAttributeLabel('razon_social_new'),
								    					'value' => $model['razon_social_new'],
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
<!-- FIN DE RAZON SOCIAL NUEVO -->


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