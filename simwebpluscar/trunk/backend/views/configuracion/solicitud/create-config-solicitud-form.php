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
 	//use kartik\icons\Icon;
 	//use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;
?>

<div class="create-config-solicitud-form" style="margin-top: 0px;">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'config-solicitud-form',
 			'method' => 'post',
 			'action' => ['/configuracion-solicitud/create'],
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);

 		// Lista de los impuesto
 		$listaImpuesto = ArrayHelper::map($modelImpuesto, 'impuesto', 'descripcion');

 		// Lista de los codigos de area de telefonos nacionales.
 		//$listaCodigoNacionales = ArrayHelper::map($modelCodigo, 'codigo', 'codigo');

 		// Lista de los codigos de telefonos movil (celular).
 		//$listaCodigoCelular = ArrayHelper::map($modelCodigoCelular, 'codigo', 'codigo');
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading"><h4><?= Html::encode($this->title) ?></h4></div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">
<!-- Inicio Impuesto -->
			        <div class="row" style=" margin-top: 0px;">
			        	<div class="col-sm-2" style="margin-left: -15px;">
							<div class="row" style="width:100%;">
								<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('impuesto')) ?></i></p>
							</div>
							<div class="row">
								<div class="impuesto">
									<?= $form->field($model, 'impuesto')->dropDownList($listaImpuesto,[
				                																		'id' => 'impuesto',
				                																		'style' => 'width:110%;',
				                                                                     				 	'prompt' => Yii::t('backend', 'Select'),
				                                                                						 ])->label(false)
								   ?>
								</div>
							</div>
						</div>

					</div>	<!-- Fin de row -->
				</div>		<!-- Fin de col-sm-12 -->

				<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
					<div class="col-sm-3">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', 'Execute Create'),
																					  [
																						'id' => 'btn-create',
																						'class' => 'btn btn-success',
																						'value' => 1,
																						//'action' => ['/administradora/create'],
																						'name' => 'btn-create',
																						'style' => 'width: 100%;',
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