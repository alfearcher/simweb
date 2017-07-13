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
 *  @file historico-solicitud-consulta-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-06-2017
 *
 *  @view historico-solicitud-consulta-form.php
 *  @brief vista que se utilizara para consultar el historico de las solicitudes.
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
<div class="historico-solicitud-consulta">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-historico-solicitud-consulta-form',
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
        		<div class="col-sm-4" style="padding-top: 10px;width: 40%;">
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
        						<?= Html::tag('li', Yii::t('backend', 'Impuesto - Tipo Solicitud - Rango de Fechas'));?>
        						<?= Html::tag('li', Yii::t('backend', 'Impuesto - Rango de Fechas'));?>
        						<?= Html::tag('li', Yii::t('backend', 'Numero de Solicitud')); ?>
        						<?php if ( $esFuncionario ) { ?>
        						<?= Html::tag('li', Yii::t('backend', 'Identiicador (ID) del Contribuyente'));?>
        						<?php } ?>
        						<?= Html::tag('p', Yii::t('backend', ''));?>
        						<?= Html::tag('p', Yii::t('backend', 'Puede complementar la consulta con los Parametros adicionales'));?>
        					</p>
        				</div>
        			</div>

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?=Yii::t('backend', 'Busqueda por Impuesto - Tipo Solicitud - Fechas Creacion') ?></strong></h4>
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
                                                                  'onchange' => '$.post( "' . $urlRequest . '&i=' . '" + $(this).val(), function( data ) {
                                                                                                                 $( "select#tipo-solicitud" ).html( data );
                                                                                                           });'
                                                                  // 'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                  //                      		           ->createUrl('reporte/solicitud/historico/historico-solicitud/lista-solicitud') . '&i=' . '" + $(this).val(), function( data ) {
                                                                  //                                                $( "select#tipo-solicitud" ).html( data );
                                                                  //                                          });'
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

					<div class="row" style="padding-left: 15px;">
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
						<h4><strong><?=Yii::t('backend', 'Busqueda por Nro. de Solicitud') ?></strong></h4>
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


<!-- Inicio de Id Contribuyente -->
					<?php if ( $esFuncionario ) { ?>
						<div class="row" style="border-bottom: 0.5px solid #ccc;">
							<h4><strong><?=Yii::t('backend', 'Busqueda por Contribuyente') ?></strong></h4>
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
					<?php } ?>
<!-- Fin de Id Contribuyente -->

					<div class="row" style="border-bottom: 0.5px solid #ccc;color:blue;">
						<h4><strong><?=Yii::t('backend', 'Parametros adicionales') ?></strong></h4>
					</div>

<!-- Inicio Estatus de Solicitud -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel('estatus') ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="estatus-solicitud">
								 <?= $form->field($model, 'estatus')
								          ->dropDownList($listaEstatus, [
	                                                            	'id'=> 'estatus-solicitud',
	                                                            	'prompt' => Yii::t('backend', 'Select'),
	                                                            	'style' => 'width:260px;',
	                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Estatus de Solicitud -->


<!-- Inicia de busqueda de todos los funcionarios -->
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
<!-- Fin de busqueda de todos los funcionarios -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


