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
 *  @file inscripcion-propaganda-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @view inscripcion-propaganda-form
 *  @brief vista principal para la inscripcion de la propaganda.
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

<div class="inscripcion-propaganda-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-inscripcion-propaganda-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?=$form->field($model, 'id_sim')->hiddenInput(['value' => 0])->label(false); ?>

	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>
	<?=$form->field($model, 'estatus')->hiddenInput(['value' => $model->estatus])->label(false); ?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

		        	<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid;padding-left: 5px;padding-top: 0px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', $subCaption))?></strong></h4>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Datos Básicos'))?></strong></h4>
						</div>

<!-- NOMBRE DE LA PROPAGANDA -->
						<div class="row" style="width:100%;padding:0px;margin-top: 20px;">
							<div class="col-sm-2" style="width: 10%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Nombre:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:60%;padding:0px;margin-left:100px;">
								<?= $form->field($model, 'nombre_propaganda')->textInput([
																				'id' => 'nombre-propaganda',
																				'class' => 'form-control',
																				'style' => 'width:100%;',
																			])->label(false);
								?>
							</div>
						</div>

<!-- DIRECCION DDE UBICACION DE LA PROPAGANDA -->
						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width: 18%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Dirección (Ubicación):'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:60%;padding:0px;margin-left:15px;">
								<?= $form->field($model, 'direccion')->textInput([
																				'id' => 'direccion',
																				'class' => 'form-control',
																				'style' => 'width:100%;',
																			])->label(false);
								?>
							</div>
						</div>

<!-- USO PROPAGANDA -->
						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Uso:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
								 <?= $form->field($model, 'uso_propaganda')
								          ->dropDownList($listaUsoPropaganda, [
	                                                              'id'=> 'uso-propaganda',
	                                                              'prompt' => Yii::t('backend', 'Select'),
	                                                              'style' => 'width:340px;',
	                                                              'onchange' => '$( "select#tipo-propaganda" ).html( "" );',
	                                                                        ])->label(false);
	                            ?>
							</div>
						</div>

<!-- CLASE PROPAGANDA -->

						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Clase:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
								 <?= $form->field($model, 'clase_propaganda')
								          ->dropDownList($listaClasePropaganda, [
	                                                              'id'=> 'clase-propaganda',
	                                                              'prompt' => Yii::t('backend', 'Select'),
	                                                              'style' => 'width:340px;',
	                                                              'onchange' => '$.post( "' . Yii::$app->urlManager
	                                                                                   		           ->createUrl('/propaganda/inscripcionpropaganda/inscripcion-propaganda/generar-lista-tipo') . '&c=' . '" + $(this).val() +  "' .
	                                                                                   		           																								'&u=' . '" + $("#uso-propaganda").val(),
	                                                                                   		           			 function( data ) {
	                                                                                   		           			 	$( "select#tipo-propaganda" ).html( "" );
	                                                                                                             	$( "select#tipo-propaganda" ).html( data );
	                                                                                                       		}
	                                                                                    );'
	                                                                        ])->label(false);
	                            ?>
							</div>
						</div>

<!-- TIPO DE PROPAGANDA -->

						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Tipo:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
								 <?= $form->field($model, 'tipo_propaganda')
								          ->dropDownList([], [
                                                      'id'=> 'tipo-propaganda',
                                                      'prompt' => Yii::t('backend', 'Select'),
                                                      'style' => 'width:740px;',
                                                      'onchange' => '$( "#id-descripcion" ).html( $( "#tipo-propaganda option:selected").text() )',
                                                                ])->label(false);
	                            ?>
							</div>
						</div>


						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-4" style="width:50%;padding:0px;margin-left:205px;">
								<?= $form->field($model, 'descripcion')->textArea([
																		'id' => 'id-descripcion',
																		'rows' => 4,
																		'style' => 'width:140%;background-color:white;',
																		'readOnly' => true,
																 	])->label(false) ?>
							</div>
						</div>

