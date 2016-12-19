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
 *  @file autorizar-ramo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-10-2015
 *
 *  @view autorizar-ramo-form.php
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
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="autorizar-ramo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'autorizar-ramo-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $datos['id_contribuyente']])->label(false);?>
	<?=$form->field($model, 'ano_impositivo')->hiddenInput(['value' => $añoCatalogo])->label(false);?>
	<?=$form->field($model, 'periodo')->hiddenInput(['value' => $periodo])->label(false);?>
	<?=$form->field($model, 'fecha_inicio')->hiddenInput(['value' => $datos['fecha_inicio']])->label(false);?>
	<?=$form->field($model, 'fecha_desde')->hiddenInput(['value' => $fechaDesde])->label(false);?>
	<?=$form->field($model, 'fecha_hasta')->hiddenInput(['value' => $fechaHasta])->label(false);?>
	<?=$form->field($model, 'ano_hasta')->hiddenInput(['value' =>$añoVenceOrdenanza])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 110%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-4" style="padding-top: 10px;">
	        			<h3><?= Html::encode($this->title) ?></h3>
	        		</div>
	        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 40px;padding-top: 15px;">

							<style type="text/css">
								.col-sm-3 > ul > li > a:hover {
									background-color: #337AB3;
								}
			    			</style>
			        		<?= MenuController::actionMenuSecundario([
			        						'help' => '/aaee/autorizarramo/autorizar-ramo/solicitar-ayuda',
			        						'quit' => '/aaee/autorizarramo/autorizar-ramo/quit',
			        			])
			        		?>
			        		<a href="<?=Url::toRoute(['/aaee/autorizarramo/autorizar-ramo/downloadFile', 'file'=>'10.pdf'])?>"><h3>Descargar</h3></a>
			        		<!-- <?//=Url::toRoute('Link',['@frontend/view/aaee/autorizar-ramo/prueba/10.pdf'],[
			        		//						'class' => 'btn btn-primary',
			        		//						'target' => '_blank',
			        		//])?> -->
							<!-- <i class="fa fa-question-circle fa-3x" aria-hidden="true"></i> -->


		        	</div>
	        	<!-- <h3><?//= Html::encode($this->title) ?></h3> -->
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
					        	<span><?= Html::encode(Yii::t('backend', 'Category')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row">

<!-- Id Contribuyente -->
									<div class="col-sm-2" style="margin-left: 15px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_contribuyente')) ?></i></p>
										</div>
										<div class="row">
											<div class="id-contribuyente">
												<?= $form->field($model, 'id_contribuyente')->textInput([
																									'id' => 'id-contribuyente',
																									'name' => 'id-contribuyente',
																									'style' => 'width:100%;',
																									'value' => $datos['id_contribuyente'],
																									'disabled' => true,
																						 		])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Id Contribuyente -->

<!-- Fecha de Inicio -->
									<div class="col-sm-2" style="margin-left: 5px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_inicio')) ?></i></p>
										</div>
										<div class="row">
											<div class="fecha-inicio">
												<?= $form->field($model, 'fecha_inicio')->textInput([
																								'id' => 'fecha-inicio',
																								'name' => 'fecha-inicio',
																								'style' => 'width:70%;',
																								'value' => $datos['fecha_inicio'],
																								'disabled' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Fecha de Inicio -->

<!-- Año Catalogo de Rubro -->
									<div class="col-sm-2" style="margin-left: -45px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('ano_impositivo')) ?></i></p>
										</div>
										<div class="row">
											<div class="ano-impositivo">
												<?= $form->field($model, 'ano_impositivo')->textInput([
																								'id' => 'ano-impositivo',
																								'name' => 'ano-impositivo',
																								'style' => 'width:60%;',
																								'value' => $añoCatalogo,
																								'disabled' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Año Catalogo de Rubro -->

<!-- Inicio de Periodo -->
									<div class="col-sm-2" style="margin-left: -75px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('periodo')) ?></i></p>
										</div>
										<div class="row">
											<div class="periodo">
												<?= $form->field($model, 'periodo')->textInput([
																								'id' => 'periodo',
																								'name' => 'periodo',
																								'style' => 'width:30%;',
																								'value' => $periodo,
																								'disabled' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Periodo -->


<!-- Inicio de Fecha Desde -->
									<div class="col-sm-2" style="margin-left: -120px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_desde')) ?></i></p>
										</div>
										<div class="row">
											<div class="fecha-desde">
												<?= $form->field($model, 'fecha_desde')->textInput([
																								'id' => 'fecha-desde',
																								'name' => 'fecha-desde',
																								'style' => 'width:60%;',
																								'value' => $fechaDesde,
																								'disabled' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Fecha Desde -->


<!-- Inicio de Fecha Hasta -->
									<div class="col-sm-2" style="margin-left: -75px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_hasta')) ?></i></p>
										</div>
										<div class="row">
											<div class="fecha-hasta">
												<?= $form->field($model, 'fecha_hasta')->textInput([
																								'id' => 'fecha-hasta',
																								'name' => 'fecha-hasta',
																								'style' => 'width:60%;',
																								'value' => $fechaHasta,
																								'disabled' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Fecha Hasta -->



<!-- Año de Vencimiento de la Ordenanza, segun el Año Catalogo -->
									<div class="col-sm-2" style="margin-left: -35px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Ordinance Expired') ?></i></p>
										</div>
										<div class="row">
											<div class="ano-hasta">
												<?= $form->field($model, 'ano_hasta')->textInput([
																									'id' => 'ano-hasta',
																									'name' => 'ano-hasta',
																									'style' => 'width:70%;',
																									'value' => $añoVenceOrdenanza,
																									'disabled' => true,
																						 		])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Año de Vencimiento de la Ordenanza, segun el Año Catalogo -->


								</div> 		<!-- Fin de row -->


								<div class="row">
									<div class="col-sm-5" style="width:80%;">
										<div class="list-group">
  											<a href="#" class="list-group-item">
    											<h4 class="list-group-item-heading">Observaciones</h4>
    											<p class="list-group-item-text">La autorización de los ramos comprenderan el lapso de tiempo entre los años <?= Html::encode($añoCatalogo) ?> y <?= Html::encode($añoVenceOrdenanza) ?>. Ambos inclusibles</p>
  											</a>
										</div>
									</div>
								</div>



<!-- LISTA DE CATALOGO DE RUBROS -->
								<div class="row" >
									<div class="row" style="padding-left: 35px;">
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
													<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Search')),
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

									<div class="row" style="width: 100%; padding-left: 25px;margin-top: 15px;">
										<div class="row" style="padding-left: 15px;width:100%;">
											<span><h4><?= Html::encode(Yii::t('backend', 'Category List')) ?></h4></span>
										</div>
										<?= GridView::widget([
											'id' => 'grid-lista-rubro',
	    									'dataProvider' => $dataProvider,
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
								</div>
<!-- Fin de LISTA DE CATALOGO DE RUBROS -->

								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Add Category Selected')),
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

								<div class="row" style="margin-left: 5px;border-bottom: 0.5px solid #ccc;width:99%;"></div>
<!-- Lista de Rubros seleccionados para autorizar -->
								<div class="row" style="width: 100%; padding-left: 15px;margin-top: 15px;">
										<div class="row" style="padding-left: 15px;width:100%;">
											<span><h4><?= Html::encode(Yii::t('backend', 'Add Category List')) ?></h4></span>
										</div>
										<?= GridView::widget([
											'id' => 'grid-lista-rubro-seleccionado',
	    									'dataProvider' => $dataProviderSeleccionado,
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
								</div>
<!-- Fin de lista de Rubros seleccionados  para autorizar -->
								<?php if ( trim($errorRubroSeleccionado) !== '' ) { ?>
									<div class="row">
										<div class="error-chk-selected" style="padding-left:35px;">
											<div class="well well-sm" style="width:70%; color:red; padding-left:35px;">
												<?=$errorRubroSeleccionado; ?>
											</div>
										</div>
									</div>
								<?php } ?>

								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Remove Category Selected')),
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
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Create Request')),
																			  [
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'value' => 4,
																				'style' => 'width: 100%; margin-left: 100px;margin-top:20px;',
																				'name' => 'btn-create',
																				'disabled' => ( $activarBotonCreate == 1 ) ? false : true,
																			  ])
						?>
					</div>
				</div>


				<div class="col-sm-3">
					<div class="form-group">
						<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																			  [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 60%; margin-left: 300px;margin-top:20px;',
																				'name' => 'btn-quit',

																			  ])
						?>
					</div>
				</div>
			</div>

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


