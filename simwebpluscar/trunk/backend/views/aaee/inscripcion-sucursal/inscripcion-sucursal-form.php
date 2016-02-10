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

 	//use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use backend\models\registromaestro\TipoNaturaleza;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use backend\models\TelefonoCodigo;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="inscripcion-sucursal-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'inscripcion-sucursal-form',
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
<!--
	<?//= Html::activeHiddenInput($model, 'id_sede_principal', ['id' => 'id-sede-principal', 'name' => 'id-sede-principal', 'value' => $_SESSION['idContribuyente']]) ?>
 -->
	<?= $form->field($model, 'id_sede_principal')->hiddenInput(['value' => $_SESSION['idContribuyente']])->label(false); ?>
	<?= $form->field($model, 'naturaleza')->hiddenInput(['value' => $model->naturaleza])->label(false); ?>
	<?= $form->field($model, 'cedula')->hiddenInput(['value' => $model->cedula])->label(false); ?>
	<?= $form->field($model, 'tipo')->hiddenInput(['value' => $model->tipo])->label(false); ?>
	<?= $form->field($model, 'nro_solicitud')->hiddenInput(['value' => $model->nro_solicitud])->label(false); ?>
	<?= $form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false); ?>
	<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => 0])->label(false); ?>
	<?= $form->field($model, 'origen')->hiddenInput(['value' => 'LAN'])->label(false); ?>
	<?= $form->field($model, 'fecha_hora')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false); ?>
	<?= $form->field($model, 'usuario')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false); ?>
	<?= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>

	<?= $form->field($modelActEcon, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
	<?= $form->field($modelActEcon, 'origen')->hiddenInput(['value' => 'LAN'])->label(false); ?>
	<?= $form->field($modelActEcon, 'fecha_hora')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false); ?>
	<?= $form->field($modelActEcon, 'usuario')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false); ?>

<!--
	 <?//= $form->field($modelActEcon, 'naturaleza_rep')->hiddenInput(['value' => $modelActEcon->naturaleza_rep])->label(false); ?>
	 <?//= $form->field($modelActEcon, 'cedula_rep')->hiddenInput(['value' => $modelActEcon->cedula_rep])->label(false); ?>
	 <?//= $form->field($modelActEcon, 'representante')->hiddenInput(['value' => $modelActEcon->representante])->label(false); ?>
 -->
<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">

