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
 *  @file resumen-pago-efectuado-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @view resumen-pago-efectuado-form
 *  @brief vista principal
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
	use common\mensaje\MensajeController;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>

<div class="resumen-pago-efectuado-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-resumen-pago-efectuado-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" style="width:100%;padding:0px;margin: 0px;">

		        	<div class="row" style="width:100%;padding:0px;margin: 0px;">
						<div class="row" style="width: 100%;padding-left: 20px;">
							<?= MensajeController::actionMensaje($codigo); ?>
	 					</div>

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
								<?= Html::submitButton(Yii::t('backend', 'Pagar Otro'),
																  [
																	'id' => 'btn-pagar-otro',
																	'class' => 'btn btn-primary',
																	'value' => 9,
																	'style' => 'width: 100%;font-weight: bold;',
																	'name' => 'btn-pagar-otro',
																  ])
								?>
							</div>
						</div>

						<div class="col-sm-2" style="margin-left: 5px;width: 25%;">
							<div class="form-group">
								<?php
									$disabled = '';
									$target = '_blank';

									if ( $desactivarBotonRafaga ) {
										$disabled = ' disabled';
										$target = '';
									}
								 ?>

								<?= Html::a(Yii::t('backend', 'Imprimir Rafaga del Recibo'),
															[
																'mostrar-form-rafaga-print',
															   	'recibo' => ( $modelRecibo->recibo > 0 ) ? $modelRecibo->recibo : '#',
															   	'nro' => $modelRecibo->nro_control,
												   				'id_contribuyente' => $modelRecibo->id_contribuyente,
															],
															[
																'id' => 'btn-rafaga-print',
																'class' => 'btn btn-primary' . $disabled,
																'value' => 2,
																'style' => 'width: 100%',
																'name' => 'btn-rafaga-print',
																'target' => $target,
															])
								?>

								<!-- <?//= Html::submitButton(Yii::t('backend', 'Imprimir Rafaga del Recibo'),
																 //  [
																	// 'id' => 'btn-rafaga-print',
																	// 'class' => 'btn btn-primary',
																	// 'value' => 2,
																	// 'style' => 'width: 100%',
																	// 'name' => 'btn-rafaga-print',
																	// 'disabled' => $desactivarBotonRafaga,
																 //  ])
								?> -->
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