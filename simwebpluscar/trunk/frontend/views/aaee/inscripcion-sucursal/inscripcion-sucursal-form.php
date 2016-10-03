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
 *  @file inscripcion-sucursal-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-10-2015
 *
 *  @view inscripcion-sucursal-form.php
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
	use yii\jui\DatePicker;
	use yii\widgets\DetailView;
	//use backend\models\registromaestro\TipoNaturaleza;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	//use backend\models\TelefonoCodigo;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="inscripcion-sucursal-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-inscripcion-sucursal-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

	<?= $form->field($model, 'id_sede_principal')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'naturaleza')->hiddenInput(['value' => $datos['naturaleza']])->label(false); ?>
	<?= $form->field($model, 'cedula')->hiddenInput(['value' => $datos['cedula']])->label(false); ?>
	<?= $form->field($model, 'tipo')->hiddenInput(['value' => $datos['tipo']])->label(false); ?>
	<?= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?= $form->field($model, 'reg_mercantil')->hiddenInput(['value' => $datos['reg_mercantil']])->label(false); ?>
	<?= $form->field($model, 'tomo')->hiddenInput(['value' => $datos['tomo']])->label(false); ?>
	<?= $form->field($model, 'num_reg')->hiddenInput(['value' => $datos['num_reg']])->label(false); ?>
	<?= $form->field($model, 'fecha')->hiddenInput(['value' => $datos['fecha']])->label(false); ?>
	<?= $form->field($model, 'folio')->hiddenInput(['value' => $datos['folio']])->label(false); ?>
	<?= $form->field($model, 'capital')->hiddenInput(['value' => $datos['capital']])->label(false); ?>
	<?= $form->field($model, 'naturaleza_rep')->hiddenInput(['value' => $datos['naturaleza_rep']])->label(false); ?>
	<?= $form->field($model, 'cedula_rep')->hiddenInput(['value' => $datos['cedula_rep']])->label(false); ?>
	<?= $form->field($model, 'representante')->hiddenInput(['value' => $datos['representante']])->label(false); ?>


