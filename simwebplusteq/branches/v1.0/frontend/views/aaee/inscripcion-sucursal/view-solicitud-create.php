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
 *  @date 21-07-2016
 *
 *  @view view-solicitud-create.php
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

 <div class="view-inscripcion-sucursal-creada">
	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
	        	<div class="col-sm-4">
	        		<h3><?= Html::encode(Yii::t('frontend', 'Request Nro. ' . $model->nro_solicitud)) ?></h3>
	        	</div>
	        	<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
	        		<style type="text/css">
						.col-sm-3 > ul > li > a:hover {
							background-color: #337AB7;
						}
	    			</style>
	        		<?= MenuController::actionMenuSecundario($opciones); ?>
	        	</div>
        	</div>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!-- DATOS DE LA SUCURSAL -->
					<div class="row" style="width: 100%;">

						<div class="row" style="margin-left:18px;width: 95%;">
							<?=$viewSolicitudPlanilla?>
						</div>

		        		<div class="row" style="padding-left: 10px; width: 100%;">
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
					    					'label' => $model->getAttributeLabel('razon_social'),
					    					'value' => $model->razon_social,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('id_sim'),
					    					'value' => $model->id_sim,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('fecha_inicio'),
					    					'value' => $model->fecha_inicio,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('domicilio_fiscal'),
					    					'value' => $model->domicilio_fiscal,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('email'),
					    					'value' => $model->email,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('tlf_ofic'),
					    					'value' => $model->tlf_ofic,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('tlf_ofic_otro'),
					    					'value' => $model->tlf_ofic_otro,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('tlf_celular'),
					    					'value' => $model->tlf_celular,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('usuario'),
					    					'value' => $model->usuario,
					    				],
					    				// [
					    				// 	'label' => $model->getAttributeLabel('fecha_hora'),
					    				// 	'value' => $model->fecha_hora,
					    				// ],
					    				[
					    					'label' => $model->getAttributeLabel('orugen'),
					    					'value' => $model->origen,
					    				],
					    				[
					    					'label' => $model->getAttributeLabel('estatus'),
					    					'value' => $model['estatusInscripcion']->descripcion,
					    					//'value' => $modelSearch->getDescripcionEstatus($model->estatus),
					    				],

					    			],
								])
							?>
						</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->
</div>	 <!-- Fin de inscripcion-sucursal-create -->
