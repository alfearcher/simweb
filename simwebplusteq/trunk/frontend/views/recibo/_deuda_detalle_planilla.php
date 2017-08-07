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
  use yii\bootstrap\Modal;
	use yii\widgets\MaskedInput;


	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

?>

<div class="deuda-detalle-planilla">
 	<?php
 		$form = ActiveForm::begin([
        'id' => 'id-deuda-detalle-planilla',
 			  'method' => 'post',
        'action'=> Url::to(['index-create']),
        'enableClientValidation' => false,
 			  'enableAjaxValidation' => false,
 			  'enableClientScript' => true,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?> -->

  <?=Html::hiddenInput('id_contribuyente', $idContribuyente) ?>

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

	<div class="row" style="padding-left: 0px;margin-top: 25px;width: 105%;">
      <div class="col-sm-3" style="margin-left:0px;width: 50%;font-family: verdana;font-size:60%;border-bottom: 1px solid #ccc;background-color: #F1F1F1;">
		     <strong><h4><?=Html::encode($caption)?></h4></strong>
      </div>

       <div class="col-sm-3" style="margin-left: 10px; width: 45%;font-family: verdana;font-size:60%;border-bottom: 1px solid #ccc;background-color: #F1F1F1;">
         <strong><h4><?=Html::encode('Planilla(s) Bloqueada(s)')?></h4></strong>
      </div>
	</div>

	<div class="row" class="deuda-planilla" style="padding-top: 10px;">
      <div class="col-sm-3" id="grid" style="padding-left: 0px;width: 52%;">

    		<?= GridView::widget([
              'id' => 'grid-deuda-detalle-planilla',
              'dataProvider' => $dataProvider,
              'headerRowOptions' => ['class' => 'success'],
              'rowOptions' => function($model) {
                    if ( $model['bloquear'] == 1 ) {
                        return [ 'class' => 'hidden'];
                    }
              },
              'tableOptions' => [
                    'class' => 'table table-hover',
              ],
              'summary' => '',
              'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chkSeleccionDeuda',

                        'checkboxOptions' => function ($model, $key, $index, $column) {

                              if ( $model['bloquear'] == 1 ) {
                                   return [
                                      'readOnly' => true,
                                      'disabled' => 'disabled',
                                      'onClick' => 'javascript: return false;',
                                      'checked' => false,
                                  ];
                              } else {
                                  return [
                                      'readOnly' => true,
                                      'onClick' => 'javascript: return false;',
                                      'checked' => true,
                                  ];
                              }


                        },
                        'multiple' => false,
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
                        //'visible' => ( $periodoMayorCero ) ? false : true,
                    ],

                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'monto'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tmonto'], 2);
        					                 },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recargo'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['trecargo'], 2);
                                  },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'interes'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tinteres'], 2);
                                 },
                       'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'descuento'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tdescuento'], 2);
                                  },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recon./reten.'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tmonto_reconocimiento'], 2);
                                   },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'sub-total'),
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
                        'visible' => ( $periodoMayorCero ) ? false : true,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;;text-align:center;',
                        ],
                        'label' => Yii::t('frontend', 'bloqueado'),
                        'value' => function($data) {
                                      if ( $data['bloquear'] == 1 ) {
                                          return 'SI';
                                      } else {
                                          return 'NO';
                                      }
                                 },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;;text-align:center;',
                        ],
                        'label' => Yii::t('frontend', 'causa'),
                        'value' => function($data) {
                                     return $data['causaBloquear'];
                                 },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'class' => 'yii\grid\ActionColumn',
                        'header'=> Yii::t('frontend', 'Total'),
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                  if ( $model['bloquear'] == 1 ) {

                                      return Html::button('<div class="item-list" style="color: #000000;"><center>' . Yii::$app->formatter->asDecimal($model['acumulado'], 2) .'</center></div>',
                                                              [
                                                                  'id' => 'id-acumulado' . $model['planilla'],
                                                                  'name' => 'acumulado[' . $model['planilla'] . ']',
                                                                  'class' => 'btn btn-default',
                                                                  'title' => 'total '. $model['acumulado'],
                                                                  'disabled' => 'disabled',
                                                              ]
                                                    );

                                  } else {

                                      return Html::button('<div class="item-list" style="color: #000000;"><center>' . Yii::$app->formatter->asDecimal($model['acumulado'], 2) .'</center></div>',
                                                              [
                                                                  'id' => 'id-acumulado' . $model['planilla'],
                                                                  'name' => 'acumulado[' . $model['planilla'] . ']',
                                                                  'class' => 'btn btn-default',
                                                                  'title' => 'total '. $model['acumulado'],
                                                                  'onClick' => '$("#id-pf").val("' . $model['planilla'] . '");
                                                                                $("#id-suma").val("' . Yii::$app->formatter->asDecimal($model['acumulado'], 2) . '");

                                                                                var montoAcumulado = $("#id-suma").val();
                                                                                var montoAcumulado1 = montoAcumulado.split(".").join("");
                                                                                var montoAcumulado = montoAcumulado1.replace(".", "");
                                                                                var acumulado = montoAcumulado.replace(",", ".");

                                                                                var n = acumulado;
                                                                                var total = $( "#id-total" ).val();
                                                                                var total1 = total.split(".").join("");
                                                                                var total = total1.replace(".", "");
                                                                                var t = total.replace(",", ".");

                                                                                var s = parseFloat(n) + parseFloat(t);
                                                                                if ( n <= 0 ) {
                                                                                    $("#btn-add-seleccion").attr("disabled", true);
                                                                                } else {
                                                                                    $( "#btn-add-seleccion" ).removeAttr("disabled");
                                                                                }

                                                                                var subTotal = s.toFixed(2);

                                                                                $( "#id-sub-total" ).val(s.toFixed(2));
                                                                                //var subTotal = $( "#id-sub-total" ).val();
                                                                                //var suma = subTotal.split(",").join("");
                                                                                //var suma1 = suma.replace("");
                                                                                ',

                                                              ]
                                                    );
                                  }
                            },

                        ],
                        'visible' => ( $periodoMayorCero ) ? true : false,
                    ],
              ]
    		]);?>
      </div>

