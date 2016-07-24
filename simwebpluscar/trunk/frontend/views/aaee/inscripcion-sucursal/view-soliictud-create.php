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
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\widgets\DetailView;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="view-inscripcion-sucursal-creada">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-inscripcion-sucursal-creada',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode(Yii::t('frontend', 'Inscription of Branch Office')) ?></h3>
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
									<?= DetailView::widget([
											'model' => $model,
							    			'attributes' => [

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
							    					'value' => $modelSearch->getDescripcionEstatus($model->estatus),
							    				],

							    			],
										])
									?>
								</div>
							</div>
						</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-sucursal-create -->