<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row">
						<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Info of Headquarters Main')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row" id="datos-principal-primario" style="padding-left: 15px; width: 100%;">
									<!-- <h4><?//= Html::encode(Yii::t('backend', 'Info of Headquarters Main')) ?></h3> -->
										<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [

								    				[
								    					'label' => $model->getAttributeLabel('id_sede_principal'),
								    					'value' => $datos['id_contribuyente'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('dni_principal'),
								    					'value' => $datos['naturaleza'] . '-' . $datos['cedula'] . '-' . $datos['tipo'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('representante'),
								    					'value' => $datos['representante'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('dni_representante'),
								    					'value' =>  $datos['naturaleza_rep'] . '-' . $datos['cedula_rep'],
								    				],
								    				[
								    					'format'=>['date', 'dd-MM-yyyy'],
								    					'label' => $model->getAttributeLabel('fecha_inicio'),
								    					'value' =>  $datos['fecha_inicio'],
								    				],
								    			],
											])
										?>
					        	</div>

								<?php if ( trim($errorMensajeFechaInicioSedePrincipal) !== '' ) { ?>
									<div class="row" id="mensaje-error-fecha-inicio-principal" style="padding-left:50px;">
										<div class="well well-sm" style="padding-top:0px;margin-left:0px; width:60%; color: red;">
											<h3><?=Html::encode($errorMensajeFechaInicioSedePrincipal); ?></h3>
										</div>
									</div>
								<?php } ?>


					        	<div class="row" id="datos-principal-reg-mercantil" style="padding-left: 15px; width: 100%;">
					        		<h4><?= Html::encode(Yii::t('backend', 'Info of Commercial Register')) . '. ';?>
			        					<b><span>
			        						<?php if ( trim($mensajeRegistroMercantil) !== '' ) { ?>
				        						<div id="info-registro-mercantil-completa" class="info-registro-mercantil-completa">
													<div class="well well-sm" style="color: red;"><?=Html::encode($mensajeRegistroMercantil);?></div>
				        						</div>
				        					<?php } ?>
			        					</span></b>
					        		</h4>

					        		<?= DetailView::widget([
												'model' => $model,
								    			'attributes' => [

								    				[
								    					'label' => $model->getAttributeLabel('reg_mercantil'),
								    					'value' => $datos['reg_mercantil'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('num_reg'),
								    					'value' => $datos['num_reg'],
								    				],
								    				[
								    					'format'=>['date', 'dd-MM-yyyy'],
								    					'label' => $model->getAttributeLabel('fecha'),
								    					'value' => date('d-M-Y', strtotime($datos['fecha'])),
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('tomo'),
								    					'value' => $datos['tomo'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('folio'),
								    					'value' => $datos['folio'],
								    				],
								    				[
								    					'label' => $model->getAttributeLabel('capital'),
								    					'value' => $datos['capital'],
								    				],
								    			],
											])
										?>
					        	</div>
					        </div>
						</div>
					</div>

<!-- DATOS DE LA SUCURSAL -->
							<div class="row">
								<h4><?= Html::encode(Yii::t('backend', 'Branch Office')) ?></h3>
				        		<div class="panel panel-success" style="width: 100%;">
									<div class="panel-heading">
							        	<span><?= Html::encode(Yii::t('backend', 'Add the next information')) ?></span>
							        </div>
							        <div class="panel-body">

							        	<div class="row">
<!-- Razon Social -->
							        		<div class="col-sm-6" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('razon_social')) ?></i></p>
												</div>
												<div class="row" >
													<div class="razon-social">
														<?= $form->field($model, 'razon_social')->textInput([
																											'id' => 'razon-social',
																											'style' => 'width:100%;',
																								 			])->label(false) ?>
													</div>
												</div>
											</div>
<!-- Fin de Razon Social de la Sucursal -->


<!-- Id Sim del la Sucursal (Numero de Licencia) -->
											<div class="col-sm-3" style="margin-left: 3px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_sim')) ?></i></p>
												</div>
												<div class="row" >
													<div class="id-sim">
														<?= $form->field($model, 'id_sim')->textInput([
																										'id' => 'id-sim',
																										'style' => 'width:100%;',
																							 			])->label(false) ?>
													</div>
												</div>
											</div>
<!-- Fin de Id Sim del la Sucursal (Numero de Licencia) -->

<!-- Fecha de Inicio de la Sucursal -->
											<div class="col-sm-2" style="margin-left: 3px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_inicio')) ?></i></p>
												</div>
												<div class="row" >
													<div class="fecha-inicio">
														<?php
															$f = '';
															if ( $datos['fecha_inicio'] !== null || $datos['fecha_inicio'] !== '0000-00-00' ) {
																$añoLimite = (int)date('Y') - (int)date('Y', strtotime($datos['fecha_inicio']));
																$mesLimite = (int)date('m') - (int)date('m', strtotime($datos['fecha_inicio']));
																$diaLimite = (int)date('d') - (int)date('d', strtotime($datos['fecha_inicio']));
																$f = '-'.$añoLimite.'Y -'.$mesLimite.'M -'.$diaLimite.'D';
															}
														?>
														<?= $form->field($model, 'fecha_inicio')->widget(\yii\jui\DatePicker::classname(),['id' => 'fecha-inicio',
																																			'clientOptions' => [
																																				'minDate' => $f,
																																				'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																																				'changeMonth' => true,
																																				'changeYear' => true,
																																			],
																																			'language' => 'es-ES',
																																			'dateFormat' => 'dd-MM-yyyy',
																																			'options' => [
																																					'class' => 'form-control',
																																					'readonly' => true,
																																					'style' => 'background-color: white;',

																																			]
																																			])->label(false)
																																			  ->hint('Limite ' . date('d-M-Y', strtotime($datos['fecha_inicio'])));
														?>
													</div>
												</div>
											</div>
<!-- Fin de Fecha de Inicio de la Sucursal -->

							        	</div>   <!-- fin de row -->


										<div class="row">
<!-- Domicilio de la Sucursal -->
							        		<div class="col-sm-6" style="margin-left: 15px">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('domicilio_fiscal')) ?></i></p>
												</div>
												<div class="row" >
													<div class="domicilio-fiscal">
														<?= $form->field($model, 'domicilio_fiscal')->textArea([
																											'id' => 'domicilio-fiscal',
																											'style' => 'width:100%;',
																											//'height:150px'
																								 			])->label(false) ?>
													</div>
												</div>
											</div>
<!-- Fin de Domicilio de la Sucursal -->


<!-- Email -->
											<div class="col-sm-5" style="margin-left: 3px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('email')) ?></i></p>
												</div>
												<div class="row" >
													<div class="email">
														<?= $form->field($model, 'email')->textInput([
																									'id' => 'email',
																									'style' => 'width:100%;',
																						 			])->label(false) ?>
													</div>
												</div>
											</div>
<!-- Fin de Email -->
							        	</div>   <!-- fin de row -->

<!-- Telefonos de Oficinas -->

<!-- Codigo Telefonicos Local 1-->
										<div class="row">
											<div class="col-sm-2" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Codigo') ?></i></p>
												</div>
												<div class="row" >
													<div class="codigo" >
														<?= $form->field($modelTelefono, 'codigo')->dropDownList($listaTelefonoCodigo, [
                                                                                                 							'prompt' => Yii::t('backend', 'Select'),
                                                                                                 							'style' => 'width:100px;',
                                                                                                 							'id' => 'codigo',
                                                                                                 							'name' => 'codigo',
                                                                                                							])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: -58px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('num_tlf_ofic')) ?></i></p>
												</div>
												<div class="row" >
													<div class="num-tlf-ofic">
														<?= $form->field($model, 'num_tlf_ofic')->textInput([
																											'id' => 'num-tlf-ofic',
																											'name' => 'num-tlf-ofic',
																											'style' => 'width:100%;',
																											'maxlength' => 7,
																								 			])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: 5px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('tlf_ofic')) ?></i></p>
												</div>
												<div class="row" >
													<div class="tlf-ofic">
														<?= $form->field($model, 'tlf_ofic')->textInput([
																										'id' => 'tlf-ofic',
																										'style' => 'width:100%;',
																										'readonly' => true,
																							 			])->label(false) ?>
													</div>
												</div>
											</div>