<!-- <div class="row" style="width: 100%;"> -->
						<div class="col-5" style="width: 50%;float: left;">

							<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
								<h4><strong><?=Html::encode(Yii::t('frontend', 'Dimensiones, Medidas y Cantidades'))?></strong></h4>
							</div>

<!-- CANTIDAD DE PROPAGANDA -->
							<div class="row" style="width:100%;padding:0px;margin-top: 20px;">
								<div class="col-sm-2" style="width: 10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Cantidad:'))?></strong></p>
								</div>
								<div class="col-sm-4" style="width:40%;padding:0px;margin-left:100px;">
									<?= $form->field($model, 'cantidad_propagandas')->textInput([
																					'id' => 'cantidad-propagandas',
																					'class' => 'form-control',
																					'style' => 'width:100%;',
																				])->label(false);
									?>
								</div>
							</div>

<!-- ALTO -->

							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Alto:'))?></strong></p>
								</div>

								<div class="col-sm-4" style="width: 40%;padding:0px;margin-left:100px;">
									<div class="alto">
										<?= $form->field($model, 'alto')->widget(MaskedInput::className(), [
																						'id' => 'alto',
																						//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																						'options' => [
																							'class' => 'form-control',
																							'style' => 'width: 100%;',
																							'placeholder' => '0.00',
																						],
																						'clientOptions' => [
																							'alias' =>  'decimal',
																							'digits' => 2,
																							'digitsOptional' => false,
																							'groupSeparator' => ',',
																							'removeMaskOnSubmit' => true,
																							// 'allowMinus'=>false,
																							//'groupSize' => 3,
																							'radixPoint'=> ".",
																							'autoGroup' => true,
																							//'decimalSeparator' => ',',
																						],

																	  				  ])->label(false) ?>
									</div>
								</div>
							</div>

<!-- ANCHO -->

							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Ancho:'))?></strong></p>
								</div>

								<div class="col-sm-4" style="width: 40%;padding:0px;margin-left:100px;">
									<div class="alto">
										<?= $form->field($model, 'ancho')->widget(MaskedInput::className(), [
																						'id' => 'ancho',
																						//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																						'options' => [
																							'class' => 'form-control',
																							'style' => 'width: 100%;',
																							'placeholder' => '0.00',
																						],
																						'clientOptions' => [
																							'alias' =>  'decimal',
																							'digits' => 2,
																							'digitsOptional' => false,
																							'groupSeparator' => ',',
																							'removeMaskOnSubmit' => true,
																							// 'allowMinus'=>false,
																							//'groupSize' => 3,
																							'radixPoint'=> ".",
																							'autoGroup' => true,
																							//'decimalSeparator' => ',',
																						],

																	  				  ])->label(false) ?>
									</div>
								</div>
							</div>


<!-- PROFUNDIDAD -->
							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Profundidad:'))?></strong></p>
								</div>

								<div class="col-sm-4" style="width: 40%;padding:0px;margin-left:100px;">
									<div class="profundidad">
										<?= $form->field($model, 'profundidad')->widget(MaskedInput::className(), [
																						'id' => 'profundidad',
																						//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																						'options' => [
																							'class' => 'form-control',
																							'style' => 'width: 100%;',
																							'placeholder' => '0.00',
																						],
																						'clientOptions' => [
																							'alias' =>  'decimal',
																							'digits' => 2,
																							'digitsOptional' => false,
																							'groupSeparator' => ',',
																							'removeMaskOnSubmit' => true,
																							// 'allowMinus'=>false,
																							//'groupSize' => 3,
																							'radixPoint'=> ".",
																							'autoGroup' => true,
																							//'decimalSeparator' => ',',
																						],

																	  				  ])->label(false) ?>
									</div>
								</div>
							</div>


