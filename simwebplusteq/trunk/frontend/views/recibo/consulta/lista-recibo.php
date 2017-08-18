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
 *  @file lista-recibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view pre-view-recibo-create-form
 *  @brief vista principal del formulario para la creacion de los recibos de pago.
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


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

?>

<div class="lista-recibo">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-recibo',
 			'method' => 'post',
 			//'action'=> '#',
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 95%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

					<div class="row" style="width: 100%;margin-top: 10px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', 'Lista de Recibos'))?></h4>
						</div>

						<div class="row" id="lista-recibo">
							<?= GridView::widget([
								'id' => 'grid-lista-recibo',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'tableOptions' => [
                    				'class' => 'table table-hover',
              					],
								'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

				                    // [
				                    //     'contentOptions' => [
				                    //           'style' => 'font-size: 90%;',
				                    //     ],
				                    //     'label' => Yii::t('frontend', 'recibo'),
				                    //     'value' => function($data) {
				                    //                		return $data['recibo'];
				            			     //       },
				                    // ],
				                    [
					                	'contentOptions' => [
					                    	'style' => 'font-size: 90%;text-align:center;width:22%;',
					                	],
				                    	'class' => 'yii\grid\ActionColumn',
				            			'header'=> Yii::t('frontend', 'Recibo'),
				            			'template' => '{view}',
				            			'buttons' => [
				                			'view' => function ($url, $model, $key) {
				                   					return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. $model['recibo'] .'</center></div>',
				                    							[
				                    								'id' => 'id-recibo',
					                        						'name' => 'id',
					                        						'value' => $model['recibo'],
					                        						'class' => 'btn btn-default',
					                        						'title' => 'recibo '. $model['recibo'],
								                        		]
							                        		);
				                				},
				                		],
				                	],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;width:10%;',
				                        ],
				                        'label' => Yii::t('frontend', 'fecha'),
				                        'value' => function($data) {
				                                   		return date('d-m-Y', strtotime($data['fecha']));
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:right;',
				                        ],
				                        'label' => Yii::t('frontend', 'monto'),
				                        'value' => function($data) {
				                                    	return Yii::$app->formatter->asDecimal($data['monto'], 2);
				        					       },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;text-align:center;',
				                        ],
				                        'label' => Yii::t('frontend', 'condicion'),
				                        'value' => function($data) {
				                                   		return $data->condicion->descripcion;
				            			           },
				                    ],

					        	]
							]);?>

						</div>
					</div>

					<div class="row">
						<div class="col-sm-3" style="width:20%;padding-top: 15px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('frontend', 'Back'),
																		[
																			'id' => 'btn-back',
																			'class' => 'btn btn-danger',
																			'value' => 2,
																			'style' => 'width: 100%',
																			'name' => 'btn-back',
																		])
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
