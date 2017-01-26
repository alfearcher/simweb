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
 *  @file view-propaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-01-2017
 *
 *  @view view-propaganda.php
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

 <div class="view-propaganda-creada">
	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-4">
	        		<h3><?= Html::encode(Yii::t('frontend', 'Propaganda Nro. ' . $model->id_impuesto )) ?></h3>
	        	</div>
        	</div>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width: 100%;">
						<div class="row" style="padding-left: 0px; width: 100%;">
							<?= DetailView::widget([
									'model' => $model,
					    			'attributes' => [

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
					    					'value' => $model->clase->descripcion,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('uso_propaganda'),
					    					'value' => $model->uso->descripcion,
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


					    			],
								])
							?>
						</div>

						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1; padding-left: 5px;margin-top:20px;">
						</div>

					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->
</div>	 <!-- Fin de inscripcion-sucursal-create -->
