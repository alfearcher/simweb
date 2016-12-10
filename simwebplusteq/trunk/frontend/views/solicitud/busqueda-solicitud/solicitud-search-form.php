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
 *  @file solicitud-search-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-07-2016
 *
 *  @view solicitud-search-form.php
 *  @brief vista que utilizara el contribuyente para realizar las busqueda de los solicitudes que posea.
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
<div class="solicitud-search">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-solicitud-search-form',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>
	<?= $form->field($model, 'id_contribuyente')->hiddenInput([
															'id' => 'id-contribuyente',
															'value' => $idContribuyente,
														])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 80%;">
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
	        		<?= MenuController::actionMenuSecundario($opciones); ?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Html::encode($caption) ?></strong></h4>
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
                                                                                       		           ->createUrl('solicitud/solicitud-creada/list-solicitud') . '&i=' . '" + $(this).val(), function( data ) {
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

					<div class="row">
						<div class="col-sm-3" style="float: right;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search Request'),
																		  [
																			'id' => 'btn-search',
																			'class' => 'btn btn-primary',
																			'value' => 1,
																			'name' => 'btn-search',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

<!-- Inicia de busqueda de todos los funcionarios -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search All')?></strong></h4>
					</div>
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search All'),
																		  [
																			'id' => 'btn-search-all',
																			'class' => 'btn btn-default',
																			'value' => 3,
																			'name' => 'btn-search-all',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>

<!-- Fin de busqueda de todos los funcionarios -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


