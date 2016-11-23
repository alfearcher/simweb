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

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="row">
	<div class="info-solicitud">
		<div class="row" style="padding-left: 0px; width: 50%;">
			<h4><?= Html::encode(Yii::t('frontend', 'Main Information')) ?></h4>
			<?= DetailView::widget([
					'model' => $model,
	    			'attributes' => [

	    				[
	    					'label' => Yii::t('frontend', 'Nro de Solicitud'),
	    					'value' => $model[0]['nro_solicitud'],
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
	    					'label' => Yii::t('frontend', 'Tipo de Licencia'),
	    					'value' => $model[0]['tipo'],
	    				],
	    				[
	    					'label' => Yii::t('frontend', 'Licencia'),
	    					'value' => $model[0]['licencia'],
	    				],
	    			],
				])
			?>
		</div>

		<div class="row" style="width:100%;padding-left: 15px;">
			<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-left: 5px;padding-top: 0px;">
				<h4><?=Html::encode(Yii::t('frontend', 'Rubros registrados'))?></h4>
			</div>
			<div class="row" id="id-rubro-registrado">
				<?= GridView::widget([
					'id' => 'id-grid-rubro-registrado',
					'dataProvider' => $dataProvider,
					'headerRowOptions' => [
						'class' => 'success',
					],
					'tableOptions' => [
						'class' => 'table table-hover',
						],
					'summary' => '',
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						[
		                    'class' => 'yii\grid\CheckboxColumn',
		                    'name' => 'chkRubro',
		                    'checkboxOptions' => function ($model, $key, $index, $column) {
		                    	return [
		                        	'value' => $model->id_rubro,
		                            'onClick' => 'javascript: return false;',
		                            'checked' => true,
		                        ];
		                    },
		                    'multiple' => false,
		                ],

		                [
		                    'contentOptions' => [
		                          'style' => 'font-size: 90%;',
		                    ],
		                    'label' => Yii::t('frontend', 'rubro'),
		                    'value' => function($data) {
		                               		return $data->rubro->rubro;
		        			           },
		                ],
		                [
		                    'contentOptions' => [
		                          'style' => 'font-size: 90%;',
		                    ],
		                    'label' => Yii::t('frontend', 'descripcion'),
		                    'value' => function($data) {
		                               		return $data->rubro->descripcion;
		        			           },
		                ],

		                [
		                    'contentOptions' => [
		                          'style' => 'font-size: 90%;',
		                    ],
		                    'label' => Yii::t('frontend', 'licor'),
		                    'value' => function($data) {
		                    				if ( $data->rubro->licores == 1 ) {
		                    					return 'SI';
		                    				} else {
		                    					return 'NO';
		                    				}

		        			           },
		                ],
		                [
		                    'contentOptions' => [
		                          'style' => 'font-size: 90%;',
		                    ],
		                    'label' => Yii::t('frontend', 'condicion'),
		                    'value' => function($data) {
		                               		return $data->estatusSolicitud->descripcion;
		        			           },
		                ],

		               /* [
		                    'contentOptions' => [
		                          'style' => 'font-size: 90%;text-align:center;width:10%;',
		                    ],
		                    'label' => Yii::t('frontend', 'fecha'),
		                    'value' => function($data) {
		                               		return date('d-m-Y', strtotime($data['fecha']));
		        			           },
		                ],*/

		        	]
				]);?>
			</div>
		</div>
	</div>
</div>
