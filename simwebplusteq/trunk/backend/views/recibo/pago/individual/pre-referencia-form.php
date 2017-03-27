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
 *  @file pre-referencia-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view pre-referencia-form
 *  @brief vista principal
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
	//use yii\widgets\Pjax;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>

<div class="pre-referencia-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-referencia-form',
 			'method' => 'post',
 			'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?> -->


	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

		        	<div class="row" style="width:100%;margin-bottom: 20px;">
						<div class="row" style="border-bottom: 1px solid;padding-left: 5px;padding-top: 0px;">
							<h4><strong><?=Html::encode(Yii::t('backend', 'Información del Recibo'))?></strong></h4>
						</div>

						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 20px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Recibo Nro.:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:20%;padding:0px;margin-left:0px;">
								<?=Html::textInput('recibo',
											       $datosRecibo[0]['recibo'],
											       [
											       		'class' => 'form-control',
											       		'style' => 'width:100%;
											       					background-color:white;
											       					font-size:120%;
											       					font-weight:bold;text-align:right;',
											       		'readOnly' => true,
											       ])
								?>
							</div>
						</div>

						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Monto:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:20%;padding:0px;margin-left:0px;">
								<?=Html::textInput('monto',
											       Yii::$app->formatter->asDecimal($datosRecibo[0]['monto'], 2),
											       [
											       		'class' => 'form-control',
											       		'style' => 'width:100%;
											       					background-color:white;
											       					font-size:120%;
											       					font-weight:bold;
											       					text-align:right;',
											       		'readOnly' => true,
											       ])
								?>
							</div>
						</div>

						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Id. Contribuyente:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:20%;padding:0px;margin-left:0px;">
								<?=Html::textInput('id-contribuyente',
											       $datosRecibo[0]['id_contribuyente'],
											       [
											       		'class' => 'form-control',
											       		'style' => 'width:100%;
											       					background-color:white;
											       					font-size:120%;
											       					font-weight:bold;text-align:right;',
											       		'readOnly' => true,
											       ])
								?>
							</div>
						</div>


						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Fecha Pago:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:13%;padding:0px;margin-left:0px;">
								<?=Html::textInput('fecha-pago-recibo',
											       ( $datosRecibo[0]['estatus'] == 1 ) ? $datosRecibo[0]['fecha'] : '',
											       [
											       		'class' => 'form-control',
											       		'style' => 'width:100%;
											       					background-color:white;
											       					font-size:120%;
											       					font-weight:bold;',
											       		'readOnly' => true,
											       ])
								?>
							</div>
						</div>

						<div class="row" style="border-bottom: 1px solid;padding-left: 5px;padding-top: 0px;">
							<h4><strong><?=Html::encode(Yii::t('backend', 'Cuenta Recaudadora'))?></strong></h4>
						</div>

<!-- LISTA DE BANCOS CON CUENTAS RECAUDADORAS-->
						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 20px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Listado de Bancos:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:60%;padding:0px;margin-left:0px;">
								<?= $form->field($model, 'id_banco')
							              ->dropDownList($listaBanco, [
                                                             'id'=> 'id-banco',
                                                             'prompt' => Yii::t('backend', 'Select'),
                                                             'style' => 'width:100%;',
                                                             'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                   		           ->createUrl('/recibo/pago/individual/pago-recibo-individual/listar-cuenta-recaudadora') . '&id=' . '" + $(this).val(),
                                                                                   		           			 function( data ) {
                                                                                   		           			 	$( "select#id-cuenta-recaudadora" ).html( "" );
                                                                                                             	$( "select#id-cuenta-recaudadora" ).html( data );
                                                                                                       		}
                                                                                    );'
                                        ])->label(false);
                            ?>
							</div>
						</div>
<!-- FIN LISTA DE BANCOS CON CUENTAS RECAUDADORAS -->

<!-- LISTA DE CUENTA RECAUDADORAS -->
						<div class="row" style="width:100%;padding:0px;margin:0px;">
							<div class="col-sm-2" style="width: 20%;padding:0px;">
								<p><strong><?=Html::encode(Yii::t('backend', 'Cuenta Recaudadora:'))?></strong></p>
							</div>
							<div class="col-sm-3" style="width:60%;padding:0px;margin-left:0px;">
								 <?= $form->field($model, 'cuenta_recaudadora')
								          ->dropDownList([], [
	                                                      'id'=> 'id-cuenta-recaudadora',
	                                                      'prompt' => Yii::t('backend', 'Select'),
	                                                      'style' => 'width:100%;',
	                                                      // 'onchange' => '$.post( "' . Yii::$app->urlManager
	                                                      //                      		           ->createUrl('/recibo/pago/individual/pago-recibo-individual/listar-cuenta-recaudadora') . '&a=' . '" + $(this).val() +  "' .
	                                                      //                      		           															   '&i=' . '" + $("#impuesto").val(),
	                                                      //                      		           				 function( data ) {
	                                                      //                      		           				 	//$( "select#id-codigo" ).html( "" );
	                                                      //                      		           				 	$( "select#id-grupo-subnivel" ).html( "" );
	                                                      //                      		           				 	$( "select#codigo" ).html( "" );
	                                                      //                      		           				 	$( "#id-codigo-descripcion" ).html( "" );
	                                                      //                                                		$( "select#id-codigo" ).html( data );
	                                                      //                                          			}
	                                                       //                     );'
	                                                                ])->label(false);
	                            ?>
							</div>
						</div>
