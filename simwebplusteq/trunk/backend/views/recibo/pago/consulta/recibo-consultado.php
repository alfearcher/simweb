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
 *  @file recibo-consultado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-07-2017
 *
 *  @view recibo-consultado.php
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
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	//use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;
 ?>


<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-top: 0px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Recibo de pago Nro. ') . $model->recibo)?></strong></h4>
</div>

<div class="row">
	<div class="row" style="padding-left: 15px; width: 100%;">
		<?= DetailView::widget([
				'model' => $model,
    			'attributes' => [

    				[
    					'label' => Yii::t('backend', 'Recibo'),
    					'value' => $model['recibo'],
    				],
    				// [
    				// 	'label' => $model->getAttributeLabel('dni'),
    				// 	'value' => $datosRecibido['dni'],
    				// ],
    				// [
    				// 	'label' => $model->getAttributeLabel('razon_social'),
    				// 	'value' => $datosRecibido['razon_social'],
    				// ],
    				// [
    				// 	'label' => $model->getAttributeLabel('domicilio_fiscal_v'),
    				// 	'value' => $model['domicilio_fiscal_v'],
    				// ],
    				// [
    				// 	'label' => $model->getAttributeLabel('domicilio_fiscal_new'),
    				// 	'value' => $model['domicilio_fiscal_new'],
    				// ],
    			],
			])
		?>
	</div>
</div>


