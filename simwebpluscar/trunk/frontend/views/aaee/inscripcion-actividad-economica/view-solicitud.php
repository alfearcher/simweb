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
	use backend\controllers\menu\MenuController;
?>

<div class="row">
	<div class="info-solicitud">
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

	    				//'id_administradora',
	        			// [
	        			// 	'label' => Yii::t('frontend', 'Nro. Request'),
	        			// 	'value' => $model->nro_solicitud,
	        			// ],
	        			// [
	           //  			'label' => Yii::t('backend', $model->getAttributeLabel('cod_tlf_oficina_1') . '-' . $model->getAttributeLabel('tlf_oficina_1')  ),
	           //  			'value' => $model->cod_tlf_oficina_1 . '-' . $model->tlf_oficina_1,
	        			// ],
	        			// [
	           //  			'label' => Yii::t('backend', $model->getAttributeLabel('cod_tlf_oficina_2') . '-' . $model->getAttributeLabel('tlf_oficina_2')  ),
	           //  			'value' => $model->cod_tlf_oficina_2 . '-' . $model->tlf_oficina_2,
	        			// ],
	        			// [
	           //  			'label' => Yii::t('backend', $model->getAttributeLabel('cod_celular_1') . '-' . $model->getAttributeLabel('tlf_celular_1')  ),
	           //  			'value' => $model->cod_celular_1 . '-' . $model->tlf_celular_1,
	        			// ],
	        			//'created_at:datetime', // creation date formatted as datetime
	    			],
				])
			?>
	</div>
</div>