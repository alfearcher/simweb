<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;
use backend\models\vehiculo\calcomania\FuncionarioCalcomaniaSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\vehiculo\calcomania\FuncionarioCalcomaniaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = Yii::t('backend', 'Funcionario Calcomania Forms');
// $this->params['breadcrumbs'][] = $this->title;
?>
<!-- VARIABLE QUE MANEJA EL MENSAJE DE ERROR -->
<?= $msg ?>
<div class="funcionario-calcomania-form-index">
    <?= Html::beginForm( [ 'vehiculo/calcomania/funcionario-calcomania/automatic' ], 'post' );?>
        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?php //= Html::a(Yii::t('backend', 'Create Funcionario Calcomania Form'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <p>
            <?= Html::submitButton( 'Automatic Allocation', [ 'class' => 'btn btn-primary', 'name' => 'btn', 'id' => "btn" ] );?>
        </p>

        <div>
            <?php
                // echo "<pre>"; var_dump($dataProvider); echo "</pre>";
            ?>        
        </div>

        <div class="container">        
            <div class="container-fluid" style="width:1024px">
                <div class="panel-body" >
                 <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => '{items}',
                            'columns' => [
                                ['class' => 'yii\grid\CheckboxColumn'],
                                ['class' => 'yii\grid\SerialColumn'],
                                'id_funcionario',          
                                'funcionarioApellido',
                                'funcionarioName',
                                ['label' => 'Inicio',
                                    'value' => function($data) {
                                            return $data->distribucionRangoInicial;
                                    }
                                ],
                                ['label' => 'Fin',
                                    'value' => function($data) {
                                            return $data->distribucionRangoFinal;
                                    }
                                ],
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
    <?= Html::endForm();?> 
</div>