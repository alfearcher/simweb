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
 *  @file resumen-individual-liquidacion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @view resumen-individual-liquidacion
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
	use backend\models\utilidad\exigibilidad\ExigibilidadSearch;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="view-liquidacion-vehiculo-resultante">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-liquidacion-vehiculo-resultante-form',
 			'method' => 'post',
 			'enableClientValidation' => false,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<div class="row" style="padding:0px;padding-left: 30px;padding-top: 20px;">
		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;width:100%;">
			<div class="col-sm-3" style="width: 100%;">
				<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
			</div>
		</div>
		<div class="row" id="id-grid-liquidacion-individual" style="width: 100%;">
			<?= GridView::widget([
							'id' => 'id-grid-detalle-liquidacion',
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
								// [
			     //                    'class' => 'yii\grid\CheckboxColumn',
			     //                     'name' => 'chkIdImpuesto',
			     //                    'checkboxOptions' => function ($model, $key, $index, $column) {
				    //                         	return [
				    //                                 'onClick' => 'javascript: return false;',
				    //                                 'checked' => true,

				    //                             ];
			     //                    },
			     //                    'multiple' => false,
				    //             ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;',
			                        ],
			                        'format' => 'raw',
			                        'label' => Yii::t('frontend', 'id-pago'),
			                        'value' => function($data) {
			                        				// return Html::textInput('id-pago[' . $data['id_impuesto'] . ']',
				                        			// 												$data['id_pago'],
				                        			// 												[
						                         //           												'readOnly' => true,
						                         //           												'class' => 'form-control',
						                         //           												'style' => 'width: 100%;
						                         //           														    background-color:white;',
						                         //           											]);
			                                   		return $data['id_pago'];
			            			           },
			            			'visible' => false,
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;',
			                        ],
			                        'label' => Yii::t('frontend', 'id-impuesto'),
			                        'value' => function($data) {
			                                   		return $data['id_impuesto'];
			            			           },
			            			'visible' => false,
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;',
			                        ],
			                        'format' => 'raw',
			                        'label' => Yii::t('frontend', 'impuesto'),
			                        'value' => function($data) {
			                        				// return Html::textInput('impuesto[' . $data['id_impuesto'] . ']',
				                        			// 												$data['impuesto'],
				                        			// 												[
						                         //           												'readOnly' => true,
						                         //           												'class' => 'form-control',
						                         //           												'type' => 'hidden',
						                         //           												'style' => 'width: 100%;
						                         //           														    background-color:white;',
						                         //           											]);
			                                   		return $data['impuesto'];
			            			           },
			            			'visible' => false,
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: center;',
			                        ],
			                        'format' => 'raw',
			                        'label' => Yii::t('frontend', 'Año'),
			                        'value' => function($data) {
			                        				// return Html::textInput('ano_impositivo[' . $data['id_impuesto'] . ']',
				                        			// 												$data['ano_impositivo'],
				                        			// 												[
						                         //           												'readOnly' => true,
						                         //           												'class' => 'form-control',
						                         //           												'style' => 'width: 100%;
						                         //           														    background-color:white;',
						                         //           											]);
			                                   		return $data['ano_impositivo'];
			            			           },
			            			//'visible' => false,
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: center;',
			                        ],
			                        'label' => Yii::t('frontend', 'Periodo'),
			                        'value' => function($data) {
			                                   		return $data['trimestre'];
			            			           },
			            			//'visible' => false,
			                    ],
			                    // [
			                    //     'contentOptions' => [
			                    //           'style' => 'font-size: 90%;',
			                    //     ],
			                    //     'label' => Yii::t('frontend', 'Tipo'),
			                    //     'value' => function($data) {
			                    //                		return $data['exigibilidad']['unidad'];
			            			     //       },
			                    // ],

			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: right;',
			                        ],
			                        'label' => Yii::t('frontend', 'Monto'),
			                        'value' => function($data) {
			                                   		return Yii::$app->formatter->asDecimal($data['monto'], 2);
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: right;',
			                        ],
			                        'label' => Yii::t('frontend', 'Recargo'),
			                        'value' => function($data) {
			                                   		return Yii::$app->formatter->asDecimal($data['recargo'], 2);
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: right;',
			                        ],
			                        'label' => Yii::t('frontend', 'Interes'),
			                        'value' => function($data) {
			                                   		return Yii::$app->formatter->asDecimal($data['interes'], 2);
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: right;',
			                        ],
			                        'label' => Yii::t('frontend', 'Descuento'),
			                        'value' => function($data) {
			                                   		return Yii::$app->formatter->asDecimal($data['descuento'], 2);
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: right;',
			                        ],
			                        'label' => Yii::t('frontend', 'Rec/Ret'),
			                        'value' => function($data) {
			                                   		return Yii::$app->formatter->asDecimal($data['monto_reconocimiento'], 2);
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: center;',
			                        ],
			                        'label' => Yii::t('frontend', 'Fecha Vcto'),
			                        'value' => function($data) {
			                                   		return $data['fecha_vcto'];
			            			           },
			                    ],
			                    [
			                        'contentOptions' => [
			                              'style' => 'font-size: 90%;text-align: center;',
			                        ],
			                        'label' => Yii::t('frontend', 'Observacion'),
			                        'value' => function($data) {
			                                   		return $data['descripcion'];
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
 	<?php ActiveForm::end(); ?>
</div>
