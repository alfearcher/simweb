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
 *  @file total-resumen-general.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view total-resumen-general.php
 *  @brief vista que muestra el resumen general de la recaudacion.
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
	use yii\grid\GridView;


?>
<div class="total-resumen-general">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-total-resumen-general',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<div class="row" style="width: 100%;">
		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#EAEFFE;padding-top: 0px;">
			<div class="col-sm-2" style="width: 10%;font-size: 90%;margin-top:15px;">
				<strong><?=Yii::t('backend', 'Total Deposito + Chq. Recuperados')?></strong>
			</div>

<!-- Total por impuesto -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Impuesto')?></div>
				<div class="row"><?=Html::textInput('total-recaudado-impuesto',
													 Yii::$app->formatter->asDecimal($totalRecaudado['monto'], 2),
													 [
													 	'class' => 'form-control',
													 	'readOnly' => true,
													 	'style' => 'text-align:right;
													 				font-weight: bold;
													 				font-size:90%;
													 				background-color:white;',

													 ])
								?>
				</div>
			</div>
<!-- Fin de Total por impuesto -->

<!-- Total por recargo -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Recargos')?></div>
				<div class="row"><?=Html::textInput('total-recaudado-recargo',
													 Yii::$app->formatter->asDecimal($totalRecaudado['recargo'], 2),
													 [
													 	'class' => 'form-control',
													 	'readOnly' => true,
													 	'style' => 'text-align:right;
													 				font-weight: bold;
													 				font-size:90%;
													 				background-color:white;',

													 ])
								?>
				</div>
			</div>
<!-- Fin de Total por recargo -->


<!-- Total por interes -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Interes')?></div>
				<div class="row"><?=Html::textInput('total-recaudado-interes',
													 Yii::$app->formatter->asDecimal($totalRecaudado['interes'], 2),
													 [
													 	'class' => 'form-control',
													 	'readOnly' => true,
													 	'style' => 'text-align:right;
													 				font-weight: bold;
													 				font-size:90%;
													 				background-color:white;',

													 ])
								?>
				</div>
			</div>
<!-- Fin de Total por interes -->

<!-- Total por descuento -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Descuentos')?></div>
				<div class="row"><?=Html::textInput('total-recaudado-descuento',
													 Yii::$app->formatter->asDecimal($totalRecaudado['descuento'], 2),
													 [
													 	'class' => 'form-control',
													 	'readOnly' => true,
													 	'style' => 'text-align:right;
													 				font-weight: bold;
													 				font-size:90%;
													 				background-color:white;',

													 ])
								?>
				</div>
			</div>
<!-- Fin de Total por descuento -->

<!-- Total por monto reconocimiento -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Recon/Ret')?></div>
				<div class="row"><?=Html::textInput('total-recaudado-monto-reconocimiento',
													 Yii::$app->formatter->asDecimal($totalRecaudado['monto_reconocimiento'], 2),
													 [
													 	'class' => 'form-control',
													 	'readOnly' => true,
													 	'style' => 'text-align:right;
													 				font-weight: bold;
													 				font-size:90%;
													 				background-color:white;',

													 ])
								?>
				</div>
			</div>
<!-- Fin de Total por monto reconocimiento -->

<!-- Total por monto total impuesto - ( descuento + monto reconocimiento ) -->
			<div class="row" style="width:100%;margin-top: 10px;">
				<div class="col-sm-2" style="width:66%;text-align:right;margin-top: 5px;"><?=Html::label('Impuesto - ( Descuentos + Recon/Ret.)')?></div>
				<div class="col-sm-2" style="width:19%;"><?=Html::textInput('total-recaudado-total',
													 				  Yii::$app->formatter->asDecimal($totalRecaudado['monto'] - ( $totalRecaudado['descuento'] + $totalRecaudado['monto_reconocimiento']), 2),
																	 [
																	 	'class' => 'form-control',
																	 	'readOnly' => true,
																	 	'style' => 'text-align:right;
																	 				font-weight: bold;
																	 				font-size:90%;
																	 				background-color:white;',

																	 ])
								?>
				</div>
			</div>
<!-- Fin de Total por monto total impuesto - ( descuento + monto reconocimiento ) -->
		</div>
	</div>
<!-- ///// -->

	<?php ActiveForm::end(); ?>
</div>


