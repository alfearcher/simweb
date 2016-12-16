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
 *  @file lista-vehiculo-liquidacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view lista-vehiculo-liquidacion.php
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


<div class="lista-vehiculo-liquidacion">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-vehiculo-liquidacion-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left:10px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>

						<div class="row" style="padding:0px;">
							<div class="col-sm-2" style="width:15%;padding:0px;padding-left: 5px;">
								<h5><?=Html::encode('Id. Objeto')?></h5>
							</div>
							<div class="col-sm-2" style="width:15%;padding:0px;padding-left: 5px;">
								<h5><?=Html::encode('Placa')?></h5>
							</div>
							<div class="col-sm-2" style="width:15%;padding:0px;padding-left: 10px;">
								<h5><?=Html::encode('Marca')?></h5>
							</div>
							<div class="col-sm-2" style="width:15%;padding:0px;padding-left: 10px;">
								<h5><?=Html::encode('Modelo')?></h5>
							</div>
							<div class="col-sm-2" style="width:15%;padding:0px;padding-left: 15px;">
								<h5><?=Html::encode('Color')?></h5>
							</div>
							<div class="col-sm-2" style="width:18%;padding:0px;margin:0px;padding-left: 25px;">
								<h5><?=Html::encode('Lapsos (Liquidar Hasta)')?></h5>
							</div>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;padding:0px;">
						</div>

						<div class="row">
							<div class="container-items" style="padding-top: 10px;">
								<?php foreach ( $models as $i => $model ): ?>

									<?=$form->field($model, "[{$i}]id_contribuyente")->hiddenInput()->label(false);?>
									<?=$form->field($model, "[{$i}]id_impuesto")->hiddenInput()->label(false);?>

									<div class="row" style="padding: 0px;">
										<div class="col-sm-2" style="width:15%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]id_impuesto")->textInput([
	                                															'readonly' => true,
	                                															'style' => 'width: 100%;background-color:white;'
			                                											])->label(false);
			                                ?>
			                            </div>

										<div class="col-sm-2" style="width:15%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]placa")->textInput([
	                                														'readonly' => true,
	                                														'style' => 'width: 100%;background-color:white;'
			                                											])->label(false);
			                                ?>
			                            </div>

										<div class="col-sm-2" style="width:15%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]marca")->textInput([
	                                														'readonly' => true,
	                                														'style' => 'width: 100%;background-color:white;'
			                                											])->label(false);
			                                ?>
			                            </div>

			                            <div class="col-sm-2" style="width:15%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]modelo")->textInput([
	                                														'readonly' => true,
	                                														'style' => 'width: 100%;background-color:white;'
			                                											])->label(false);
			                                ?>
			                            </div>

			                            <div class="col-sm-2" style="width:15%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]color")->textInput([
	                                														'readonly' => true,
	                                														'style' => 'width: 100%;background-color:white;'
			                                											])->label(false);
			                                ?>
			                            </div>

			                            <div class="col-sm-2" style="width:20%;padding:0px;margin:0px;padding-left: 10px;margin-top:-10px;">
			                                <?= $form->field($model, "[{$i}]lapso")->dropDownList($lapso[$i],[
			                                                                                	'prompt' => Yii::t('backend', 'Select'),
			                                                                                ])->label(false)
		                					?>
			                            </div>
									</div>


								<?php endforeach; ?>
							</div>
						</div>

					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Confirmar Liquidacion'),
																					  [
																						'id' => 'btn-confirm-create',
																						'class' => 'btn btn-success',
																						'value' => 5,
																						'style' => 'width: 100%;',
																						'name' => 'btn-confirm-create',

																					  ])
									?>
								</div>
							</div>

							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Back'),
																			  [
																				'id' => 'btn-back',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%;',
																				'name' => 'btn-back',

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

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


