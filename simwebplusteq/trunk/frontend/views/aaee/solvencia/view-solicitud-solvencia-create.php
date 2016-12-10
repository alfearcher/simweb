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
 *  @file view-solicitud-licencia-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view view-solicitud-licencia-create.php
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
	use yii\widgets\DetailView;
	use backend\models\solicitud\estatus\EstatusSolicitud;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);


 ?>


<div class="solicitud-solvencia-creada">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-view-solicitud-solvencia-creada',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row">
						<div class="row" style="padding-left: 0px; width: 70%;">
			        		<h4><?= Html::encode(Yii::t('frontend', $caption)) ?></h4>
							<?= DetailView::widget([
									'model' => $model,
					    			'attributes' => [

					    				[
					    					'label' => Yii::t('frontend', 'Nro de Solicitud'),
					    					'value' => $model[0]['nro_solicitud'],
					    				],
					    				[
					    					'label' => Yii::t('frontend', 'Tipo Solicitud'),
					    					'value' => $tipoSolicitud,
					    				],
					    				[
					    					'label' => Yii::t('frontend', 'Id. Contribuyente'),
					    					'value' => $model[0]['id_contribuyente'],
					    				],
					    				[
					    					'label' => Yii::t('frontend', 'Año'),
					    					'value' => $model[0]['ano_impositivo'],
					    				],
					    				[
					    					'label' => Yii::t('frontend', 'Ultimo pago al momento de realizar la solicitud'),
					    					'value' => $model[0]['ultimo_pago'],
					    				],
					    				[
					    					'label' => Yii::t('frontend', 'Observacion'),
					    					'value' => $model[0]['observacion'],
					    				],

					    				[
					    					'label' => Yii::t('frontend', 'Condicion'),
					    					'value' => EstatusSolicitud::findOne($model[0]['estatus'])['descripcion'],
					    				],
					    			],
								])
							?>
						</div>

					</div>


					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::a(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																					['quit'],
																				  	[
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'value' => 1,
																						'style' => 'width: 100%;',
																						'name' => 'btn-quit',

																				  	])
									?>
								</div>
							</div>

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


