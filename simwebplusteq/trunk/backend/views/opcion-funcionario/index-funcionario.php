<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Official User Register');

?>
<div class="inmuebles-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 
    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulariofuncionario',
    'action'=> ['/opcion-funcionario/registrarfuncionariousuario'],
    'enableClientValidation' => false,
    'enableAjaxValidation' => false, 
    'options' => ['class' => 'form-vertical'],]); ?>
    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id_funcionario',
            'ci',
            'apellidos',            
            'nombres',
            'status_funcionario',
            

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('backend', 'Register'),
                                                                            'style' => 'margin: 0 auto; display: block;',

                                                                        ]
                                                                    );
                                        },
                                    ],
            ],
        ],
    ]); ?>

    

</div>
<?= Html::endForm();?>