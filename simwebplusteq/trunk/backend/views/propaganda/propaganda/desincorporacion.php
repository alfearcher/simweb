<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;

$this->title =Yii::t( 'backend', 'List of Advertisements' );
?>

<script type="text/javascript">

function seleccion() {

    if ( document.getElementById('checkbox').checked == 1 ) {

                btn.disabled = false;
    } else {
                btn.disabled = true;
    }
}

</script>

<body onload="seleccion()"/>
<div class="propaganda-form-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::beginForm( [ 'propaganda/propaganda/disable' ], 'post' );?>

        <?= Html::submitButton( 'Inactive', [ 'class' => 'btn btn-primary', 'name' => 'btn', 'id' => "btn", 'value' => 'inactive', 'disabled' => 'disabled' ] );?>
    </p>

    <?= GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' =>  $searchModel,
           'columns' =>    [
                                [ 'class' => 'yii\grid\CheckboxColumn' ],
                                [ 'class' => 'yii\grid\SerialColumn' ],
                                'id_impuesto',
                                'ano_impositivo',
                                'contribuyenteName',
                                'usoName',
                                'claseName',
                                'inactivoName',
                                'fecha_hora',
                            ]
    ] ); ?>
    <?= Html::endForm();?>
</div>

