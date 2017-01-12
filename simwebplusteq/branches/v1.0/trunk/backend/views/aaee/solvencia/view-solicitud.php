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
 *  @date 21-11-2016
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


<div class="row">
	<div class="info-solicitud">
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

		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
			<h4><?=Html::encode(Yii::t('backend', 'Ultimo lapso pagado'))?></h4>
		</div>

		<div class="row" style="margin-bottom: 10px;border-bottom: 1px solid #ccc;padding-bottom: 20px;">
			<div class="col-sm-2" style="padding: 0px;width: 6%;padding-left: 18px;">
				<div class="row" style="padding: 0px;">
					<h5><strong><?=Html::encode(Yii::t('backend', 'Año'))?></strong></h5>
				</div>
				<div class="row" style="padding: 0px;">
					<?=Html::textInput('ano_impositivo', isset($lapso[0]) ? trim($lapso[0]) : '',
														[
															'class' => 'form-control',
															'readOnly' => true,
															'style' => 'width:100%;background-color: white;'
														]);
					?>
				</div>
			</div>

			<div class="col-sm-2" style="padding: 0px;width: 5%;padding-left: 35px;">
				<div class="row" style="padding: 0px;">
					<h5><strong><?=Html::encode(Yii::t('backend', 'Periodo'))?></strong></h5>
				</div>
				<div class="row" style="padding: 0px;">
					<?=Html::textInput('periodo', isset($lapso[1]) ? trim($lapso[1]) : '',
														[
															'class' => 'form-control',
															'readOnly' => true,
															'style' => 'width:100%;background-color: white;'
														]);
					?>
				</div>
			</div>

			<div class="col-sm-2" style="padding: 0px;width: 20%;padding-left: 42px;">
				<div class="row" style="padding: 0px;">
					<h5><strong><?=Html::encode(Yii::t('backend', 'Descripcion'))?></strong></h5>
				</div>
				<div class="row" style="padding: 0px;">
					<?=Html::textInput('exigibilidad', isset($lapso[2]) ? trim($lapso[2]) : '',
														[
															'class' => 'form-control',
															'readOnly' => true,
															'style' => 'width:100%;background-color: white;'
														]);
					?>
				</div>
			</div>

			<div class="col-sm-2" style="padding: 0px;width: 20%;padding-left: 60px;">
				<div class="row" style="padding: 0px;">
					<h5><strong><?=Html::encode(Yii::t('backend', 'Condicion'))?></strong></h5>
				</div>
				<div class="row" style="padding: 0px;">
					<?=Html::textInput('solvente', $solvente,
														[
															'id' => 'id-solvente',
															'class' => 'form-control',
															'readOnly' => true,
															'style' => 'width:100%;background-color: white;'
														]);
					?>
				</div>
			</div>
		</div>


	</div>
</div>
