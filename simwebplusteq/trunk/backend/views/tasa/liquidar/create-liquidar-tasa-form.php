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
 *  @file create-liquidar-tasa-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-01-2017
 *
 *  @view create-liquidar-tasa-form.php
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


<div class="crear-liquidar-tasa-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-create-liquidar-tasa-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>
	<?=$form->field($model, 'multiplicar_por')->hiddenInput(['value' => $model->multiplicar_por])->label(false);?>
	<?=$form->field($model, 'observacion')->hiddenInput(['value' => $model->observacion])->label(false);?>
	<?=$form->field($model, 'id_impuesto')->hiddenInput(['id' => 'id-impuesto'])->label(false);?>
	<?=$form->field($model, 'resultado')->hiddenInput(['id' => 'resultado'])->label(false);?>
	<?=$form->field($model, 'id_pago')->hiddenInput(['id' => 'id_pago'])->label(false);?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="col-sm-2" style="width: 10%;padding:0px;">
							<h4><strong>Impuesto</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
							 <?= $form->field($model, 'impuesto')
							          ->dropDownList($listaImpuesto, [
                                                              'id'=> 'impuesto',
                                                              'prompt' => Yii::t('backend', 'Select'),
                                                              'style' => 'width:340px;',
                                                              'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                   		           ->createUrl('/tasa/liquidar/liquidar-tasa/generar-lista') . '&i=' . '" + $(this).val(),
                                                                                   		           			 function( data ) {
                                                                                   		           			 	$( "select#id-codigo" ).html( "" );
                                                                                   		           			 	$( "select#id-grupo-subnivel" ).html( "" );
                                                                                   		           			 	$( "select#codigo" ).html( "" );
                                                                                   		           			 	$( "#id-codigo-descripcion" ).html( "" );
                                                                                                             	$( "select#id-ano-impositivo" ).html( data );
                                                                                                       		}
                                                                                    );'
                                                                        ])->label(false);
                            ?>
						</div>
					</div>


					<div class="row" id="lista-ano-impositivo">
						<div class="col-sm-2" style="width: 10%;padding:0px;">
							<h4><strong>Año</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:100px;">
							 <?= $form->field($model, 'ano_impositivo')
							          ->dropDownList([], [
                                                      'id'=> 'id-ano-impositivo',
                                                      'prompt' => Yii::t('backend', 'Select'),
                                                      'style' => 'width:120px;',
                                                      'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                           		           ->createUrl('/tasa/liquidar/liquidar-tasa/generar-lista') . '&a=' . '" + $(this).val() +  "' .
                                                                           		           															   '&i=' . '" + $("#impuesto").val(),
                                                                           		           				 function( data ) {
                                                                           		           				 	//$( "select#id-codigo" ).html( "" );
                                                                           		           				 	$( "select#id-grupo-subnivel" ).html( "" );
                                                                           		           				 	$( "select#codigo" ).html( "" );
                                                                           		           				 	$( "#id-codigo-descripcion" ).html( "" );
                                                                                                     		$( "select#id-codigo" ).html( data );
                                                                                               			}
                                                                            );'
                                                                ])->label(false);
                            ?>
						</div>
					</div>



					<div class="row" id="codigo-presupuestario">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Codigo Preupuestario</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= $form->field($model, 'id_codigo')
							          ->dropDownList([], [
                                                      'id'=> 'id-codigo',
                                                      'prompt' => Yii::t('backend', 'Select'),
                                                      'style' => 'width:600px;',
                                                      'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                           		           ->createUrl('/tasa/liquidar/liquidar-tasa/generar-lista') . '&idcodigo=' . '" + $(this).val() + "' .
                                                                           		                                                                       '&i=' . '" + $("#impuesto").val() + "' .
                                                                           		                                                                       '&a=' . '" + $("#id-ano-impositivo").val(),
                                                                           		           			function( data ) {
                                                                           		           				$( "select#codigo" ).html( "" );
                                                                           		           				$( "select#id-grupo-subnivel" ).html( "" );
                                                                           		           				$( "#id-codigo-descripcion" ).html( "" );
                                                                                                    	$( "select#id-grupo-subnivel" ).html( data );
                                                                                               		}
                                                                            );'
                                                                ])->label(false);
                            ?>
						</div>
					</div>



					<div class="row" id="grupo-sub-nivel">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Grupo SubNivel</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= $form->field($model, 'grupo_subnivel')
							          ->dropDownList([], [
                                                      'id'=> 'id-grupo-subnivel',
                                                      'prompt' => Yii::t('backend', 'Select'),
                                                      'style' => 'width:600px;',
                                                      'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                           		           ->createUrl('/tasa/liquidar/liquidar-tasa/generar-lista') . '&subnivel=' . '" + $(this).val() + "' .
                                                                           		                                                                       '&i=' . '" + $("#impuesto").val() + "' .
                                                                           		                                                                       '&a=' . '" + $("#id-ano-impositivo").val() + "' .
                                                                           		                                                                       '&idcodigo=' . '" + $("#id-codigo").val(),
                                                                           		           			function( data ) {
                                                                           		           				$( "#id-codigo-descripcion" ).html( "" );
                                                                           		           				$( "#codigo" ).html( "" );
                                                                                                    	$( "select#codigo" ).html( data );
                                                                                               		}
                                                                            );'
                                                                ])->label(false);
                            ?>
						</div>
					</div>




					<div class="row" id="codigo-sub-nivel">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Codigo SubNivel</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							 <?= $form->field($model, 'codigo')
							          ->dropDownList([], [
                                                      'id'=> 'codigo',
                                                      'prompt' => Yii::t('backend', 'Select'),
                                                      'style' => 'width:600px;',
                                                      'onchange' => '$( "#id-codigo-descripcion" ).html( $( "#codigo option:selected").text() )',
                                                      ])->label(false);
                            ?>
						</div>
					</div>


					<div class="row" id="codigo-descripcion">
						<div class="col-sm-2" style="width: 18%;padding:0px;">
							<h4><strong>Descripcion</strong></h4>
						</div>
						<div class="col-sm-4" style="width:50%;padding:0px;margin-left:16px;">
							<?= $form->field($model, 'descripcion')->textArea([
																	'id' => 'id-codigo-descripcion',
																	'rows' => 6,
																	'style' => 'width:112%;background-color:white;',
																	'readOnly' => true,
															 	])->label(false) ?>
						</div>
					</div>



					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Aceptar'),
																			  [
																				'id' => 'btn-aceptar',
																				'class' => 'btn btn-primary',
																				'value' => 1,
																				'style' => 'width: 100%;',
																				'name' => 'btn-aceptar',

																			  ])
									?>
								</div>
							</div>

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


							<!-- <div class="col-sm-2" style="margin-left: 50px;">
								<div class="form-group">-->
								<!-- '../../common/docs/user/ayuda.pdf'  funciona -->
									<!-- <?//= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
														// 	'id' => 'btn-help',
														// 	'class' => 'btn btn-default',
														// 	'name' => 'btn-help',
														// 	'target' => '_blank',
														// 	'value' => 1,
														// 	'style' => 'width: 100%;'
														// ])?> -->
								<!-- </div>
							</div>  -->

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


