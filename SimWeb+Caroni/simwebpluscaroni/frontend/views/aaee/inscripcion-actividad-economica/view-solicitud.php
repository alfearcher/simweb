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
 *  @file view-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-05-2016
 *
 *  @view view-solicitud.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;
	use backend\controllers\menu\MenuController;
	use common\mensaje\MensajeController;
?>

<?php
		$form = ActiveForm::begin([
			'id' => 'view-solicitud-seleccionada-form',
		    'method' => 'post',
		    // 'action' => $url,
			'enableClientValidation' => false,
			'enableAjaxValidation' => false,
			'enableClientScript' => true,
		]);
	?>


<div class="row" style="width: 70%;">
	<div class="info-solicitud">
		<div class="row">
			<div class="alert">
				<?= MensajeController::actionMensaje($codigoMensaje);  ?>
			</div>
		</div>
		<div class="row" style="width: 100%;">
			<?=$viewSolicitudPlanilla?>
		</div>
		<div class="row" style="width: 100%;">
			<h3><?= Html::encode($caption) ?></h3>
				<?= DetailView::widget([
						'model' => $model,
		    			'attributes' => [
		    				'nro_solicitud',
		    				'id_contribuyente',
		    				'num_reg',
		    				'reg_mercantil',
		    				'tomo',
		    				'folio',
		    				'fecha',
		    				'capital',
		    				'num_empleados',
		    				[
		    					'label' => Yii::t('frontend', 'DNI Represent'),
		    					'value' => $model->naturaleza_rep . '-' . $model->cedula_rep,
		    				],
		    				'representante',
		    				'fecha_inicio',
		    				'origen',
		    				'fecha_hora',

		        			//'created_at:datetime', // creation date formatted as datetime
		    			],
					])
				?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>
</div>