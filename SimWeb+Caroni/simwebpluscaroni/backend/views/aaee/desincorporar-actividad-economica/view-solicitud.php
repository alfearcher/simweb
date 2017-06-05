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
 *  @file view-solicitud-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-05-2017
 *
 *  @view view-solicitud-create, vista de la solicitude creada por desincorporacion
 *  como contribuyente de actividad economica.
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use yii\widgets\DetailView;
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

<div class="row" style="padding-top: 15px; width: 100%;">
	<?= DetailView::widget([
			'model' => $model,
			'attributes' => [

				[
					'label' => $model->getAttributeLabel('nro_solicitud'),
					'value' => $model->nro_solicitud,
				],
				[
                    'label' => Yii::t('frontend', 'Request Description'),
                    'value' => $model->getDescripcionTipoSolicitud($model->nro_solicitud),

                ],
				[
					'label' => $model->getAttributeLabel('id_contribuyente'),
					'value' => $model->id_contribuyente,
				],

				[
					'label' => Yii::t('frontend', 'Taxpayer'),
					'value' => $model->contribuyente->razon_social,
				],

				// [
				// 	'label' => $model->getAttributeLabel('domicilio_fiscal_v'),
				// 	'value' => $model->domicilio_fiscal_v,
				// ],
				// [
				// 	'label' => $model->getAttributeLabel('domicilio_fiscal_new'),
				// 	'value' => $model->domicilio_fiscal_new,
				// ],
				[
					'label' => $model->getAttributeLabel('usuario'),
					'value' => $model->usuario,
				],
				[
					'label' => $model->getAttributeLabel('fecha_hora'),
					'value' => $model->fecha_hora,
				],
				[
					'label' => $model->getAttributeLabel('origen'),
					'value' => $model->origen,
				],
				[
					'label' => $model->getAttributeLabel('estatus'),
					'value' => $model['estatusSolicitud']->descripcion,
					//'value' => $modelSearch->getDescripcionEstatus($model->estatus),
				],

			],
		])
	?>
</div>
