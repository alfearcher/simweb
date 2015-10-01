<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;

$this->title = Yii::t('backend', 'List of Lawful Bets');
?>

<div class="apuestas-licita-form-index">
    <h1><?= Html::encode( $this->title )?></h1>
   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id_impuesto',
            //'id_contribuyente',
            'contribuyenteName',
            'descripcion',
            'direccion',
            //'id_cp',
            //'id_sim',
            'inactivoName',
            'fecha_creacion',
            ['class' => 'yii\grid\ActionColumn', 'template'=> '{update}'],
        ],
    ]); ?>
</div>