<!-- METROS -->
							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width:10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Metros:'))?></strong></p>
								</div>

								<div class="col-sm-4" style="width: 40%;padding:0px;margin-left:100px;">
									<div class="mts">
										<?= $form->field($model, 'mts')->widget(MaskedInput::className(), [
																						'id' => 'mts',
																						//'mask' => '9{1,3}[,9{1,3}][,9{1,3}]',
																						'options' => [
																							'class' => 'form-control',
																							'style' => 'width: 100%;',
																							'placeholder' => '0.00',
																						],
																						'clientOptions' => [
																							'alias' =>  'decimal',
																							'digits' => 2,
																							'digitsOptional' => false,
																							'groupSeparator' => ',',
																							'removeMaskOnSubmit' => true,
																							// 'allowMinus'=>false,
																							//'groupSize' => 3,
																							'radixPoint'=> ".",
																							'autoGroup' => true,
																							//'decimalSeparator' => ',',
																						],

																	  				  ])->label(false) ?>
									</div>
								</div>
								<div class="col-sm-2" id="metros" style="width:10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Lineales'))?></strong></p>
								</div>
							</div>

						</div>


						<div class="col-4" style="width: 45%;float: right;">

							<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
								<h4><strong><?=Html::encode(Yii::t('frontend', 'Lapso de Publicación'))?></strong></h4>
							</div>

<!-- FECHA DE INICIO -->
							<div class="row" style="width:100%;padding:0px;margin-top:20px;">
								<div class="col-sm-2" style="width:34%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Fecha Inicio:'))?></strong></p>
								</div>

								<div class="col-sm-4" style="width: 25%;padding:0px;margin-left:0px;">
									<div class="fecha-inicio">
										<?= $form->field($model, 'fecha_inicio')->widget(\yii\jui\DatePicker::classname(),[
																							  'clientOptions' => [
																									'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																									'changeMonth' => true,
																									'changeYear' => true,
																								],
																							  'language' => 'es-ES',
																							  'dateFormat' => 'dd-MM-yyyy',
																							  'options' => [
																							  		'id' => 'fecha-inicio',
																									'class' => 'form-control',
																									'readonly' => true,
																									'style' => 'background-color: white;width:100%;',

																								]
																					])->label(false) ?>
									</div>
								</div>
							</div>

<!-- CANTIDAD DE TIEMPO	 -->
							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width: 10%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Cantidad:'))?></strong></p>
								</div>
								<div class="col-sm-4" style="width:15%;padding:0px;margin-left:115px;float: left;">
									<?= $form->field($model, 'cantidad_tiempo')->textInput([
																					'id' => 'cantidad-tiempo',
																					'class' => 'form-control',
																					'style' => 'width:100%;',
																				])->label(false);
									?>
								</div>
								<div class="col-sm-4" style="width:35%;padding:0px;float:left;margin: 0px;padding-left: 10px;">
									 <?= $form->field($model, 'id_tiempo')
									          ->dropDownList($listaTiempo, [
                                                              'id'=> 'id-tiempo',
                                                              'prompt' => Yii::t('backend', 'Select'),
                                                              'style' => 'width:140px;',
                                                              'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                               		               ->createUrl('/propaganda/inscripcionpropaganda/inscripcion-propaganda/determinar-fecha-hasta') . '&t=' . '" + $(this).val() +  "' .
                                                                               		           																								       '&c=' . '" + $("#cantidad-tiempo").val() +  "' .
                                                                               		           																								       '&f=' . '" + $("#fecha-inicio").val(),
                                                                               		           			 function( data ) {
                                                                               		           			 	//$( "#fecha-fin" ).html( "" );
                                                                                                         	$( "#fecha-fin" ).html( data );
                                                                                                   		}
                                                                                );'
                                                                        ])->label(false);
		                            ?>
								</div>

							</div>

