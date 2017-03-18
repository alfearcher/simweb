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
 	//use kartik\icons\Icon;
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


 ?>

<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;margin-top: 20px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Datos del Recibo'))?></strong></h4>
</div>

<div class="row" style="width: 100%;padding: 0px;margin-left:5px;">
	<div class="col-sm-2" style="width: 15%;padding: 0px;padding-top: 25px;margin-bottom: 20px;">
		<div class="row" style="width: 30%;padding: 0px;">
			<?=Html::label(Yii::t('backend', 'Recibo'), 'recibo-label',['style' => 'font-size:120%;'])?>
		</div>
		<div class="row" style="padding: 0px;">
			<?= Html::textInput('recibo',
								 $datosRecibo[0]['recibo'],
								 [
									'id' => 'id-recibo',
									'class' => 'form-control',
									'style' => 'width:100%;
												background-color:white;
												font-weight:bold;
												text-align:right;
												font-size:140%;;',
									'readOnly' => true,
								])
			?>
		</div>
	</div>

	<div class="col-sm-2" style="width: 25%;padding: 0px;padding-left: 15px;padding-top: 25px;margin-left: 10px;">
		<div class="row" style="width: 65%;padding: 0px;margin-left: 3px;">
			<?=Html::label(Yii::t('backend', 'Monto Recibo'), 'recibo-label',['style' => 'font-size:120%;'])?>
		</div>
		<div class="div" style="padding:0px;">
			<?= Html::textInput('montoRecibo',
							     Yii::$app->formatter->asDecimal($datosRecibo[0]['monto'], 2),
							     [
							     	'id' => 'id-monto-recibo',
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

	<div class="col-sm-2" style="width: 25%;padding: 0px;padding-left: 15px;padding-top: 25px;">
		<div class="row" style="width: 65%;padding: 0px;margin-left: 3px;">
			<?=Html::label(Yii::t('backend', 'Monto Faltante'), 'recibo-label',['style' => 'font-size:120%;'])?>
		</div>
		<div class="monto-sobrante" style="margin-left: 0px;">
			<?= Html::textInput('montoSobrante',
							     Yii::$app->formatter->asDecimal($montoSobrante, 2),
							     [
							     	'id' => 'id-monto-sobrante',
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

<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;margin-top: 20px;">
	<h4><strong><?=Html::encode(Yii::t('backend', 'Seleccione la(s) forma(s) de pago'))?></strong></h4>
</div>

<div class="row" style="width:100%;">
	<div class="col-sm-2" style="width: 70%;padding-top: 15px;padding-left: 80px;">
		<div class="btn-toolbar" role="toolbar">
			<div class="btn-group">
				<?php foreach ( $listaForma as $key => $value ):
					$icon = '';
					if ( $key == 1 ) {
						$icon = 'fa fa-cc';
					} elseif ( $key == 2 ) {
						$icon = 'fa fa-newspaper-o';
					} elseif ( $key == 3 ) {
						$icon = 'fa fa-money';
					} elseif ( $key == 4 ) {
						$icon = 'fa fa-credit-card';
					}

					echo Html::a('&nbsp;&nbsp;' . strtolower($value),
				   				['view-forma-pago', 'forma' => $key,'recibo' => $datosRecibo[0]['recibo']],
					    		[
					    	  		'class' => $icon . ' btn btn-default btn-lg',
					    	  		'id' => 'id-' . strtolower($value),
					    	  		'style' => 'width:140px;margin-left:10px;font-size:140%;',

					    	  	]);
					endforeach;
				?>
			</div>
		</div>
	</div>
</div>

<!-- FORMA DE PAGO REGISTRADAS -->
<div class="row" style="width: 100%;margin-top: 25px;">
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

            [
                'contentOptions' => [
                      'style' => 'font-size: 100%;
                      			  text-align:right;
                      			  font-weight:bold;',
                ],
                'label' => Yii::t('frontend', 'Editar'),
                'format' => 'raw',
                'value' => function($data, $key) {
                				return Html::a(Yii::t('backend', 'Editar'),
                				 			   Url::to(['update', 'linea' => $data['linea'], 'recibo' => $data['recibo']]),
                				 			   [
                				 			   		'class' => 'btn btn-primary',
                				 			   ]);
                }
            ],

    	]
	]);?>
</div>
<!-- FIN DE FORMA DE PAGO REGISTRADAS -->
