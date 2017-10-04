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
 *  @file reporte-recibo-busqueda-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-10-2017
 *
 *  @view reporte-recibo-busqueda-form
 *  @brief vista del formulario que permitira la busqueda de los registros
 * de los recibos.
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
	use yii\jui\DatePicker;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

?>

<div class="reporte-recibo-busqueda-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-reporte-recibo-busqueda-form',
 			'method' => 'post',
 			//'action'=> '#',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 80%;margin: auto;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

					<div class="row" style="width: 100%;margin-top: 10px;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('backend', 'Parametros de consulta del reporte'))?></h4>
						</div>

						<div class="row">
							<div class="col-sm-5" style="width: 60%;">
								<div class="row" id="rango-fecha" style="padding-top: 15px;">
									<div class="col-sm-2" style="width: 30%;">
										<h5><strong><?=Html::encode(Yii::t('backend', 'Rango de Fecha'))?></strong></h5>
									</div>
									<div class="col-sm-3" id="fecha-desde" style="width: 23%;padding: 0px;margin: 0px;">
										<?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
																	  'clientOptions' => [
																			'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																			'changeMonth' => true,
																			'changeYear' => true,
																		],
																	  'language' => 'es-ES',
																	  'dateFormat' => 'dd-MM-yyyy',
																	  'options' => [
																	  		'id' => 'id-fecha-desde',
																			'class' => 'form-control',
																			'readonly' => true,
																			'style' => 'background-color: white;width:100%;margin:0px;',

																		]
														])->label(false)
										 ?>
									</div>

									<div class="col-sm-3" id="fecha-hasta" style="width: 26%;padding: 0px;margin: 0px; padding-left: 15px;">
										<?=$form->field($model, 'fecha_hasta')->widget(\yii\jui\DatePicker::classname(),[
																	  'clientOptions' => [
																			'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																			'changeMonth' => true,
																			'changeYear' => true,
																		],
																	  'language' => 'es-ES',
																	  'dateFormat' => 'dd-MM-yyyy',
																	  'options' => [
																	  		'id' => 'id-fecha-hasta',
																			'class' => 'form-control',
																			'readonly' => true,
																			'style' => 'background-color: white;width:100%;margin:0px;',

																		]
														])->label(false)
										 ?>
									</div>
								</div>

								<div class="row" id="condicion">
									<div class="col-sm-2" style="width: 30%;">
										<h5><strong><?=Html::encode(Yii::t('backend', 'Condicion'))?></strong></h5>
									</div>
									<div class="col-sm-3" style="width: 50%;padding: 0px;margin:0px;">
										<?= $form->field($model, 'estatus')->dropDownList($lista,[
                																		'id' => 'id-estatus',
                																		'style' => 'width:100%;',
                                                                     				 	'prompt' => Yii::t('backend', 'Select'),
                                                                     				 	//'onChance' => 'alert(this.value);',
                                                                     				 	'onChange' => 'if ( this.value == 1 ) {
                                                                                   	 						$( "select#id-usuario" ).removeAttr("disabled");
                                                                                					  } else {
                                                                                					  		$("select#id-usuario").val("");
                                                                                					  		$("select#id-usuario").attr("disabled", true);
                                                                     				 				  }',
                                                                			])->label(false)
									   ?>
									</div>
								</div>

								<div class="row" id="usuario-creador">
									<div class="col-sm-2" style="width: 30%;">
										<h5><strong><?=Html::encode($model->getAttributeLabel('usuario_creador'))?></strong></h5>
									</div>
									<div class="col-sm-3" style="width: 50%;padding: 0px;margin:0px;">
										<?= $form->field($model, 'usuario_creador')->dropDownList($listaUsuario,[
                																		'id' => 'id-usuario-creador',
                																		'style' => 'width:100%;',
                                                                     				 	'prompt' => Yii::t('backend', 'Select'),
                                                                			])->label(false)
									   ?>
									</div>
								</div>

								<div class="row" id="usuario-pago">
									<div class="col-sm-2" style="width: 30%;">
										<h5><strong><?=Html::encode($model->getAttributeLabel('usuario'))?></strong></h5>
									</div>
									<div class="col-sm-3" style="width: 50%;padding: 0px;margin:0px;">
										<?= $form->field($model, 'usuario')->dropDownList($listaUsuarioPago,[
                																		'id' => 'id-usuario',
                																		'style' => 'width:100%;',
                                                                     				 	'prompt' => Yii::t('backend', 'Select'),
                                                                			])->label(false)
									   ?>
									</div>
								</div>

							</div>

							<div class="col-sm-4" class="botones">
								<div class="row" id="buscar-params">
									<div class="col-sm-3" style="width:80%;padding-top: 15px;">
										<div class="form-group">
											<?= Html::submitButton(Yii::t('backend', 'Search'),
																					[
																						'id' => 'btn-search-params',
																						'class' => 'btn btn-primary',
																						'value' => 2,
																						'style' => 'width: 100%',
																						'name' => 'btn-search-params',
																					])
											?>
										</div>
									</div>
								</div>
								<div class="row" id="buton-reinico"></div>
							</div>
						</div>

						<div class="col-sm-3" style="border-bottom: 1px solid #ccc;width: 100%;padding-left: 90px;">
							</div>

						<div class="row" id="recibo" style="padding-top: 25px;">

							<div class="col-sm-2" style="width: 18%;">
								<h5><strong><?=$model->attributeLabels()['recibo']?></strong></h5>
							</div>

							<div class="col-sm-3" id="nro-recibo" style="width: 30%;padding:0px;margin:0px;">
								<?= $form->field($model, 'recibo')->textInput([
																			'id' => 'id-recibo',
																			'style' => 'width:100%;',
																	 	])->label(false)
								?>
							</div>

							<div class="row" id="buscar-recibo">
								<div class="col-sm-3" style="width:35%;padding-left: 100px;">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('backend', 'Search'),
																				[
																					'id' => 'btn-search-recibo',
																					'class' => 'btn btn-success',
																					'value' => 3,
																					'style' => 'width: 100%',
																					'name' => 'btn-search-recibo',
																				])
										?>
									</div>
								</div>
							</div>

						</div>

						<div class="col-sm-3" style="border-bottom: 1px solid #ccc;width: 100%;padding-left: 90px;">
							</div>
						<div class="row"></div>
					</div>


					<div class="row" style="width: 100%;padding-top: 25px;">
						<div class="col-sm-3" style="width:50%;"></div>
						<div class="col-sm-3" style="width:25%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
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
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
