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
 *  @file prueba-form-buscar.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-08-2015
 *
 *  @view prueba-form-buscar.php
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
	//use kartik\icons\Icon;
	use yii\web\View;
	use backend\models\TipoNaturaleza;
 ?>

<div class="buscar-general-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'buscar-general-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => false,
 		]);

 		$disableJuridico = true;
 		$disableNatural = true;
 		$disableAll = true;
 		if ( $tipoNat == 0 ) {				//	Natural
 			$disableNatural = false;
 			//$maxLength = 8;					// Maximos digitos que acepta la cedula

 		} elseif ( $tipoNat == 1 ) {		// Juridico
 			$disableJuridico = false;
 			//$maxLength = 9;					// Maximos digitos que acepta el RIF
 		}

 		if (!$disableNatural || !$disableJuridico) { $disableAll = false; }
 	 ?>
	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 55%;">
        <div class="panel-heading">
			<?= Html::encode($titulo) ?>
        </div>

<!--	 <? //= Html::activeHiddenInput($model, 'tipo_naturaleza', ['id' => 'tipo-nat', 'name' => 'tipo-nat', 'value' => 12]) ?> -->

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row">

<!-- OPCIONES DEL RADIO SELECTOR-->
						<div class="col-sm-4">
							<div class="radio-selector" style="border-right: 0.2em inset;">
								<p><?= Yii::t('backend', 'Types of Taxpayers') ?></p>

								<div class="radio">
									<?= $form->field($model, 'tipo_naturaleza')->label(false)->radioList([
								 																		'1' => Yii::t('backend', 'Legal Taxpayer'),
								 																		'0' => Yii::t('backend', 'Natural Taxpayer')
								 																	  ],
								 																	  [
								 																	  	'onclick' => 'tipo(this)',
								 																	  ]
								 																	  ) ?>
								</div>
							</div>
						</div> <!-- Fin de col-sm-4 -->
<!-- FIN DE OPCIONES DEL RADIO SELETOR -->

<!--
	Columna donde se colocara campos de la cedula-rif, razon social, nro de licencia, apellidos y nombres
-->
						<div class="col-sm-8">
							<div class="row" style="width:100%;">
								<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', 'naturaleza') ?></small></i></p>
							</div>
<!-- COMBO NATURALEZA -->
							<div class="row" style="width:100%; padding-left: 0px;padding-right: 0px;">
								<div class="container-fluid" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;">
									<div class="col-sm-5" style="padding-right: 12px;">
					          			<div class="naturaleza">
					                		<?= $form->field($model, 'naturaleza')->dropDownList([],[
	                																	 			'id' => 'naturaleza',
	                                                                     				 			'prompt' => Yii::t('backend', 'Select'),
	                                                                     				 			'style' => 'height:32px;width:150px;',
	                                                                     				 			'disabled' => $disableAll,
	                                                                    							])->label(false)
					    					?>
										</div>
									</div>
<!-- FIN DE COMBO NATURALEZA -->

<!-- CEDULA -->
									<div class="col-sm-4" style="padding-left: 25px;">
										<div class="cedula">
											<?= $form->field($model, 'cedula')->textInput([
																							'id' => 'cedula',
																							'style' => 'height:32px;width:122px;',
																							'disabled' => $disableAll,
																							//'maxlength' => $maxLength,
																		  				  ])->label(false) ?>
										</div>
									</div>
<!-- FIN DE CEDULA -->

<!-- TIPO -->
									<div class="col-sm-1" style="padding-right: 0px;padding-left: 35px;">
										<div class="tipo">
											<?= $form->field($model, 'tipo')->textInput([
																							'id' => 'tipo',
																							'style' => 'height:32px;width:38px;',
																							'disabled' => $disableJuridico,
																							'maxlength' => 1,
																			  			 ])->label(false) ?>
										</div>
									</div>
								</div>
<!-- FIN DE TIPO -->
							</div> <!-- Fin de row -->

<!-- RAZON SOCIAL -->
							<div class="row" style="width:100%;">
								<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $model->getAttributeLabel('razon_social')) ?></small></i></p>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="razon-social">
										<?= $form->field($model, 'razon_social')->textInput([
																							'id' => 'razon-social',
																							'style' => 'height:32px;width:320px;',
																							'disabled' => $disableJuridico,
																				 			])->label(false) ?>
									</div>
								</div>
							</div>
<!-- FIN DE RAZON SOCIAL -->

<!-- ID-SIM, nro de licencia -->
							<div class="row" style="width:100%;">
								<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $model->getAttributeLabel('id_sim')) ?></small></i></p>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="id-sim">
										<?= $form->field($model, 'id_sim')->textInput([
																							'id' => 'id-sim',
																							'style' => 'height:32px;width:155px;',
																							'disabled' => $disableJuridico,
																				 			])->label(false) ?>
									</div>
								</div>
							</div>
