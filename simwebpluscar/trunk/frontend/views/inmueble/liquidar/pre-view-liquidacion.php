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
 *  @file pre-view-liquidacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view pre-view-liquidacion.php
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

 	use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="pre-view-liquidacion">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-liquidacion-form',
 			'method' => 'post',
 			'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?php foreach ( $models as $i => $model ): ?>
		<?php foreach ( $model as $j => $mod ): ?>

			<?=$form->field($mod, "[{$i}][{$j}]id_detalle")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]id_pago")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]id_impuesto")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]impuesto")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]ano_impositivo")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]trimestre")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]monto")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]descuento")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]recargo")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]interes")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]fecha_emision")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]fecha_vcto")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]pago")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]fecha_pago")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]referencia")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]descripcion")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]monto_reconocimiento")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]exigibilidad_pago")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]fecha_desde")->hiddenInput()->label(false);?>
			<?=$form->field($mod, "[{$i}][{$j}]fecha_hasta")->hiddenInput()->label(false);?>

		<?php endforeach; ?>
	<?php endforeach; ?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;padding-left:10px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>
						<div class="row" id="id-lista-inmueble" style="padding: 0px;">
							<?php
								foreach ( $gridHtml as $i => $grid ) {
									echo $grid;
								}

							 ?>
						</div>
					</div>

					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">
							<div class="col-sm-3" style="width: 25%;padding: 0px;padding-left: 15px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Guardar'),
																					  [
																						'id' => 'btn-confirm-save',
																						'class' => 'btn btn-success',
																						'value' => 7,
																						'style' => 'width: 100%;',
																						'name' => 'btn-confirm-save',

																					  ])
									?>
								</div>
							</div>


							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Back'),
																			  [
																				'id' => 'btn-back',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%;',
																				'name' => 'btn-back',

																			  ])
									?>
								</div>
							</div>




							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('frontend', 'Quit'),
																			  [
																				'id' => 'btn-quit',
																				'class' => 'btn btn-danger',
																				'value' => 1,
																				'style' => 'width: 100%;',
																				'name' => 'btn-quit',

																			  ])
									?>
								</div>
							</div>

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>
