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
 *  @file correccion-rep-legal-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-08-2016
 *
 *  @view correccion-rep-legal-form
 *  @brief vista principal del cambio o correccion del representante legal
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

<div class="correccion-representante-legal-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-correccion-cedula-rif-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $datos['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'dni_principal')->hiddenInput([
												'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
								])->label(false);?>
	<?=$form->field($model, 'naturaleza_rep_v')->hiddenInput(['value' => $datos['naturaleza_rep']])->label(false);?>
	<?=$form->field($model, 'cedula_rep_v')->hiddenInput(['value' => $datos['cedula_rep']])->label(false);?>
	<?=$form->field($model, 'representante_v')->hiddenInput(['value' => $datos['representante']])->label(false);?>
	<?=$form->field($model, 'razon_social')->hiddenInput(['value' => $datos['razon_social']])->label(false);?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>
	<?=$form->field($model, 'id_sim')->hiddenInput(['value' => $datos['id_sim']])->label(false); ?>

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
					        		<h4><?= Html::encode(Yii::t('frontend', 'Main Taxpayer')) ?></h4>
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

							    				[
							    					'label' => $model->getAttributeLabel('id_contribuyente'),
							    					'value' => $datos['id_contribuyente'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('dni_principal'),
							    					'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('razon_social'),
							    					'value' => $datos['razon_social'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('id_sim'),
							    					'value' => $datos['id_sim'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('dni_representante_v'),
							    					'value' => $datos['naturaleza_rep'] . '-' . $datos['cedula_rep'],
							    				],
							    				[
							    					'label' => $model->getAttributeLabel('representante_v'),
							    					'value' => $datos['representante'],
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
									                    'value' => function($data) {
	                            										return $data->id_contribuyente;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Current DNI'),
									                    'value' => function($data) {
	                            										return $data->naturaleza . '-' . $data->cedula . '-' . $data->tipo;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Taxpayer'),
									                    'value' => function($data) {
	                            										return $data->razon_social;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'License No.'),
									                    'value' => function($data) {
	                            										return $data->id_sim;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'DNI Legal Represent'),
									                    'value' => function($data) {
	                            										return $data->naturaleza_rep . '-' . $data->cedula_rep;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Legal Represent'),
									                    'value' => function($data) {
	                            										return $data->representante;
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
					        	<span><?= Html::encode(Yii::t('backend', 'Info of Legal Represent')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Cedula / Rif Nueva -->
									<div class="col-sm-2" style="padding-left: 30px; width:21%;">
										<div class="row">
											<p><strong><?= Yii::t('backend', $model->getAttributeLabel('dni_representante_new')) ?></strong></p>
										</div>
									</div>

									<div class="col-sm-5" style="margin-left: -20px;margin-top: 0px">
										<div class="form-group">
<!-- Combo Naturaleza Nuevo -->
			        						<div class="col-sm-3" style="width: 50%;">
												<div class="naturaleza-rep-new">
							                		<?= $form->field($model, 'naturaleza_rep_new')->dropDownList($listaNaturaleza,[
			                																	 			'id' => 'naturaleza-rep-new',
			                																	 			'style' => 'width: 100%;',
			                                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
			                                                                     				 			//'value' => $datos['naturaleza_rep'],
			                                                                    							])->label(false)
							    					?>
												</div>
			        						</div>
<!-- Fin de Combo Naturaleza Nuevo -->

<!-- Cedula o Rif Nuevo -->
											<div class="col-sm-3" style="width: 40%;margin-left: -25px;">
												<div class="cedula-rep-new">
													<?= $form->field($model, 'cedula_rep_new')->textInput([
																									'id' => 'cedula-rep-new',
																									'style' => 'width: 100%;',
																									'maxlength' => 8,
																									//'value' => $datos['cedula_rep'],
																				  				  ])->label(false) ?>
												</div>
											</div>
<!-- Fin de Cedula o Rif Nuevo -->
										</div>
									</div>
								</div>


								<div class="row">
<!-- Apellidos y nombre del representante legal -->
									<div class="col-sm-2" style="padding-left: 30px; width:20%;">
										<div class="row">
											<p><strong><?= Yii::t('backend', $model->getAttributeLabel('representante_new')) ?></strong></p>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-6" style="width: 40%; margin-left: 5px;">
											<div class="representante-new">
												<?= $form->field($model, 'representante_new')->textInput([
																							'id' => 'representante-new',
																							'maxlength' => 200,
																							'style' => 'width: 120%;',
																							//'value' => $datos['representante'],
																				  			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Apellidos y nombre del representante legal -->
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

<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>