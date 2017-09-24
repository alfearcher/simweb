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
 *  @file deposito-detalle-forma-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-08-2017
 *
 *  @view deposito-detalle-forma-pago.php
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


<div class="row" style="width:100%;padding:0px;">
    <div class="row" style="width:100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-left: 5px;">
    	<h4><?=Html::encode(Yii::t('backend', 'Formas de pago registradas'))?></h4>
    </div>

<!-- FORMA DE PAGO REGISTRADAS -->
    <div class="row" style="width: 100%;margin-top: 5px;">
    	<?= GridView::widget([
    		'id' => 'id-grid-forma-pago-registrada',
    		'dataProvider' => $dataProviderDetalle,
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
                               		return $data['formaPago']['descripcion'];
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
                          'style' => 'font-size: 100%;font-weight:bold;',
                    ],
                    'label' => Yii::t('frontend', 'Deposito'),
                    'format' => 'raw',
                    'value' => function($data) {
                    				if ( $data['id_forma'] == 2 ) {
                              return $data['deposito'];
                    					// return Html::a($data['deposito'], '#',
                    					// 				[
                    				 // 			   			'id' => 'link-detail',
                    				 // 			   			'data-toggle' => 'modal',
                    				 // 			   			'data-target' => '#modal',
                    				 // 			   			'data-url' => Url::to(['view-detalle-deposito',
                    				 // 			   				     				'linea' => $data['linea'],
                    				 // 			   			    	 				'recibo' => $data['recibo'],
                    				 // 			   			    	 				 'deposito' => $data['deposito']]),
                    				 // 			   			'data-pjax' => 0,
                    				 // 			   			//'class' => 'btn btn-default',
                    				 // 			   		]);
                    				} else {
                               			return $data['deposito'];
                    				}
        			           },
                ],

                [
                    'contentOptions' => [
                          'style' => 'font-size: 90%;',
                    ],
                    'label' => Yii::t('frontend', 'Cuenta'),
                    'value' => function($data) {
                    				      if ( $data['id_forma'] == 1 ) {
                                    return $data['cuenta'];
                         		      } elseif ( $data['id_forma'] == 4 ) {
                               			return $data['cuenta'];
                               		} else {
    									              return $data['cuenta'];
                               		}
        			           },
                ],

                [
                    'contentOptions' => [
                          'style' => 'font-size: 90%;',
                    ],
                    'label' => Yii::t('frontend', 'Nro/Cheque o Tipo/Tarjeta'),
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

        	]
    	]);?>
    </div>
<!-- FIN DE FORMA DE PAGO REGISTRADAS -->
</div>