<!-- Fin de Boton para borrar el contenido -->
										</div>
<!-- Fin de Telefonos de Oficinas 1 -->


<!-- Codigo Telefonicos Local 2 -->
										<div class="row">
											<div class="col-sm-2" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Codigo') ?></i></p>
												</div>
												<div class="row" >
													<div class="codigo2" >
														<?= $form->field($modelTelefono, 'codigo')->dropDownList($listaTelefonoCodigo, [
                                                                                                 							'prompt' => Yii::t('backend', 'Select'),
                                                                                                 							'style' => 'width:100px;',
                                                                                                 							'id' => 'codigo2',
                                                                                                 							'name' => 'codigo2',
                                                                                                							])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: -58px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('num_tlf_ofic_otro')) ?></i></p>
												</div>
												<div class="row" >
													<div class="num-tlf-ofic-otro">
														<?= $form->field($model, 'num_tlf_ofic_otro')->textInput([
																											'id' => 'num-tlf-ofic-otro',
																											'name' => 'num-tlf-ofic-otro',
																											'style' => 'width:100%;',
																											'maxlength' => 7,
																								 			])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: 5px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('tlf_ofic_otro')) ?></i></p>
												</div>
												<div class="row" >
													<div class="tlf-ofic-otro">
														<?= $form->field($model, 'tlf_ofic_otro')->textInput([
																											'id' => 'tlf-ofic-otro',
																											'style' => 'width:100%;',
																											'readonly' => true,
																								 			])->label(false) ?>
													</div>
												</div>
											</div>
										</div>
