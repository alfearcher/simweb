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
<div class="busqueda-contribuyente-existentes">
	<?php
		$form = ActiveForm::begin([
			'id' => 'busqueda-contribuyente-existentes-form',
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
						<h4><strong><?=Yii::t('backend', 'Parametros de Consulta') ?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;padding-left: 55px;">
<!-- Inicio Tipo de Naturaleza-->
						<div class="row" style="padding-left: 40px;padding-top: 20px;">
							<div class="col-sm-2">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('tipo_naturaleza') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="row" class="tipo-naturaleza">
									 <?= $form->field($model, 'tipo_naturaleza')
									          ->dropDownList($listaTipoNaturaleza, [
		                                                           	'id'=> 'id-tipo-naturaleza',
		                                                           	'prompt' => Yii::t('backend', 'Select'),
		                                                           	'style' => 'width:260px;',
		                                                           ])->label(false);
	                                ?>
								</div>
							</div>
						</div>
<!-- Fin de Tipo de Naturaleza -->


<!-- Inicio Condicion del registro -->
						<div class="row" style="padding-left: 40px;">
							<div class="col-sm-2">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('condicion') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="row" class="condicion">
									 <?= $form->field($model, 'condicion')
									          ->dropDownList($listaCondicion, [
		                                                           	'id'=> 'id-condicion',
		                                                           	'prompt' => Yii::t('backend', 'Select'),
		                                                           	'style' => 'width:260px;',
		                                                           ])->label(false);
	                                ?>
								</div>
							</div>
						</div>
<!-- Fin de Condicion del registro -->

					</div>


					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
						<h4><strong><?= Yii::t('backend', 'Parametros adicionales')?></strong></h4>
					</div>

					<div class="row" style="width: 100%;margin-top:10px;">
						<?= Html::activeCheckbox($model, 'sin_licencia', [
																			'label' => $model->getAttributeLabel('sin_licencia'),
																			'labelOptions' => [
																				'style' => 'width:100%;',
																			],
																		]);
						?>
					</div>

					<div class="row" style="width: 100%;">
						<?= Html::activeCheckbox($model, 'sin_email', [
																			'label' => $model->getAttributeLabel('sin_email'),
																			'labelOptions' => [
																				'style' => 'width:100%;',
																			],
																		]);
						?>
					</div>


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


