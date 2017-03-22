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
 *  @file forma-deposito.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view forma-deposito.php
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
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-forma-deposito-form',
		'method' => 'post',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

	<?=$form->field($model, 'linea')->hiddenInput(['value' => $model->linea])->label(false)?>
	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false)?>
	<?=$form->field($model, 'id_forma')->hiddenInput(['value' => $model->id_forma])->label(false)?>
	<?=$form->field($model, 'cuenta')->hiddenInput(['value' => $model->cuenta])->label(false)?>
	<?=$form->field($model, 'cheque')->hiddenInput(['value' => $model->cheque])->label(false)?>
	<?=$form->field($model, 'conciliado')->hiddenInput(['value' => $model->conciliado])->label(false)?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false)?>
	<?=$form->field($model, 'codigo_banco')->hiddenInput(['value' => $model->codigo_banco])->label(false)?>
	<?=$form->field($model, 'cuenta_deposito')->hiddenInput(['value' => $model->cuenta_deposito])->label(false)?>
	<?=$form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false)?>
	<?=$form->field($model, 'banco')->hiddenInput(['value' => $model->banco])->label(false)?>
	<?=$form->field($model, 'monto')->hiddenInput(['value' => $model->monto])->label(false)?>

<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
	<h4><strong><?=Yii::t('backend', 'Datos del ' . $caption)?></strong></h4>
</div>


<!-- NUMERO DE DEPOSITO -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Nro. Deposito'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:25%; padding: 0px;margin:0px;padding-right: 5px;">
		<div class="deposito">
			<?=$form->field($model, 'deposito')->textInput([
														'id' => 'id-deposito',
														'style' => 'width: 100%;
														 font-size:130;
														 font-weight:bold;',
													])->label(false)
			?>
		</div>
	</div>
</div>
<!-- FIN DEL NUMERO DE DEPOSITO -->

<!-- FECHA DEL DEPOSITO -->
<div class="row" style="width: 100%;padding: 0px;">
	<div class="col-sm-2" style="margin-right:45px;">
		<p><strong><?=Html::encode(Yii::t('backend', 'Fecha'))?></strong></p>
	</div>
	<div class="col-sm-2" style="width:25%; padding: 0px;margin:0px;">
		<div class="fecha">
			<?= $form->field($model, 'fecha')->widget(\yii\jui\DatePicker::classname(),[
															  'clientOptions' => [
																	'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																	'changeMonth' => true,
																	'changeYear' => true,
																],
															  'language' => 'es-ES',
															  'dateFormat' => 'dd-MM-yyyy',
															  'options' => [
															  		'id' => 'id-fecha',
																	'class' => 'form-control',
																	'readonly' => true,
																	'style' => 'background-color:white;
																			    width:55%;
																				font-size:120;
												 								font-weight:bold;',
																]
																])->label(false) ?>
		</div>
	</div>
</div>
<!-- FIN DE FECHA DE DEPOSITO -->


<div class="row" style="width:100%;">
	<div class="col-sm-2" style="margin-left: 10px;">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Guardar'),
											  [
												'id' => 'btn-add-forma',
												'class' => 'btn btn-primary',
												'value' => $model->id_forma,
												'style' => 'width: 120%',
												'name' => 'btn-add-forma',
											  ])
			?>
		</div>
	</div>

	<div class="col-sm-2">
		<?php if ( count($operacion) > 0 ) {
			foreach ( $operacion as $key => $value ) {
				echo Html::tag('li', $value, ['style' => 'color:red;']);
			}

		}?>
	</div>
</div>



<div class="row" style="width:100%;border-bottom: 0.5px solid  #ccc;padding:0px;padding-left:5px;margin-bottom: 15px;">
	<h4><strong><?=Yii::t('backend', 'Detalle(s) del deposito')?></strong></h4>
</div>

<!-- FORMA DE PAGO REGISTRADAS -->
<div class="row" style="width: 100%;margin-top: 5px;">
	<?= GridView::widget([
		'id' => 'id-grid-vauche-registrado',
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
   //              'contentOptions' => [
   //                    'style' => 'font-size: 90%;',
   //              ],
   //              'label' => Yii::t('frontend', 'Deposito'),
   //              'value' => function($data) {
   //                         		return $data['deposito'];
   //  			           },
   //          ],

			[
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Tipo'),
                'value' => function($data) {
                           		return $data['forma'];
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Cuenta'),
                'value' => function($data) {
                				if ( $data['tipo'] == 2 ) {
                           			return $data['codigo_cuenta'] . $data['cuenta'];
                           		} else {
									return $data['cuenta'];
                           		}
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Nro de Cheque'),
                'value' => function($data) {
                				if ( $data['tipo'] == 1 ) {
                           			return $data['cheque'];
                           		} else {
                           			return $data['cheque'];
                           		}
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 100%;
                      			  text-align:right;
                      			  font-weight:bold;',
                ],
                'label' => Yii::t('frontend', 'monto'),
                'value' => function($data) {
                				return Yii::$app->formatter->asDecimal($data['monto'], 2);
    			           },
            ],

            [
            	'class' => 'yii\grid\ActionColumn',
            	'header' => 'Suprimir',
            	'template' => '{delete}',
            	'buttons' => [
            		'delete' => function($url, $data, $key) {
            			return Html::a('<center><span class="fa fa-times fa-lg"></center></span>',
            							['suprimir-detalle-vauche', 'id' => $key, 'l' => $data['linea'], 'recibo' => $data['recibo']],
            							[
            								'style' => 'font-size:140%;color:red;'
            							]);
            		}
            	],
            ],

    	]
	]);?>
</div>
<!-- FIN DE FORMA DE PAGO REGISTRADAS -->





<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
</div>

<?php ActiveForm::end(); ?>