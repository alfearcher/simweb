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
 *  @file _resultado-operacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-05-2017
 *
 *  @view _resultado-operacion.php
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
	use yii\web\View;
	use yii\widgets\ActiveForm;
	use yii\grid\GridView;
	use common\mensaje\MensajeController;

 ?>

<div class="resultado-operacion">
	<?php
		$form = ActiveForm::begin([
			'id' => 'resultado-operacion-form',
			//'action' => $url,
		    //'method' => 'post',
			'enableClientValidation' => false,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
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

					 <div class="row" style="width:100%;">
					 	<div class="row" style="width:100%;">
							<?=MensajeController::actionMensaje($codigo); ?>
					 	</div>

						<div class="row" style="width:100%;margin-left:15px;">
							<?=$this->render('/ajuste/pago/cuenta-recaudadora/pago-actualizado', [
																	'model' => $model,
																	'caption' => $caption,
																	'dataProvider' => $dataProvider,
					    					]);
					    	?>
						</div>
					</div>

					<div class="row" style="width: 100%;">
						<div class="col-sm-3" style="width: 25%;">
							<?= Html::a(Yii::t('backend', 'Ir al principio'), Url::to(['index']),
																			  [
																				'class' => 'btn btn-primary',
																				'style' => 'width: 100%;',
																			  ])
							?>
						</div>

						<div class="col-sm-3" style="width: 25%;">
							<?= Html::a(Yii::t('backend', 'Quit'), Url::to(['quit']),
																  [
																	'class' => 'btn btn-danger',
																	'style' => 'width: 100%;',
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