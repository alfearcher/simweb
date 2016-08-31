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

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 110%;">
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
					        	<span><?= Html::encode(Yii::t('frontend', 'Info of Taxpayer')) ?></span>
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
					        	<span><?= Html::encode(Yii::t('frontend', 'Categories Registers')) ?></span>
					        </div>
					        <div class="panel-body">
<!-- INICIO RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->
								<div class="row"  style="padding-left: 10px; width: 100%;">
									<div class="rubro-registrado">
										<div class="row" style="margin-left: 5px;">
											<h3><span><?= Html::encode(Yii::t('frontend', 'Categories Registers ')) ?></span></h3>
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
<!-- FIN DE RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->

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
	    									'dataProvider' => $dataProviderRubroAnexar,
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
																				'value' => 5,
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


