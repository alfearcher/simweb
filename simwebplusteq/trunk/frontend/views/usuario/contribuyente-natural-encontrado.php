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

$this->title = Yii::t('frontend','Resultado de la consulta '.BuscarGeneralForm::getCedulaRif(0, $naturaleza, $cedula, $tipo));

?>
<div class="buscar-gerenarl-view-list">

    <?php
        $form = ActiveForm::begin([
            'id' => 'contribuyente-natural-encontrado',
            'method' => 'post',
            /*'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,*/
        ]);
     ?>

    <div class="contribuyente-encontrado">

        <h3><?= Html::encode($this->title) ?></h3>

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
                // [
                //     'label' =>  Yii::t('frontend','Cedula/Rif'),
                //     'value' => function($data) {
                //             return BuscarGeneralForm::getCedulaRif($data->tipo_naturaleza, $data->naturaleza, $data->cedula, $data->tipo);
                //     },
                // ],

                [
                    'label' => Yii::t('frontend','Taxpayer'),
                    'value' => function($data) {
                            return BuscarGeneralForm::getDescripcionContribuyente($data->tipo_naturaleza, $data->razon_social, $data->apellidos, $data->nombres);
                    }
                ],


                 [
                    'label' => Yii::t('frontend','Address'),
                    'value' => function($data) {
                            return $data->domicilio_fiscal;
                    }
                ],

                // [
                //     'label' => Yii::t('frontend', 'Condition'),
                //     'value'=> function($data) {
                //         return BuscarGeneralForm::getActivoInactivo($data->inactivo);
                //     },
                // ],

                [
                    'label' => Yii::t('frontend','Type'),
                    'value' => function($data) {
                        if ($data->tipo_naturaleza == 1 and $data->id_rif == 0 ) {
                            return BuscarGeneralForm::getTipoNaturaleza($data->tipo_naturaleza) . ' (SP)';
                        } else {
                            return BuscarGeneralForm::getTipoNaturaleza($data->tipo_naturaleza);
                        }
                    }
                ],

                // [
                //     'class' => 'yii\grid\ActionColumn',
                //     'header'=> Yii::t('frontend','View'),
                //     'template' => '{view}',
                //     'buttons' => [
                //         'view' => function ($url, $model, $key) {
                //             return Html::a('<center><span class="glyphicon glyphicon-user"></span></center>',['view','idContribuyente' => $key]);
                //         },
                //         /*'link' => function ($url, $model, $key) {
                //             return Html::a('Action', $url);
                //         },*/
                //     ],
                // ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'OK',
                    'template' => '{ok}',
                    'buttons' => [
                        'ok' => function ($url, $model, $key) {
                            if ($model->inactivo == 0 ) {
                                return Html::a('<center><span class= "fa fa-thumbs-up"></span></center>',['validar-natural','id' => $key]);
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