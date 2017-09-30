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
 *  @file registro-txt-recibo-resumen.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-09-2017
 *
 *  @view registro-txt-recibo-resumen.php
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
	use yii\bootstrap\Progress;


 ?>


<?php

	$form = ActiveForm::begin([
		'id' => 'id-registro-txt-recibo',
		//'method' => 'post',
		//'action' => '#',
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

<div class="row" style="width:100%;">
	<div class="row" style="width:100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;">
		<h4><?=Html::encode(Yii::t('backend', 'Registros txt (Resumen)'))?></h4>
	</div>

	<div class="row" style="width:100%;">
    	<?= GridView::widget([
    		'id' => 'id-grid-registro-txt-recibo',
    		'dataProvider' => $dataProviderRegistroTxtRecibo,
    		'headerRowOptions' => [
    			'class' => 'success',
    		],
    		'tableOptions' => [
    			'class' => 'table table-hover',
    			],
    		//'summary' => '',
    		'columns' => [
    			['class' => 'yii\grid\SerialColumn'],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'Id Registro'),
                    'value' => function($model, $key) {
                					return $model['id_registro_recibo'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'recibo'),
                    'value' => function($model) {
                					return $model['recibo'];
        			           },
                ],
                [
                    'contentOptions' => [
                    	'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto recibo'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_recibo'], 2);
        			           },
        			'visible' => true,
                ],

                [
                    'contentOptions' => [
                    	'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'fecha pago'),
                    'value' => function($model) {
                					return date('d-m-Y', strtotime($model['fecha_pago']));
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                    	'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'id contribuyente'),
                    'value' => function($model) {
                					return $model['id_contribuyente'];
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto efectivo'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_efectivo'], 2);
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto cheque'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_cheque'], 2);
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'cuenta cheque'),
                    'value' => function($model) {
                					return $model['cuenta_cheque'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'numero cheque'),
                    'value' => function($model) {
                					return $model['nro_cheque'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'fecha cheque'),
                    'value' => function($model) {
                					return date('d-m-Y', strtotime($model['fecha_cheque']));
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto TDD'),
                    'value' => function($model, $key) {
                					return Yii::$app->formatter->asDecimal($model['monto_debito'], 2);
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'numero TDD'),
                    'value' => function($model) {
                					return $model['nro_debito'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto TDC'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_credito'], 2);
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'numero TDC'),
                    'value' => function($model) {
                					return $model['nro_credito'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto transferencia'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_transferencia'], 2);
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'numero transaccion'),
                    'value' => function($model) {
                					return $model['nro_transaccion'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;text-align:right;',
                    ],
                    'label' => Yii::t('backend', 'monto total'),
                    'value' => function($model) {
                					return Yii::$app->formatter->asDecimal($model['monto_total'], 2);
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'cuenta recaudadora'),
                    'value' => function($model) {
                					return $model['nro_cuenta_recaudadora'];
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'planillas'),
                    'value' => function($model) {
                					return $model['planillas'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'estatus'),
                    'value' => function($model) {
                					return $model['estatus'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'fecha_hora'),
                    'value' => function($model) {
                					return $model['fecha_hora'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'usuario'),
                    'value' => function($model) {
                					return $model['usuario'];
        			           },
        			'visible' => false,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'archivo_txt'),
                    'value' => function($model) {
                					return $model['archivo_txt'];
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'nro_control'),
                    'value' => function($model) {
                					return $model['nro_control'];
        			           },
        			'visible' => true,
                ],
                [
                    'contentOptions' => [
                          'style' => 'font-size: 85%;',
                    ],
                    'label' => Yii::t('backend', 'observacion'),
                    'value' => function($model) {
                					return $model['observacion'];
        			           },
        			'visible' => false,
                ],
        	]
    	]);?>
    </div>
</div>