<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\bootstrap\Modal;
    use yii\helpers\Url;
    use backend\models\buscargeneral\BuscarGeneralForm;
    use kartik\icons\Icon;
    use yii\widgets\ActiveForm;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);


/* @var $this yii\web\View */
/* @var $searchModel app\models\BancoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend','Result of the Consult');

?>
<div class="buscar-gerenarl-view-list">

    <?php
        $form = ActiveForm::begin([
            'id' => 'buscar-general-form',
            'method' => 'post',
            /*'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,*/
        ]);
     ?>

    <div class="buscar-general-contribuyente">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            //'layout'=>"n{pager}\n{items}",

            'headerRowOptions' => ['class' => 'success'],
            'rowOptions' => function($data) {
                if ( $data->inactivo == 1 ) {
                    return ['class' => 'danger'];
                }
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'ID.',
                    'value' => 'id_contribuyente',
                ],
                [
                    'label' =>  Yii::t('backend','Cedula/Rif'),
                    'value' => function($data) {
                            return BuscarGeneralForm::getCedulaRif($data->tipo_naturaleza, $data->naturaleza, $data->cedula, $data->tipo);
                    },
                ],

                [
                    'label' => Yii::t('backend','Taxpayer'),
                    'value' => function($data) {
                            return BuscarGeneralForm::getDescripcionContribuyente($data->tipo_naturaleza, $data->razon_social, $data->apellidos, $data->nombres);
                    }
                ],

                [
                    'label' => Yii::t('backend', 'Condition'),
                    'value'=> function($data) {
                        return BuscarGeneralForm::getActivoInactivo($data->inactivo);
                    },
                ],

                [
                    'label' => Yii::t('backend','Type'),
                    'value' => function($data) {
                        return BuscarGeneralForm::getTipoNaturaleza($data->tipo_naturaleza);
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> Yii::t('backend','View'),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<center><span class="glyphicon glyphicon-user"></span></center>',['view','idContribuyente' => $key]);
                        },
                        /*'link' => function ($url, $model, $key) {
                            return Html::a('Action', $url);
                        },*/
                    ],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'OK',
                    'template' => '{ok}',
                    'buttons' => [
                        'ok' => function ($url, $model, $key) {
                            if ($model->inactivo == 0 ) {
                                return Html::a('<center><span class= "fa fa-thumbs-up"></span></center>',['ok','idContribuyente' => $key]);
                            } else {
                                return '<span><center>---</span></center>';
                            }
                        },
                    ],
                ],

            ],
        ]); ?>

    </div>
     <?php ActiveForm::end(); ?>
</div>