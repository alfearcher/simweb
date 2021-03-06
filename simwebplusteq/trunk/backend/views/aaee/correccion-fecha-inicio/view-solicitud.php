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
 *  @date 17-08-2016
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
?>

<div class="row">
	<div class="info-solicitud">
		<div class="row">
			<h3><?= Html::encode($caption) ?></h3>
				<?= DetailView::widget([
						'model' => $model,
		    			'attributes' => [
		    				'nro_solicitud',
		    				[
			                	'label' => Yii::t('frontend', 'Request Description'),
			                    'value' => $model->getDescripcionTipoSolicitud($model->nro_solicitud),
		                	],
		    				'id_contribuyente',
		    				[
		    					'format'=>['date', 'dd-MM-yyyy'],
		    					'attribute' => 'fecha_inicio_v',
		    				],
		    				[
		    					'format'=>['date', 'dd-MM-yyyy'],
		    					'attribute' => 'fecha_inicio_new',
		    				],
		    				'usuario',
		    				'fecha_hora',
		    				[
		    					'label' => Yii::t('frontend', 'Condition'),
		    					'value' => $model->estatusSolicitud->descripcion,
		    				],
		    				//'estatus',

		    			],
					])
				?>
		</div>
	</div>
</div>