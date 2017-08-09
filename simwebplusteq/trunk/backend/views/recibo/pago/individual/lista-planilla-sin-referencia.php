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
 *  @file serial-agregado-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view serial-agregado-form
 *  @brief vista principal los seriales agregados.
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
    //use yii\widgets\Pjax;
    //use common\models\contribuyente\ContribuyenteBase;
    use yii\widgets\DetailView;
    use yii\widgets\MaskedInput;

?>

<div class="lista-planilla-sin-referencia">
    <?php

        $form = ActiveForm::begin([
            'id' => 'id-lista-planilla-sin-referencia-form',
            'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'enableClientScript' => false,
        ]);
    ?>

    <!-- <?//=$form->field($model, 'fecha_pago')->hiddenInput(['value' => $model->fecha_pago])->label(false);?> -->

    <?=Html::hiddenInput('fecha_pago', $fechaPago)?>
    <?=Html::hiddenInput('cuenta_recaudadora', $cuentaRecaudadora)?>

    <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;width: 100%;padding-top: 10px;">
        <strong><p><?=Yii::t('backend', 'Recibos sin referencia ( Referencias Bancarias )')?></p></strong>
    </div>
    <div class="row" style="width:100%;">

        <?= GridView::widget([
            'id' => 'id-grid-planilla-sin-referencia',
            'dataProvider' => $dataProvider,
            'headerRowOptions' => ['class' => 'info'],
            'tableOptions' => [
                'class' => 'table table-hover',
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                   'class' => 'yii\grid\CheckboxColumn',
                   'name' => 'chkIdRegistro',
                   'multiple' => false,
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                            // return [
                            //     'onClick' => 'javascript: return false;',
                            //     'checked' => true,
                            // ];

                    }
                ],
                [
                    'label' => Yii::t('backend', 'Recibo'),
                    'contentOptions' => [
                        'style' => 'text-align:center;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return $model->recibo;
                                },
                ],
                [
                    'label' => Yii::t('backend', 'Fecha'),
                    'contentOptions' => [
                        'style' => 'text-align:center;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return date('d-m-Y', strtotime($model->fecha_pago));
                                },
                ],
                [
                    'label' => Yii::t('backend', 'Monto'),
                    'contentOptions' => [
                        'style' => 'text-align:right;font-weight:bold;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return Yii::$app->formatter->asDecimal($model->monto_recibo, 2);
                                },
                ],
                // [
                //     'class' => 'yii\grid\ActionColumn',
                //     'header' => Yii::t('backend', 'Agregar'),
                //     'template' => '{add}',
                //     'buttons' => [
                //         'add' => function($url, $data, $key) {
                //             return Html::a('<center><span class="fa fa-plus-square fa-lg"></center></span>',
                //                             ['agregar-planilla-como-serial', 'id' => $key],
                //                             [
                //                                 'style' => 'font-size:140%;color:blue;'
                //                             ]);
                //         }
                //     ],
                // ],

            ]
        ]);?>
    </div>
    <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;width: 100%;padding: 0px;margin-top: -10px;">
        <div class="col-sm-4" style="width: 50%;padding: 0px;margin: 0px;">
            <strong><h4><?=Yii::t('backend', 'Total (Recibo):')?></h4></strong>
        </div>
        <div class="col-sm-4" style="width: 40%;padding: 0px;margin: 0px;text-align: right;">
            <strong><h4><?=Yii::$app->formatter->asDecimal($totalizar, 2)?></h4></strong>
        </div>
    </div>

    <div class="row" style="width:100%;margin: 0px;padding: 0px;margin-top: 10px;">
        <div class="col-sm-2" style="width:30%;padding:0px;margin:0px;">
            <?= Html::submitButton(Yii::t('backend', 'Guardar'),
                                              [
                                                'id' => 'btn-add-planilla',
                                                'class' => 'btn btn-primary',
                                                'value' => 4,
                                                'style' => 'width: 100%',
                                                'name' => 'btn-add-planilla',
                                              ])
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

