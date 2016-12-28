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
 *  @file create-funcionario-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-12-2016
 *
 *  @view create-funcionario-form
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
	use backend\models\registromaestro\TipoNaturaleza;
	use yii\widgets\MaskedInput;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);


 ?>


<div class="create-funcionario-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-create-funcinario-form',
 			'method' => 'post',
 			//'action' => ['index'],
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => false,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

        <?= $form->field($model, 'entes_ente')->hiddenInput(['value' => Yii::$app->ente->getEnte()])->label(false); ?>
        <?= $form->field($model, 'status_funcionario')->hiddenInput(['value' => 0])->label(false); ?>
        <?= $form->field($model, 'fecha_fin')->hiddenInput(['value' => '0000-00-00'])->label(false); ?>
        <?= $form->field($model, 'en_uso')->hiddenInput(['value' => 0])->label(false); ?>
        <?= $form->field($model, 'login')->hiddenInput(['value' => null])->label(false); ?>
        <?= $form->field($model, 'clave11')->hiddenInput(['value' => null])->label(false); ?>
        <?= $form->field($model, 'fecha_inclusion')->hiddenInput(['value' => date('Y-m-d')])->label(false); ?>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">


					<div class="row" style="border-bottom: 1px solid #ccc;margin-top: 20px;;margin-bottom: 20px;">
							<h4><strong><?=Html::encode(Yii::t('backend', 'Ingrese los siguientes datos '))?></strong></h4>
						</div>

					<div class="row" style="width: 100%;padding: 0px;">
<!-- Cedula del Funcionario -->
						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:12%;padding: 0px;">
								<div class="cedula">
			                		<strong><h5><?=Html::encode('Cedula')?></h5></strong>
								</div>
							</div>
						</div>

						<div class="row" style="width: 100%;">
							<div class="col-sm-2" style="width:18%;padding: 0px;">
								<div class="naturaleza">
			                		<?= $form->field($model, 'naturaleza')->dropDownList($listaNaturaleza,[
																		 			'id' => 'naturaleza_new',
																		 			'style' => 'width: 100%;',
	                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
	                                                            		])->label(false)
			    					?>
								</div>
							</div>

							<div class="col-sm-2" style="width:18%;padding:0px;padding-left: 5px;">
								<div class="ci">
									<?= $form->field($model, 'ci')->textInput([
																		'id' => 'ci',
																		'style' => 'width: 100%;',
																		'maxlength' => 8,
																  ])->label(false) ?>
								</div>
							</div>

						</div>
<!-- Fin de Cedula de Funcionario -->


<!-- Apellidos, Nombre del Funcionario -->
						<div class="row" style="width: 100%;padding: 0px;padding-left: 15px;">
							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="apellidos">
			                			<strong><h5><?=Html::encode('Apellidos')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="apellidos">
											<?= $form->field($model, 'apellidos')->textInput([
																				'id' => 'apellidos',
																				'style' => 'width: 100%;',
																		  ])->label(false) ?>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="nombres">
			                			<strong><h5><?=Html::encode('Nombres')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="nombres">
											<?= $form->field($model, 'nombres')->textInput([
																				'id' => 'nombres',
																				'style' => 'width: 100%;',
																		  ])->label(false) ?>
										</div>
									</div>
								</div>
							</div>
						</div>
<!-- Fin de Apellidos, Nombres de Funcionario -->

<!-- Datos de ubicacion -->
						<div class="row" style="width: 100%;padding: 0px;padding-left: 15px;">
							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="email">
			                			<strong><h5><?=Html::encode('Correo')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="email">
											<?= $form->field($model, 'email')->textInput([
																				'id' => 'email',
																				'style' => 'width: 100%;',
																		  ])->label(false) ?>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-4" style="padding: 0px;width: 35%;">
								<div class="row" style="padding: 0px;">
									<div class="celular">
			                			<strong><h5><?=Html::encode('Celular')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:55%;padding:0px;">
										<div class="celular">
											<?= $form->field($model, 'celular')->textInput([
																				'id' => 'celular',
																				'style' => 'width: 100%;',
																				'maxlength' => 12,
																		  ])->label(false) ?>
										</div>
									</div>
								</div>
							</div>
						</div>
