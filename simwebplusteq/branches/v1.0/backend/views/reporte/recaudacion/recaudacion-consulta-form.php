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
 *  @file recaudacion-consulta-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view recaudacion-consulta-form.php
 *  @brief vista que se utilizara para consultar la recaudacion de ingresos.
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
<div class="recaudacion-consulta">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-recaudacion-consulta-form',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 60%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;width: 80%;">
        			<h4><strong><?= Html::encode($caption) ?></strong></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row">
        				<div class="list-group">
        					<strong><h3 class="list-group-item-heading"><?=Yii::t('backend', 'Indicaciones');?></h3></strong>
        					<p class="list-group-item-text">
        						<strong><?=Html::tag('p', Yii::t('backend', 'Puede realizar la consulta a traves de los siguientes metodos:'))  ?></strong>
        						<?= Html::tag('li', Yii::t('backend', 'Tipo de Recaudación - Rango de Fechas'));?>
        					</p>
        				</div>
        			</div>

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?=Yii::t('backend', 'Busqueda por Tipo Recaudación - Fechas de Pago') ?></strong></h4>
					</div>

<!-- Inicio Tipo de Recaudacion -->
					<div class="row" style="margin-top:15px;">
						<div class="col-sm-2" style="width: 28%;">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('tipo_recaudacion') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5" style="width: 50%;">
							<div class="row" class="tipo-recaudacion">
								 <?= $form->field($model, 'tipo_recaudacion')
								          ->dropDownList($listaRecaudacion, [
                                                            	'id'=> 'tipo-recaudacion',
                                                            	'prompt' => Yii::t('backend', 'Select'),
                                                            	'style' => 'width:200px;',
                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Tipo de Recaudacion -->

					<div class="row" style="padding-left: 15px;">
<!-- Inicio de Fecha Desde -->
						<div class="row">
							<div class="col-sm-2" style="width: 27%;">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('fecha_desde') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-2" style="width: 18%;">
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
							<div class="col-sm-2" style="width: 27%;">
								<div class="row">
									<p><strong><?= $model->getAttributeLabel('fecha_hasta') ?></strong></p>
								</div>
							</div>
							<div class="col-sm-2" style="width: 18%;">
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


<!-- Inicia de busqueda-->
					<div class="row">
						<div class="col-sm-3" style="width: 30%;float: right;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search'),
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
<!-- Fin de busqueda -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


