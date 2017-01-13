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
 *  @file view-detalle-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-07-2016
 *
 *  @view view-detalle-solicitud.php
 *  @brief vista que permite la salida.
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
	use backend\controllers\menu\MenuController;
	use yii\widgets\Pjax;

?>
<div class="row">
	<div class="detalle-solicitud">
		<?php
			$form = ActiveForm::begin([
				'id' => 'id-detalle-solicitud',
			]);
		?>

		<meta http-equiv="refresh">
	    <div class="panel panel-default"  style="width: 100%;">
	        <div class="panel-heading">
	        	<div class="row">
	        		<div class="col-sm-4" style="padding-top: 10px;">
	        			<h4><?= Html::encode($caption) ?></h4>
	        		</div>
	        	</div>
	        </div>
			<div class="panel-body">
				<div class="container-fluid">
					<div class="col-sm-12">

						<div class="row" style="border-bottom: 0.5px solid #ccc;">
							<h4><strong><?= Html::encode($caption) ?></strong></h4>
						</div>

						<div class="row">
							<div class="view-detalle-solicitud" style="width: 95%; padding-left: 20px;">
								<?= $viewDetalleSolicitud;?>
							</div>
							<div class="view-documento-consignado" style="width: 95%; padding-left: 20px;">
								<?= $viewDocumentoConsignado;?>
							</div>
							<div class="view-solicitud-planilla" style="width: 95%; padding-left: 20px;">
								<?= $viewSolicitudPlanilla;?>
							</div>
						</div>

					</div>
				</div>	<!-- Fin de container-fluid -->
			</div>		<!-- Fin de panel-body -->
		</div>			<!-- Fin de panel panel-default -->

		<?php ActiveForm::end(); ?>
	</div>
</div>