<!-- Fin de datos de ubicacion -->

<!-- Datos de trabajo -->
						<div class="row" style="width: 100%;padding: 0px;padding-left: 15px;">
							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="departamento">
			                			<strong><h5><?=Html::encode('Departamento')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="departamento">
											<?= $form->field($model, 'id_departamento')->dropDownList($listaDepartamento,[
																		 			'id' => 'departamento',
																		 			'style' => 'width: 100%;',
	                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
	                                                     				 			'onchange' => '$.post( "' . Yii::$app->urlManager
	                                                                                       		           ->createUrl('funcionario/funcionario/lista-unidad') . '&i=' . '" + $(this).val(), function( data ) {
	                                                                                                                 $( "select#unidad" ).html( data );
	                                                                                                           });'
	                                                            		])->label(false)
			    							?>
										</div>
									</div>
								</div>
							</div>


							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="unidad">
			                			<strong><h5><?=Html::encode('Unidad')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="unidad">
											<?= $form->field($model, 'id_unidad')->dropDownList([],[
																		 			'id' => 'unidad',
																		 			'style' => 'width: 100%;',
	                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
	                                                            		])->label(false)
			    							?>
										</div>
									</div>
								</div>
							</div>
						</div>
<!-- Fin de datos de trabajo -->


<!-- Cargo y fecha de Entrada -->
						<div class="row" style="width: 100%;padding: 0px;padding-left: 15px;">
							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="cargo">
			                			<strong><h5><?=Html::encode('Cargo')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="cargo">
											<?= $form->field($model, 'cargo')->textInput([
																				'id' => 'cargo',
																				'style' => 'width: 100%;',
																		  ])->label(false) ?>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-4" style="padding: 0px;width: 20%;">
								<div class="row" style="padding: 0px;">
									<div class="nivel">
			                			<strong><h5><?=Html::encode('Nivel')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:85%;padding:0px;">
										<div class="nivel">
											<?= $form->field($model, 'niveles_nivel')->dropDownList($listaNivel,[
																		 			'id' => 'niveles-nivel',
																		 			'style' => 'width: 100%;',
	                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
	                                                            		])->label(false)
			    							?>
										</div>
									</div>
								</div>
							</div>


							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="fecha-inicio">
			                			<strong><h5><?=Html::encode('Fecha Inicio')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:45%;padding:0px;">
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
																									'style' => 'background-color: white;width:55%;',

																								]
																								])->label(false) ?>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-4" style="padding: 0px;width: 45%;">
								<div class="row" style="padding: 0px;">
									<div class="vigencia">
			                			<strong><h5><?=Html::encode('Vigente Hasta')?></h5></strong>
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-sm-2" style="width:45%;padding:0px;">
										<div class="vigencia">
											<?= $form->field($model, 'vigencia')->widget(\yii\jui\DatePicker::classname(),[
																							  'clientOptions' => [
																									//'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																									'changeMonth' => true,
																									'changeYear' => true,
																								],
																							  'language' => 'es-ES',
																							  'dateFormat' => 'dd-MM-yyyy',
																							  'options' => [
																							  		'id' => 'vigencia',
																									'class' => 'form-control',
																									'readonly' => true,
																									'style' => 'background-color: white;width:55%;',

																								]
																								])->label(false) ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Carga y fecha de entrada -->


					<div class="row" style="border-bottom: 1px solid #ccc;margin-top: 20px;;margin-bottom: 20px;">
					</div>


					<div class="row" style="width: 100%;">
						<div class="col-sm-3" style="width: 30%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Guardar'),
																		  [
																			'id' => 'btn-create',
																			'class' => 'btn btn-primary',
																			'value' => 3,
																			'name' => 'btn-create',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>


						<div class="col-sm-3" style="width: 30%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Salir'),
																		  [
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'name' => 'btn-quit',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