<!-- FIN DE ID-SIM, nro de licencia -->

<!-- APELLIDOS -->
							<div class="row" style="width:100%;">
								<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $model->getAttributeLabel('apellidos')) ?></small></i></p>
							</div>
							<div class="row">
								<div class="col-sm-3" style="padding-right: 84px;">
									<div class="apellidos">
										<?= $form->field($model, 'apellidos')->textInput([
																							'id' => 'apellidos',
																							'style' => 'height:32px;width:260px;',
																							'disabled' => $disableNatural,
																			   			])->label(false) ?>
									</div>
								</div>
							</div>
<!-- FIN DE APELLIDOS -->

<!-- NOMBRES -->
							<div class="row" style="width:100%;">
								<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $model->getAttributeLabel('nombres')) ?></small></i></p>
							</div>
							<div class="row">
								<div class="col-sm-3" style="padding-right: 84px;">
									<div class="nombres">
										<?= $form->field($model, 'nombres')->textInput([
																				'id' => 'nombres',
																				'style' => 'height:32px;width:260px;',
																				'disabled' => $disableNatural,
																			 ])->label(false) ?>
									</div>
								</div>
							</div>
<!-- FIN DE NOMBRES -->
						</div> <!-- Fin de col-sm-8 -->
					</div>	<!-- Fin de row -->

<!-- ID CONTRIBUYENTE -->
					<div class="row" style="width:100%;">
						<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $model->getAttributeLabel('id_contribuyente')) ?></small></i></p>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="id-contribuyente">
								<?= $form->field($model, 'id_contribuyente')->textInput([
																							 'id' => 'id-contribuyente',
																							 'style' => 'height:32px;width:155px;',
																			  				])->label(false) ?>
							</div>
						</div>
<!-- FIN DE ID CONTRIBUYENTE -->

<!-- BOTON SEARCH -->
						<div class="col-sm-2"  >
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Search')),
																									  [
																										'id' => 'btn-search',
																										'class' => 'btn btn-success',
																										'name' => 'btn-search',
																									  ])
								?>
							</div>
						</div>
<!-- FIN DE SEARCH -->

<!-- BOTON QUIT -->
						<div class="col-sm-2"  >
							<div class="form-group">
								 <?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'], ['class' => 'btn btn-danger']) ?>
							</div>
						</div>
					</div>
<!-- FIN DE QUIT -->

				</div> <!-- Fin de col-sm-12 -->
			</div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>


<script type="text/javascript">

 	function tipo(s) {

 		//alert($('input:radio[name="BuscarGeneralForm[tipo_naturaleza]"]:checked').val());
 		$("#id-contribuyente").val('');
 		$("#naturaleza").val('');
 		$("#cedula").val('');
 		$("#tipo").val('');
 		$("#razon-social").val('');
 		$("#id-sim").val('');
 		$("#apellidos").val('');
 		$("#nombres").val('');


 		// Juridico
 		if ($('input:radio[name="BuscarGeneralForm[tipo_naturaleza]"]:checked').val() == '1') {
 			$("#naturaleza").attr('disabled', false);
	 		$("#cedula").attr('disabled', false);
	 		$("#tipo").attr('disabled', false);
	 		$("#razon-social").attr('disabled', false);
	 		$("#id-sim").attr('disabled', false);
	 		$("#apellidos").attr('disabled', true);
	 		$("#nombres").attr('disabled', true);
	 		<?php echo '$.post("' .Yii::$app->urlManager->createUrl('/buscargeneral/buscar-general/vista-campos-tipo-naturaleza') . '&tipoNaturaleza=1' . '",
														function( data ) {
															$( "select#naturaleza" ).html( data );
														}
								);';
			?>
 		}

 		// Natural
 		if ($('input:radio[name="BuscarGeneralForm[tipo_naturaleza]"]:checked').val() == '0') {
	 		$("#naturaleza").attr('disabled', false);
	 		$("#cedula").attr('disabled', false);
	 		$("#tipo").attr('disabled', true);
	 		$("#razon-social").attr('disabled', true);
	 		$("#id-sim").attr('disabled', true);
	 		$("#apellidos").attr('disabled', false);
	 		$("#nombres").attr('disabled', false);
	 		<?php echo '$.post("' .Yii::$app->urlManager->createUrl('/buscargeneral/buscar-general/vista-campos-tipo-naturaleza') . '&tipoNaturaleza=0' . '",
														function( data ) {
															$( "select#naturaleza" ).html( data );
														}
								);';
			?>
 		}
 	}

</script>