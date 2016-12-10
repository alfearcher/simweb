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
		        	<div class="col-sm-4" style="width: 70%;">
		        		<h3><?= Html::encode(Yii::t('frontend', $caption)) ?></h3>
		        	</div>
		        	<div class="col-sm-3" style="width: 25%; float:right; padding-right: 50px;">
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
			        	<div class="row" style="margin: -25px; padding-top: 20px; width: 105%;">
							<?= GridView::widget([
									'id' => 'grid-declaracion',
									'dataProvider' => $dataProvider,
									'headerRowOptions' => ['class' => 'success'],
									//'filterModel' => $searchModel,
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Año'),
						                     'contentOptions' => [
						                    	//'style' => 'text-align: center',
						                    ],
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
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($model) {
	                										return Yii::$app->formatter->asDecimal($model->estimado, 2);
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Definitiva'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($model) {
	                										return Yii::$app->formatter->asDecimal($model->reales, 2);
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

						<div class="row">
							<div class="form-group">

								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Boletin: ' . $caption),
																				[$urlBoletin],
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


						<div class="row" style="border-bottom: 1px solid #ccc;margin-top: 20px;;margin-bottom: 20px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'HISTORICO LAPSO: ') . $lapso['a'] . ' - ' . $lapso['p'])?></strong></h4>
						</div>

						<div class="row" style="margin: -25px; padding-top: 20px; width: 105%;">
							<?= GridView::widget([
									'id' => 'grid-historico-declaracion',
									'dataProvider' => $dataProviderHistorico,
									'headerRowOptions' => ['class' => 'success'],
									'columns' => [
										//['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Id'),
						                   	'contentOptions' => [
						                    	'style' => 'text-align: center;font-size: 85%;',
						                    ],
						                    'value' => function($model) {
	                										return $model->id_historico;
	        											},
						                ],

										[
						                    'label' => Yii::t('frontend', 'Fecha/Hora'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: center;font-size: 85%;',
						                    ],
						                    'value' => function($model) {
	                										return $model->fecha_hora;
	        											},
						                ],

						            	[
						                    'label' => Yii::t('frontend', 'Tipo'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: center;font-size: 85%;',
						                    ],
						                    'value' => function($model) {
	                										return $model->tipoDeclaracion->descripcion;
	        											},
						                ],

						                [
				                            'label' => 'Certificado',
				                            'format' => 'raw',
				                            'value' => function($data) {
				                            	return Html::a($data['serial_control'],
				                            			 		Url::to(['generar-certificado', 'id' => $data['id_historico'], 'idC' => $data['id_contribuyente']]),
				                            			 		[
				                            			 			'class' => 'btn btn-success',
				                            			 			'style' => 'font-size: 85%;',
				                            			 			'title' => 'Certificado ' . $data['serial_control'],
				                            			 			'target' => '_blank',
				                            			 		]);
				                            },
				                        ],

				                        [
				                            'label' => 'Comprobante',
				                            'format' => 'raw',
				                            'value' => function($data) {
				                            	return Html::a($data['serial_control'],
				                            			 		Url::to(['generar-comprobante', 'id' => $data['id_historico'], 'idC' => $data['id_contribuyente']]),
				                            			 		[
				                            			 			'class' => 'btn btn-primary',
				                            			 			'style' => 'font-size: 85%;',
				                            			 			'title' => 'Comprobante ' . $data['serial_control'],
				                            			 			'target' => '_blank',
				                            			 		]);
				                            },
				                        ],

						                [
						                    'label' => Yii::t('frontend', 'Observación'),
						                    'contentOptions' => [
						                    	'style' => 'font-size: 85%;',
						                    ],
						                    'value' => function($model) {
	                										return $model->observacion;
	        											},
						                ],

						        	]
								]);?>
						</div>
					</div>	<!-- Fin de col-sm-12 -->
				</div> <!-- Fin de container-fluid -->

				<div class="row">
					<div class="form-group">

						<div class="col-sm-3" style="width: 20%;margin-left:50px;">
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

					</div>
				</div>

			</div>	<!-- Fin de panel-body -->
		</div>	<!-- Fin de panel panel-primary -->
	</div>		<!-- Fin de declaracion-existente -->
<?php ActiveForm::end(); ?>
</div>	 <!-- Fin  -->
