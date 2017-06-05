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
 *  @file seleccionar-lapso-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-09-2016
 *
 *  @view seleccionar-lapso-form.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="seleccionar-lapso-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-seleccionar-lapso-form',
 			'method' => 'post',
 			'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 110%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
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
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<b><span><?= Html::encode($subCaption) ?></span></b>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
									<div class="col-sm-2" style="width: 12%;">
										<div class="ano-impositivo">
					                		<?= $form->field($model, 'ano_impositivo')->dropDownList($listaAño,[
	                																	 			'id' => 'ano-impositivo',
	                																	 			'style' => 'width: 100%;',
	                                                                     				 			'prompt' => Yii::t('backend', 'Select..'),
	                                                                     				 			'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                                                                         ->createUrl($rutaLista) . '&id=' . '" + $(this).val(),
                                                                                                                             		 													function( data ) {
                                                                                                                                                                                            $( "select#id-exigibilidad-periodo" ).html( data );
                                                                                                                                                                                        });'
				                                                                			])->label(false)
					    					?>
										</div>
	        						</div>

									<div class="col-sm-2" style="width: 25%;margin-left: -25px;">
										<div class="periodo">
					                		<?= $form->field($model, 'exigibilidad_periodo')->dropDownList([],[
            																	 			'id' => 'id-exigibilidad-periodo',
            																	 			'style' => 'width: 80%;',
                                                                 				 			'prompt' => Yii::t('backend', '-'),
                                                                						])->label(false)
					    					?>
										</div>
	        						</div>

									<div class="form-group">
										<div class="col-sm-3" style="width: 18%;margin-top: -22px;">
											<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Accept')),
																								  [
																									'id' => 'btn-accept',
																									'class' => 'btn btn-primary',
																									'style' => 'width: 100%; margin-left: 0px;margin-top:20px;',
																									'name' => 'btn-accept',
																									'value' => 1,
																									//'disabled' => ( $activarBotonCreate == 1 ) ? false : true,
																								  ])
											?>
										</div>

	        						</div>
								</div> 		<!-- Fin de row -->
							</div>
						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																			  [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%; margin-left: 300px;margin-top:20px;',
																				'name' => 'btn-quit',

																			  ])
						?>
					</div>
				</div>

				<div class="col-sm-1" style="width: 6%;"></div>


				<?php if ( $btnBack == 1 ) { ?>
					<div class="col-sm-3">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('frontend', Yii::t('frontend', 'Back')),
																				  [
																					'id' => 'btn-back-form',
																					'class' => 'btn btn-danger',
																					'value' => 1,
																					'style' => 'width: 100%; margin-left: 300px;margin-top:20px;',
																					'name' => 'btn-back-form',

																				  ])
							?>
						</div>
					</div>
				<?php } ?>

			</div>

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


