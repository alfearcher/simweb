<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use kartik\icons\Icon;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Property Urban');

?>
<div class="inmuebles-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'action'=> ['/inmueble/linderos-inmuebles-urbanos/view'],
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-vertical'],]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

        'id_impuesto',
        'direccion',
        'catastro',
        'id_contribuyente',
        

             ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('frontend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',

                                                                        ]
                                                                    );
                                        },
                                    ],
            ],
        ],
    ]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Register Property Urban'), ['inmueble/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
    </p>

</div>
<?//= Html::endForm();?>
<?php ActiveForm::end(); ?> 