<!-- FECHA FIN -->
							<div class="row" style="width:100%;padding:0px;">
								<div class="col-sm-2" style="width: 34%;padding:0px;padding-left: 20px;">
									<p><strong><?=Html::encode(Yii::t('frontend', 'Fecha Hasta:'))?></strong></p>
								</div>
								<div class="col-sm-4" style="width:25%;padding:0px;margin-left: 2px;">
									<?= $form->field($model, 'fecha_fin')->textInput([
																					'id' => 'fecha-fin',
																					'class' => 'form-control',
																					'style' => 'width:100%;',
																					// 'readOnly' => true,
																				])->label(false);
									?>
								</div>
							</div>

						</div>

					</div>




					<div class="row" style="width:100%;">

						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Otras Caracteristicas'))?></strong></h4>
						</div>


						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-4" style="width:5%;padding:0px;">
								<?= $form->field($model, 'cigarros')->checkbox([
																			'id' => 'cigarros',
																			'class' => 'form-control',
																			'style' => 'width:100%;',
																			'label' => null,
																		]);
								?>
							</div>
							<div class="col-sm-2" style="width: 10%;padding:0px;margin-top: 15px;">
								<p><strong><?=Html::encode(Yii::t('frontend', ' De Cigarros'))?></strong></p>
							</div>
						</div>

						<div class="row" style="width:40%;padding:0px;">
							<div class="col-sm-4" style="width:5%;padding:0px;padding-top: -50px;padding-left: 20px;">
								<?= $form->field($model, 'bebidas_alcoholicas')->checkbox([
																			'id' => 'bebidas-alcoholicas',
																			'class' => 'form-control',
																			'style' => 'width:100%;',
																			'label' => null,
																		]);
								?>
							</div>
							<div class="col-sm-2" style="width: 60%;padding:0px;padding-top: 13px;padding-left: 30px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'De Bedidas Alcoholicas'))?></strong></p>
							</div>
						</div>


						<div class="row" style="width:40%;padding:0px;">
							<div class="col-sm-4" style="width:5%;padding:0px;padding-top: -50px;padding-left: 20px;">
								<?= $form->field($model, 'idioma')->checkbox([
																			'id' => 'idioma',
																			'class' => 'form-control',
																			'style' => 'width:100%;',
																			'label' => null,
																		]);
								?>
							</div>
							<div class="col-sm-2" style="width: 60%;padding:0px;padding-top: 13px;padding-left: 30px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'En otro idioma'))?></strong></p>
							</div>
						</div>


<!-- MEDIO DIFUSION -->
						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width:20%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Medio de Difusión:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;">
								 <?= $form->field($model, 'medio_difusion')
								          ->dropDownList($listaMedioDifusion, [
	                                                              'id'=> 'medio-difusion',
	                                                              'prompt' => Yii::t('backend', 'Select'),
	                                                              'style' => 'width:340px;',

	                                                            ])->label(false);
	                            ?>
							</div>
						</div>

<!-- MEDIO TRANSPORTE -->

						<div class="row" style="width:100%;padding:0px;">
							<div class="col-sm-2" style="width:20%;padding:0px;padding-left: 20px;">
								<p><strong><?=Html::encode(Yii::t('frontend', 'Medio de Transporte:'))?></strong></p>
							</div>
							<div class="col-sm-4" style="width:50%;padding:0px;">
								 <?= $form->field($model, 'medio_transporte')
								          ->dropDownList($listaMedioDifusion, [
	                                                              'id'=> 'medio-transporte',
	                                                              'prompt' => Yii::t('backend', 'Select'),
	                                                              'style' => 'width:340px;',

	                                                            ])->label(false);
	                            ?>
							</div>
						</div>


					</div>


					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Create'),
																  [
																	'id' => 'btn-create',
																	'class' => 'btn btn-success',
																	'value' => 1,
																	'style' => 'width: 100%',
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

						<div class="col-sm-2" style="margin-left: 50px;">
							<div class="form-group">
							<!-- '../../common/docs/user/ayuda.pdf'  funciona -->
								<?= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
														'id' => 'btn-help',
														'class' => 'btn btn-default',
														'name' => 'btn-help',
														'target' => '_blank',
														'value' => 1,
														'style' => 'width: 100%;'
													])?>
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