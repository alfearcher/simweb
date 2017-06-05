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
 *  @file busqueda-archivo-txt-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-04-2017
 *
 *  @view busqueda-archivo-txt-form
 *  @brief vista principal para busqueda del archivo-txt de pago.
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
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>

<div class="busqueda-archivo-txt-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-busqueda-archivo-txt-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

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
							<h4><strong><?=Html::encode(Yii::t('frontend', 'Ingrese:'))?></strong></h4>
						</div>

						<div class="row" style="width: 100%;padding:0px;margin:0px;margin-top: 20px;">
<!-- LISTA DE BANCO -->
							<div class="row" style="width:100%;padding:0px;margin:0px;">
								<div class="col-sm-2" style="width: 20%;padding:0px;margin:0px;padding-left: 10px;">
									<p><strong><?=Html::encode(Yii::t('backend', 'Seleccione Banco:'))?></strong></p>
								</div>
								<div class="col-sm-2" style="width:60%;padding:0px;margin:0px;">
									<?= $form->field($model, 'id_banco')
								              ->dropDownList($listaBanco, [
	                                                             'id'=> 'id-banco',
	                                                             'prompt' => Yii::t('backend', 'Select'),
	                                                             'style' => 'width:100%;',
	                                        ])->label(false);
	                            	?>
								</div>
							</div>
<!-- FIN DE LISTA DE BANCO -->

<!-- FECHA DE PAGO -->
							<div class="row" style="width:100%;padding:0px;margin:0px;">
								<div class="col-sm-2" style="width: 20%;padding:0px;margin:0px;padding-left: 10px;">
									<p><strong><?=Html::encode(Yii::t('backend', 'Fecha de Pago:'))?></strong></p>
								</div>

								<div class="col-sm-2" style="width:12%; padding: 0px;margin:0px;">
									<?= $form->field($model, 'fecha_pago')->widget(\yii\jui\DatePicker::classname(),[
																					  'clientOptions' => [
																							'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																							'changeMonth' => true,
																							'changeYear' => true,
																						],
																					  'language' => 'es-ES',
																					  'dateFormat' => 'dd-MM-yyyy',
																					  'options' => [
																					  		'id' => 'id-fecha-pago',
																							'class' => 'form-control',
																							'readonly' => true,
																							'style' => 'background-color:white;
																									    width:100%;
																										font-size:100;
																		 								font-weight:bold;',
																						]
																						])->label(false) ?>
								</div>
							</div>
<!-- FIN DE FECHA DE PAGO -->
						</div>

						<div class="row">
							<div class="col-sm-2" style="width:15%;padding:0px;margin-left:225px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('backend', 'Buscar'),
																	  [
																		'id' => 'btn-buscar-archivo',
																		'class' => 'btn btn-primary',
																		'value' => 3,
																		'style' => 'width: 100%',
																		'name' => 'btn-buscar-archivo',
																	  ])
									?>
								</div>
							</div>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
						</div>

					</div>

					<div class="row" style="margin-top: 30px;">
						<div class="col-sm-2" style="margin-left: 40px;">
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