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
 *  @file reporte-recaudacion-general-maestro.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-09-2017
 *
 *  @view reporte-recaudacion-general-maestro.php
 *  @brief vista que muestra el resumen general del reporte.
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
	use yii\grid\GridView;


?>
<div class="reporte-recaudacion-general-maestro">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-reporte-recaudacion-general',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => true,
		]);
	?>

	<div class="row" style="width: 100%;">
		<?php foreach ( $htmlSubTotalNivel as $i => $view ): ?>
			<?=$view; ?>
		<?php endforeach; ?>
	</div>
	<div class="row" style="width: 100%;">
		<div class="col-sm-2" style="width: 45%;">
			<h4><strong><?=Html::label(Yii::t('backend', 'TOTAL DEPOSITOS'))?></strong></h4>
		</div>
		<div class="col-sm-2" style="width: 30%;">
			<?=Html::textInput('total-deposito',
								Yii::$app->formatter->asDecimal($totalDeposito, 2),
								[
									'class' => 'form-control',
									'style' => 'width:100%;
												font-size:140%;
												font-weight: bold;
												text-align:right;'
								])
			?>
		</div>
	</div>
	<div class="row" style="width: 100%;">
		<div class="col-sm-2" style="width: 45%;">
			<h4><strong><?=Html::label(Yii::t('backend', 'MENOS: Notas de Debitos(Cheques Devueltos)'))?></strong></h4>
		</div>
		<div class="col-sm-2" style="width: 30%;">
			<?=Html::textInput('total-nota-debito',
								Yii::$app->formatter->asDecimal((-1)*$totalNotaDebito, 2),
								[
									'class' => 'form-control',
									'style' => 'width:100%;
												font-size:140%;
												font-weight: bold;
												text-align:right;'
								])
			?>
		</div>
	</div>
	<div class="row" style="width: 100%;">
		<div class="col-sm-2" style="width: 45%;">
			<h4><strong><?=Html::label(Yii::t('backend', 'TOTAL GENERAL'))?></strong></h4>
		</div>
		<div class="col-sm-2" style="width: 30%;">
			<?=Html::textInput('total-general',
								Yii::$app->formatter->asDecimal($totalGeneral, 2),
								[
									'class' => 'form-control',
									'style' => 'width:100%;
												font-size:140%;
												font-weight: bold;
												text-align:right;'
								])
			?>
		</div>
	</div>
	<div class="row" style="width: 100%;">
		<div class="col-sm-2" style="width: 45%;">
			<h4><strong><?=Html::label(Yii::t('backend', 'CHEQUE RECUPERADO AÑO ACTUAL'))?></strong></h4>
		</div>
		<div class="col-sm-2" style="width: 30%;">
			<?=Html::textInput('total-cheque-recuperado-ano-actual',
								Yii::$app->formatter->asDecimal($cheque['año-actual'], 2),
								[
									'class' => 'form-control',
									'style' => 'width:100%;
												font-size:140%;
												font-weight: bold;
												text-align:right;'
								])
			?>
		</div>
	</div>
	<div class="row" style="width: 100%;">
		<div class="col-sm-2" style="width: 45%;">
			<h4><strong><?=Html::label(Yii::t('backend', 'CHEQUE RECUPERADO AÑO ABTERIORES'))?></strong></h4>
		</div>
		<div class="col-sm-2" style="width: 30%;">
			<?=Html::textInput('total-cheque-recuperado-ano-anterior',
								Yii::$app->formatter->asDecimal($cheque['año-anterior'], 2),
								[
									'class' => 'form-control',
									'style' => 'width:100%;
												font-size:140%;
												font-weight: bold;
												text-align:right;'
								])
			?>
		</div>
	</div>
	<div class="row" style="width:100%;margin-top: 45px;">
		<div class="col-sm-3" style="width: 25%;">
			<?= Html::submitButton(Yii::t('backend', 'Back'),
												  [
													'id' => 'btn-back',
													'class' => 'btn btn-danger',
													'value' => 1,
													'name' => 'btn-back',
													'style' => 'width: 100%;',
												  ])
			?>
		</div>
		<div class="col-sm-3" style="width: 25%;">
			<?= Html::submitButton(Yii::t('backend', 'Quit'),
												  [
													'id' => 'btn-quit',
													'class' => 'btn btn-danger',
													'value' => 1,
													'name' => 'btn-quit',
													'style' => 'width: 100%;',
												  ])
			?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>
</div>


