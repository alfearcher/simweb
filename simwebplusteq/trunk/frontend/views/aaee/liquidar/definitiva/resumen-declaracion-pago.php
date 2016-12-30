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
 *  @file resumen-declaracion-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view resumen-declaracion-pago
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
	use backend\models\utilidad\exigibilidad\ExigibilidadSearch;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="view-resumen-declaracion-pago">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-resumen-declaracion-pago-form',
 			'method' => 'post',
 			'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-top: 0px;width:100%;">
							<div class="col-sm-3" style="width: 100%;">
								<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
							</div>
						</div>

						<div class="row" id="id-detalle-resumen-declaracion-pago" style="width: 100%;padding-top: 10px;">
							<?= GridView::widget([
									'id' => 'grid-declaracion',
									'dataProvider' => $dataProvider,
									'headerRowOptions' => ['class' => 'success'],
									'summary' => '',
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],

										[
						                    'label' => Yii::t('frontend', 'Año'),
						                     'contentOptions' => [
						                    	//'style' => 'text-align: center',
						                    ],
						                    'value' => function($data) {
	                										return $data['ano_impositivo'];
	        											},
						                ],
										[
						                    'label' => Yii::t('frontend', 'Rubro'),
						                    'value' => function($data) {
	                										return $data['rubro'];
	        											},
						                ],
						            	[
						                    'label' => Yii::t('frontend', 'Descripcion'),
						                    'value' => function($data) {
	                										return $data['descripcion'];
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Declaracion'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['declaracion'], 2);
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Minimo'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['minimo'], 2);
	        											},
						                ],
						                [
						                    'label' => Yii::t('frontend', 'Impuesto'),
						                    'contentOptions' => [
						                    	'style' => 'text-align: right',
						                    ],
						                    'value' => function($data) {
	                										return Yii::$app->formatter->asDecimal($data['impuesto'], 2);
	        											},
						                ],

						        	]
								]);?>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-3" style="width: 70%;text-align: right;">
								<h3><strong><?=Html::encode(Yii::t('frontend','Total por impuesto:'))?></strong></h3>
							</div>
							<div class="col-sm-3" style="width: 28%;padding:0px;padding-top: 12px;padding-right: 15px;">
								<?=Html::textInput('total-impuesto', Yii::$app->formatter->asDecimal($sumaImpuesto, 2),[
																		'class' => 'form-control',
																		'style' => 'width: 100%;
																					background-color: white;
																					text-align:right;
																					font-size:large;font-weight: bold;',
																		'readOnly' => true,
								])?>
							</div>
						</div>
					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>


					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Descargar planilla'),
																		  [
																			'id' => 'btn-descargar',
																			'class' => 'btn btn-success',
																			'value' => 1,
																			'style' => 'width: 100%;',
																			'name' => 'btn-descargar',
																			'data' => [
																				'method' => 'post',
																				'params' => [
																					'planilla' => 0,
																				]
																			]

																		  ])
								?>
							</div>
						</div>

					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