<!-- ID SEDE PRINCIPAL, RIF Y DESCRIPCION -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Datos sede principal')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
<!--  Id de la sede principal-->
									<div class="col-sm-3" style="margin-left: 10px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_sede_principal')) ?></i></p>
										</div>
										<div class="row" >
											<div class="id-sede-principal">
												<?= $form->field($model, 'id_sede_principal')->textInput([
																									'id' => 'id-sede-principal',
																									'style' => 'width:98%;',
																									'readonly' => true,
																									'value' => $_SESSION['idContribuyente'],
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Id de la sede principal -->


<!-- RIF DE LA SEDE PRINCIPAL -->
									<?php
				                		$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 1 and 4')->all();
						            	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');
						            ?>
<!-- Naturaleza de la sede principal -->
									<div class="col-sm-3" style="margin-left: 0px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('naturaleza')) ?></i></p>
										</div>
										<div class="row">
											<div class="naturaleza">
						                        <?= $form->field($model, 'naturaleza')->dropDownList($listaNaturaleza,[
					                                                                                               	'prompt' => Yii::t('backend', 'Select'),
					                                                                                                'style' => 'width:70%;',
					                                                                                                'readonly' => true,
					                                                                                                'value' => $model->naturaleza,
					                                                                                             	])->label(false)
					                			?>
				                			</div>
										</div>
									</div>
<!-- Fin del Naturaleza de la sede principal -->


<!-- Cedula de la sede principal -->
									<div class="col-sm-2" style="margin-left: -69px; top: 20px;">
										<div class="row">
											<div class="cedula">
				                				<?= $form->field($model, 'cedula')->textInput([
				                																'id' => 'cedula',
				                																'maxlength' => 8,
				                																'style' => 'width:75%;',
				                																'readonly' => true,
				                															])->label(false) ?>
				                			</div>
				                		</div>
									</div>
<!-- Fin de Cedula de la sede principal -->

<!-- Tipo de sede principal -->
									<div class="col-sm-1" style="margin-left: -53px; top: 20px;">
										<div class="tipo">
											<?= $form->field($model, 'tipo')->textInput([
																						'id' => 'tipo',
																						'style' => 'width:38px;',
																						'readonly' => true,
																						'maxlength' => 1,
																		  			 ])->label(false) ?>
										</div>
									</div>
<!-- Fin de tipo en sede principal -->


									</div>
<!-- FIN DE RIF DE LA SEDE PRINCIPAL -->
								</div>


<!-- REPRESENTANTE LEGAL -->
								<?php
				                	//$modeloTipoNaturaleza1 = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
						            //$listaNaturaleza1 = ArrayHelper::map($modeloTipoNaturaleza1, 'siglas_tnaturaleza', 'nb_naturaleza');
						        ?>
								<div class="row">
<!-- Naturaleza de Representante legal -->
									<!-- <div class="col-sm-3">
										<div class="row" style="width:100%;">
											<p style="margin-left: 25px;margin-top: -20px;margin-bottom: 0px;"><i><?//=Yii::t('backend', $modelActEcon->getAttributeLabel('naturaleza_rep')) ?></i></p>
										</div>
										<div class="row">
											<div class="naturaleza_rep" style="margin-left: 25px;">
						                        <?/*= $form->field($modelActEcon, 'naturaleza_rep')->dropDownList($listaNaturaleza1,[
									                        																	'id' => 'naturaleza_rep',
								                                                                                               	'prompt' => Yii::t('backend', 'Select'),
								                                                                                                'style' => 'height:32px;width:70%;',
								                                                                                                'disabled' => true,
					                                                                                             				])->label(false)
					                			*/?>
				                			</div>
										</div>
									</div> -->
<!-- Fi de Naturaleza de Representante Legal -->


<!-- Cedula del representante legal -->
									<!-- <div class="col-sm-2" style="margin-left: -62px;">
										<div class="row" style="width:100%;">
											<p style="margin-left: 0px;margin-top: -20px;margin-bottom: 0px;"><i><?//=Yii::t('backend', $modelActEcon->getAttributeLabel('cedula_rep')) ?></i></p>
										</div>
										<div class="row">
											<div class="cedula_rep">
				                				<?/*= $form->field($modelActEcon, 'cedula_rep')->textInput([
				                																'id' => 'cedula_rep',
				                																'maxlength' => 8,
				                																'style' => 'width:75%;height:32px;',
				                																'disabled' => true,
				                															])->label(false) */?>
				                			</div>
				                		</div>
									</div> -->
<!-- Fin de la cedula del representante legal -->


<!-- Apellidos y Nombres de Representante Legal -->
									<div class="col-sm-6" style="margin-left: 25px; top: -20px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('representante')) ?></i></p>
										</div>
										<div class="row">
											<div class="representante">
												<?= $form->field($modelActEcon, 'representante')->textInput([
																									'id' => 'representante',
																									'style' => 'width:100%;',
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Apellidos y Nombres de Representante Legal -->
					        	</div>
<!-- FIN DE REPRESENTANTE LEGAL -->


<!-- REGISTRO MERCANTIL -->
					        	<div class="row" style="margin-left: 10px;">
					        		<div class="panel panel-warning" style="width: 97%;">
										<div class="panel-heading">
								        	<span><?= Html::encode(Yii::t('backend', 'Datos del Registro Mercantil')) ?></span>
								        </div>
								        <div class="panel-body">
								        	<div class="row">
<!-- Numero de Registro Mercantil -->
												<div class="col-sm-3" style="margin-left: 15px;">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('num_reg')) ?></i></p>
													</div>
													<div class="row">
														<div class="num-reg">
															<?= $form->field($modelActEcon, 'num_reg')->textInput([
																												'id' => 'num-reg',
																												'style' => 'width:100%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>
<!-- Fin de Numero de Registro Mercantil -->

<!-- Fecha de Registro Mercantil -->
												<div class="col-sm-2" style="margin-left: 3px;">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('fecha')) ?></i></p>
													</div>
													<div class="row">
														<div class="fecha">
															<?= $form->field($modelActEcon, 'fecha')->textInput([
																												'id' => 'fecha',
																												'style' => 'width:100%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>
<!-- Fin de Fecha de Registro Mercantil -->

<!-- Nombre del Registro Mercantil -->
												<div class="col-sm-6" style="margin-left: 3px;">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('reg_mercantil')) ?></i></p>
													</div>
													<div class="row">
														<div class="reg-mercantil">
															<?= $form->field($modelActEcon, 'reg_mercantil')->textInput([
																												'id' => 'reg-mercantil',
																												'style' => 'width:100%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>
<!-- Fin de Nombre del Registro Mercantil -->
											</div>    <!-- fin de row -->

											<div class="row">
<!-- Tomo de Registro Mercantil -->
												<div class="col-sm-3" style="margin-left: 15px;">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('tomo')) ?></i></p>
													</div>
													<div class="row" >
														<div class="tomo">
															<?= $form->field($modelActEcon, 'tomo')->textInput([
																												'id' => 'tomo',
																												'style' => 'width:98%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>
<!-- Fin de Tomo de Registro Mercantil -->

<!-- Folio de Registro Mercantil -->
												<div class="col-sm-3" style="margin-left: 0px;">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('folio')) ?></i></p>
													</div>
													<div class="row" >
														<div class="folio">
															<?= $form->field($modelActEcon, 'folio')->textInput([
																												'id' => 'folio',
																												'style' => 'width:98%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>

<!-- Fin de Folio de Registro Mercantil -->
												<div class="col-sm-3" style="margin-left: 0px">
													<div class="row" style="width:100%;">
														<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $modelActEcon->getAttributeLabel('capital')) ?></i></p>
													</div>
													<div class="row" >
														<div class="capital" style="margin-left: 0px;">
															<?= $form->field($modelActEcon, 'capital')->textInput([
																												'id' => 'capital',
																												'style' => 'width:98%;',
																												'readonly' => true,
																									 			])->label(false) ?>
														</div>
													</div>
												</div>
<!-- Capital -->


<!-- Fin de Capital -->
											</div>   <!-- fin de row -->
										</div>		<!-- fin de panel-body representante -->
									</div>          <!-- fin de panel panel-warning representante-->
					        	</div>
<!-- FIN DE REGISTRO MERCANTIL -->
					        </div>


<!-- DATOS DE LA SUCURSAL -->
							<div class="row">
				        		<div class="panel panel-success" style="width: 100%;">
									<div class="panel-heading">
							        	<span><?= Html::encode(Yii::t('backend', 'Datos de la Sucursal')) ?></span>
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
														<?= $form->field($model, 'fecha_inicio')->widget(\yii\jui\DatePicker::classname(),['id' => 'fecha-inicio',
																																			'clientOptions' => [
																																				'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																																				'changeYear' => true,
																																			],
																																			'language' => 'es-ES',
																																			'dateFormat' => 'dd-MM-yyyy',
																																			'options' => [
																																					'class' => 'form-control',
																																					'readonly' => true,
																																					'style' => 'background-color: white;',

																																			]
																																			])->label(false) ?>
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
										<?php
			                            	$listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo(false);
			                                $mt = new TelefonoCodigo();
			                            ?>

<!-- Codigo Telefonicos Local 1-->
										<div class="row">
											<div class="col-sm-2" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Codigo') ?></i></p>
												</div>
												<div class="row" >
													<div class="codigo" >
														<?= $form->field($mt, 'codigo')->dropDownList($listaTelefonoCodigo, [
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

<!-- Boton para borrar el contenido -->
											<div class="col-sm-2" style="margin-left: 5px;top:23px;">
												<i class="fa fa-pencil-square-o fa-2x"></i>
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
														<?= $form->field($mt, 'codigo')->dropDownList($listaTelefonoCodigo, [
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
										<?php
			                            	$listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo(true);
			                                $mtc = new TelefonoCodigo();
			                            ?>
										<div class="row">
											<div class="col-sm-2" style="margin-left: 15px;">
												<div class="row" style="width:100%;">
													<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Codigo') ?></i></p>
												</div>
												<div class="row" >
													<div class="tlf-celular" >
														<?= $form->field($mtc, 'codigo')->dropDownList($listaTelefonoCodigo, [
                                                                                                 							'prompt' => Yii::t('backend', 'Select'),
                                                                                                 							'style' => 'width:100px;',
                                                                                                 							'id' => 'codigo3',
                                                                                                 							'name' => 'codigo3',
                                                                                                							])->label(false) ?>
													</div>
												</div>
											</div>

											<div class="col-sm-2"  style="margin-left: -58px;">
												<div class="row" style="width:100%;">
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
												<div class="row" style="width:100%;">
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

<!-- LISTA DE DOCUMENTOS Y REQUISITOS -->
							<div class="row">
								<div class="panel panel-success" style="width: 100%;">
							        <div class="panel-heading">
							        	<span><?= Html::encode(Yii::t('backend', 'Documents and Requirements Consigned')) ?></span>
							        </div>
							        <div class="panel-body">
							        	<div class="row">
							        		<div class="col-sm-8">
												<div class="documento-requisito-consignado">
											        <?= GridView::widget([
											        	'id' => 'grid-list',
											            'dataProvider' => DocumentoRequisitoController::actionGetDataProviderSegunImpuesto(1),
											            //'filterModel' => $searchModel,
											            //'layout'=>"n{pager}\n{items}",

											            //'headerRowOptions' => ['class' => 'success'],
											            // 'rowOptions' => function($data) {
											            //     if ( $data->inactivo == 1 ) {
											            //         return ['class' => 'danger'];
											            //     }
											            // },
											            'columns' => [
											                	['class' => 'yii\grid\SerialColumn'],
											                	[
												                    'label' => 'ID.',
												                    'value' => 'id_documento',
												                ],
												                [
												                    'label' => 'Descripcion',
												                    'value' => 'descripcion',
												                ],
												                ['class' => 'yii\grid\CheckboxColumn'],
											            ]
													]);?>
												</div>
											</div>
										</div>
									</div>   	<!-- Fin de panel-body documento -->
								</div>  		<!-- Fin de panel panel-success documento -->
							</div>				<!-- Fin de row documento -->
<!-- FINAL DE DOCUMENTOS Y REQUISITOS -->


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

						</div>
		        	</div>
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
</script>