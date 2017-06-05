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
 *  @date 23-01-2017
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

 <div class="view-inscripcion-propaganda-creada">

    	<div class="row">
        	<div class="col-sm-4">
        		<h3><?= Html::encode($caption) ?></h3>
        	</div>
    	</div>
		<div class="row" style="padding-left: 0px; width: 100%;">
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
    					'label' => $model->getAttributeLabel('id_impuesto'),
    					'value' => $model->id_impuesto,
    				],
    				[
    					'label' => $model->getAttributeLabel('nombre_propaganda'),
    					'value' => $model->nombre_propaganda,
    				],
    				[
    					'label' => $model->getAttributeLabel('direccion'),
    					'value' => $model->direccion,
    				],
    				[
    					'label' => $model->getAttributeLabel('fecha_inicio'),
    					'value' => $model->fecha_inicio,
    				],
    				[
    					'label' => $model->getAttributeLabel('fecha_fin'),
    					'value' => $model->fecha_fin,
    				],
    				[
    					'label' => $model->getAttributeLabel('ano_impositivo'),
    					'value' => $model->ano_impositivo,
    				],
    				[
    					'label' => $model->getAttributeLabel('clase_propaganda'),
    					'value' => $model->clasePropaganda->descripcion,
    				],
    				[
    					'label' => $model->getAttributeLabel('uso_propaganda'),
    					'value' => $model->usoPropaganda->descripcion,
    				],
    				[
    					'label' => $model->getAttributeLabel('tipo_propaganda'),
    					'value' => $model->tipoPropaganda->descripcion,
    				],
    				[
    					'label' => $model->getAttributeLabel('cantidad_propagandas'),
    					'value' => $model->cantidad_propagandas,
    				],
    				[
    					'label' => $model->getAttributeLabel('alto'),
    					'value' => Yii::$app->formatter->asDecimal($model->alto, 2),
    				],
    				[
    					'label' => $model->getAttributeLabel('ancho'),
    					'value' => Yii::$app->formatter->asDecimal($model->ancho, 2),
    				],
    				[
    					'label' => $model->getAttributeLabel('profundidad'),
    					'value' => Yii::$app->formatter->asDecimal($model->profundidad, 2),
    				],
    				[
    					'label' => $model->getAttributeLabel('mts'),
    					'value' => Yii::$app->formatter->asDecimal($model->mts, 2),
    				],
    				[
    					'label' => $model->getAttributeLabel('costo'),
    					'value' => Yii::$app->formatter->asDecimal($model->costo, 2),
    				],
    				[
    					'label' => $model->getAttributeLabel('observacion'),
    					'value' => $model->observacion,
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

</div>	 <!-- Fin de inscripcion-sucursal-create -->
