<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
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
 *  @file create-config-solicitud-form.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com - jperez820@hotmail.com
 *
 *  @date 22-02-2016
 *
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
	use yii\widgets\Pjax;
	use backend\controllers\configuracion\procesosolicitud\SolicitudProcesoController;

?>

<?php
	$this->title = Yii::t('backend', 'Create Setup Request.');

	Icon::map($this, Icon::FA);
?>



<div class="create-config-solicitud-form" style="margin-top: 0px;">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'config-solicitud-form',
 			'method' => 'post',
 			'action' => ['/configuracion/solicitud/configurar-solicitud/create'],
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);

 		// Lista de los impuesto
 		$listaImpuesto = ArrayHelper::map($modelImpuesto, 'impuesto', 'descripcion');

 		//Campo oculto
 		$form->field($model, 'ejecutar_en')->hiddenInput()->label(false);
 		// Lista de los niveles de aprobacion.
 		//$listaNivelesAprobacion = ['1' => 'AUTOMATICO', '2' => 'SELECCION DE OPCIONES', '3' => 'ACTIVACION DE FORMULARIO'];
 		//$listaNivelesAprobacion = ArrayHelper::map($modelCodigoCelular, 'codigo', 'codigo');
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading"><h4><?= Html::encode($this->title) ?></h4></div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">
<!-- Inicio Impuesto -->
			        <div class="row" style=" margin-top: 0px;margin-left:10px;">
			        	<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('impuesto')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="row">
								<div class="impuesto">
									<?= $form->field($model, 'impuesto')->dropDownList($listaImpuesto,[
				                																		'id' => 'impuesto',
				                																		'style' => 'width:100%;',
				                                                                     				 	'prompt' => Yii::t('backend', 'Select'),
				                                                                     				 	'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                                                                             ->createUrl('/configuracion/solicitud/configurar-solicitud/lista-tipo-solicitud') . '&id=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                            $( "select#tipo-solicitud" ).html( data );
                                                                                                                                                                                                        });'
				                                                                						 ])->label(false)
								   ?>
								</div>
							</div>
						</div>
					</div>	<!-- Fin de row -->
<!-- Fin de Impuesto -->


<!-- Inicio Tipo Solicitud -->
			        <div class="row" style=" margin-top: 0px;margin-left:10px;">
			        	<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('tipo_solicitud')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								<div class="tipo-solicitud">
									<?= $form->field($model, 'tipo_solicitud')->dropDownList([],[
				                																	'id' => 'tipo-solicitud',
				                																	'style' => 'width:160%;',
				                                                                     				'prompt' => Yii::t('backend', 'Select'),
				                                                                     				'value' => Yii::$app->request->post('tipo_solicitud'),
				                                                                				])->label(false)
								   ?>
								</div>
							</div>
						</div>
					</div>	<!-- Fin de row -->
<!-- Fin de Tipo Solicitud -->

<!-- Inicio Nivel de Aprobacion -->
			        <div class="row" style=" margin-top: 0px;margin-left:10px;">
			        	<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('nivel_aprobacion')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row">
								<div class="nivel-aprobacion">
									<?= $form->field($model, 'nivel_aprobacion')->dropDownList($listaNivelAprobacion,[
				                																				'id' => 'nivel-aprobacion',
				                																				'style' => 'width:120%;',
				                                                                     							'prompt' => Yii::t('backend', 'Select'),
				                                                                							])->label(false)
								   ?>
								</div>
							</div>
						</div>
					</div>	<!-- Fin de row -->
<!-- Fin de Nivel de Aprobacion -->

<!-- Fecha de Inicio de Fecha Desde -->
					<div class="row" style=" margin-top: 0px;margin-left:10px;">
						<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_desde')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="row" >
								<div class="fecha-desde">
									<?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
																													  'clientOptions' => [
																															//'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																															'changeYear' => true,
																													   ],
																													   'language' => 'es-ES',
																													   'dateFormat' => 'dd-MM-yyyy',
																													   'options' => [
																															'id' => 'fecha-desde',
																															'class' => 'form-control',
																															'readonly' => true,
																															'style' => 'background-color: white;width:75%;',

																													    ]
																														])->label(false) ?>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Fecha Desde -->


<!-- Inicio Fecha Hasta -->
					<div class="row" style=" margin-top: 0px;margin-left:10px;">
						<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_hasta')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="row" >
								<div class="fecha-hasta">
									<?= $form->field($model, 'fecha_hasta')->widget(\yii\jui\DatePicker::classname(),[
																													  'clientOptions' => [
																															//'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																															'changeYear' => true,
																														],
																													  'language' => 'es-ES',
																													  'dateFormat' => 'dd-MM-yyyy',
																													  'options' => [
																													  		'id' => 'fecha-hasta',
																															'class' => 'form-control',
																															'readonly' => true,
																															'style' => 'background-color: white;width:75%;',

																														]
																														])->label(false) ?>
								</div>
							</div>
						</div>
						<div class="col-sm-1" style="margin-left: -35px;margin-top: 4px;">
							<div class="row">
								<?= Html::button('<center><span class= "fa fa-undo"></span></center>',
																						[
																							'id' => 'id-btn-resetear',
																							'style' => 'color:blue;',
																							//'onclick' => 'resetearFecha($this)'
																						])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Fecha Hasta -->

