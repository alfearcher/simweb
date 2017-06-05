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
 *  @file inscripcion-act-econ-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-09-2015
 *
 *  @view inscripcion-act-econ-form.php
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

 	//use yii\web\Response;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use backend\models\registromaestro\TipoNaturaleza;

	//session_start();
 ?>

 <div class="inscripcion-act-econ-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'inscripcion-act-econ-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

	 <!-- <?//= Html::activeHiddenInput($model, 'id_contribuyente', ['id' => 'id-contribuyente', 'name' => 'id-contribuyente', 'value' => $_SESSION['idContribuyente']]) ?> -->

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!-- ID CONTRIBUYENTE Y NRO SOLICITUD -->
		        	<div class="row">
<!-- id contribuyente -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_contribuyente')) ?></i></p>
							</div>
							<div class="row" >
								<div class="id-contribuyente" style="margin-left: 0px;">
									<?= $form->field($model, 'id_contribuyente')->textInput([
																						'id' => 'id-contribuyente',
																						'style' => 'height:32px;width:98%;',
																						'readonly' => true,
																						'value' => $_SESSION['idContribuyente'],
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- nro solicitud -->
						<div class="col-sm-3" style="padding-right: 15px;">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('nro_solicitud')) ?></i></p>
							</div>
							<div class="row">
									<div class="nro-solicitud" style="margin-left: 0px;">
										<?= $form->field($model, 'nro_solicitud')->textInput([
																							'id' => 'nro-solicitud',
																							'style' => 'height:32px;width:100%;',
																							'disabled' => true,
																							'value' => 0,
																				 			])->label(false) ?>
									</div>
							</div>
						</div>
					</div>  <!-- Fin de row contribuyente y nro solicitud -->
<!-- FIN DE ID CONTRIBUYENTE Y NRO DE SOLICITUD -->

<!-- NUMERO DE REGISTRO MERCANTIL, REGISTRO MERCANTIL, FECHA -->

<!--  Numero de Registro Mercantil -->
					<div class="row">
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('num_reg')) ?></i></p>
							</div>
							<div class="row" >
								<div class="num-reg" style="margin-left: 0px;">
									<?= $form->field($model, 'num_reg')->textInput([
																						'id' => 'num-reg',
																						'style' => 'height:32px;width:98%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Numero de Registro Mercantil -->

<!-- Registro Mercantil -->
						<div class="col-sm-6">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('reg_mercantil')) ?></i></p>
							</div>
							<div class="row" >
								<div class="reg-mercantil" style="margin-left: 0px;">
									<?= $form->field($model, 'reg_mercantil')->textInput([
																						'id' => 'reg-mercantil',
																						'style' => 'height:32px;width:99%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Numero de Registro Mercantil -->

<!-- Fecha de Registro Mercantil -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha')) ?></i></p>
							</div>
							<div class="row" >
								<div class="fecha" style="margin-left: 0px;">
									<?= $form->field($model, 'fecha')->input('date',
																					[
																						'id' => 'fecha',
																						'type' => 'date',
																						'style' => 'height:32px;width:70%;',
																		 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Fecha de Numero de Registro Mercantil -->
					</div>

<!-- FIN DE NUMERO DE REGISTRO MERCANTIL, REGISTRO MERCANTIL, FECHA -->

<!--  TOMO, FOLIO, CAPITAL Y NUMERO DE EMPLEADOS -->
					<div class="row">
<!-- Tomo -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('tomo')) ?></i></p>
							</div>
							<div class="row" >
								<div class="tomo" style="margin-left: 0px;">
									<?= $form->field($model, 'tomo')->textInput([
																						'id' => 'tomo',
																						'style' => 'height:32px;width:98%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Tomo -->

<!-- Folio -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('folio')) ?></i></p>
							</div>
							<div class="row" >
								<div class="folio" style="margin-left: 0px;">
									<?= $form->field($model, 'folio')->textInput([
																						'id' => 'folio',
																						'style' => 'height:32px;width:98%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Folio -->

<!-- Capital -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('capital')) ?></i></p>
							</div>
							<div class="row" >
								<div class="capital" style="margin-left: 0px;">
									<?= $form->field($model, 'capital')->textInput([
																						'id' => 'capital',
																						'style' => 'height:32px;width:98%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Capital -->

<!-- Numero de Empleados -->
						<div class="col-sm-3">
							<div class="row" style="width:100%;">
								<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('num_empleados')) ?></i></p>
							</div>
							<div class="row">
								<div class="num-empleados" style="margin-left: 0px;">
									<?= $form->field($model, 'num_empleados')->textInput([
																						'id' => 'num-empleados',
																						'style' => 'height:32px;width:50%;',
																			 			])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Numero de Empleados -->

					</div>
<!-- FIN DE TOMO, FOLIO, CAPITAL Y NUMERO DE EMPLEADOS -->

<!-- REPRESNTANTE LEGAL -->
					<div class="row">
						<div class="panel panel-success" style="width: 100%;">
					        <div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Legal Representative')) ?></span>
					        </div>
					        <div class="panel-body">
<!-- CEDULA DEL REPRESENTANTE LEGAL  -->
					        	<div class="row">
									<?php
				                		$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
						            	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');
						            ?>

<!-- Naturaleza del Representante Legal -->
									<div class="col-sm-3">
										<div class="row" style="width:100%;">
											<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('naturaleza_rep')) ?></i></p>
										</div>
										<div class="row">
											<div class="naturaleza-rep" style="margin-left: 15px;">
						                        <?= $form->field($model, 'naturaleza_rep')->dropDownList($listaNaturaleza,[
						                        																//'inline' => true,
						                                                                                               	'prompt' => Yii::t('backend', 'Select'),
						                                                                                                'style' => 'height:32px;
						                                                                                                			width:70%;
						                                                                                                			'
						                                                                                             ])->label(false)
					                			?>
				                			</div>
										</div>
									</div>
<!-- Fin del Naturaleza del Representante Legal -->

<!-- Cedula del Representante Legal -->
									<div class="col-sm-3" style="margin-left: -62px;">
										<div class="row" style="width:100%;">
											<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('cedula_rep')) ?></i></p>
										</div>
										<div class="row">
											<div class="cedula-rep">
				                				<?= $form->field($model, 'cedula_rep')->textInput(['id' => 'cedula-rep', 'maxlength' => 8, 'style' => 'width:75%;height:32px;'])->label(false) ?>
				                			</div>
				                		</div>
									</div>
<!-- Fin de Cedula del Representante Legal -->

<!-- Apellidos y Nombres de Representante Legal -->
									<div class="col-sm-6" style="margin-left: -55px;">
										<div class="row" style="width:100%;">
											<p style="margin-left: 0px;margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('representante')) ?></i></p>
										</div>
										<div class="row">
											<div class="representante" style="margin-left: 0px;">
												<?= $form->field($model, 'representante')->textInput([
																									'id' => 'representante',
																									'style' => 'height:32px;width:100%;',
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Apellidos y Nombres de Representante Legal -->

					        	</div>
<!-- FIN DE CEDULA DE REPRESENTANTE LEGAL -->
					        </div>   <!-- Fi de panel-body -->
					    </div>  <!-- Fin de <div panel panel-success -->
					</div>
<!-- FIN DE REPRESNTANTE LEGAL -->

					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Create'),['id' => 'btn-create', 'class' => 'btn btn-success', 'name' => 'btn-create'])?>
							</div>
						</div>
						<div class="col-sm-2" style="margin-left: 150px;">
							<div class="form-group">
								 <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
							</div>
						</div>
					</div>

				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->

