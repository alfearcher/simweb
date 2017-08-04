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
 *  @file busqueda-recibo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view busqueda-recibo-form
 *  @brief vista principal para busqueda del recibo de pago.
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>

<div class="busqueda-recibo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-busqueda-recibo-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >
<!-- DATOS DEL RECIBO -->
					<div class="row" style="width:100%;padding:0px;padding-left: 10px;">
						<?=$htmlRecibo;?>
					</div>

<!-- FIN DE DATOS DEL RECIBO -->

					<div class="row" style="margin-top: 25px;">

						<?php if ( $bloquearFormaPago ) {
								$bloquear = true;
							} else {
								$bloquear = false;
							}
						?>
						<div class="col-sm-2" style="margin-left: 20px;width: 20%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Formas de Pago'),
																  [
																	'id' => 'btn-forma-pago',
																	'class' => 'btn btn-primary',
																	'value' => 2,
																	'style' => 'width: 100%',
																	'name' => 'btn-forma-pago',
																	'disabled' => $bloquear,
																  ])
								?>
							</div>
						</div>


						<div class="col-sm-2" style="margin-left: 5px;width: 15%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Back'),
																  [
																	'id' => 'btn-back',
																	'class' => 'btn btn-danger',
																	'value' => 1,
																	'style' => 'width: 100%',
																	'name' => 'btn-back',
																  ])
								?>
							</div>
						</div>

						<div class="col-sm-2" style="margin-left: 5px;width: 15%;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
																  [
																	'id' => 'btn-quit',
																	'class' => 'btn btn-danger',
																	'value' => 1,
																	'style' => 'width: 100%',
																	'name' => 'btn-quit',
																  ])
								?>
							</div>
						</div>

						<!-- <div class="col-sm-2" style="margin-left: 50px;">
							 <div class="form-group">
							 '../../common/docs/user/ayuda.pdf'  funciona
								<?//= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
													// 	'id' => 'btn-help',
													// 	'class' => 'btn btn-default',
													// 	'name' => 'btn-help',
													// 	'target' => '_blank',
													// 	'value' => 1,
													// 	'style' => 'width: 100%;'
													// ])?>

							</div>
						</div> -->
<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>