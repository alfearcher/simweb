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
 *  @file busqueda-solicitud-licencia-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-01-2017
 *
 *  @view busqueda-solicitud-licencia-form.php
 *  @brief vista del formualario que se utilizara para buscar las solicitudes de licencias.
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
<div class="busqueda-solicitud-licencia">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-busqueda-solicitud-certificado-form',
			'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 65%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top:10px;width: 60%;font-size: 140%;">
        			<strong><p><?= Html::encode($caption) ?></p></strong>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Buscar por Tipo de Certificado Catastral')?></strong></h4>
					</div> 

<!-- Combo de tipos de licencias -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= Yii::t('backend', 'Buscar Tipo de Certificado Catastral') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								 <?= $form->field($model, 'tipo')
								          ->dropDownList($listaTipoLicencia, [
                                                                  'id'=> 'id-tipo',
                                                                  'prompt' => Yii::t('backend', 'Select'),
                                                                  'style' => 'width:200px;',
                                                          ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de tipo de licencia -->

<!-- Inicio Rango de fecha -->
					<div class="row">
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
																								'style' => 'background-color: white;width:100%;',

																							]
																							])->label(false) ?>
								</div>
							</div>
						</div>
<!-- Fin de Fecha Hasta -->
					</div>

					<div class="row">
						<div class="col-sm-3" style="float: right;width: 40%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search Request'),
																		  [
																			'id' => 'btn-search-tipo',
																			'class' => 'btn btn-primary',
																			'value' => 3,
																			'name' => 'btn-search-tipo',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

				</div>
				<div class="row" style="border-bottom: 0.5px solid #ccc;"></div>

				<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
					<h4><strong><?= Yii::t('backend', 'Buscar por Id. Contribuyente')?></strong></h4>
				</div>


				<div class="row" style="padding-top: 15px;">
					<!-- <div class="col-sm-2" style="width: 22%;padding: 0px;padding-left: 25px;">
						<div class="row">
							<p><strong><?//= $model->getAttributeLabel('id_contribuyente') ?></strong></p>
						</div>
					</div>
					<div class="col-sm-2" style="width: 25%;">
						<div class="row" class="id-contribuyente">
							<?//= $form->field($model, 'id_contribuyente')->textInput([
																			// 'id' => 'id-contribuyente',
																			// 'style' => 'width: 100%;'
																		//])->label(false) ?>
						</div>
					</div>

					<div class="col-sm-3" style="float: right;width: 40%;">
						<div class="form-group">
							<?//= Html::submitButton(Yii::t('backend', 'Search Request'),
																	 //  [
																		// 'id' => 'btn-search-contribuyente',
																		// 'class' => 'btn btn-warning',
																		// 'value' => 5,
																		// 'name' => 'btn-search-contribuyente',
																		// 'style' => 'width: 100%;',
																	 //  ])
							?>
						</div>
					</div> -->

				</div>

			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


