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
 *  @file total-recaudacion-detallada-por-lapso.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view total-recaudacion-detallada-por-lapso.php
 *  @brief vista que muestra el resultado de la totalizacion de los montos por lapsos
 * de la recaudacion detallada.
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
<div class="total-recaudacion-detallada-por-lapso">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-total-recaudacion-detallada-por-lapso',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);

		$descripcionLapso = ( $lapso == 1 ) ? Yii::t('backend', 'AÑO ACTUAL') : Yii::t('backend', 'AÑOS ANTERIORES');
	?>

	<div class="row" style="width: 100%;">
		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#C0C0C0;padding-top: 0px;">
			<div class="col-sm-2" style="width: 10%;font-size: 90%;margin-top:15px;">
				<strong><?=Html::encode($descripcionLapso)?></strong>
			</div>

<!-- Total del lapso por impuesto -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Impuesto')?></div>
				<div class="row"><?=Html::textInput('total-lapso-impuesto',
													 Yii::$app->formatter->asDecimal($totalLapso['monto'], 2),
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
<!-- Fin de Total del lapso por impuesto -->

<!-- Total del lapso por recargo -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Recargos')?></div>
				<div class="row"><?=Html::textInput('total-lapso-recargo',
													 Yii::$app->formatter->asDecimal($totalLapso['recargo'], 2),
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
<!-- Fin de Total del lapso por recargo -->


<!-- Total del lapso por interes -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Interes')?></div>
				<div class="row"><?=Html::textInput('total-lapso-interes',
													 Yii::$app->formatter->asDecimal($totalLapso['interes'], 2),
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
<!-- Fin de Total del lapso por interes -->

<!-- Total del lapso por descuento -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Descuentos')?></div>
				<div class="row"><?=Html::textInput('total-lapso-descuento',
													 Yii::$app->formatter->asDecimal($totalLapso['descuento'], 2),
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
<!-- Fin de Total del lapso por descuento -->

<!-- Total del lapso por monto reconocimiento -->
			<div class="col-sm-2" style="12%;">
				<div class="row"><?=Html::label('Recon/Ret')?></div>
				<div class="row"><?=Html::textInput('total-lapso-monto-reconocimiento',
													 Yii::$app->formatter->asDecimal($totalLapso['monto_reconocimiento'], 2),
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
<!-- Fin de Total del lapso por monto reconocimiento -->

<!-- Total del lapso por monto total impuesto - ( descuento + monto reconocimiento ) -->
			<div class="row" style="width:100%;margin-top: 10px;">
				<div class="col-sm-2" style="width:77%;text-align:right;margin-top: 5px;"><?=Html::label('Impuesto - ( Descuentos + Recon/Ret.)')?></div>
				<div class="col-sm-2" style="width:19%;"><?=Html::textInput('total-lapso-total',
													 				  Yii::$app->formatter->asDecimal($totalLapso['monto'] - ( $totalLapso['descuento'] + $totalLapso['monto_reconocimiento']), 2),
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
<!-- Fin de Total del lapso por monto total impuesto - ( descuento + monto reconocimiento ) -->
		</div>
	</div>


	<?php ActiveForm::end(); ?>
</div>