<!-- planillas bloqueadas -->
      <div class="col-sm-3" id="grid-bloqueada" style="padding-left: 0px;width: 47%;">

        <?= GridView::widget([
              'id' => 'grid-deuda-detalle-planilla-bloqueda',
              'dataProvider' => $dataProvider,
              'headerRowOptions' => ['class' => 'danger'],
              'rowOptions' => function($model) {
                    if ( $model['bloquear'] == 0 ) {
                        return [ 'class' => 'hidden', ];
                    }
              },
              'summary' => '',
              'columns' => [

                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;',
                        ],
                        'label' => Yii::t('frontend', 'planilla'),
                        'value' => function($data) {
                                      return $data['planilla'];
                                   },
                        //'visible' => ( $periodoMayorCero ) ? false : true,
                    ],

                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'monto'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tmonto'], 2);
                                   },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recargo'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['trecargo'], 2);
                                  },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'interes'),
                        'value' => function($data) {
                                      return Yii::$app->formatter->asDecimal($data['tinteres'], 2);
                                 },
                       'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                              'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'descuento'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tdescuento'], 2);
                                  },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'recon./reten.'),
                        'value' => function($data) {
                                        return Yii::$app->formatter->asDecimal($data['tmonto_reconocimiento'], 2);
                                   },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;text-align:right;',
                        ],
                        'label' => Yii::t('frontend', 'sub-total'),
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
                        'visible' => ( $periodoMayorCero ) ? false : true,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;;text-align:center;',
                        ],
                        'label' => Yii::t('frontend', 'bloqueado'),
                        'value' => function($data) {
                                      if ( $data['bloquear'] == 1 ) {
                                          return 'SI';
                                      } else {
                                          return 'NO';
                                      }
                                 },
                        'visible' => false,
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'font-size: 90%;',
                        ],
                        'label' => Yii::t('frontend', 'causa'),
                        'value' => function($data) {
                                     return $data['causaBloquear'];
                                 },
                        'visible' => true,
                    ],

                ]
          ]);?>
      </div>

	</div>
<!-- Final de planillas bloqueadas -->


  <div class="row" style="padding-bottom: 10px;padding-top: 10px;width: 80%;">

      <div class="col-sm-3" style="width: 20%;text-align: left;margin-top:0px;background-color: #F1F1F1;padding-top:25px;">
          <div class="row" style="padding-left: 10px;">
              <h4><strong><p>Suma Seleccion:</p></strong></h4>
          </div>

      </div>

    <div class="col-sm-3" id="suma-seleccion" style="width:30%;text-align: right;background-color: #F1F1F1;">
      <h3><strong><p><?= MaskedInput::widget([
                              'name' => 'suma',
                              'id' => 'id-suma',
                              'options' => [
                                  'class' => 'form-control',
                                  'style' => 'width:100%;text-align: right;font-size:90%;background-color:#FFFFFF;',
                                  'readonly' => true,
                                  'placeholder' => '0,00',

                              ],
                                  'clientOptions' => [
                                      'alias' =>  'decimal',
                                      'digits' => 2,
                                      'digitsOptional' => false,
                                      'groupSeparator' => '.',
                                      'removeMaskOnSubmit' => true,
                                      // 'allowMinus'=>false,
                                      //'groupSize' => 3,
                                      'radixPoint'=> ",",
                                      'autoGroup' => true,
                                      'decimalSeparator' => ',',
                                ],

                        ]);?></p></strong></h3>
    </div>


    <div class="col-sm-4" style="width: 30%;padding-top: 15px;float: left;">
        <?= Html::submitButton(Yii::t('backend', 'Agregar Monto Seleccionado'),
                                                    [
                                                        'id' => 'btn-add-seleccion',
                                                        'class' => 'btn btn-warning',
                                                        'value' => 3,
                                                        'disabled' => 'disabled',
                                                        'style' => 'width: 100%',
                                                        'name' => 'btn-add-seleccion',
                                                    ])
        ?>
    </div>
  </div>

  <div class="row" >
      <div class="row" style="width: 39%;background-color: #F1F1F1;padding-left:0px;margin-left:0px;">
          <div class="col-sm-3" style="width: 40%;">
              <h6><strong><p>+ Total Seleccionado:</p></strong></h6>
          </div>
          <div class="col-sm-3" id="id-sub-totales" style="width: 60%;">
              <h3><strong><p><?= MaskedInput::widget([
                              'name' => 'subtotal',
                              'id' => 'id-sub-total',
                              'options' => [
                                  'class' => 'form-control',
                                  'style' => 'width:100%;text-align: right;font-size:90%;background-color:#FFFFFF;',
                                  'readonly' => true,
                                  'placeholder' => '0,00',

                              ],
                                  'clientOptions' => [
                                      'alias' =>  'decimal',
                                      'digits' => 2,
                                      'digitsOptional' => false,
                                      'groupSeparator' => ',',
                                      'removeMaskOnSubmit' => true,
                                      // 'allowMinus'=>false,
                                      //'groupSize' => 3,
                                      'radixPoint'=> ".",
                                      'autoGroup' => true,
                                      //'decimalSeparator' => ',',
                                ],

                        ]);?></p></strong></h3>
          </div>
      </div>
  </div>

	<?php ActiveForm::end();?>

</div>

