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
 *  @file solicitud-creada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-06-2017
 *
 *  @view solicitud-creada.php
 *  @brief vista.
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
	use yii\grid\GridView;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="solicitud-creada">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-solicitud-creada',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<!-- <?//=$form->field($model, 'nro_solicitud')->hiddenInput(['value' => 0])->label(false);?> -->

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?=Html::encode($caption)?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid" style="width: 100%;padding: 0px;">
				<div class="col-sm-12" style="width: 100%;padding: 0px;">

<!-- Vista Maestro de la solicitud -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;width: 100%;background-color:#F1F1F1;color:blue;padding-left: 10px;">
						<h4><strong><?=Yii::t('backend', 'Información maestra de la solicitud')?></strong></h4>
					</div>

					<div class="row" style="width: 100%;padding-top:10px;padding-left: 30px;">
						<?=$viewMaestro;?>
					</div>
<!-- Final Vista Maestro de la solicitud -->

<!-- Vista detalle de la solicitud -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;width: 100%;background-color:#F1F1F1;color:blue;padding-left: 10px;">
						<h4><strong><?=Yii::t('backend', 'Detalle de la solicitud')?></strong></h4>
					</div>
					<div class="row" style="width: 100%;padding-left: 30px;">
						<?=$viewDetalle;?>
					</div>
<!-- Final Vista detalle de la solicitud -->




				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>