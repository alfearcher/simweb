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
 *  @file resumen-individual-liquidacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view resumen-individual-liquidacion
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
	use backend\models\utilidad\exigibilidad\ExigibilidadSearch;
	use common\mensaje\MensajeController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="view-resumen-general-liquidacion">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-resumen-general-liquidacion-form',
 			'method' => 'post',
 			'action' => $url,
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width: 100%;">
						<div class="col-5" style="padding: 0px;">
							<?= MensajeController::actionMensaje($codigo); ?>
						</div>

	 				</div>

					<div class="row" style="padding:0px;padding-top: 5px;">

						<div class="row" style="width: 100%;padding:0px;">
							<div class="container-items" style="padding-top: 10px;">
								<?php foreach ( $gridHtml as $grid ): ?>
									<?php echo $grid; ?>
								<?php endforeach; ?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
 	<?php ActiveForm::end(); ?>
</div>
