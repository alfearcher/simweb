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
 *  @file seleccionar-ramo-anexar-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-08-2016
 *
 *  @view seleccionar-ramo-anexar-form.php
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
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="anexo-ramo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-seleccionar-anexo-ramo-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $model->ano_impositivo])->label(false);?>
	<?=$form->field($model, 'periodo')->hiddenInput(['value' => $model->periodo])->label(false);?>
	<?=$form->field($model, 'fecha_desde')->hiddenInput(['value' => $model->fecha_desde])->label(false); ?>
	<?=$form->field($model, 'fecha_hasta')->hiddenInput(['value' => $model->fecha_hasta])->label(false); ?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?=$form->field($model, 'fecha_hora')->hiddenInput(['value' => $model->fecha_hora])->label(false); ?>
	<?=$form->field($model, 'origen')->hiddenInput(['value' => $model->origen])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 110%;">
        <div class="panel-heading">
        	<div class="row">
				<div class="col-sm-4" style="padding-top: 10px;">
        			<h3><?= Html::encode(Yii::t('frontend', 'Add New Categories')) ?></h3>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #337AB7;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario($opciones);
	        		?>
	        	</div>
			</div>
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
					        	<b><span><?= Html::encode(Yii::t('frontend', 'Categories Registered ' . $model->ano_impositivo . ' - ' . $model->periodo)) ?></span></b>
					        </div>
					        <div class="panel-body">
<!-- INICIO RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->
								<div class="row"  style="padding-left: 10px; width: 100%;">
									<div class="rubro-registrado">
										<div class="row" style="margin-left: 5px;">
											<h3><span><?= Html::encode(Yii::t('frontend', 'Categories Registered ' . $model->ano_impositivo . ' - ' . $model->periodo)) ?></span></h3>
										</div>
										<div class="row" style="width:35%;padding-left: 15px;">
											<?= DetailView::widget([
													'model' => $model,
													'attributes' => [
														[
															'label' => Yii::t('frontend', 'Fiscal Start Date'),
															'value' => $model['fecha_desde'],
														],
														[
															'label' => Yii::t('frontend', 'Fiscal End Date'),
															'value' => $model['fecha_hasta'],
														],
													],
												])
											?>
										</div>
										<?= GridView::widget([
												// Rubros registrados para el año-periodo.
												'id' => 'grid-lista-rubro-registrado',
		    									'dataProvider' => $dataProviderRubro,
		    									'headerRowOptions' => ['class' => 'success'],
		    									'summary' => '',
		    									'columns' => [
		    										['class' => 'yii\grid\SerialColumn'],
									            	[
									                    'label' => Yii::t('frontend', 'Category'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->rubro;
		                											},
									                ],
									                [
									                    'label' => Yii::t('frontend', 'Year'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->ano_impositivo;
		                											},
									                ],
									                [
									                    'label' => Yii::t('frontend', 'Perid'),
									                    'value' => function($data) {
		                        										return $data->exigibilidad_periodo;
		                											},
									                ],
									                [
									                    'label' => Yii::t('frontend', 'Descripcion'),
									                    'value' => function($data) {
		                        										return $data->rubroDetalle->descripcion;
		                											},
									                ],
									        	]
											]);
										?>
									</div>
								</div>
							</div>
						</div>
<!-- FIN DE RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->

