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
 *  @date 01-08-2016
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
	use yii\grid\GridView;
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
	        		<h3><?= Html::encode(Yii::t('frontend', 'Request Nro. ' . $model[0]->nro_solicitud )) ?></h3>
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
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!-- DATOS DE LA SUCURSAL -->
					<div class="row">
		        		<div class="panel panel-success" style="width: 100%;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Summary')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row" style="padding-left: 15px; width: 100%;">
									<?= GridView::widget([
										'id' => 'grid-contribuyente-asociado',
    									'dataProvider' => $dataProvider,
    									//'filterModel' => $model,
    									'columns' => [
    										//['class' => 'yii\grid\SerialColumn'],
    										[
							                    'label' => Yii::t('frontend', 'Request'),
							                    'value' => function($model) {
                        										return $model->nro_solicitud;
                											},
							                ],
							                [
							                    'label' => Yii::t('frontend', 'Request Description'),
							                    'value' => function($model) {
                        										return $model->getDescripcionTipoSolicitud($model->nro_solicitud);
                											},
							                ],
							            	[
							                    'label' => Yii::t('frontend', 'Id. Taxpayer'),
							                    'value' => function($model) {
                        										return $model->id_contribuyente;
                											},
							                ],
							                [
							                    'label' => Yii::t('frontend', 'Ant Capital'),
							                    'value' => function($model) {
                        										return $model->capital_v;
                											},
							                ],
							                [
							                    'label' => Yii::t('frontend', 'New Capital'),
							                    'value' => function($model) {
                        										return $model->capital_new;
                											},
							                ],
							                [
							                	//'attribute' => 'sucursal.razon_social',
							                   	'label' =>Yii::t('frontend', 'Branch Office'),
							                    'value' => function($model) {
                        										return $model->sucursal->razon_social;
                											},
							                ],
							                [
							                	//'attribute' => 'sucursal.id_sim',
							                    'label' => Yii::t('frontend', 'License'),
							                    'value' => function($model) {
                        										return $model->sucursal->id_sim;
                											},
							                ],
							                [
							                	//'attribute' => 'sucursal.id_sim',
							                    'label' => Yii::t('frontend', 'Condition'),
							                    'value' => function($model) {
                        										return $model->estatusSolicitud->descripcion;
                											},
							                ],

							        	]
									]);
									?>
								</div>
							</div>
						</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->
</div>	 <!-- Fin de inscripcion-sucursal-create -->