<!-- Inicio Mostrar solo a funcionario -->
			        <div class="row" style=" margin-top: 0px;margin-left:10px;">
			        	<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('solo_funcionario')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								<div class="solo-funcionario" style=" margin: 0 0 0 0;">
									<?= $form->field($model, 'solo_funcionario')->checkBox([
				                															'id' => 'solo-funcionario',
				                															'label' => '',
				                															'labelOptions' => [
				                																'style' => 'width: 35%;margin-left:0px;',
				                															],
				                															'style' => 'width:50%;margin-left:-24px;',
				                                                                		])
								   ?>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Mostrar solo a funcionario -->

<!-- Inicio Observacion -->
			        <div class="row" style=" margin-top: 0px;margin-left:10px;">
			        	<div class="col-sm-2">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('observacion')) ?></i></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								<div class="observacion">
									<?= $form->field($model, 'observacion')->textArea([
	                																	'id' => 'observacion',
	                																	'style' => 'width:160%;',
	                                                                				])->label(false)
								   ?>
								</div>
							</div>
						</div>
					</div>	<!-- Fin de row -->
<!-- Fin de Tipo Solicitud -->


<!-- Inicio de Procesos que genera la solicitud -->
					<div class="row" style="border-bottom: solid 1px #ccc;color: blue;">
						<h4><?= Yii::t('backend', 'Procesos que genera la Solicitud.') ?></h4>
					</div>
					<div class="row">
						<div class="well" style="color: red;"><?= Html::encode($errorEjecutarEn); ?></div>
					</div>
					<div class="row">
		        		<div class="col-sm-8" style="width: 90%;">
							<div class="lista-proceso-generado">
								<?= SolicitudProcesoController::actionListarProcesoSolicitud(); ?>
							</div>
						</div>
					</div>
<!-- Fin de Procesos que genera la solicitud -->

<!-- Inicio de Documentos y/o Requisitos de la solicitud -->
					<div class="row" style="border-bottom: solid 1px #ccc;color: blue;">
						<h4><?= Yii::t('backend', 'Documentos y/o Requisitos.') ?></h4>
					</div>
					<div class="row">
		        		<div class="col-sm-8">
							<div class="documento-requisito-consignado">
									<? Pjax::begin(); ?>
									<div id="lista-documento-requisito">
									</div>
									<? Pjax::end(); ?>
							</div>
						</div>
					</div>
<!-- Fin de Documentos y/o Requisitos de la solicitud -->

				</div>		<!-- Fin de col-sm-12 -->


				<div class="row" style="margin-left:50px; margin-top: 85px;">
<!-- Boton para aplicar la actualizacion -->
					<div class="col-sm-3">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', 'Execute Create'),
																					  [
																						'id' => 'btn-create',
																						'class' => 'btn btn-success',
																						'value' => 1,
																						'name' => 'btn-create',
																						'style' => 'width: 100%;',
																						'data-confirm' => Yii::t('backend', 'Confirm Save?.'),
																					  ])
							?>
						</div>
					</div>
<!-- Fin de Boton para aplicar la actualizacion -->

					<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
					<div class="col-sm-2" style="margin-left: 50px;">
						<div class="form-group">
							<?= Html::a(Yii::t('backend', 'Quit'), ['quit'], [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'style' => 'width: 100%;',
																			])
							?>
						</div>
					</div>
<!-- Fin de Boton para salir de la actualizacion -->
				</div>		<!-- Fin de row botones -->


			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de Panel body -->
    </div>		<!-- Fin de Panel Panel-Default -->
    <?php ActiveForm::end() ?>
</div>

<?php
	$this->registerJs(
		'$("#impuesto").on("change", function() {
			var url = "' . Yii::$app->urlManager
			                        ->createUrl("/configuracion/solicitud/configurar-solicitud/lista-documento-requisito") . "&id=" . '" + $(this).val();

			//$.blockUI({ message: "Espere un momento por favor..." });

			$.ajax({
				type: "POST",
				url: url,
				data: $("#config-solicitud-form").serialize(),
				success: function(data) {
					$("#lista-documento-requisito").html(data);
				}
			});
			//$.unblockUI();
			return false;
		});

		$("#id-btn-resetear").on("click", function() {
			$("#fecha-hasta").val("");
		});
	')
?>