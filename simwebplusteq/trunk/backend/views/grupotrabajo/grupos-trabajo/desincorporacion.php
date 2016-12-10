<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;

$this->title =Yii::t( 'backend', 'List of Working Groups' );
?>

<div class="apuestas-ilicita-form-index">
    <h1><?= Html::encode( $this->title )?></h1>
      
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' =>  $searchModel,
        'columns' =>    [
                        [ 'class' => 'yii\grid\SerialColumn' ],
                        'id_grupo',
                        'descripcion',
                        'departamentoName',
                        'unidadName', 
                        'inactivoName',
                        'fecha',
                        [ 'class' => 'yii\grid\ActionColumn', 'template'=> '{disable}' ],
                    ]
    ] ); ?>
</div>
   





