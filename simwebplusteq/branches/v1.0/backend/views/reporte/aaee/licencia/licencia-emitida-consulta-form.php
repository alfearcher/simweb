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
 *  @file licencia-emitida-consulta-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @view licencia-emitida-consulta-form.php
 *  @brief vista del formualario que se utilizara para consultar las licencias emitidas.
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
<div class="busqueda-licencia-emitida">
	<?php
		$form = ActiveForm::begin([
			'id' => 'busqueda-licencia-emitida-form',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 80%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

<!-- Inicio Rango de fecha -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?=Yii::t('backend', 'Buscar por Rango de Fecha') ?></strong></h4>
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
								<div class="row" class="fecha-desde" style="width: 100%;">
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
																								'style' => 'background-color: white;width:100%;',

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
								<div class="row" class="fecha-hasta" style="width: 100%;">
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
																								'style' => 'background-color: white;width:100%;',

																							]
																							])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Fecha Hasta -->
					</div>


<!-- Buscar por Contribuyente -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
						<h4><strong><?= Yii::t('backend', 'Buscar por Contribuyente')?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2" style="width: 22%;padding: 0px;padding-left: 25px;">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('id_contribuyente') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2" style="width: 25%;">
							<div class="row" class="id-contribuyente">
								<?= $form->field($model, 'id_contribuyente')->textInput([
																				'id' => 'id-contribuyente',
																				'style' => 'width: 100%;'
																			])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Buscar por Contribuyente -->


<!-- Buscar por Licencia -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
						<h4><strong><?= Yii::t('backend', 'Buscar por Licencia')?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2" style="width: 22%;padding: 0px;padding-left: 25px;">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('licencia') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2" style="width: 25%;">
							<div class="row" class="licencia">
								<?= $form->field($model, 'licencia')->textInput([
																				'id' => 'id-licencia',
																				'style' => 'width: 100%;'
																			])->label(false) ?>
							</div>
						</div>
					</div>
<!-- Fin de Buscar por Licencia -->


					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;color:blue;">
						<h4><strong><?= Yii::t('backend', 'Parametros adicionales')?></strong></h4>
					</div>
<!-- Inicio Tipo de Licencia -->
					<div class="row" style="padding-left: 40px;padding-top: 20px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('tipo_licencia') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="row" class="tipo-licencia">
								 <?= $form->field($model, 'tipo_licencia')
								          ->dropDownList($listaTipoLicencia, [
	                                                           	'id'=> 'id-tipo-licencia',
	                                                           	'prompt' => Yii::t('backend', 'Select'),
	                                                           	'style' => 'width:260px;',
	                                                           ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Tipo de Licencia -->


<!-- Inicio Condicion de la Licencia -->
					<!-- <div class="row" style="padding-left: 40px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?//= $model->getAttributeLabel('estatus') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="estatus">
								 <?//= $form->field($model, 'estatus')
								          // ->dropDownList($listaEstatus, [
	                 //                                           	'id'=> 'id-estatus',
	                 //                                           	'prompt' => Yii::t('backend', 'Select'),
	                 //                                           	'style' => 'width:260px;',
	                 //                                           ])->label(false);
                                ?>
							</div>
						</div>
					</div> -->
<!-- Fin de Condicion de la licencia -->



<!-- Inicio Lista de Usuarios -->
					<div class="row" style="padding-left: 40px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('usuario') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="usuario">
								 <?= $form->field($model, 'usuario')
								          ->dropDownList($listaUsuario, [
	                                                           	'id'=> 'id-usuario',
	                                                           	'prompt' => Yii::t('backend', 'Select'),
	                                                           	'style' => 'width:260px;',
	                                                           ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Lista de Usuarios -->








					<div class="row" style="padding-top: 20px;">
						<div class="col-sm-3" style="float: right;">
							<?= Html::submitButton(Yii::t('backend', 'Search'),
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
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


