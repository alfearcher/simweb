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
 *  @file resumen-recibo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @view resumen-recibo-form
 *  @brief vista principal que muestra el resumen del proceso de pago.
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

<div class="resumen-recibo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-resumen-recibo-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?> -->


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" style="width:100%;padding:0px;margin: 0px;">

		        	<div class="row" style="width:100%;padding:0px;margin: 0px;">
<!-- DATOS DEL RECIBO Y LAS PLANILLAS -->
						<div class="row" style="width: 100%;padding: 0px;margin: 0px;margin-left: 15px;">
							<?=$htmlRecibo?>
						</div>
<!-- FIN DE DATOS DEL RECIBO Y LAS PLANILLAS -->

<!-- FORMAS DE PAGO -->
						<div class="row" style="width: 100%;padding: 0px;margin: 0px;margin-left: 15px;">
							<?=$htmlFormaPago?>
						</div>
<!-- FIN DE FORMA DE PAGO -->

<!-- CUENTA RECAUDADORA-->
						<div class="row" style="width: 100%;padding: 0px;margin: 0px;margin-left: 15px;">
							<?=$htmlCuentaRecaudadora?>
						</div>

<!-- FIN DE CUENTA RECAUDADORA-->
					</div>

					<div class="row" style="margin-top: 20px;">
						<div class="col-sm-2" style="width: 30%;margin-left: 10px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'GUARDAR PAGO'),
																  [
																	'id' => 'btn-guardar-pago',
																	'class' => 'btn btn-success',
																	'value' => 9,
																	'style' => 'width: 100%;font-weight: bold;',
																	'name' => 'btn-guardar-pago',
																  ])
								?>
							</div>
						</div>

						<div class="col-sm-2" style="width: 20%;margin-left: 10px;">
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

						<div class="col-sm-2" style="width: 20%;margin-left: 10px;">
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
					</div>

				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>