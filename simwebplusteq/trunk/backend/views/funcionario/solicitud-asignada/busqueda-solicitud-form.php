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
 *  @file funcionario-desincorporar-solicitud-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-04-2016
 *
 *  @view funcionario-desincorporar-solicitud-form.php
 *  @brief vista del formualario que se utilizara para capturar los datos a guardar.
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

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\jui\DatePicker;
	use backend\controllers\menu\MenuController;

?>
<div class="busqueda-solicitud">
	<?php
		$form = ActiveForm::begin([
			'id' => 'busqueda-solicitud-form',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => false,
		]);
	?>


	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
					.col-sm-3 > ul > li > a:hover {
						background-color: #F5F5F5;
					}
    			</style>
	        		<?= MenuController::actionMenuSecundario([
	        						'undo' => '/funcionario/solicitud/solicitud-asignada/index',
	        						'quit' => '/funcionario/solicitud/solicitud-asignada/quit',
	        			])
	        		?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search for Tax  - Request')?></strong></h4>
					</div>

<!-- Inicio Impuetos -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('impuesto') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								 <?= $form->field($model, 'impuesto')
								          ->dropDownList($listaImpuesto, [
                                                                  'id'=> 'impuesto',
                                                                  'prompt' => Yii::t('backend', 'Select'),
                                                                  'style' => 'width:460px;',
                                                                  'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                       		           ->createUrl('funcionario/solicitud/solicitud-asignada/list-solicitud') . '&i=' . '" + $(this).val(), function( data ) {
                                                                                                                 $( "select#tipo-solicitud" ).html( data );
                                                                                                           });'
                                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Impuestos -->

<!-- Inicio Tipo de Solicitud -->
					<div class="row">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('tipo_solicitud') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="tipo-solicitud">
								 <?= $form->field($model, 'tipo_solicitud')
								          ->dropDownList([], [
                                                            	'id'=> 'tipo-solicitud',
                                                            	'prompt' => Yii::t('backend', 'Select'),
                                                            	'style' => 'width:460px;',
                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Tipo de Solicitud -->

<!-- Inicio Rango de fecha -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Rango de Fecha')?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;padding-left: 55px;">
<!-- Inicio de Fecha Desde -->
						<div class="row">
							<div class="col-sm-2">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('fecha_desde') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row" class="fecha-desde">
									<?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
																						  'clientOptions' => [
																								'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
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

<!-- Fin Fecha Desde -->

<!-- Inicio de Fecha Hasta -->
						<div class="row">
							<div class="col-sm-2">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('fecha_hasta') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row" class="fecha-hasta">
									<?= $form->field($model, 'fecha_hasta')->widget(\yii\jui\DatePicker::classname(),[
																						  'clientOptions' => [
																								'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
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
<!-- Fin de Fecha Hasta -->
					</div>

<!-- Inicio de Nro de Solicitud -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= $model->getAttributeLabel('nro_solicitud') ?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('nro_solicitud') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row" class="nro-solicitud">
								<?= $form->field($model, 'nro_solicitud')->textInput([
																				'id' => 'nro-solicitud',
																			])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Nro de Solicitud -->



<!-- Inicio de Id Contribuyente-->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= $model->getAttributeLabel('id_contribuyente') ?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('id_contribuyente') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="row" class="id-contribuyente">
								<?= $form->field($model, 'id_contribuyente')->textInput([
																				'id' => 'id-contribuyente',
																			])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Id Contribuyente -->




					<div class="row">
						<div class="col-sm-3" style="float: right;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search Request'),
																		  [
																			'id' => 'btn-search-request',
																			'class' => 'btn btn-primary',
																			'value' => 1,
																			'name' => 'btn-search-request',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


