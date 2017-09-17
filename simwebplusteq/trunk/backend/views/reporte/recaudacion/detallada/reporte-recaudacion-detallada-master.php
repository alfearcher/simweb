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
 *  @file reporte-recaudacion-detallada-master.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @view reporte-recaudacion-detallada-master.php
 *  @brief vista que muestra el resultado de la consulta de la recaudacion
 *  detallada de un codigo presupuestario de deuda morosa o deuda actual..
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
<div class="reporte-recaudacion-detallada-master">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-reporte-recaudacion-detallada-master',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<div class="row" style="width: 100%;padding-left:55px;">
		<div class="row" style="width:100%;text-align:center;">
			<h3><strong><?=Html::label(Yii::t('backend', 'REPORTE DE RECAUDACION DE INGRESOS MUNICIPALES'))?></strong></h4>
			<h4><strong><?=Html::label(Yii::t('backend', 'Desde: ') . $model['fecha_desde'] . ' - ' . Yii::t('backend', 'Hasta: ') . $model['fecha_hasta'])   ?></strong></h4>
		</div>

		<div class="row">
			<?php foreach ( $lapsos as $i => $lapso ): ?>
				<?php foreach ( $htmlRecaudacion[$lapso] as $html ): ?>
					<?php echo $html; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
		<div class="row">
			<?php echo $htmlTotalRecaudado; ?>
		</div>
	</div>
	<div class="row" style="width:100%;margin-top:45px;margin-bottom:45px;">
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


