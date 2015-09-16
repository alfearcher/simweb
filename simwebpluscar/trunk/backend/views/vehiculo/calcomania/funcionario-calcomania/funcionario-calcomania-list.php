<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\vehiculo\calcomania\FuncionarioCalcomaniaSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\vehiculo\calcomania\FuncionarioCalcomaniaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = Yii::t('backend', 'Funcionario Calcomania Forms');
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="funcionario-calcomania-form-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //= Html::a(Yii::t('backend', 'Create Funcionario Calcomania Form'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <?= Html::a(Yii::t('backend', 'Automatic Allocation'), ['automaticAllocation'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div>
        <?php
            // echo "<pre>"; var_dump($model[0]); echo "</pre>";
        ?>        
    </div>

    <div class="container">        
        <div class="container-fluid" style="width:1024px">
            <div class="panel-body" >
             <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\CheckboxColumn'],
                            ['class' => 'yii\grid\SerialColumn'],
                            'id_funcionario',                            
                            'funcionarioApellido',
                            'funcionarioName',
                            // 'loteCalcomaniaRangoInicial',
                            ['label' => 'ci',
                                'value' => function($data) {
                                        return $data->naturaleza .'-'. $data->ci;
                                }
                            ],

                            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
                        ],
                    ]); ?>
                
            </div>
        </div>
    </div>
</div>