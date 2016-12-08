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
 *  @file solicitud-planilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-07-2016
 *
 *  @view solicitud-planilla.php
 *  @brief vista
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


	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use backend\controllers\menu\MenuController;
	use yii\widgets\Pjax;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>
<div class="solicitud-planilla-detalle">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-view-solicitud-planilla-detalle',
		    //'method' => 'post',
		    //'action' => '#',
			//'enableClientValidation' => true,
			//'enableAjaxValidation' => true,
			//'enableClientScript' => true,
		]);
	?>

    <div class="row">
		<div class="row">
			<small><strong><?= Yii::t('backend', 'Planilla') ?></strong></small>
		</div>

		<div class="row">
			<div class="grid-solicitud-planilla-detalle" id="grid-solicitud-planilla-detalle">
				 <?= GridView::widget([
		         		'id' => 'id-grid-solicitud-planilla',
		               	'dataProvider' => $dataProvider,
		               	'headerRowOptions' => ['class' => 'primary'],
						'rowOptions' => function($data) {
								if ( $data['pago'] == 0 ) {
									return ['class' => 'danger'];
								} elseif ( $data['pago'] == 1 ) {
									return ['class' => 'success'];
								}
						},
		               'summary' => '',
		               'columns' => [
		               		[
    							'class' => 'yii\grid\CheckboxColumn',
    							'name' => 'chk-planilla',
    							'checkboxOptions' => [
            							'id' => 'chk-planilla',
            							// Lo siguiente mantiene el checkbox tildado.
            							'onClick' => 'javascript: return false;',
            							'checked' => true,
            							//'disabled' => true, funciona.
            					],
            					'multiple' => false,

    						],
	                        ['class' => 'yii\grid\SerialColumn'],
	                        [
	                            'label' => 'Planilla',
	                            'format' => 'raw',
	                            'value' => function($data) {
	                            	return Html::a($data['planilla'], '#', [
															'id' => 'link-view-planilla',
												            //'class' => 'btn btn-success',
												            'data-toggle' => 'modal',
												            'data-target' => '#modal',
												            'data-url' => Url::to(['view-planilla', 'p' => $data['planilla']]),
												            'data-planilla' => $data['planilla'],
												            'data-pjax' => '0',
												        ]);
	                            	//return Html::a($data['planilla'], ['view-planilla', 'p' => $data['planilla']]);
	                            },
	                        ],
	                        [
	                            'label' => 'Impuesto',
	                            'value' => function($data) {
	                            	return $data['descripcion_impuesto'];
	                            },
	                        ],
	                        [
	                            'label' => 'Total',
	                            'value' => function($data) {
	                            	return ($data['sum_monto'] + $data['sum_recargo'] + $data['sum_interes']) - ($data['sum_descuento'] + $data['sum_monto_reconocimiento']);
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
	                            'contentOptions' => function($data) {
	                            		if ( $data['pago'] == 0 ) {
	                            			return ['style' => 'display: block;color: red;'];
	                            		} elseif ( $data['pago'] == 1 ) {
	                            			return ['style' => 'display: block;color: blue;'];
	                            		}
                        		},
	                            //
	                            'value' => function($data) {
	                            	if ( $data['pago'] == 0 ) {
	                            		return Html::tag('strong', Html::tag('h3',
	                            								   			$data['estatus'],
	                            								   			['class' => 'label label-danger',
	                            								   			 'id' => 'pago',
	                            								   			 'name' => 'pago',
	                            								   			]));
	                            	} elseif ( $data['pago'] == 1 ) {
	                            		return Html::tag('strong', Html::tag('h4',
	                            			                     			 $data['estatus'],
	                            			                     			 ['class' => 'label label-primary',
	                            								   			  'id' => 'pago',
	                            								   			  'name' => 'pago',
	                            								   			]));

	                            	} elseif ( $data['pago'] == 9 ) {
	                            		return Html::tag('strong', Html::tag('h4',
	                            			                     			 $data['estatus'],
	                            			                     			 ['class' => 'label label-warning',
	                            								   			  'id' => 'pago',
	                            								   			  'name' => 'pago',
	                            								   			]));
	                            	} else {
	                            		return Html::tag('strong', Html::tag('h4',
	                            			                     			 $data['estatus'],
	                            			                     			 ['class' => 'label label-warning',
	                            								   			  'id' => 'pago',
	                            								   			  'name' => 'pago',
	                            								   			]));
	                            	}
	                            },
	                        ],
		               ]
		            ]);
		        ?>
			</div>
		</div>
    </div>

	<?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs(
    '$(document).on("click", "#link-view-planilla", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".planilla").html(data);
                $("#modal").modal();
            }
        );
    }));
    '
); ?>

<style type="text/css">
	.modal-content	{
			margin-top: 150px;
			margin-left: -180px;
			width: 150%;
	}
</style>

<?php
Modal::begin([
    'id' => 'modal',
    //'header' => '<h4 class="modal-title">Complete</h4>',
    'size' => 'modal-lg',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Cerrar</a>',
]);

//echo "<div class='well'></div>";
Pjax::begin();
echo "<div class='planilla'></div>";
Pjax::end();
Modal::end();
?>