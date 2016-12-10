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
 *  @file _deuda_detalle.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view _deuda_detalle
 *  @brief vista detalle de las planillas
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
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);
  $acumulado = [];

?>

<div class="deuda-detalle">
 	<?php
 		$form = ActiveForm::begin([
 			'id' => 'id-deuda-detalle',
 			//'method' => 'post',
 			//'action'=> $url,
 			//'enableClientValidation' => true,
 			// 'enableAjaxValidation' => false,
 			//'enableClientScript' => true,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?> -->

	<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
		<h4><?=Html::encode($caption)?></h4>
	</div>

	<div class="row" class="deuda" style="padding-top: 10px;">
		<?= GridView::widget([
			'id' => 'grid-deuda-detalle',
			'dataProvider' => $dataProvider,
			//'filterModel' => $model,
      'rowOptions' => function($data, $idSeleccionado) {
                        // if ( count($idSeleccionado) > 0 ) {
                        //   if ( in_array($data['id_detalle'], $idSeleccionado) ) {
                        //       return [
                        //         'class' => 'success',
                        //       ];
                        //   }
                        // }
                      },
			'summary' => '',
			'columns' => [

          // [
          //     'contentOptions' => [
          //          'style' => 'font-size: 90%;',
          //     ],
          //     'label' => Yii::t('frontend', 'nro.'),
          //     'value' => function($data) {
          //                   return $data['id_detalle'];
          //                },
          // ],
          [
              'contentOptions' => [
              	   'style' => 'font-size: 90%;',
          	  ],
              'label' => Yii::t('frontend', 'planilla'),
              'value' => function($data) {
    				                return $data['planilla'];
    			               },
          ],
          [
              'contentOptions' => [
              	   'style' => 'font-size: 90%;text-align:center;',
              ],
              'label' => Yii::t('frontend', 'año'),
              'value' => function($data) {
						                return $data['año'];
					               },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:center;',
              ],
              'label' => Yii::t('frontend', 'periodo'),
              'value' => function($data) {
					                 return $data['periodo'];
				                 },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:center;',
              ],
              'label' => Yii::t('frontend', 'unidad'),
              'value' => function($data) {
					                 return $data['unidad'];
					               },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'monto'),
              'value' => function($data) {
					                 return Yii::$app->formatter->asDecimal($data['monto'], 2);
					               },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'recargo'),
              'value' => function($data) {
                           return Yii::$app->formatter->asDecimal($data['recargo'], 2);
                         },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'interes'),
              'value' => function($data) {
                           return Yii::$app->formatter->asDecimal($data['interes'], 2);
                         },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'descuento'),
              'value' => function($data) {
                           return Yii::$app->formatter->asDecimal($data['descuento'], 2);
                         },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'recon./reten.'),
              'value' => function($data) {
                           return Yii::$app->formatter->asDecimal($data['monto_reconocimiento'], 2);
                         },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;text-align:right;',
              ],
              'label' => Yii::t('frontend', 'subtotal'),
              'value' => function($data) {
                            $st = ( $data['monto'] + $data['recargo'] + $data['interes'] ) - ( $data['descuento'] + $data['monto_reconocimiento'] );
                            return Yii::$app->formatter->asDecimal($st, 2);
                         },
          ],
          [
              'contentOptions' => [
                  'style' => 'font-size: 90%;',
              ],
              'label' => Yii::t('frontend', 'concepto'),
              'value' => function($data) {
					                 return $data['descripcion'];
					               },
          ],

          [
              'attribute' => 'aumulado',
              'value' => function($model) {
                            return 0;
              }
          ]

              //   [
              //   	'contentOptions' => [
              //       	'style' => 'font-size: 90%;text-align:right;',
              //   	],
              //       'class' => 'yii\grid\ActionColumn',
            		// 'header'=> Yii::t('frontend', 'Deuda'),
            		// 'template' => '{view}',
            		// 'buttons' => [
              //   		'view' => function ($url, $model, $key) {
              //   				$url =  Url::to(['buscar-deuda']);
              //      				return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. $model['deuda'] .'</center></div>',
              //       							[
              //       								'id' => 'id-deuda-por-periodo',
              //       								'value' => json_encode([
              //       												'view' => 2,
              //       												'i' => $model['impuesto'],
              //       												'idC' => $model['id_contribuyente'],
              //       												'tipo' => $model['tipo'],
              //       											]),
	             //            						'name' => 'id',
	             //            						'class' => 'btn btn-default',
	             //            						'title' => 'deuda '. $model['deuda'],
	             //            						//'data-url' => $url,
	             //            						'style' => 'text-align:right;',
				          //               		]
			           //              		);
              //   				},
              //   	],
              //   ],
        	]
		]);?>
	</div>

	<?php ActiveForm::end();?>

</div>