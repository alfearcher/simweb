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
 *  @file busqueda-pago-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-01-2017
 *
 *  @view busqueda-pago-form.php
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
<div class="busqueda-pago">
	<?php
		$form = ActiveForm::begin([
			'id' => 'busqueda-pago-form',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>


	<!-- <?//=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
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

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Buscar por Recibo')?></strong></h4>
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin: 0px;">

<!-- INICIO DE RECIBO -->
						<div class="row" style="padding: 0px;margin: 0px;padding-top: 15px;">
							<div class="col-sm-2" style="width: 15%;">
								<p><strong><?= $model->getAttributeLabel('recibo') ?></strong></p>
							</div>
							<div class="col-sm-2" style="width: 25%;">
								<?= $form->field($model, 'recibo')->textInput([
																		'id' => 'id-recibo',
																		'style' => 'width:100%;',
														 			])->label(false)
								?>
							</div>
						</div>
<!-- FIN DE RECIBO -->

<!-- INICIO DE RANGO DE FECHAS -->
						<div class="row" style="border-bottom: 0.5px solid #ccc;">
							<h4><strong><?= Yii::t('backend', 'Buscar por Rango de Fecha')?></strong></h4>
						</div>

						<div class="row" style="padding: 0px;margin: 0px;padding-top: 15px;">
	<!-- Inicio de Fecha Desde -->
							<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
								<div class="col-sm-2" style="width: 15%;">
									<p><strong><?= $model->getAttributeLabel('fecha_desde') ?></strong></p>
								</div>
								<div class="col-sm-2" style="width: 16%;">
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

	<!-- Inicio de Fecha Hasta -->
							<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
								<div class="col-sm-2" style="width: 15%;">
									<p><strong><?= $model->getAttributeLabel('fecha_hasta') ?></strong></p>
								</div>
								<div class="col-sm-2" style="width: 16%;">
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
	<!-- Fin de Fecha Hasta -->
						</div>
<!-- FIN DE RANGO DE FECHAS -->

<!-- INICIO BANCO -->
						<div class="row" style="width: 100%;padding: 0px;margin: 0px;">
							<div class="col-sm-2" style="width: 17%;">
								<p><strong><?=$model->getAttributeLabel('codigo_banco')?></strong></p>
							</div>
							<div class="col-sm-2" style="width:60%;padding:0px;margin: 0px;">
								<?= $form->field($model, 'codigo_banco')
							              ->dropDownList($listaBanco, [
                                                             'id'=> 'id-codigo-banco',
                                                             'prompt' => Yii::t('backend', 'Select'),
                                                             'style' => 'width:100%;',
                                                             'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                   		           ->createUrl('/ajuste/pago/cuentarecaudadora/ajuste-cuenta-recaudadora/listar-cuenta-recaudadora') . '&id=' . '" + $(this).val(),
                                                                                   		           			 function( data ) {
                                                                                   		           			 	$( "select#id-cuenta-deposito" ).html( "" );
                                                                                                             	$( "select#id-cuenta-deposito" ).html( data );
                                                                                                       		}
                                                                                    );'
                                        ])->label(false);
                            ?>
							</div>
						</div>
<!-- FIN DE BANCO -->

<!-- LISTA DE CUENTA RECAUDADORAS -->
						<div class="row" style="width:100%;padding:0px;margin:0px;">
							<div class="col-sm-2" style="width: 17%;">
								<p><strong><?=$model->getAttributeLabel('cuenta_deposito')?></strong></p>
							</div>
							<div class="col-sm-3" style="width:60%;padding:0px;margin-left:0px;">
								 <?= $form->field($model, 'cuenta_deposito')
								          ->dropDownList([], [
	                                                'id'=> 'id-cuenta-deposito',
	                                                'prompt' => Yii::t('backend', 'Select'),
	                                                'style' => 'width:100%;',
	                                           	])->label(false);
	                            ?>
							</div>
						</div>
<!-- FIN DE LISTA DE CUENTA RECAUDADORAS -->

					</div>

					<div class="row" style="width:100%;padding:0px;margin:0px;padding-top: 10px;">
						<div class="col-sm-2" style="width: 100%;">
							<?= $form->field($model, 'cuenta_deposito_faltante')->checkBox([
																			'id' => 'cuenta_deposito_faltante',
																			'label' => 'No contienen Cuenta Recaudadora',
																		])->label(false) ?>
						</div>
					</div>


					<div class="row">
						<div class="col-sm-3" style="float: right;">
							<div class="form-group">
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
<!-- Fin de Rango Fecha -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


