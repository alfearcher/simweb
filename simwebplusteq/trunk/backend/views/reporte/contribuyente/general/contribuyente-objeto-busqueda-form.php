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
 *  @file contribuyente-objeto-busqueda-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-07-2017
 *
 *  @view contribuyente-objeto-busqueda-form.php
 *  @brief vista del formualario que se utilizara para buscar a los contribuyentes
 *  con sus respectivos objetos imponibles.
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
<div class="contribuyente-objeto-busqueda-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-contribuyente-objeto-busqueda-form-form',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 70%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;width: 100%;">
        			<h4><strong><?= Html::encode($caption) ?></strong></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

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


<!-- Chebox Todos los contribuyentes -->
					<div class="row" style="width: 100%;">

						<div class="col-sm-2" style="width:32%;padding-top: 2px;">
							<?= Html::activeCheckbox($model, 'todos', [
																		'label' => $model->getAttributeLabel('todos'),
																		'labelOptions' => [
																			'style' => 'width:100%;',
																		],
																	]);
							?>
						</div>

						<div class="col-sm-2" style="width:60%;padding-left:0px;">
<!-- Inicio Condicion del contribuyente -->
							<div class="row" style="width:100%;">
								<div class="col-sm-2" style="width:22%;padding-top:6px;">
									<p><?= $model->getAttributeLabel('condicion_contribuyente') ?></p>
								</div>
								<div class="col-sm-5" style="width:55%;padding-left:0px;">
									 <?= $form->field($model, 'condicion_contribuyente')
									          ->dropDownList($listaCondicionContribuyente, [
		                                                           	'id'=> 'id-condicion-contribuyente',
		                                                           	'prompt' => Yii::t('backend', 'Select'),
		                                                           	'style' => 'width:140px;',
		                                                           ])->label(false);
	                                ?>
								</div>
							</div>
<!-- Fin de Condicion del contribuyente -->
						</div>
					</div>


<!-- Condicion de los objetos -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;margin-top: 20px;">
						<h4><strong><?=Html::encode($captionObjeto)?></strong></h4>
					</div>

					<div class="row" style="width:100%;padding-top: 15px;">
						<div class="col-sm-2" style="width:16%;padding-top:6px;">
							<p><strong><?= $model->getAttributeLabel('condicion_objeto') ?></strong></p>
						</div>
						<div class="col-sm-5" style="width:55%;padding-left:0px;">
							 <?= $form->field($model, 'condicion_objeto')
							          ->dropDownList($listaCondicionContribuyente, [
	                                                       	'id'=> 'id-condicion-objeto',
	                                                       	'prompt' => Yii::t('backend', 'Select'),
	                                                       	'style' => 'width:140px;',
	                                                       ])->label(false);
	                        ?>
						</div>
					</div>

					<?php if ( $showFecha ) { ?>
<!-- Rango de Inscripcion -->
						<div class="row" style="width:100%;padding-top: 0px;">
							<div class="col-sm-2" style="width:28%;padding-top:6px;">
								<p><strong><?= Yii::t('backend', 'Rango de Inscripcion') ?></strong></p>
							</div>
						</div>

<!-- Inicio de Fecha Desde -->
						<div class="row" style="width:100%;padding-top: 0px;margin-left: 15px;">
							<div class="col-sm-2" style="width:10%;margin-top: 5px;">
								<p><strong><?= $model->getAttributeLabel('fecha_desde') ?></strong></p>
							</div>
							<div class="col-sm-2" style="width:20%;">
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
<!-- Fin Fecha Desde -->


<!-- Inicio de Fecha Hasts -->
						<div class="row" style="width:100%;padding-top: 0px;margin-left: 15px;">
							<div class="col-sm-2" style="width:10%;margin-top: 5px;">
								<p><strong><?= $model->getAttributeLabel('fecha_hasta') ?></strong></p>
							</div>
							<div class="col-sm-2" style="width:20%;">
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
<!-- Fin Fecha Hasta -->
					<?php } ?>

					<div class="row" style="padding-top: 20px;">
						<div class="col-sm-3" style="">
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