<!-- LISTA DE CATALOGO DE RUBROS -->
						<div class="row" style="margin-left: -15px;width: 103%;">
							<div class="panel panel-success">
								<div class="panel-heading">
						        	<b><span><?= Html::encode(Yii::t('frontend', 'List Categories')) ?></span></b>
						        </div>
						        <div class="panel-body">
									<div class="row" style="padding-left: 10px; width: 100%;">
										<div class="col-sm-4">
											<div class="row" style="width: 65%;">
												<span><?= Html::encode(Yii::t('backend', 'Input Search Category')) ?></span>
											</div>
											<div class="row">
												<?= Html::input('text', 'inputSearch', '',
												 								[
												 									'class' => 'form-control',
												 									'style' => 'width: 120%;'
												 								])
												?>
											</div>
										</div>

										<div class="col-sm-2">
											<div class="row">
												<div class="form-group">
													<?= Html::submitButton(Yii::t('frontend', 'Search Category'),
																										  [
																											'id' => 'btn-search',
																											'class' => 'btn btn-primary',
																											'value' => 1,
																											'style' => 'width: 100%; margin-left: 220px;margin-top:20px;',
																											'name' => 'btn-search',
																										  ])
													?>
												</div>
											</div>
										</div>
									</div>

									<div class="row" style="margin-left: 5px;border-bottom: 0.5px solid #ccc;width:99%;"></div>

									<div class="row" style="margin-top: 20px;">
										<div class="col-sm-5" style="width:80%;">
											<div class="list-group">
	  											<a href="#" class="list-group-item">
	    											<h4 class="list-group-item-heading">Observaciones</h4>
	    											<p class="list-group-item-text">Seleccione los ramos que desee anexar</p>
	  											</a>
											</div>
										</div>
									</div>

									<div class="row" style="width: 100%; padding-left: 10px;margin-top: -15px;">
										<div class="row" style="padding-left: 15px;width:100%;">
											<h3><span><?= Html::encode(Yii::t('backend', 'List Categories')) ?></span></h3>
										</div>
										<?= GridView::widget([
											'id' => 'grid-lista-rubro',
	    									'dataProvider' => $dataProviderRubroCatalogo,
	    									'headerRowOptions' => ['class' => 'success'],
	    									//'filterModel' => $searchModel,
	    									'columns' => [
	    										//['class' => 'yii\grid\SerialColumn'],
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
								                [
								                    'label' => Yii::t('frontend', 'Descripcion'),
								                    'value' => function($data) {
	                        										return $data->descripcion;
	                											},
								                ],
								                [
								                	'class' => 'yii\grid\CheckboxColumn',
								                	'name' => 'chkRubro',
								                	'multiple' => false,
								                ],
								        	]
										]);?>
									</div>

									<div class="row">
										<div class="col-sm-3">
											<div class="form-group">
												<?= Html::submitButton(Yii::t('frontend', 'Add Category Selected'),
																									  [
																										'id' => 'btn-add-category',
																										'class' => 'btn btn-primary',
																										'value' => 2,
																										'style' => 'width: 100%; margin-left: 800px;margin-top:20px;',
																										'name' => 'btn-add-category',
																									  ])
												?>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
<!-- Fin de LISTA DE CATALOGO DE RUBROS -->



<!-- LISTA DE RUBROS AGREGADOS -->
						<div class="row" style="margin-left: -15px;width: 103%;">
							<div class="panel panel-default">
								<div class="panel-heading">
						        	<b><span><?= Html::encode(Yii::t('frontend', 'Categories Added')) ?></span></b>
						        </div>
						        <div class="panel-body">
									<div class="row" style="width: 100%; padding-left: 10px;margin-top: -15px;">
										<div class="row" style="padding-left: 15px;width:100%;">
											<h3><span><?= Html::encode(Yii::t('backend', 'Categories Added')) ?></span></h3>
										</div>
										<?= GridView::widget([
											'id' => 'grid-lista-rubro',
	    									'dataProvider' => $dataProviderRubroAnexar,
	    									'headerRowOptions' => ['class' => 'default'],
	    									//'filterModel' => $searchModel,
	    									'columns' => [
	    										//['class' => 'yii\grid\SerialColumn'],
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
								                [
								                    'label' => Yii::t('frontend', 'Descripcion'),
								                    'value' => function($data) {
	                        										return $data->descripcion;
	                											},
								                ],
								                [
								                	'class' => 'yii\grid\CheckboxColumn',
								                	'name' => 'chkRubroSeleccionado',
								                	'multiple' => false,
								                ],
								        	]
										]);?>
									</div>

									<div class="row">
										<div class="col-sm-3">
											<div class="form-group">
												<?= Html::submitButton(Yii::t('frontend', 'Remove Category Selected'),
																								  [
																									'id' => 'btn-remove-category',
																									'class' => 'btn btn-warning',
																									'value' => 3,
																									'style' => 'width: 100%; margin-left: 800px;margin-top:20px;',
																									'name' => 'btn-remove-category',
																									'disabled' => ( $activarBotonCreate == 1 ) ? false : true,
																								  ])
												?>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
<!-- Fin de LISTA DE CATALOGO DE RUBROS -->

					</div>
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="form-group">
					<div class="col-sm-3" style="width: 20%;margin-left:100px;">
						<?= Html::submitButton(Yii::t('frontend', 'Create Request'),
																			  [
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'value' => 5,
																				'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																				'name' => 'btn-create',
																				'disabled' => ( $activarBotonCreate == 1 ) ? false : true,
																			  ]);
						?>
					</div>

					<div class="col-sm-3" style="width: 20%;margin-left:100px;">
						<?= Html::submitButton(Yii::t('frontend', 'Back Form'),
																		  [
																			'id' => 'btn-back-form',
																			'class' => 'btn btn-danger',
																			'value' => 6,
																			'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																			'name' => 'btn-back-form',

																		  ]);
						?>
					</div>

					<div class="col-sm-3" style="width: 20%;margin-left:100px;">
						<?= Html::submitButton(Yii::t('frontend', 'Quit'),
																	  [
																		'id' => 'btn-quit',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																		'name' => 'btn-quit',

																	  ]);
						?>
					</div>
				</div>
			</div>
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


