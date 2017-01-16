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
 *  @file view-liquidacion-creada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-01-2017
 *
 *  @view view-liquidacion-creada
 *  @brief vista principal de la tasa creada
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;

?>

<div class="view-liquidacion-tasa-creada">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-view-liquidacion-tasa',
 			// 'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="padding-left: 15px; width: 100%;">
						<?= DetailView::widget([
								'model' => $model,
				    			'attributes' => [

				    				[
				    					'label' => Yii::t('backend', 'Planilla'),
				    					'format' => 'raw',
				    					'value' => Html::a($model[0]['pagos']['planilla'],
				    					                   ['generar-pdf', 'p' => $model[0]['pagos']['planilla']],
				    					                   [
				    					                   		'target' => '_blank',
				    					                   ]),
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Id. Contribuyente'),
				    					'value' => $model[0]['pagos']['id_contribuyente'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Año'),
				    					'value' => $model[0]['ano_impositivo'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Monto'),
				    					'value' => $model[0]['monto'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Impuesto'),
				    					'value' => $tasa['impuestos']['descripcion'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Cod. Presupuestario'),
				    					'value' => $tasa['codigoContable']['codigo'] . ' - ' . $tasa['codigoContable']['descripcion'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Grupo subnivel'),
				    					'value' => $tasa['grupoSubNivel']['descripcion'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Fecha Vcto'),
				    					'value' => $model[0]['fecha_vcto'],
				    				],
				    				[
				    					'label' => Yii::t('backend', 'Observacion'),
				    					'value' => $model[0]['descripcion'],
				    				],
				    			],
							])
						?>
					</div>

					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::a(Yii::t('backend', 'Liquidar Otra'), Url::to(['index']),
																					  [
																						'id' => 'btn-otra',
																						'class' => 'btn btn-primary',
																						'value' => 1,
																						'style' => 'width: 100%',
																						'name' => 'btn-otra',
																					  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Quit'),
																		  [
																			'id' => 'btn-quit',
																			'class' => 'btn btn-danger',
																			'value' => 1,
																			'style' => 'width: 100%',
																			'name' => 'btn-quit',
																		  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>