<!-- Fin de Telefonos de Oficinas 2 -->


<!-- Codigo Telefonicos Celular -->
										<div class="row">
											<div class="col-sm-2" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Codigo') ?></i></p>
												</div>
												<div class="row" >
													<div class="tlf-celular" >
														<?= $form->field($modelTelefono, 'codigo')->dropDownList($listaTelefonoMovil, [
                                                                                                 							'prompt' => Yii::t('backend', 'Select'),
                                                                                                 							'style' => 'width:100px;',
                                                                                                 							'id' => 'codigo3',
                                                                                                 							'name' => 'codigo3',
                                                                                                							])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: -58px;">
												<div class="row" style="width:120%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('num_celular')) ?></i></p>
												</div>
												<div class="row" >
													<div class="num-celular">
														<?= $form->field($model, 'num_celular')->textInput([
																											'id' => 'num-celular',
																											'name' => 'num-celular',
																											'style' => 'width:100%;',
																											'maxlength' => 7,
																								 			])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: 5px;">
												<div class="row" style="width:120%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('tlf_celular')) ?></i></p>
												</div>
												<div class="row" >
													<div class="tlf-celular">
														<?= $form->field($model, 'tlf_celular')->textInput([
																											'id' => 'tlf-celular',
																											'style' => 'width:100%;',
																											'readonly' => true,
																								 			])->label(false) ?>
													</div>
												</div>
											</div>
										</div>
<!-- Fin de Telefonos Celular -->

							        </div>
							    </div>
							</div>   <!-- Fin de row de datos sucursal -->
<!-- FIN DE DATOS DE LA SUCURSAL -->


							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<?= Html::submitButton(Yii::t('backend', 'Create'),[
																						'id' => 'btn-create',
																						'class' => 'btn btn-success',
																						'name' => 'btn-create',
																						'value' => 1,
																						'style' => 'width: 100%;',
											])?>
									</div>
								</div>
								<div class="col-sm-2" style="margin-left: 150px;">
									<div class="form-group">
										 <?= Html::submitButton(Yii::t('backend', 'Quit'),[
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'name' => 'btn-quit',
																						'value' => 1,
																						'style' => 'width: 100%;'
											])?>
									</div>
								</div>
							</div>

						</div>
		        	<!-- </div> -->
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


<script type="text/javascript">

	$('input[name="num-tlf-ofic"]').change(function() {
	 	$('#tlf-ofic').val( $('#codigo').val() + '-' + $('#num-tlf-ofic').val() );
	});
	$('select[name="codigo"]').change(function() {
	 	$('#tlf-ofic').val( $('#codigo').val() + '-' + $('#num-tlf-ofic').val() );
	});

	$('input[name="num-tlf-ofic-otro"]').change(function() {
	 	$('#tlf-ofic-otro').val( $('#codigo2').val() + '-' + $('#num-tlf-ofic-otro').val() );
	});
	$('select[name="codigo2"]').change(function() {
	 	$('#tlf-ofic-otro').val( $('#codigo2').val() + '-' + $('#num-tlf-ofic-otro').val() );
	});

	$('input[name="num-celular"]').change(function() {
	 	$('#tlf-celular').val( $('#codigo3').val() + '-' + $('#num-celular').val() );
	});
	$('select[name="codigo3"]').change(function() {
	 	$('#tlf-celular').val( $('#codigo3').val() + '-' + $('#num-celular').val() );
	});

	$(document).ready(function() {
		var n = $( "#info-registro-mercantil-completa" ).length;
		if ( n > 0 ) {
			$( "#btn-create" ).attr("disabled", true);
		} else {
			//$( "#btn-create" ).removeAttr("disabled");
		}
	});


	$(document).ready(function() {
		var n = $( "#mensaje-error-fecha-inicio-principal" ).length;
		if ( n > 0 ) {
			$( "#btn-create" ).attr("disabled", true);
		} else {
			//$( "#btn-create" ).removeAttr("disabled");
		}
	});
</script>