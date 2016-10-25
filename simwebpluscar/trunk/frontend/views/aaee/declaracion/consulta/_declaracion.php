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
 *  @file _declaracion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-10-2016
 *
 *  @view _declaracion.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use yii\widgets\DetailView;
	use yii\grid\GridView;
	use yii\widgets\ActiveForm;
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="declaracion">
 	<?php
 		$form = ActiveForm::begin([
 			'id' => 'id-declaracion',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<div class="declaracion-existente">
		<meta http-equiv="refresh">
	    <div class="panel panel-primary" style="width: 100%;">
	        <div class="panel-heading">
	        	<div class="row">
		        	<div class="col-sm-4">
		        		<h3><?= Html::encode(Yii::t('frontend', $caption)) ?></h3>
		        	</div>
		        	<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
		        		<style type="text/css">
							.col-sm-3 > ul > li > a:hover {
								background-color: #337AB7;
							}
		    			</style>
		        		<?= MenuController::actionMenuSecundario($opciones); ?>
		        	</div>
	        	</div>
	        </div>

	<!-- Cuerpo del formulario -->
	        <div class="panel-body">
	        	<div class="container-fluid">
	        		<div class="col-sm-12">
			        	<div class="row" style="margin: -20; padding-left: -10px; width: 105%;">
							<?= GridView::widget([
									'id' => 'grid-declaracion',
									'dataProvider' => $dataProvider,
									'headerRowOptions' => ['class' => 'success'],
									//'filterModel' => $searchModel,
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Año'),
						                    'value' => function($model) {
	                										return $model->actividadEconomica->ano_impositivo;
	        											},
						                ],
										[
						                    'label' => Yii::t('frontend', 'Rubro'),
						                    'value' => function($model) {
	                										return $model->rubroDetalle->rubro;
	        											},
						                ],
						            	[
						                    'label' => Yii::t('frontend', 'Descripcion'),
						                    'value' => function($model) {
	                										return $model->rubroDetalle->descripcion;
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Estimada'),
						                    'value' => function($model) {
	                										return $model->estimado;
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Definitiva'),
						                    'value' => function($model) {
	                										return $model->reales;
	        											},
						                ],

						                // [
						                //     'label' => Yii::t('frontend', 'Condition'),
						                //     'value' => function($model) {
	               //  										return $model->estatusSolicitud->descripcion;
	        							// 				},
					                	// ],
						        	]
								]);?>
						</div>
					</div>	<!-- Fin de col-sm-12 -->
				</div> <!-- Fin de container-fluid -->

				<div class="row">
					<div class="form-group">
						<div class="col-sm-3" style="width: 20%;margin-left:100px;">
							<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																				  [
																					'id' => 'btn-quit',
																					'class' => 'btn btn-danger',
																					'value' => 1,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-quit',

																				  ]);
							?>
						</div>

						<div class="col-sm-3" style="width: 20%;margin-left:100px;">
							<?= Html::submitButton(Yii::t('frontend', 'Back'),
																	  [
																		'id' => 'btn-back-form',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																		'name' => 'btn-back-form',

																	  ]);
							?>
						</div>


						<div class="col-sm-3" style="width: 20%;margin-left:100px;">
							<?= Html::a(Yii::t('frontend', 'Descargar Boletin'),
																		['generar-boletin-estimada'],
																	  	[
																			'id' => 'btn-boletin',
																			'class' => 'btn btn-primary',
																			'value' => $lapso['tipo'],
																			'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																			'name' => 'btn-boletin',
																			'target' => '_blank',

																	  	]);
							?>
						</div>

					</div>
				</div>

			</div>	<!-- Fin de panel-body -->
		</div>	<!-- Fin de panel panel-primary -->
	</div>		<!-- Fin de declaracion-existente -->
<?php ActiveForm::end(); ?>
</div>	 <!-- Fin  -->
