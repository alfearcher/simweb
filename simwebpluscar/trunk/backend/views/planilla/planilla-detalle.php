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
 *  @file view_solicitud_seleccionada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view view_solicitud_seleccionada.php
 *  @brief vista del formualario que se utilizara para mostrar los datos principales
 *  de la solicitud seleccionada y los datos basicos del contribuyente.
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

 	//session_start();		// Iniciando session

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use backend\controllers\menu\MenuController;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>
<div class="planilla-detalle">
	<?php
		$form = ActiveForm::begin([
			'id' => 'view-planilla-detalle',
		    'method' => 'post',
		    'action' => '#',
			//'enableClientValidation' => true,
			//'enableAjaxValidation' => true,
			//'enableClientScript' => true,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 90%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-10">
					<div class="row">
						<small><strong><?= Yii::t('backend', 'Planilla') ?></strong></small>
					</div>

					<div class="row">
						<div class="grid-detalle" id="grid-detalle">
							 <?= GridView::widget([
					         		'id' => 'grid-detalle-planilla',
					               	'dataProvider' => $dataProvider,
					               	'headerRowOptions' => ['class' => 'success'],
									'rowOptions' => function($data) {
											if ( $data['pago'] == 0 ) {
		    									return ['class' => 'danger'];
											} elseif ( $data['pago'] == 1 ) {
												return ['class' => 'success'];
											}
									},
					               'summary' => '',
					               'columns' => [
				                        ['class' => 'yii\grid\SerialColumn'],
				                        // [
				                        //     'label' => 'Planilla',
				                        //     'format'=>'raw',
				                        //     'value' => function($data) {
				                        //     	return $data['planilla'];
				                        //     },
				                        // ],
				                        [
				                            'label' => 'Año',
				                            'value' => function($data) {
				                            	return $data['ano_impositivo'];
				                            },
				                        ],
				                        [
				                            'label' => 'Periodo',
				                            'value' => function($data) {
				                            	return $data['trimestre'];
				                            },
				                        ],
				                        [
				                            'label' => 'Unidad',
				                            'value' => function($data) {
				                            	return $data['unidad'];
				                            },
				                        ],
				                        [
				                            'label' => 'Monto',
				                            'value' => function($data) {
				                            	return $data['monto'];
				                            },
				                        ],
				                        [
				                            'label' => 'Recargo',
				                            'value' => function($data) {
				                            	return $data['recargo'];
				                            },
				                        ],
				                        [
				                            'label' => 'Interes',
				                            'value' => function($data) {
				                            	return $data['interes'];
				                            },
				                        ],
				                        [
				                            'label' => 'Descuento',
				                            'value' => function($data) {
				                            	return $data['descuento'];
				                            },
				                        ],
				                        [
				                            'label' => 'Recon.',
				                            'value' => function($data) {
				                            	return $data['monto_reconocimiento'];
				                            },
				                        ],
				                         [
				                            'label' => 'Observacion',
				                            'value' => function($data) {
				                            	return $data['descripcion'];
				                            },
				                        ],
				                        [
				                            'label' => 'Pago',
				                            'format'=>'raw',
				                            // afecta solo a la celda
				                            'contentOptions' => [
				                            			'style' => 'display: block;color: red;'
				                            ],
				                            //
				                            'value' => function($data) {
				                            	if ( $data['pago'] == 0 ) {
				                            		return Html::tag('strong', 'NO', ['class' => 'danger']);
				                            	} elseif ( $data['pago'] == 1) {
				                            		return Html::tag('strong', 'SI', ['class' => 'success']);
				                            	}
				                            },
				                        ],
					               ]
					            ]);
					        ?>
						</div>
					</div>

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
</div>
