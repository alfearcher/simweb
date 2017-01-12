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
 *  @file declarar-sustitutiva-definitiva-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @view declarar-sustitutiva-definitiva-form.php
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
	// use yii\jui\DatePicker;
	//use yii\widgets\Pjax;
	use yii\widgets\DetailView;
	//use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use backend\controllers\menu\MenuController;
	use yii\widgets\MaskedInput;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	//use yii\yii\i18n\Formatter;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="ramo-registrado-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-sustitutiva-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);

 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 110%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-7" style="padding-top: 10px;width: 75%;">
	    			<h3><?= Html::encode($caption) ?></h3>
	    		</div>
	    		<div class="col-sm-3" style="width: 15%; float:right; padding-right: 50px;">
	    			<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #337AB7;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario($opciones);?>
	        	</div>
	        </div>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 104%;margin-left: -25px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode(Yii::t('frontend', 'Info of Taxpayer')) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
<!-- Datos del Contribuyente -->
									<div class="col-sm-4" style="margin-left: 15px;width: 90%">
										<div class="row">
											<div class="id-contribuyente">
												<?= $this->render('@common/views/contribuyente/datos-contribuyente', [
							        											'model' => $findModel,
							        							])
												?>
											</div>
										</div>
									</div>
<!-- Fin de datos del Contribuyente -->

								</div> 		<!-- Fin de row -->
							</div>
						</div>
					</div>


					<div class="row">
						<div class="panel panel-success" style="width: 104%;margin-left: -25px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode($subCaption) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row" style="padding-left: 15px;">
					        		<div class="well well-sm" style="width: 50%;padding-left: 10px;">
  										<h4><strong>Importante:</strong></h4>Debe habilitar la sustitutiva del rubro para que los cambio se apliquen.
									</div>
					        	</div>

								<?php if ( $errorHabilitar ) { ?>
									<div class="row" style="padding-left: 15px;">
						        		<div class="well well-sm" style="width: 50%;padding-left: 10px;color: red;">
											<h4><strong><?=Html::encode(Yii::t('frontend', 'No ha habilitado ningun rubro. Tilde opcion Habilitar Sustitutiva')); ?></strong></h4>
										</div>
						        	</div>
						        <?php } ?>

<!-- INICIO RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->
								<div class="row"  style="padding-left: 10px; width: 100%;">
									<div class="rubro-registrado">
										<div class="row" style="margin-left: 5px;">
											<h3><span><?= Html::encode(Yii::t('frontend', $subCaption)) ?></span></h3>
										</div>

										 <div class="container-items">
<!-- Aqui se coloca le foreach para replicar el formulario tantos rubros tenga declarado. -->
											<?php foreach ( $model as $i => $mod ): ?>

												<div class="item panel panel-primary"><!-- widgetItem -->
													<div class="panel-heading">
														<div class="checkbox"><?= Html::activeCheckbox($mod, "[{$i}]chkHabilitar",
																									 [
																									 	'label' => 'Habilitar Sustitutiva',
																									 	'id' => 'id-chkHabilitar[' . $i . ']',

																									 	'onChange' => 'if ( $(this).is(":checked") ) {
																		                                                var input = "#sustitutiva" + ' . $i . '
																		                                                $(input).removeAttr("readonly");
																		                                              } else {
																		                                                var input = "#sustitutiva" + ' . $i . '
																		                                                $(input).attr("readonly", true);
																		                                              }',

																									 ]
																							);
																				?>
														</div>
														<h3 class="panel-title pull-left"><b><?=Html::encode( $i+1 .'. '. Yii::t('frontend', 'Rubro') . ' ' . $mod->rubro); ?></b></h3>
									                    <div class="clearfix"></div>
									                </div>
								                    <div class="panel-body">
								                    	<?=$form->field($mod, "[{$i}]id_contribuyente")->hiddenInput()->label(false);?>
								                    	<?=$form->field($mod, "[{$i}]ano_impositivo")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]exigibilidad_periodo")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]fecha_inicio")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]id_impuesto")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]id_rubro")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]estimado")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]rectificatoria")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]auditoria")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]rectificatoria")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]periodo_fiscal_desde")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]periodo_fiscal_hasta")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]tipo_declaracion")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]usuario")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]fecha_hora")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]origen")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]estatus")->hiddenInput()->label(false);?>
														<?=$form->field($mod, "[{$i}]condicion")->hiddenInput()->label(false);?>
														<!-- <?//=$form->field($mod, "[{$i}]user_funcionario")->hiddenInput()->label(false);?> -->


								                        <div class="row" style="padding-top:0px">
								                        	<div class="col-sm-2" style="padding-right:0px;padding-top:0px;margin-left: 0px;border-right: 0px;">
								                                <?= $form->field($mod, "[{$i}]rubro")->textInput([
						                                													'readonly' => true,
						                                													'style' => 'width: 100%;margin-left: 0px;margin-right:0px;'
								                                	])
								                                ?>
								                            </div>
								                            <div class="col-sm-5" style="width: 45%;padding-left: -20px;">
								                                <?= $form->field($mod, "[{$i}]descripcion")->textArea([
						                                														'readonly' => true,
						                                														'rows' => 2,
						                                														'style' => 'width: 100%;'
								                                	])
								                                ?>
								                            </div>

								                        	<div class="col-sm-2" style="padding-right:6px;">
								                                <?= $form->field($mod, "[{$i}]reales")->widget(MaskedInput::className(), [
																															'id' => 'reales' . $i,
																															'name' => 'reales' . $i,
																															//'mask' => '',
																															'options' => [
																																'class' => 'form-control',
																																'style' => 'width: 100%;',
																																'readonly' => true,
																																//'placeholder' => '0.00',

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
																										  				  ])->label('Definitiva');
																?>
								                            </div>

<?php
	if ($mod->chkHabilitar == 1 ) {
		$readOnly = false;
 	} else {
 		$readOnly = true;
 	}
 ?>
								                        	<div class="col-sm-2" style="padding-left:0px;">
								                                <?= $form->field($mod, "[{$i}]sustitutiva")->widget(MaskedInput::className(), [
																															'name' => 'sustitutiva' . $i,
																															//'mask' => '',
																															'options' => [
																																'class' => 'form-control',
																																'style' => 'width: 100%;',
																																'placeholder' => '0.00',
																																'id' => 'sustitutiva' . $i,
																																'readonly' => $readOnly,

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
																										  				  ]);
																?>
								                            </div>
								                        </div>

								                    </div>
								                </div>

											<?php endforeach; ?>
										 </div>

									</div>
								</div>
<!-- FIN DE RUBROS REGISTRADOS PARA EL AÑO-PERIODO -->

							</div>
						</div>
					</div>
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="form-group">
					<div class="col-sm-3" style="width: 15%;margin-left:70px;">
						<?= Html::submitButton(Yii::t('frontend', 'Create Request'),
																			  [
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'value' => 3,
																				'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																				'name' => 'btn-create',
																				//'disabled' => true,
																			  ]);
						?>
					</div>

					<div class="col-sm-3" style="width: 15%;margin-left:70px;">
						<?= Html::submitButton(Yii::t('frontend', 'Back'),
																		  [
																			'id' => 'btn-back-form',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																			'name' => 'btn-back-form',

																		  ]);
						?>
					</div>

					<div class="col-sm-3" style="width: 15%;margin-left:70px;">
						<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																			  [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
																				'name' => 'btn-quit',

																			  ])
						?>
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
													'style' => 'width: 100%;margin-top:20px;'
												])?>
						</div>
					</div>
				</div>
			</div>
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
