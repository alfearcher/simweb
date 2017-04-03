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

<div class="seriales-agregados-form">
    <?php

        $form = ActiveForm::begin([
            'id' => 'id-serial-agregado-form',
            'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'enableClientScript' => false,
        ]);
    ?>

    <!-- <?//=$form->field($model, 'recibo')->hiddenInput(['value' => $model->recibo])->label(false);?> -->
    <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;width: 100%;padding-top: 10px;">
        <strong><p><?=Yii::t('backend', 'Seriales (Referencias Bancarias)')?></p></strong>
    </div>
    <div class="row" style="width:100%;">

        <?= GridView::widget([
            'id' => 'id-grid-serial-referencia',
            'dataProvider' => $dataProvider,
            'headerRowOptions' => ['class' => 'success'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'chkSerial',
                    'multiple' => false,
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                            return [
                                'onClick' => 'javascript: return false;',
                                'checked' => true,
                            ];

                    }
                ],
                [
                    'label' => Yii::t('backend', 'Nro. Recibo'),
                    'value' => function($model) {
                                 return $model->recibo;
                             },
                ],
                [
                    'label' => Yii::t('backend', 'Serial'),
                    'contentOptions' => [
                        'style' => 'text-align:center;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return $model->serial;
                                },
                ],
                [
                    'label' => Yii::t('backend', 'Fecha'),
                    'contentOptions' => [
                        'style' => 'text-align:center;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return date('d-m-Y', strtotime($model->fecha_edocuenta));
                                },
                ],
                [
                    'label' => Yii::t('backend', 'Monto'),
                    'contentOptions' => [
                        'style' => 'text-align:right;font-weight:bold;font-size:90%;',
                    ],
                    'format' => 'raw',
                    'value' => function($model) {
                                    return Yii::$app->formatter->asDecimal($model->monto_edocuenta, 2);
                                },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Suprimir',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, $data, $key) {
                            return Html::a('<center><span class="fa fa-times fa-lg"></center></span>',
                                            ['suprimir-serial-agregado', 'id' => $key, 'recibo' => $data['recibo']],
                                            [
                                                'style' => 'font-size:140%;color:red;'
                                            ]);
                        }
                    ],
                ],

            ]
        ]);?>
    </div>
    <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;width: 100%;padding: 0px;margin-top: -10px;">
        <div class="col-sm-4" style="width: 60%;padding: 0px;margin: 0px;">
            <strong><h4><?=Yii::t('backend', 'Total (Seriales):')?></h4></strong>
        </div>
        <div class="col-sm-4" style="width: 30%;padding: 0px;margin: 0px;text-align: right;">
            <strong><h4><?=Yii::$app->formatter->asDecimal($totalizar, 2)?></h4></strong>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

