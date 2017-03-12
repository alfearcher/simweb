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
 *  @file _registar-formas-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-02-2017
 *
 *  @view _registar-formas-pago.php
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
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-registrar-forma-pago-form',
		'method' => 'post',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption . '. ' . $captionRecibo) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

					<div class="row" style="margin-top: 25px;">

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width: 20%;padding: 0px;padding-top: 25px;margin-bottom: 20px;">
								<div class="recibo" style="margin-left: 0px;">
									<?= Html::textInput('recibo',
														 $datosRecibo[0]['recibo'],
														 [
															'id' => 'id-recibo',
															'class' => 'form-control',
															'style' => 'width:100%;
																		background-color:white;
																		font-weight:bold;
																		text-align:right;
																		font-size:140%;;',
															'readOnly' => true,
														])
									?>
								</div>
							</div>

							<div class="col-sm-2" style="width: 25%;padding: 0px;padding-left: 15px;padding-top: 25px;">
								<div class="monto" style="margin-left: 0px;">
									<?= Html::textInput('montoRecibo',
													     Yii::$app->formatter->asDecimal($datosRecibo[0]['monto'], 2),
													     [
													     	'id' => 'id-monto-recibo',
													     	'class' => 'form-control',
															'style' => 'width:100%;
															background-color:white;
															font-weight:bold;
															text-align:right;
															font-size:140%;',
															'readOnly' => true,
													     ])
									?>
								</div>
							</div>


							<div class="col-sm-2" style="width: 25%;padding: 0px;padding-left: 15px;padding-top: 25px;">
								<div class="monto-sobrante" style="margin-left: 0px;">
									<?= Html::textInput('montoSobrante',
													     Yii::$app->formatter->asDecimal($montoSobrante, 2),
													     [
													     	'id' => 'id-monto-sobrante',
													     	'class' => 'form-control',
															'style' => 'width:100%;
															background-color:white;
															font-weight:bold;
															text-align:right;
															font-size:140%;',
															'readOnly' => true,
													     ])
									?>
								</div>
							</div>


							<div class="col-sm-2" style="width: 20%;padding-top: 15px;padding-left: 80px;">
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown">
										Formas de Pago <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu" style="background-color: white;">
										<?php
											foreach ( $listaForma as $key => $value ) {
												echo Html::tag('li',
															   Html::button($value,
															    		[
															    	  		'value' => Url::to(['view-forma-pago', 'forma' => $key,]),
															    	  		'class' => 'form-control',
															    	  		'id' => 'id-' . strtolower($value),
															    	  	]),
															   ['id' => 'id-forma-pago2']
															);
											}
											// ['registrar-formas-pago', 'id' => $key, 'd' => $datosRecibo[0]['recibo']]
										?>
									</ul>
								</div>
							</div>
						</div>

<!-- INICIO DEL FORMULARIO MODAL -->

						<style type="text/css">
							.modal-content {
								margin-top: 150px;

							}
						</style>
						<div class="row">
							<?php
								Modal::begin([
									'header' => 'Forma de pagos',
									'id' => 'modal',
									'size' => 'modal-lg',
									'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>',
								]);

								echo "<div id='modalContent' style='padding-left: 20px;'></div>";

								Modal::end();
							 ?>
						</div>

<!-- FINAL DEL FORMULARIO MODAL -->


						<div class="row" style="padding: 0px;padding-left: 15px;">
							<?php if ( $htmlFormaPago !== null ) { ?>
								<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
								</div>
								<div class="formas-pago"><?=$htmlFormaPago;?></div>
								<div class="row" style="width:100%;border-bottom: 0.5px solid;padding:0px;padding-left:5px;margin-bottom: 15px;">
								</div>
							<?php } ?>
						</div>


<!-- FORMA DE PAGO REGISTRADAS -->
						<div class="row" style="width: 100%;">
							<?= GridView::widget([
								'id' => 'id-grid-forma-pago-registrada',
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

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Forma de Pago'),
				                        'value' => function($data) {
				                                   		return $data['formaPago']['descripcion'];
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Fecha'),
				                        'value' => function($data) {
				                                   		return $data['fecha'];
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Deposito'),
				                        'value' => function($data) {
				                                   		return $data['deposito'];
				            			           },
				                    ],

				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Cheque'),
				                        'value' => function($data) {
				                                   		return $data['cheque'];
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

					        	]
							]);?>
						</div>

<!-- FIN DE FORMA DE PAGO REGISTRADAS -->

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="margin-left: 50px;">
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

							<div class="col-sm-2" style="margin-left: 50px;">
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
						</div>

						<!-- <div class="col-sm-2" style="margin-left: 50px;">
							 <div class="form-group">
							 '../../common/docs/user/ayuda.pdf'  funciona
								<?//= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
													// 	'id' => 'btn-help',
													// 	'class' => 'btn btn-default',
													// 	'name' => 'btn-help',
													// 	'target' => '_blank',
													// 	'value' => 1,
													// 	'style' => 'width: 100%;'
													// ])?>

							</div>
						</div> -->
<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>

