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
 *  @file listado-pago-encontrado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-01-2017
 *
 *  @view listado-pago-encontrado.php
 *  @brief vista.
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

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\grid\GridView;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\jui\DatePicker;
	use backend\controllers\menu\MenuController;

?>
<div class="pago-seleccionado">
	<?php
		$form = ActiveForm::begin([
			'id' => 'pago-seleccionado-form',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>


	<!-- <?//=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Nueva Cuenta Recaudadora')?></strong></h4>
					</div>

<!-- INICIO BANCO -->
					<div class="row" style="width: 100%;padding: 0px;margin: 0px;margin-top: 15px;">
						<div class="col-sm-2" style="width: 17%;">
							<p><strong><?=$model->getAttributeLabel('codigo_banco')?></strong></p>
						</div>
						<div class="col-sm-2" style="width:60%;padding:0px;margin: 0px;">
							<?= $form->field($model, 'codigo_banco')
						              ->dropDownList($listaBanco, [
                                                         'id'=> 'id-codigo-banco',
                                                         'prompt' => Yii::t('backend', 'Select'),
                                                         'style' => 'width:100%;',
                                                         'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                               		           ->createUrl('/ajuste/pago/cuentarecaudadora/ajuste-cuenta-recaudadora/listar-cuenta-recaudadora') . '&id=' . '" + $(this).val(),
                                                                               		           			 function( data ) {
                                                                               		           			 	$( "select#id-cuenta-deposito" ).html( "" );
                                                                                                         	$( "select#id-cuenta-deposito" ).html( data );
                                                                                                   		}
                                                                                );'
                                    ])->label(false);
                        ?>
						</div>
					</div>
<!-- FIN DE BANCO -->

<!-- LISTA DE CUENTA RECAUDADORAS -->
					<div class="row" style="width:100%;padding:0px;margin:0px;">
						<div class="col-sm-2" style="width: 17%;">
							<p><strong><?=$model->getAttributeLabel('cuenta_deposito')?></strong></p>
						</div>
						<div class="col-sm-3" style="width:60%;padding:0px;margin-left:0px;">
							 <?= $form->field($model, 'cuenta_deposito')
							          ->dropDownList([], [
                                                'id'=> 'id-cuenta-deposito',
                                                'prompt' => Yii::t('backend', 'Select'),
                                                'style' => 'width:100%;',
                                           	])->label(false);
                            ?>
						</div>
					</div>
<!-- FIN DE LISTA DE CUENTA RECAUDADORAS -->

					<div class="row" style="border-bottom: 0.5px solid #ccc;"></div>

					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
						<h4><strong><?= Yii::t('backend', 'Listado de Pagos Seleccionados')?></strong></h4>
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
						<div class="row" style="width:100%;">
							<?= GridView::widget([
								'id' => 'id-grid-listado-pago-seleccionado',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'warning'],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkPagoConfirm',
				                        'multiple' => false,
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
				                				return [
				                					'onClick' => 'javascript: return false;',
			                            			'checked' => true,
				                				];

				                        }
				                    ],
						            [
						                'label' => Yii::t('backend', 'Recibo'),
						                'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->recibo;
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Fecha Pago'),
						                'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return date('d-m-Y', strtotime($model->depositoRecibo->fecha));
													},
						            ],

						            [
						                'label' => Yii::t('backend', 'Forma de Pago'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->formaPago->descripcion;
													},
										'visible' => false,
						            ],

						            [
						                'label' => Yii::t('backend', 'Monto'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;text-align:right;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return Yii::$app->formatter->asDecimal($model->depositoRecibo->monto, 2);
													},
						            ],
						            [
						                'label' => Yii::t('backend', 'Banco'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return isset($model->banco->nombre) ? $model->banco->nombre : '';
													},
						            ],
						            [
						                'label' => Yii::t('frontend', 'Cuenta Recaudadora'),
						                'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
														return $model->cuenta_deposito;
													},
						        	],

						    	]
							]);?>
						</div>
					</div>


					<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Actualizar Registros'),
																	  [
																		'id' => 'btn-update-seleccion',
																		'class' => 'btn btn-success',
																		'value' => 3,
																		'name' => 'btn-update-seleccion',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>

						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Back'),
																	  [
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-back',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>


						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Quit'),
																	  [
																		'id' => 'btn-quit',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-quit',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


