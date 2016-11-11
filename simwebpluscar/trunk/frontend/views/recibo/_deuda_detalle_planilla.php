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
 *  @file _deuda_detalle_planilla.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view _deuda_detalle_planilla
 *  @brief vista detalle de la deuda segun planilla
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
	//use yii\widgets\DetailView;
  use yii\bootstrap\Modal;
	use yii\widgets\MaskedInput;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);
  $acumulado = [];

?>

<div class="deuda-detalle-planilla">
 	<?php
 		$form = ActiveForm::begin([
 			'id' => 'id-deuda-detalle-planilla',
 			//'method' => 'post',
 			//'action'=> $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?> -->

  <?php if ( $periodoMayorCero ) { ?>
      <div class="row">
          <div class="col-sm-2">
              <div class="row">
                  <strong>Nro. Planilla Inicial:</strong>
              </div>
              <div class="row">
                  <?= Html::input('text', 'planillaInicial', $primeraPlanilla,
                                                [
                                                  'id' => 'id-pi',
                                                  'class' => 'form-control',
                                                  'style' => 'width: 90%;font-size:110%;',
                                                  'readOnly' => true,
                                                ])
                  ?>
              </div>
          </div>

          <div class="col-sm-2">
              <div class="row">
                  <strong>Nro. Planilla Final:</strong>
              </div>
              <div class="row">
                  <?= Html::input('text', 'planillaFinal', '',
                                                [
                                                  'id' => 'id-pf',
                                                  'class' => 'form-control',
                                                  'style' => 'width: 90%;font-size:110%;',
                                                  'readOnly' => true,
                                                ])
                  ?>
              </div>
          </div>

      </div>
  <?php } ?>

	<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
      <div class="col-sm-3" style="margin-left:-10px;">
		      <h4><?=Html::encode($caption)?></h4>
      </div>
	</div>

	<div class="row" class="deuda-planilla" style="padding-top: 10px;">
      <div class="row" id="grid" style="padding-left: 10px;">
    		<?= GridView::widget([
              'id' => 'grid-deuda-detalle-planilla',
              'dataProvider' => $dataProvider,
              'summary' => '',
              'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chkSeleccionDeuda',
                      // 'checkboxOptions' => function ($model, $key, $index, $column) {
                      //                         // $key, identificador de la tabla, pagos-detalle.
                      //                         // $index, autonumerico que comienza en 0, es como
                      //                         // el indice en un array.

                      //                       //   if ( $model['planilla'] == 3905357 ) {
                      //                       //   die(var_dump($column));
                      //                       // }
                      //                      // if ( in_array($model['id_detalle'], []) ) {
                                           // if ( $model['planilla'] == 1 ) {

                                           //        return [
                                           //            'id' => 'id-chkSeleccionDeuda',
                                           //            'onClick' => 'javascript: return false;',
                                           //            'checked' => true,
                                           //        ];
                                           //  }
                      // },

                        'checkboxOptions' => function ($model, $key, $index, $column) {
                                              return [
                                                //'onClick' => 'alert("hola " + $(this).val());',
                                                //'onClick' => '$( "#suma-seleccion" ).text(' . $model['t'] . ');',

                                                  'id' => 'id-chkSeleccionDeuda',

                                                  'onClick' => 'if ( $(this).is(":checked") ) {
                                                                    var suma = parseFloat($( "#id-suma" ).val());
                                                                    var item = parseFloat(' . $model['t'] . ');
                                                                    var total = suma + item;
                                                                    $( "#id-suma" ).val(total);
                                                                } else {
                                                                    var suma = parseFloat($( "#id-suma" ).val());
                                                                    var item = parseFloat(' . $model['t'] . ');
                                                                    if ( suma > 0 ) {
                                                                        var total = suma - item;
                                                                        $( "#id-suma" ).val(total);
                                                                    }
                                                                }',
                                              ];
                        },
                        'multiple' => true,
                        'visible' => ( $periodoMayorCero ) ? false : true,
                    ],

                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align: center;display: block;',
                        ],
                        'class' => 'yii\grid\ActionColumn',
                        'header'=> Yii::t('frontend', 'Planilla'),
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                      return Html::button('<div class="item-list" style="color: #000000;"><center>' . $model['planilla'] .'</center></div>',
                                                              [
                                                                  'id' => 'id-planilla',
                                                                  'name' => 'planilla',
                                                                  'class' => 'btn btn-default',
                                                                  'title' => 'planilla '. $model['planilla'],
                                                                  'onClick' => '$("#id-pf").val("' . $model['planilla'] . '")',

                                                              ]
                                                    );
                                      },
                        ],
                        'visible' => ( $periodoMayorCero ) ? true : false,
                    ],

                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;',
                        ],
                        'label' => Yii::t('frontend', 'planilla'),
                        'value' => function($data) {
                                      return $data['planilla'];
            			                 },
                        'visible' => ( $periodoMayorCero ) ? false : true,
                    ],

                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'monto'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tmonto'], 2);
        					                 },
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recargo'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['trecargo'], 2);
                                  },
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'interes'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tinteres'], 2);
                                 },
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'descuento'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tdescuento'], 2);
                                  },
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recon./reten.'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tmonto_reconocimiento'], 2);
                                   },
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'total'),
                        'value' => function($data) {
                                        $st = ( $data['tmonto'] + $data['trecargo'] + $data['tinteres'] ) - ( $data['tdescuento'] + $data['tmonto_reconocimiento'] );
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
                        'attribute' => 'acumulado',
                        'value' => function($model, $key, $index, $column) {
                                      $c = New $column();
                                      return $c->getDataCellValue($model, 'tmonto', 0);
                                  },
                    ],
              ]
    		]);?>
      </div>
	</div>

	<?php ActiveForm::end();?>

</div>


