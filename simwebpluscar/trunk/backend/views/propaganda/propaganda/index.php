<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;

$this->title =Yii::t( 'backend', 'List of Advertisements' );
?>

<div class="propaganda-form-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' =>  $searchModel,
           'columns' =>    [
                                [ 'class' => 'yii\grid\SerialColumn' ],
                                'id_impuesto',
                                'ano_impositivo',
                                'contribuyenteName',
                                'usoName',
                                'claseName',
                                'inactivoName',            
                                'fecha_guardado',
                                [ 'class' => 'yii\grid\ActionColumn', 'template'=> '{update}' ],
                            ]
    ] ); ?>
    <?= Html::endForm();?> 
</div>

