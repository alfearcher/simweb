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
 *  @file listado-vehiculo-existente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view listado-vehiculo-existente.php
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

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="lista-vehiculo-existentes">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-vehiculo-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>
						<div class="row" id="id-rubro-vehiculo" style="padding: 0px;">
							<?= GridView::widget([
								'id' => 'id-grid-rubro-vehiculo',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'tableOptions' => [
                    				'class' => 'table table-hover table-bordered',
              					],
								'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'id'),
				                        'value' => function($data) {
				                                   		return $data['id_impuesto'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'placa'),
				                        'value' => function($data) {
				                                   		return $data['descripcion'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'modelo'),
				                        'value' => function($data) {
				                                   		return $data['modelo'];
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'color'),
				                        'value' => function($data) {
				                                   		return $data['color'];
				            			           },
				                    ],
				                    // [
				                    //     'contentOptions' => [
				                    //           'style' => 'font-size: 90%;',
				                    //     ],
				                    //     'label' => Yii::t('frontend', 'ultimo pago'),
				                    //     'value' => function($data) {
				                    //                		return $data['ultimoPago'];
				            			     //       },
				                    // ],

				                    [
                                    	'class' => 'yii\grid\ActionColumn',
                                    	//'header'=> 'Seleccionar',
                                    	'template' => '{view}',
                                    	'buttons' => [
                                        	'view' => function ($url, $model, $key) {
                                            			return Html::submitButton('Seleccionar',
		                                                                        [
		                                                                        	'class' => 'btn btn-success',
		                                                                            'value' => $key,
		                                                                            'name' => 'id',
		                                                                            'data' => [
		                                                                            	'method' => 'post',
		                                                                            	'params' => [
		                                                                            		'id_contribuyente' => $model['id_contribuyente'],
		                                                                            		'placa' => $model['descripcion'],
		                                                                            	],

		                                                                            ],
		                                                                            'title' => Yii::t('backend', $key),
		                                                                            'style' => 'margin: 0 auto; display: block;',

		                                                                        ]);
                                        			},

                                    	],
                                	],

					        	]
							]);?>
						</div>
					</div>

					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Quit'),
																		  [
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 100%;',
																			'name' => 'btn-quit',

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


