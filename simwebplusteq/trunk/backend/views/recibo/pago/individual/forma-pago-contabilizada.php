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
 *  @file resumen-recibo-forma-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-03-2017
 *
 *  @view resumen-recibo-forma-pago.php
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
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

	$typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-forma-pago-contabilizada-form',
		//'method' => 'post',
		//'action' => ['registrar-formas-pago'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;margin-top: 20px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Formas de pago registradas'))?></strong></h4>
</div>

<!-- FORMA DE PAGO REGISTRADAS -->
<div class="row" style="width: 100%;margin-top: 5px;">
	<?= GridView::widget([
		'id' => 'id-grid-forma-pago-registrada',
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
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Forma de Pago'),
                'value' => function($data) {
                           		return $data['forma'];
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Fecha'),
                'value' => function($data) {
                           		return date('d-m-Y', strtotime($data['fecha']));
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Deposito'),
                'value' => function($data) {
                           		return $data['deposito'];
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Cuenta'),
                'value' => function($data) {
                				if ( $data['id_forma'] == 1 ) {
                           			return $data['codigo_cuenta'] . $data['cuenta'];

                           		} elseif ( $data['id_forma'] == 4 ) {
                           			return $data['codigo_cuenta'] . $data['cuenta'];

                           		} else {
									return $data['cuenta'];
                           		}
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 90%;',
                ],
                'label' => Yii::t('frontend', 'Nro de Cheque o Tarjeta'),
                'value' => function($data) {
                				if ( $data['id_forma'] == 1 ) {
                           			return $data['cheque'];
                           		} elseif ( $data['id_forma'] == 4 ) {
                           			return $data['cheque'];
                           		} else {
                           			return $data['cheque'];
                           		}
    			           },
            ],

            [
                'contentOptions' => [
                      'style' => 'font-size: 100%;
                      			  text-align:right;
                      			  font-weight:bold;',
                ],
                'label' => Yii::t('frontend', 'monto'),
                'value' => function($data) {
                				return Yii::$app->formatter->asDecimal($data['monto'], 2);
    			           },
            ],

            // [
            //     'contentOptions' => [
            //           'style' => 'font-size: 100%;
            //           			  text-align:right;
            //           			  font-weight:bold;',
            //     ],
            //     'label' => Yii::t('frontend', 'Editar'),
            //     'format' => 'raw',
            //     'value' => function($data, $key) {
            //     				return Html::a(Yii::t('backend', 'Editar'),
            //     				 			   Url::to(['update', 'linea' => $data['linea'], 'recibo' => $data['recibo']]),
            //     				 			   [
            //     				 			   		'class' => 'btn btn-primary',
            //     				 			   ]);
            //     }
            // ],

            [
            	'class' => 'yii\grid\ActionColumn',
            	'header' => 'Editar',
            	'template' => '{update}',
            	'buttons' => [
            		'update' => function($url, $data, $key) {
            			return Html::a('<center><span class="fa fa-pencil-square-o fa-lg"></center></span>',
            							['update', 'l' => $key],
            							[
            								'style' => 'font-size:140%;'
            							]);
            		}
            	],
            ],

            [
            	'class' => 'yii\grid\ActionColumn',
            	'header' => 'Suprimir',
            	'template' => '{delete}',
            	'buttons' => [
            		'delete' => function($url, $data, $key) {
            			return Html::a('<center><span class="fa fa-times fa-lg"></center></span>',
            							['suprimir-forma-pago', 'l' => $key],
            							[
            								'style' => 'font-size:140%;color:red;'
            							]);
            		}
            	],
            ],

    	]
	]);?>
</div>
<!-- FIN DE FORMA DE PAGO REGISTRADAS -->
<div class="row" style="padding: 0px;width: 100%;">
	<div class="col-sm-2" style="width: 25%;padding: 0px;padding-left: 15px;padding-top: 25px;float: right;">
		<div class="monto" style="margin-left: 0px;">
			<?= Html::textInput('montoAgregado',
							     Yii::$app->formatter->asDecimal($montoAgregado, 2),
							     [
							     	'id' => 'id-monto-agregado',
							     	'class' => 'form-control',
									'style' => 'width:100%;
									background-color:white;
									font-weight:bold;
									text-align:right;
									font-size:140%;',
									'readOnly' => true,
							     ])
			?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>