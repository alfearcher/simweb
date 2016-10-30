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
 *  @file view-solicitud-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-10-2016
 *
 *  @view view-solicitud-create.php
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
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="view-anexo-ramo-creada">
	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-4">
	        		<h3><?= Html::encode(Yii::t('frontend', 'Request Nro. ' . $model[0]->nro_solicitud) . ' ' . Yii::t('backend', 'Summary')) ?></h3>
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
								<!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">
					<div class="row" style="width:95%;margin-left: 5px;margin-bottom: 15px;border-bottom: solid 1px #ccc;">
						<h3><span><?= Html::encode(Yii::t('frontend', 'Declaracion del IVA')) ?></span></h3>
					</div>

		        	<div class="row" style="padding-left: 15px; width: 50%;">
						<?= DetailView::widget([
								'model' => $model[0],
								'attributes' => [
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_enero')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_enero, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_febrero')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_febrero, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_marzo')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_marzo, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_abril')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_abril, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_mayo')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_mayo, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_junio')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_junio, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_julio')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_julio, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_agosto')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_agosto, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_septiembre')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_septiembre, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_octubre')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_octubre, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_noviembre')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_noviembre, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('iva_diciembre')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->iva_diciembre, 2),
									],
									[
										'label' => Yii::t('frontend', 'Total'),
										'value' => Yii::$app->formatter->asDecimal($model[0]->iva_enero + $model[0]->iva_febrero +
																				   $model[0]->iva_marzo + $model[0]->iva_abril +
																				   $model[0]->iva_mayo + $model[0]->iva_junio +
																				   $model[0]->iva_julio + $model[0]->iva_agosto +
																				   $model[0]->iva_septiembre + $model[0]->iva_octubre +
																				   $model[0]->iva_noviembre + $model[0]->iva_diciembre, 2),
									],

									// [
									// 	'label' => Yii::t('frontend', 'Id. Taxpayer'),
									// 	'value' => $model['id_contribuyente'],
									// ],
								],
							])
						?>
					</div>

					<div class="row" style="width:95%;margin-left: 5px;margin-bottom: 15px;border-bottom: solid 1px #ccc;">
						<h3><span><?= Html::encode(Yii::t('frontend', 'Declaracion ISLR')) ?></span></h3>
					</div>

		        	<div class="row" style="padding-left: 15px; width: 50%;">
						<?= DetailView::widget([
								'model' => $model[0],
								'attributes' => [
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('islr')),
										'value' =>  Yii::$app->formatter->asDecimal($model[0]->islr, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('pp_industria')),
										'value' => Yii::$app->formatter->asDecimal($model[0]->pp_industria, 2),
									],
									[
										'label' => Yii::t('frontend', $model[0]->getAttributeLabel('pagos_retencion')),
										'value' => Yii::$app->formatter->asDecimal($model[0]->pagos_retencion, 2),
										'options' => [
											'class' => 'text-right',
										],
									],
									[
										'label' => Yii::t('frontend', 'Total'),
										'value' => Yii::$app->formatter->asDecimal($model[0]->islr + $model[0]->pp_industria +
																				   $model[0]->pagos_retencion, 2),
									],


									// [
									// 	'label' => Yii::t('frontend', 'Id. Taxpayer'),
									// 	'value' => $model['id_contribuyente'],
									// ],
								],
							])
						?>
					</div>


					<div class="row" style="width:95%;margin-left: 5px;margin-bottom: 15px;border-bottom: solid 1px #ccc;">
						<h3><span><?= Html::encode(Yii::t('frontend', 'Declaracion Definitiva')) ?></span></h3>
					</div>

		        	<div class="row" style="padding-left: 15px; width: 100%;">
						<?= GridView::widget([
								'id' => 'grid-declaracion-definitiva',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'danger'],
								//'filterModel' => $searchModel,
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
					                    'label' => Yii::t('frontend', 'Request'),
					                    'value' => function($model) {
                										return $model->nro_solicitud;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Request Description'),
					                    'value' => function($model) {
                										return $model->getDescripcionTipoSolicitud($model->nro_solicitud);
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Year'),
					                    'value' => function($model) {
                										return $model->ano_impositivo;
        											},
					                ],
					            	[
					                    'label' => Yii::t('frontend', 'Category'),
					                    'value' => function($model) {
                										return $model->rubro->rubro;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Descripcion'),
					                    'value' => function($model) {
                										return $model->rubro->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Monto Declaracion'),
					                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                ],
					                    'value' => function($model) {
                										return Yii::$app->formatter->asDecimal($model->monto_new, 2);
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Monto Anterior'),
					                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                ],
					                    'value' => function($model) {
                										return Yii::$app->formatter->asDecimal($model->monto_v, 2);
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Monto Minimo'),
					                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                ],
					                    'value' => function($model) {
                										return Yii::$app->formatter->asDecimal($model->monto_minimo, 2);
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Condition'),
					                    'value' => function($model) {
                										return $model->estatusSolicitud->descripcion;
        											},
				                	],
					        	]
							]);?>
					</div>

					<div class="row" style="border-top: 1px solid #ccc; padding-bottom: 10px;"></div>

					<div class="row">
						<div class="form-group">

							<?php if ( count($historico) > 0 ) { ?>
								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Declaracion ' . $historico[0]['serial_control']),
																				['generar-comprobante-definitiva', 'id' => $historico[0]['id_historico']],
																			  	[
																					'id' => 'btn-declaracion',
																					'class' => 'btn btn-default',
																					'value' => 3,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-declaracion',
																					'target' => '_blank',

																			  	]);
									?>
								</div>


								<div class="col-sm-3" style="width: 40%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Certificado ' . $historico[0]['serial_control']),
																				['generar-certificado-definitiva', 'id' => $historico[0]['id_historico']],
																			  	[
																					'id' => 'btn-certificado',
																					'class' => 'btn btn-primary',
																					'value' => 2,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-certificado',
																					'target' => '_blank',

																			  	]);
									?>
								</div>

								<div class="col-sm-3" style="width: 20%;margin-left:25px;">
									<?= Html::a(Yii::t('frontend', 'Descargar Boletin'),
																				['generar-boletin-definitiva'],
																			  	[
																					'id' => 'btn-boletin',
																					'class' => 'btn btn-primary',
																					'value' => 1,
																					'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																					'name' => 'btn-boletin',
																					'target' => '_blank',

																			  	]);
									?>
								</div>
							<?php } ?>
						</div>
					</div>

				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->
</div>	 <!-- Fin de inscripcion-sucursal-create -->
