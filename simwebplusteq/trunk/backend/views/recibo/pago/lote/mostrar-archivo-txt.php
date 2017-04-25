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
 *  @file mostrar-archivo-txt.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view mostrar-archivo-txt.php
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
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\bootstrap\Progress;


 ?>


<?php

	$form = ActiveForm::begin([
		'id' => 'id-lista-archivo',
		'method' => 'post',
		//'action' => ['mostrar-archivo-txt'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

<?=Html::hiddenInput('data-path', $ruta);?>
<?=Html::hiddenInput('data-file', $archivo);?>

<?=$form->field($model, 'id_banco')->hiddenInput(['value' => $model->id_banco])->label(false);?>
<?=$form->field($model, 'fecha_pago')->hiddenInput(['value' => $model->fecha_pago])->label(false);?>

<div class="row" style="width:100%;padding: 0px;margin: 0px;">
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;padding-top:10px;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Monto Total:'))?> </strong></h4>
	</div>
	<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
		<h4><strong><?=Html::textInput('total-txt', Yii::$app->formatter->asDecimal($montoTotal, 2),
				 								[
				 									'class' => 'form-control',
				 									'style' => 'width: 100%;
				 												text-align:right;
				 									            font-size:120%;
				 									            font-weight:bold;',
												]);
				?>
		</strong></h4>
	</div>
</div>

<div class="row" style="width:100%;padding: 0px;margin: 0px;">
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;">
			<h4><strong><?=Html::encode(Yii::t('backend', 'Total Registros:'))?> </strong></h4>
		</div>
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
			<h4><strong><?=Html::textInput('total-registro', $totalRegistro,
							 								[
							 									'class' => 'form-control',
							 									'style' => 'width: 100%;
							 												text-align:right;
							 									            font-size:120%;
							 									            font-weight:bold;',
															]);
					?>
			</strong></h4>
		</div>
	</div>

<div class="col-sm-2" style="margin-left: 40px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Analizar Archivo'),
											  [
												'id' => 'btn-analize-file',
												'class' => 'btn btn-primary',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-analize-file',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Back'),
											  [
												'id' => 'btn-back',
												'class' => 'btn btn-danger',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-back',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Quit'),
											  [
												'id' => 'btn-quit',
												'class' => 'btn btn-danger',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-quit',
											  ])
		?>
	</div>
</div>

 <div class="row" style="width:98%;margin-left: 25px;">
	<div class="lista-pago">
		<div class="row" style="width: 100%;margin-top: 5px;">
	    	<?= GridView::widget([
	    		'id' => 'id-grid-mostrar-archivo-txt',
	    		'dataProvider' => $dataProvider,
	    		'headerRowOptions' => [
	    			'class' => 'success',
	    		],
	    		'tableOptions' => [
	    			'class' => 'table table-hover',
	    			],
	    		'summary' => '',
	    		'columns' => [
	    			[
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chkRecibo',
                        'multiple' => false,
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                        	return [
            					'onClick' => 'javascript: return false;',
	                            'checked' => true,
            				];
                        	//if ( $model['bloquear'] == 1 ) {
                				// return [
                				// 	'disabled' => 'disabled',
                				// ];
                			//}
                        }
                    ],

	    			['class' => 'yii\grid\SerialColumn'],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'recibo'),
	                    'value' => function($data, $key) {
                    					return $data['recibo'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                    	'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto recibo'),
	                    'value' => function($data, $key) {
                    					return $data['monto_recibo'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                    	'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'fecha pago'),
	                    'value' => function($data, $key) {
                    					return $data['fecha_pago'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto efectivo'),
	                    'value' => function($data, $key) {
                    					return $data['monto_efectivo'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto cheque'),
	                    'value' => function($data, $key) {
                    					return $data['monto_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'cuenta cheque'),
	                    'value' => function($data, $key) {
                    					return $data['cuenta_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero cheque'),
	                    'value' => function($data, $key) {
                    					return $data['nro_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'fecha cheque'),
	                    'value' => function($data, $key) {
                    					return $data['fecha_cheque'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto TDD'),
	                    'value' => function($data, $key) {
                    					return $data['monto_tdd'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero TDD'),
	                    'value' => function($data, $key) {
                    					return $data['nro_tdd'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto TDC'),
	                    'value' => function($data, $key) {
                    					return $data['monto_tdc'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero TDC'),
	                    'value' => function($data, $key) {
                    					return $data['nro_tdc'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto transferencia'),
	                    'value' => function($data, $key) {
                    					return $data['monto_transferencia'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'numero transaccion'),
	                    'value' => function($data, $key) {
                    					return $data['nro_transaccion'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'monto total'),
	                    'value' => function($data, $key) {
                    					return $data['monto_total'];
	        			           },
	                ],

	                [
	                    'contentOptions' => [
	                          'style' => 'font-size: 90%;',
	                    ],
	                    'label' => Yii::t('backend', 'cuenta recaudadora'),
	                    'value' => function($data, $key) {
                    					return $data['nro_cuenta_recaudadora'];
	        			           },
	                ],

	        	]
	    	]);?>
	    </div>
	</div>
</div>

<div class="row">
	<div class="row" style="width:100%;padding: 0px;margin: 0px;">
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;padding-top:10px;">
			<h4><strong><?=Html::encode(Yii::t('backend', 'Monto Total:'))?> </strong></h4>
		</div>
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
			<h4><strong><?=Html::textInput('total-txt', Yii::$app->formatter->asDecimal($montoTotal, 2),
					 								[
					 									'class' => 'form-control',
					 									'style' => 'width: 100%;
					 												text-align:right;
					 									            font-size:120%;
					 									            font-weight:bold;',
													]);
					?>
			</strong></h4>
		</div>
	</div>

	<div class="row" style="width:100%;padding: 0px;margin: 0px;">
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;padding-left: 100px;">
			<h4><strong><?=Html::encode(Yii::t('backend', 'Total Registros:'))?> </strong></h4>
		</div>
		<div class="col-sm-3" style="width: 20%;padding: 0px;margin: 0px;">
			<h4><strong><?=Html::textInput('total-registro', $totalRegistro,
							 								[
							 									'class' => 'form-control',
							 									'style' => 'width: 100%;
							 												text-align:right;
							 									            font-size:120%;
							 									            font-weight:bold;',
															]);
					?>
			</strong></h4>
		</div>
	</div>
</div>


<div class="col-sm-2" style="margin-left: 40px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Analizar Archivo'),
											  [
												'id' => 'btn-analize-file',
												'class' => 'btn btn-primary',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-analize-file',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Back'),
											  [
												'id' => 'btn-back',
												'class' => 'btn btn-danger',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-back',
											  ])
		?>
	</div>
</div>

<div class="col-sm-2" style="margin-left: 20px;">
	<div class="form-group">
		<?= Html::submitButton(Yii::t('backend', 'Quit'),
											  [
												'id' => 'btn-quit',
												'class' => 'btn btn-danger',
												'value' => 1,
												'style' => 'width: 100%',
												'name' => 'btn-quit',
											  ])
		?>
	</div>
</div>

<?php ActiveForm::end(); ?>