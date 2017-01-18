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
	//use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;

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
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', $subCaption))?></strong></h4>
						</div>

						<div class="row" style="border-bottom: 1px solid; padding-left: 5px;padding-top: 10px;">
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Datos Básicos'))?></strong></h4>
						</div>

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