<!-- FIN DE LISTA DE CUENTA RECAUDADORAS -->

						<div class="row" style="border-bottom: 1px solid;padding-left: 5px;padding-top: 0px;">
							<h4><strong><?=Html::encode(Yii::t('backend', 'Referencias Bancarias'))?></strong></h4>
						</div>

						<div class="row" style="width:100%;margin-bottom: 20px;">
							<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 20px;">
								<div class="col-sm-2" style="width: 20%;padding:0px;">
									<p><strong><?=Html::encode(Yii::t('backend', 'Fecha Pago:'))?></strong></p>
								</div>
								<div class="col-sm-4" style="width:10%;padding:0px;margin-left:0px;">
									<?=Html::textInput('fecha-pago',
												       ( $datosRecibo[0]['estatus'] == 1 ) ? $datosRecibo[0]['fecha'] : date('d-m-Y'),
												       [
												       		'class' => 'form-control',
												       		'style' => 'width:100%;
												       					background-color:white;
												       					font-size:100%;
												       					font-weight:bold;',
												       		'readOnly' => true,
												       ])
									?>
								</div>

								<div class="col-sm-2" style="width:25%;padding:0px;margin:0px;margin-left: 20px;">
									<?= Html::submitButton(Yii::t('backend', 'Buscar Referencias Bancarias'),
																	  [
																		'id' => 'btn-back',
																		'class' => 'btn btn-primary',
																		'value' => 2,
																		'style' => 'width: 100%',
																		'name' => 'btn-back',
																	  ])
									?>
								</div>
							</div>

						</div>

<!-- LISTADO DE PLANILLAS A PAGAR -->
						<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
							<div class="col-sm-4" style="width:45%;padding:0px;margin-left:0px;">
								<div class="row" style="width:100%;">
									<?= GridView::widget([
										'id' => 'id-grid-planilla-sin-referencia',
										'dataProvider' => $dataProviders[1],
										'headerRowOptions' => ['class' => 'warning'],
										'columns' => [
											['class' => 'yii\grid\SerialColumn'],
											// [
								   //              'label' => Yii::t('backend', 'Nro. Recibo'),
								   //              'value' => function($model) {
											// 					return $model->recibo;
											// 				},
								   //          ],
								            [
								                'label' => Yii::t('backend', 'Planilla'),
								                'contentOptions' => [
								                	'style' => 'text-align:center;font-size:90%;',
								                ],
								                'value' => function($model) {
																return $model->planilla;
															},
								            ],
								            [
								                'label' => Yii::t('backend', 'Impuesto'),
								                'contentOptions' => [
								                	'style' => 'text-align:center;font-size:90%;',
								                ],
								                'value' => function($model) {
																return $model->impuesto;
															},
								            ],
								       //      [
								       //          'label' => Yii::t('backend', 'Contribuyente'),
								       //          'contentOptions' => [
								       //          	//'style' => 'text-align:right;',
								       //          ],
								       //          'value' => function($model) {
															// 	return $model->descripcion;
															// },
								       //      ],
								            [
								                'label' => Yii::t('backend', 'Monto'),
								                'contentOptions' => [
								                	'style' => 'text-align:right;font-weight:bold;font-size:90%;',
								                ],
								                'value' => function($model) {
																return Yii::$app->formatter->asDecimal($model->monto, 2);
															},
								            ],
								            [
								                'label' => Yii::t('frontend', 'Condition'),
								                'contentOptions' => [
								                	'style' => 'text-align:center;font-size:90%;',
								                ],
								                'value' => function($model) {
																return $model->condicion->descripcion;
															},
								        	],

								    	]
									]);?>
								</div>
							</div>
						</div>
<!-- LISTADO DE PLANILLAS A PAGAR -->



						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
						</div>

					</div>

					<div class="row" style="margin-top: 25px;">
						<div class="col-sm-2" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Back'),
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
</div>