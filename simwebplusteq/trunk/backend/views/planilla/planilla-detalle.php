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
 *  @file planilla-detalle.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view planilla-detalle.php
 *  @brief vista que muestra los detalle de la planilla, en lo referente a montos
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
	use common\models\totalizar\TotalizarGrid;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);
    $models = $dataProvider->getModels();
    $totalizar = 0;
 	foreach ( $models as $mod ) {
 		$totalizar += $mod['monto'] + $mod['recargo'] + $mod['interes'];
 	}

?>
<div class="planilla-detalle">
	<?php
		$form = ActiveForm::begin([
			'id' => 'view-planilla-detalle',
		    'method' => 'post',
		    'action' => '#',
			//'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			//'enableClientScript' => true,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>

        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12" style="100%;padding: 0px;margin: 0px;">
					<div class="row" style="100%;padding: 0px;margin: 0px;">
						<small><strong><?= Yii::t('backend', 'Planilla') ?></strong></small>
					</div>

					<div class="row" style="100%;padding: 0px;margin: 0px;">
						<div class="grid-detalle" id="grid-detalle">
							 <?= GridView::widget([
					         		'id' => 'grid-detalle-planilla',
					               	'dataProvider' => $dataProvider,
					               	'headerRowOptions' => ['class' => 'primary'],
					               	'showFooter' => true,
									'footerRowOptions' => [
										'style' => 'font-size:100%;font-weight: bold;text-align:right;',
										'class' => 'success',
									],
									'rowOptions' => function($data) {
											if ( $data['pago'] == 0 ) {
		    									return ['class' => 'default'];
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
				                            'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::getTotalizar($dataProvider, 'monto'), 2),
				                        ],
				                        [
				                            'label' => 'Recargo',
				                            'value' => function($data) {
				                            	return $data['recargo'];
				                            },
				                            'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::getTotalizar($dataProvider, 'recargo'), 2),
				                        ],
				                        [
				                            'label' => 'Interes',
				                            'value' => function($data) {
				                            	return $data['interes'];
				                            },
				                            'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::getTotalizar($dataProvider, 'interes'), 2),
				                        ],
				                        [
				                            'label' => 'Descuento',
				                            'value' => function($data) {
				                            	return $data['descuento'];
				                            },
				                            'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::getTotalizar($dataProvider, 'descuento'), 2),
				                        ],
				                        [
				                            'label' => 'Recon.',
				                            'value' => function($data) {
				                            	return $data['monto_reconocimiento'];
				                            },
				                            'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::getTotalizar($dataProvider, 'monto_reconocimiento'), 2),
				                        ],
				                         [
				                            'label' => 'Observacion',
				                            'value' => function($data) {
				                            	return $data['descripcion'];
				                            },
				                        ],
				                        [
				                            'label' => 'Condicion',
				                            'format' => 'raw',
				                            // afecta solo a la celda
				                            'contentOptions' => function($data) {
				                            		if ( $data['pago'] == 0 ) {
				                            			return ['style' => 'display: block;color: red;'];
				                            		} elseif ( $data['pago'] == 1 ) {
				                            			return ['style' => 'display: block;color: blue;'];
				                            		}
				                            },

				                            //$data['pago'] == 1 ? ['style' => 'display: block;color: black;'] : ['style' => 'display: block;color: red;'],
				                            //
				                            'value' => function($data) {
				                            	if ( $data['pago'] == 0 ) {
				                            		return Html::tag('strong', Html::tag('h3',
						                            								   	 $data['estatus'],
						                            								   	 ['class' => 'label label-danger']));
				                            	} elseif ( $data['pago'] == 1 ) {
				                            		return Html::tag('strong', Html::tag('h3',
				                            			                     			 $data['estatus'],
				                            			                     			 ['class' => 'label label-primary',
				                            								   			  'id' => 'pago',
				                            								   			  'name' => 'pago',
				                            								   			]));

				                            	} elseif ( $data['pago'] == 9 ) {
				                            		return Html::tag('strong', Html::tag('h3',
				                            			                     			 $data['estatus'],
				                            			                     			 ['class' => 'label label-warning',
				                            								   			  'id' => 'pago',
				                            								   			  'name' => 'pago',
				                            								   			]));
				                            	} else {
				                            		return Html::tag('strong', Html::tag('h3',
				                            			                     			 $data['estatus'],
				                            			                     			 ['class' => 'label label-warning',
				                            								   			  'id' => 'pago',
				                            								   			  'name' => 'pago',
				                            								   			]));
				                            	}
				                            },
				                        ],
				                        [
							                'label' => Yii::t('backend', 'Total'),
							                'contentOptions' => [
							                	'style' => 'font-size:90%;text-align:right;font-weight: bold;',
							                ],
							                'value' => function($model, $subTotal) {
							                				$subTotal = ( $model['monto'] + $model['recargo'] + $model['interes'] ) - ( $model['descuento'] + $model['monto_reconocimiento']);
															return Yii::$app->formatter->asDecimal($subTotal, 2);
														},
											'footer' => Yii::$app->formatter->asDecimal(TotalizarGrid::totalizarPlanilla($dataProvider), 2),
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
