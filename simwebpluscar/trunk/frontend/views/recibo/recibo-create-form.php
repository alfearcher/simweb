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
 *  @file recibo-create-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view recibo-create-form
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
	use yii\widgets\Pjax;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>

<div class="recibo-pago-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-recibo-create-form',
 			'method' => 'post',
 			//'action'=> $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 95%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
 <!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width: 105%;padding-left: 10px;">
						<div class="col-sm-4" style="margin-left:0px;padding-left:0; width: 40%;">

							<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
								<h4><?= Html::encode(Yii::t('frontend', 'Deuda por impuestos')) ?></h4>
							</div>

							<div class="row" class="deuda-por-impuesto" style="padding-top: 10px;">

								<?= GridView::widget([
									'id' => 'grid-deuda-general-contribuyente',
									'dataProvider' => $dataProvider,
									//'filterModel' => $model,
									'summary' => '',
									'columns' => [

						            	[
						            		'contentOptions' => [
						                    	'style' => 'font-size: 90%;width:20%;text-align:center;',
						                	],
						                    'label' => Yii::t('frontend', 'Impuesto'),
						                    'value' => function($data) {
	                										return $data['impuesto'];
	        											},
						                ],
						                [
						                	'contentOptions' => [
						                    	'style' => 'font-size: 90%;',
						                	],
						                    'label' => Yii::t('frontend', 'Descripcion'),
						                    'value' => function($data) {
	                										return $data['descripcion'];
	        											},
						                ],

						                [
						                	'contentOptions' => [
						                    	'style' => 'font-size: 90%;text-align:right;',
						                	],
						                    'class' => 'yii\grid\ActionColumn',
				                    		'header'=> Yii::t('backend','Deuda'),
				                    		'template' => '{view}',
				                    		'buttons' => [
				                        		'view' => function ($url, $model, $key) {
				                        				$url =  Url::to(['buscar-deuda']);
				                           				return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. $model['deuda'] .'</center></div>',
					                        							[
					                        								'id' => 'id-deuda',
					                        								'value' => json_encode([
					                        												'view' => 1,
					                        												'i' => $model['impuesto'],
					                        												'idC' => $model['id_contribuyente']
					                        											]),
							                        						'name' => 'id',
							                        						'class' => 'btn btn-default',
							                        						'title' => 'deuda '. $model['deuda'],
							                        						//'data-url' => $url,
							                        						'style' => 'text-align:right;',
										                        		]
									                        		);
				                        				},
						                	],
						                ],
						        	]
								]);?>
							</div>

							<div class="row" style="padding-top: 0px;margin-top: -10px;background-color: #F1F1F1;">
								<div class="col-sm-3" style="width: 45%;text-align: right;">
									<h3><strong><p>Total:</p></strong></h3>
								</div>
								<div class="col-sm-3" style="width: 55%;text-align: right;">
									<h3><strong><p><?=Html::encode($total)?></p></strong></h3>
								</div>
							</div>
						</div>


						<div class="col-sm-4" style="margin-left:40px;margin-top:0px;padding-left:0; width: 40%;">
							<?php Pjax::begin() ?>
							<div class="deuda-en-periodo">
								<?=$html;?>
							</div>
							<?php Pjax::end() ?>
						</div>

					</div>

					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Create')),
																					  [
																						'id' => 'btn-create',
																						'class' => 'btn btn-success',
																						'value' => 1,
																						'style' => 'width: 80%',
																						'name' => 'btn-create',
																					  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Quit')),
																					  [
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'value' => 1,
																						'style' => 'width: 80%',
																						'name' => 'btn-quit',
																					  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para salir de la actualizacion -->
					</div>

				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>

<?php
	$script ='
		$( "#id-deuda" ).click(function() {
			alert("hola");
		})';

	$this->registerJs($script);
?>