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
 *  @file identificar-banco-fecha.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-08-2017
 *
 *  @view identificar-banco-fecha.php
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
	use  yii\widgets\DetailView;


 ?>

<?php if ( $detailView ) { ?>
	<?=DetailView::widget([
    	'model' => $model,
	    'attributes' => [
	    	[
	            'label' => Yii::t('backend', 'Archivo'),
	            'value' => $archivo,
	        ],
	        [
	            'label' => Yii::t('backend', 'Banco'),
	            'value' => $banco->nombre,
	        ],
	        [
	            'label' => Yii::t('backend', 'Fecha'),
	            'value' => date('d-m-Y', strtotime($model->fecha_pago)),
	        ],
	    ],
	]);  ?>
<?php } else { ?>
	<div class="row" style="width: 100%;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Archivo: ') . $archivo)?></strong></h4>
		<h4><strong><?=Html::encode(Yii::t('backend', 'Banco: ') . $banco->nombre)?></strong></h4>
		<h4><strong><?=Html::encode(Yii::t('backend', 'Fecha: ') . $model->fecha_pago)?></strong></h4>
	</div>
<?php } ?>